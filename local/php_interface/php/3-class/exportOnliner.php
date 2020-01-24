<?php

/**
 * Created by PhpStorm.
 * User: maspanau
 * Date: 04.04.2019
 * Time: 10:13
 */
class exportOnliner
{
    private $URL = "https://b2bapi.onliner.by/pricelists";
    private $URL_TOKEN = "https://b2bapi.onliner.by/oauth/token";
    private $URL_ALL_ELEM_ONLINER = "https://b2bapi.onliner.by/positions";
   // private $URL_ALL_ELEM_ONLINER = "https://b2bapi.onliner.by/positions";
    private $URL_UPDATE_ELEM = '';
    public $IBLOCK_ID;
    public $NUMBER_ELEMENT;
    public $FILTER_IBLOCK = [];
    private $CLIENT_ID = '13471de509310d057810';
    private $CLIENT_SECRET = 'e41596496198c003515754c7bf31b36fd6f299f4';
    private $ACCESS_TOKEN;
    private $STORE_ID = 15738;
    private $FIELDS = [
        'id',                           //id предложения присваивает onliner.by
        'category',                     //Название раздела
        'vendor',                       //Производитель
        'model',                        //Модель
        'article',                      //Заводской артикул
        'price',                        //Цена товара
        'currency',                     //Валюта товара
        'comment',                      //комментарий продавца
        'producer',                     //изготовитель товара
        'importer',                     //Импортёр \ поставщик на территорию РБ
        'serviceCenters',               //Сервисный центр
        'warranty',                     //Гарантия (месяцев)
        'deliveryTownTime',             //Доставка по городу (дней)
        'deliveryTownPrice',            //Доставка по городу (стоимость)
        'deliveryCountryTime',          //Доставка по РБ (дней)
        'deliveryCountryPrice',         //Доставка по РБ (стоимость)
        'productLifeTime',              //Срок службы (месяцев)
        'isCashless',                   //Безналичный
        'isCredit',                     //Кредит
        'stockStatus',                  //Наличие товара(in_stock - есть на складе и доступен для покупки; run_out_of_stock - осталось мало или заканчивается)
    ];
    public $FIELDS_UPDATE_ELEM = [
        'product' => [
            'manufacturer',             //Название производителя (брэнда)
            'article',                  //Артикул товара (SKU, partnumber)
            'ean13',                    //EAN-13 код товара (https://ru.wikipedia.org/wiki/European_Article_Number)
            'description',              //Описание товара, сюда можно передать название товара и другую информацию, позволяющую понять, что это за товар
        ],
        'producer' => [
            'country',                  //Страна происхождения
            'factory',                  //Завод-изготовитель
        ],
        'stock' => [
            'status',                   //Статус наличия: in_stock (есть на складе и доступен для покупки), run_out_of_stock (осталось мало или заканчивается), out_of_stock (нет на складе или нельзя купить)
            'quantity',                 //Фактический остаток товара на складе (с той точностью, с которой можно его указать)
        ],
        'recommended_retail_price' => [
            'amount',                   //Рекомендованная розничная цена в формате "200.34" с копейками после точки
            'currency'                  //Валюта, может быть только "BYN"
        ],
        'package' => [
            'width',                    //Ширина с единицами измерения
            'length',                   //Длина с единицами измерения
            'height',                   //Высота с единицами измерения
            'weight',                   //Вес с единицами измерения
        ]
    ];

    public $FIELDS_STATUS_PRICELISTSS = [
        'id',                           //id Уникальный идентификатор загруженного прайс-листа
        'shopId',                       //Уникальный идентификатор магазина
        'statusCode',                   //Код статуса обработки
        'statusText',                   //Текст статуса
        'date',                         //Дата загрузки прайс-листа
        'processedCount',               //Кол-во обработанных позиций прайс-листа
        'errorsCount',                  //Кол-во ошибок, возникших во время обработки
    ];


    /**
     * exportOnliner constructor.
     * @param integer $IBLOCK_ID
     * @param array $FILTER_SECTION_ID
     * @param array $FIELDS
     */
    function __construct($ACTION, $IBLOCK_ID, array $FILTER_IBLOCK = [], array $FIELDS = [])
    {
        \CModule::IncludeModule('iblock');

        if ($ACTION) {
            switch ($ACTION) {
                case 'UPDATE'://обновление элементов
                    $this->IBLOCK_ID = $IBLOCK_ID;
                    $this->FILTER_IBLOCK = $FILTER_IBLOCK;
                    $this->FIELDS_UPDATE_ELEM = $this->setFields($this->FIELDS_UPDATE_ELEM, $FIELDS);
                    break;
                case 'EXPORT'://экспортируем каталог на onliner
                    echo 'Экспорт не сделан!!! :(';
                    break;
                case 'STATUS_PRICELISTSS'://Отчет по импорту
                    $this->IBLOCK_ID = $IBLOCK_ID;
                    $this->FIELDS_STATUS_PRICELISTSS = $this->setFields($this->FIELDS_STATUS_PRICELISTSS, $FIELDS);
                    break;
                default:
                    echo 'Не верно указан ACTION';
                    break;
            }
        } else {
            echo 'Не указан ACTION';
        }
    }

    public function getPriceListOnliner($DEBUG = false, $JSON = false)
    {
        $ListElements = $this->getListElements();
        $data = $this->getDataExportOnliner($ListElements);
        $data = array_values($data);
        if (!$DEBUG && !$JSON) {
            $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
            $this->getTokenForApi();
            $response = $this->curl($jsonData);
            return $response;
        } else {
            if ($JSON) {
                $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
                return $jsonData;
            } else {
                return $data;
            }
        }
    }

    public function getPriceListUpdateOnliner($DEBUG = false, $JSON = false)
    {
        $ListElements = $this->getListUpdateElem();
        /*$data = $this->getDataExportOnliner($ListElements);
        $data = array_values($data);
        if (!$DEBUG && !$JSON) {
            $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
            $this->getTokenForApi();
            $response = $this->curl($jsonData);
            return $response;
        } else {
            if ($JSON) {
                $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
                return $jsonData;
            } else {
                return $data;
            }
        }*/
    }

    private function setFields($THI_FIELDS, $FIELDS)
    {
        $mas = [];
        foreach ($THI_FIELDS as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $v) {
                    if ($FIELDS[$v]) {
                        $mas[$key][$v] = $FIELDS[$v];
                    } else {
                        $mas[$key][$v] = '';
                    }
                }
            } else {
                if ($FIELDS[$val]) {
                    $mas[$val] = $FIELDS[$val];
                } else {
                    $mas[$val] = '';
                }
            }
        }
        return $mas;
    }

    private function getListElements()
    {
        $filter = [
            'IBLOCK_ID' => $this->IBLOCK_ID,
            'SECTION_ID' => $this->FILTER_SECTION_ID,
            'CATALOG_TYPE' => \Bitrix\Catalog\ProductTable::TYPE_PRODUCT,
            'CATALOG_AVAILABLE' => 'Y',
            'INCLUDE_SUBSECTIONS' => 'Y',
        ];
        $select = ['ID', 'IBLOCK_ID', 'NAME', 'CATALOG_AVAILABLE', 'IBLOCK_SECTION_ID'];

        $resID = \CIBlockElement::GetList(array('ID' => 'ASC'), $filter, false, false, $select);
        $allIdForExport = array();
        while ($res1 = $resID->GetNextElement()) {
            $reOnl = $res1->GetFields();
            $reProp = $res1->GetProperties();
            if ($reOnl['CATALOG_AVAILABLE'] == 'Y') {
                $resPrice = \CCatalogProduct::GetOptimalPrice($reOnl["ID"], 1, [2], 'N', 's1');
                $arPrice["PRICE"] = round($resPrice['DISCOUNT_PRICE'], 2);
                $arPrice["PRICE"] = str_replace('.', ',', $arPrice["PRICE"]);

                $arPrice["CURRENCY"] = 'BYN';
                $res = CIBlockSection::GetByID($reOnl["IBLOCK_SECTION_ID"]);
                $ar_res = $res->GetNext();

                $allIdForExport[$reOnl['ID']]['ID'] = $reOnl['ID'];
                $allIdForExport[$reOnl['ID']]['NAME'] = $reOnl['NAME'];
                $allIdForExport[$reOnl['ID']]['CATALOG_SUBSCRIBE'] = $reOnl['CATALOG_SUBSCRIBE'];
                $allIdForExport[$reOnl['ID']]['CATALOG_QUANTITY'] = $reOnl['CATALOG_QUANTITY'];
                $allIdForExport[$reOnl['ID']]['SECTION_NAME'] = $ar_res['NAME'];
                $allIdForExport[$reOnl['ID']]['SECTION_ID'] = $reOnl['IBLOCK_SECTION_ID'];
                $allIdForExport[$reOnl['ID']]['PRICE'] = $arPrice["PRICE"];
                $allIdForExport[$reOnl['ID']]['CURRENCY'] = $arPrice["CURRENCY"];
                foreach ($reProp as $k => $prop) {
                    $allIdForExport[$reOnl['ID']]['PROP_' . $k] = $prop["VALUE"];
                }
            }
        }
        $this->NUMBER_ELEMENT = count($allIdForExport);
        return $allIdForExport;
    }

    private function getListUpdateElem()
    {
        $filter = [
            'IBLOCK_ID' => $this->IBLOCK_ID,
            'CATALOG_AVAILABLE' => 'Y',
            //'CATALOG_TYPE' => \Bitrix\Catalog\ProductTable::TYPE_PRODUCT,
        ];
        $filter = array_merge($filter, $this->FILTER_IBLOCK);

        $select = ['ID', 'IBLOCK_ID', 'NAME', 'CATALOG_AVAILABLE', 'IBLOCK_SECTION_ID'];

        $resID = \CIBlockElement::GetList(array('ID' => 'ASC'), $filter, false, false, $select);
        $allIdForExport = array();
        while ($res1 = $resID->GetNextElement()) {
            //PR($res1);
            $reOnl = $res1->GetFields();
            $reProp = $res1->GetProperties();
            if ($reOnl['CATALOG_AVAILABLE'] == 'Y' && $reProp['ONLINER']['VALUE'] != '') {
                $resPrice = \CCatalogProduct::GetOptimalPrice($reOnl["ID"], 1, [2], 'N', 's1');
                $arPrice["PRICE"] = round($resPrice['DISCOUNT_PRICE'], 2);
                $arPrice["PRICE"] = str_replace('.', ',', $arPrice["PRICE"]);

                $arPrice["CURRENCY"] = 'BYN';
                $res = CIBlockSection::GetByID($reOnl["IBLOCK_SECTION_ID"]);
                $ar_res = $res->GetNext();

                $allIdForExport[$reOnl['ID']]['ID'] = $reOnl['ID'];
                $allIdForExport[$reOnl['ID']]['NAME'] = $reOnl['NAME'];
                $allIdForExport[$reOnl['ID']]['CATALOG_SUBSCRIBE'] = $reOnl['CATALOG_SUBSCRIBE'];
                $allIdForExport[$reOnl['ID']]['CATALOG_QUANTITY'] = $reOnl['CATALOG_QUANTITY'];
                $allIdForExport[$reOnl['ID']]['SECTION_NAME'] = $ar_res['NAME'];
                $allIdForExport[$reOnl['ID']]['SECTION_ID'] = $reOnl['IBLOCK_SECTION_ID'];
                $allIdForExport[$reOnl['ID']]['PRICE'] = $arPrice["PRICE"];
                $allIdForExport[$reOnl['ID']]['CURRENCY'] = $arPrice["CURRENCY"];
                foreach ($reProp as $k => $prop) {
                    $allIdForExport[$reOnl['ID']]['PROP_' . $k] = $prop["VALUE"];
                }
            }
        }
        $this->NUMBER_ELEMENT = count($allIdForExport);
        return $allIdForExport;
    }

    private function getDataExportOnliner($ListElements)
    {
        $data = [];
        foreach ($ListElements as $value) {
            foreach ($this->FIELDS as $k => $v) {
                $val = '';
                switch ($k) {
                    case 'id'://id предложения присваивает onliner.by
                        $val = $v ? $value['PROP_' . $v] : $this->STORE_ID;
                        break;
                    case 'category'://Название раздела
                        $val = $v ? $value['PROP_' . $v] : 'Декоративная косметика для глаз'; //$value['SECTION_NAME'];
                        break;
                    case 'model'://Модель
                        $val = $v ? $value['PROP_' . $v] : $value['NAME'];
                        break;
                    case 'price'://Цена товара
                        $val = $v ? $value['PROP_' . $v] : $value['PRICE'];
                        break;
                    case 'currency'://Валюта товара
                        $val = $v ? $value['PROP_' . $v] : $value['CURRENCY'];
                        break;
                    case 'stockStatus'://Наличие товара(in_stock - есть на складе и доступен для покупки; run_out_of_stock - осталось мало или заканчивается)
                        $val = $v ? $value['PROP_' . $v] : 'in_stock';
                        break;
                    case 'deliveryTownTime'://Доставка по городу (дней)
                        $val = $v ? $value['PROP_' . $v] : '1';
                        break;
                    case 'deliveryTownPrice'://Доставка по городу (стоимость)
                        $val = $v ? $value['PROP_' . $v] : '5,00';
                        break;
                    case 'deliveryCountryTime'://Доставка по РБ (дней)
                        $val = $v ? $value['PROP_' . $v] : '3';
                        break;
                    case 'deliveryCountryPrice'://Доставка по РБ (стоимость)
                        $val = $v ? $value['PROP_' . $v] : '5,00';
                        break;
                    case 'comment'://комментарий продавца
                        $val = $v ? $value['PROP_' . $v] : 'Дополнительная скидка 2% на все товары магазина с промокодом ONLINER! Бесплатная доставка по Минску (при заказе от 30 рублей) и в любой город Беларуси (при заказе от 50 рублей)! Рассрочка 2 месяца по карте «Халва». 100% качество товаров.
Для всех позиций товаров';
                        break;
                    case 'article'://Заводской артикул
                        $val = $v ? $value['PROP_' . $v] : '';
                        break;
                    case 'warranty'://Гарантия (месяцев)
                    case 'importer'://Импортёр \ поставщик на территорию РБ
                    case 'producer'://изготовитель товара
                    case 'vendor'://Производитель

                    case 'serviceCenters'://Сервисный центр
                    case 'productLifeTime'://Срок службы (месяцев)
                    case 'isCashless'://Безналичный
                    case 'isCredit'://Кредит
                        $val = $v ? $value['PROP_' . $v] : 'нет';
                        break;
                    default :
                        $val = $v ? $value['PROP_' . $v] : '';
                        break;
                }
                $data[$value['ID']][$k] = $val;
            }
        }
        return $data;
    }

    private function getDataUpdateOnliner($ListElements)
    {
        $data = [];
        foreach ($ListElements as $value) {
            foreach ($this->FIELDS as $k => $v) {
                $val = '';
                switch ($k) {
                    case 'id'://id предложения присваивает onliner.by
                        $val = $v ? $value['PROP_' . $v] : $this->STORE_ID;
                        break;
                    case 'category'://Название раздела
                        $val = $v ? $value['PROP_' . $v] : 'Декоративная косметика для глаз'; //$value['SECTION_NAME'];
                        break;
                    case 'model'://Модель
                        $val = $v ? $value['PROP_' . $v] : $value['NAME'];
                        break;
                    case 'price'://Цена товара
                        $val = $v ? $value['PROP_' . $v] : $value['PRICE'];
                        break;
                    case 'currency'://Валюта товара
                        $val = $v ? $value['PROP_' . $v] : $value['CURRENCY'];
                        break;
                    case 'stockStatus'://Наличие товара(in_stock - есть на складе и доступен для покупки; run_out_of_stock - осталось мало или заканчивается)
                        $val = $v ? $value['PROP_' . $v] : 'in_stock';
                        break;
                    case 'deliveryTownTime'://Доставка по городу (дней)
                        $val = $v ? $value['PROP_' . $v] : '1';
                        break;
                    case 'deliveryTownPrice'://Доставка по городу (стоимость)
                        $val = $v ? $value['PROP_' . $v] : '5,00';
                        break;
                    case 'deliveryCountryTime'://Доставка по РБ (дней)
                        $val = $v ? $value['PROP_' . $v] : '3';
                        break;
                    case 'deliveryCountryPrice'://Доставка по РБ (стоимость)
                        $val = $v ? $value['PROP_' . $v] : '5,00';
                        break;
                    case 'comment'://комментарий продавца
                        $val = $v ? $value['PROP_' . $v] : 'Дополнительная скидка 2% на все товары магазина с промокодом ONLINER! Бесплатная доставка по Минску (при заказе от 30 рублей) и в любой город Беларуси (при заказе от 50 рублей)! Рассрочка 2 месяца по карте «Халва». 100% качество товаров.
Для всех позиций товаров';
                        break;
                    case 'article'://Заводской артикул
                        $val = $v ? $value['PROP_' . $v] : '';
                        break;
                    case 'warranty'://Гарантия (месяцев)
                    case 'importer'://Импортёр \ поставщик на территорию РБ
                    case 'producer'://изготовитель товара
                    case 'vendor'://Производитель

                    case 'serviceCenters'://Сервисный центр
                    case 'productLifeTime'://Срок службы (месяцев)
                    case 'isCashless'://Безналичный
                    case 'isCredit'://Кредит
                        $val = $v ? $value['PROP_' . $v] : 'нет';
                        break;
                    default :
                        $val = $v ? $value['PROP_' . $v] : '';
                        break;
                }
                $data[$value['ID']][$k] = $val;
            }
        }
        return $data;
    }

    /**
     * получить токин
     */
    private function getTokenForApi()
    {
        $process = curl_init($this->URL_TOKEN);
        curl_setopt($process, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($process, CURLOPT_USERPWD, $this->CLIENT_ID . ':' . $this->CLIENT_SECRET);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_POSTFIELDS, array('grant_type' => 'client_credentials'));
        $result = curl_exec($process);
        curl_close($process);
        $result = json_decode($result);

        $this->ACCESS_TOKEN = $result->access_token;
    }

    private function curl($jsonData, $url)
    {
        $process = curl_init($url);
        curl_setopt(
            $process,
            CURLOPT_HTTPHEADER,
            array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->ACCESS_TOKEN,
            )
        );
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_POSTFIELDS, $jsonData);
        $response = curl_exec($process);
        curl_close($process);
        return $response;
    }

    /**
     * @return mixed
     * Получаем все товары магазина
     */
    public function getArrayAllListElem()
    {
        $this->getTokenForApi();
        $process = curl_init($this->URL_ALL_ELEM_ONLINER);
        curl_setopt(
            $process,
            CURLOPT_HTTPHEADER,
            array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->ACCESS_TOKEN,
            )
        );
        ob_start();
        curl_setopt($process, CURLOPT_GET, 1);
        curl_exec($process);
        curl_close($process);
        $response = ob_get_contents();
        ob_end_clean();
        $data_json = json_decode($response, true);
        return $data_json;
    }

    public function testApiOnliner()
    {
        $ListElements = $this->getListElements();
        $data = [
            ['id' => '15738',
                'category' => '',//'Декоративная косметика для глаз',
                'vendor' => 'L\'Oreal',
                'model' => '',//'Brow Artist Maker (тон 01)',
                'article' => 'E1575501', //'Matrix тр керл плиз шампунь 300мл',
                'price' => "10,13",
                'currency' => "BYN",
                'comment' => "Дополнительная скидка 2% на все товары магазина с промокодом ONLINER! Бесплатная доставка по Минску (при заказе от 30 рублей) и в любой город Беларуси (при заказе от 50 рублей)! Рассрочка 2 месяца по карте «Халва». 100% качество товаров.\r\nДля всех позиций товаров",
                'producer' => 'L\'Oreal Matrix',
                'importer' => "ООО «Сэльвин», 220141, г. Минск, ул. Академика Купревича, 14, 4 этаж, каб. 37",
                'serviceCenters' => "нет",
                'warranty' => "нет",
                'deliveryTownTime' => "1",
                'deliveryTownPrice' => "5,00",
                'deliveryCountryTime' => "3",
                'deliveryCountryPrice' => "5,00",
                'productLifeTime' => "нет",
                'isCashless' => "нет",
                'isCredit' => "нет",
                'stockStatus' => "in_stock",]
        ];

        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        PR($jsonData);
        $this->getTokenForApi();
        $response = $this->curl($jsonData);
        return $response;
    }

    public function testApiOnlinerUpdateElem()
    {
        /*$OneElements = $this->getArrayAllListElem()[92];
        $OneElements['price'] = '9.20';
        $jsonData = json_encode($OneElements, JSON_UNESCAPED_UNICODE);
        PR($jsonData);
        $this->getTokenForApi();
        $response = $this->curl($jsonData, $this->URL);
        $response = json_decode($response);*/



        $this->getTokenForApi();
        $process = curl_init('"https://b2bapi.onliner.by/pricelists/5d78e5346061ac23a4170fe4/status";');
        curl_setopt(
            $process,
            CURLOPT_HTTPHEADER,
            array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->ACCESS_TOKEN,
            )
        );
        ob_start();
        curl_setopt($process, CURLOPT_GET, 1);
        curl_exec($process);
        curl_close($process);
        $response = ob_get_contents();
        ob_end_clean();
        $data_json = json_decode($response, true);


        PR($data_json);
        //return $response;
    }
}