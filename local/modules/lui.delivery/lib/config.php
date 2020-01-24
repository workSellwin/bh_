<?php


namespace Lui\Delivery;


class Config
{
    protected $arHead = [
        '1CID' => '1С номер',//1
        'ID' => 'ID',//2
        'DATE' => 'Дата получения заказа',//3
        'TIME_FROM' => 'Время получения заказа с',//4
        'TIME_TO' => 'Время получения заказа по',//5
        'UNLOADED_ORDER' => 'Товарный чек выгружен',//6
        'PHONE' => 'Телефон',//7
        'FIO' => 'Фамилия и имя получателя',//8
        'DELIVERY' => 'Служба доставки',//9
        'CITY' => 'Населенный пункт',//10
        'PRIORITY' => 'Приоритет',//11
        'PRICE' => 'Сумма',//12
        'USER_DESCRIPTION' => 'Комментарии покупателя',//13
        'PAID' => 'Оплачен',//14
        'YANDEX_ADRESS' => 'Яндекс Адрес',//15
        'APARTMENT' => 'Квартира',//16
        'YANDEX_LON' => 'lon',//17
        'YANDEX_LAT' => 'lat',//18
        'YANDEX_Q' => 'Яндекс Запрос',//19
    ];
    protected $arHead2 = [
        '1CID' => '1с',//1
        'ID' => 'ID заказа',//2
        'DATE' => 'Дата получения заказа',//3
        'TIME_FROM' => 'Время получения заказа',//4
        'TIME_TO' => 'время закрытия',//5
        'PHONE' => 'Телефон',//6
        'FIO' => 'Фамилия и имя получателя',//7
        'YANDEX_ADRESS' => ' Город',//8
        'STREET' => 'Улица',//9
        'HOME' => 'Дом',//10
        'APARTMENT' => 'Квартира',//11
        'PRICE' => 'Сумма',//12
        'USER_DESCRIPTION' => 'Комментарии покупателя',//13
        'PAID' => 'Оплачен',//14
        'TIME_IDLE' => 'Время простоя', // 15
        'PRIORITY' => 'Приоритет',//16
    ];


    public function GetConfig($opt = 0)
    {
        return $opt == 1 ? $this->arHead : $this->arHead2;
    }

}
