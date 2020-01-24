<?php

namespace Lui\Delivery;

class YandexApi
{

    function GetDataYandex($q)
    {
        $yandex_apikey = yandex_apikey;
        $base_uri = 'https://geocode-maps.yandex.ru/1.x/';
        $query = http_build_query(['apikey' => $yandex_apikey, 'format' => 'json', 'geocode' => $q, 'results' => 5]);
        $url = $base_uri . "?" . $query;
        $arJson = json_decode(file_get_contents($url), true);
        $response = $arJson['response'];
        $GeoObjectCollection = $response['GeoObjectCollection'];
        $result = $GeoObjectCollection['metaDataProperty']['GeocoderResponseMetaData'];
        if ($result['found']) {
            $GeoObject = $GeoObjectCollection['featureMember'][0]['GeoObject'];
            $Address = $GeoObject['metaDataProperty']['GeocoderMetaData']['Address'];
            $Components = $GeoObject['metaDataProperty']['GeocoderMetaData']['Address']['Components'];
            unset($Address['Components']);
            $AddressLine = $GeoObject['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AddressLine'];
            $Point = $GeoObject['Point']['pos'];
            return [
                'guery' => $q,
                'AddressLine' => $AddressLine,
                'Point' => $Point,
                'Address' => $Address,
                'Components' => array_column(is_array($Components) ? $Components : [], 'name', 'kind'),
            ];
        }
    }

    protected function GetHtml($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        return $output;
    }


    function GetDataYandexAll($q)
    {
        $yandex_apikey = yandex_apikey;
        $base_uri = 'https://geocode-maps.yandex.ru/1.x/';
        $query = http_build_query(['apikey' => $yandex_apikey, 'format' => 'json', 'geocode' => $q, 'results' => 5]);
        $url = $base_uri . "?" . $query;
        $arJson = json_decode(file_get_contents($url), true);
        $response = $arJson['response'];
        $GeoObjectCollection = $response['GeoObjectCollection'];
        $result = $GeoObjectCollection['metaDataProperty']['GeocoderResponseMetaData'];
        $arResult = [];
        if ($result['found']) {
            foreach ($GeoObjectCollection['featureMember'] as $obj) {
                $GeoObject = $obj['GeoObject'];
                $Address = $GeoObject['metaDataProperty']['GeocoderMetaData']['Address'];
                $Components = $GeoObject['metaDataProperty']['GeocoderMetaData']['Address']['Components'];
                unset($Address['Components']);
                $AddressLine = $GeoObject['metaDataProperty']['GeocoderMetaData']['AddressDetails']['Country']['AddressLine'];
                $Point = $GeoObject['Point']['pos'];
                $arResult[] = [
                    'guery' => $q,
                    'AddressLine' => $AddressLine,
                    'Point' => $Point,
                    'Address' => $Address,
                    'Components' => array_column(is_array($Components) ? $Components : [], 'name', 'kind'),
                ];
            }
            return $arResult;
        }
    }


    function GetQuery($propsData)
    {
        $q = '';
        if ($propsData['LOCATION']) {
            $q = $propsData['LOCATION'] . ' ';
        } else {
            if ($propsData['CITY']) {
                switch ($propsData['CITY']) {
                    case 'Сонечный':
                        $q .= 'посёлок ' . $propsData['CITY'] . ', ';
                        break;
                    default:
                        $q .= 'г.' . $propsData['CITY'] . ', ';
                        break;
                }
            } else {
                $q .= 'г. Минск, ';
            }
        }

        if ($propsData['STREET']) {
            $q .= $propsData['STREET'];
        }

        if ($propsData['HOME']) {
            $q .= ', д.' . $propsData['HOME'];
        }

        if (stripos($q, 'Беларусь') === false) {
            $q = 'Беларусь, ' . $q;
        }

        return $q;
    }

    function isValidAddress($filed)
    {
        if (!empty($filed)) {
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/ll4.txt', print_r($filed, true));
            $isValid = true;

            $country_code = $filed['Address']['country_code'];
            $locality = $filed['Components']['locality'];
            $street = $filed['Components']['street'];
            $house = $filed['Components']['house'];
            //проверка на беларусь
            if ($country_code != 'BY') {
                $isValid = false;
            }
            //г. Минск но нет улицы
            if ($locality == 'Минск' && $filed['Components']['locality'] == false) {
                $isValid = false;
            }
            //г. Минск, есть улицы но нет дома
            if ($locality == 'Минск' && $filed['Components']['locality'] != false && $house == false) {
                $isValid = false;
            }
            //г.Минск, яндекс находит этот адресс, когда ошибка в запросе
            if($filed['Point'] == '27.502947 53.942794'){
                $isValid = false;
            }
            //улица не подтянулась
//            if( !isset($street) ){
//                $isValid = false;
//            }
            //проверяем населённый пункт
            if( isset($locality) ){
                preg_match('/[А-ЯЁ][^,]+/u', $locality, $matches);

                if( !empty($matches[0]) ){
                    $town = $matches[0];
                    if( strpos( mb_strtolower($filed['guery']), mb_strtolower($town) ) === false ){
                        $isValid = false;
                    }
                }
            }
            else{
                $isValid = false;
            }

            return $isValid;
        } else {
            return false;
        }
    }
}
