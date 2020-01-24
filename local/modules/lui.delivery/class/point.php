<?php


class point extends stdClass
{
    public $latitude;              //01 шарота, десятичное, необязательное**
    public $longitude;             //02 долгота, десятичное, необязательное**
    public $weight;                //03 масса, десятичное, необязательное*
    public $volume;                //04 объём, десятичное, необязательное*
    public $readyTime;             //05 время готовности, целое, необязательное*

    public $dueTime;               //06 крайнее время прибытия, целое, необязательное*
    public $serviceTime;           //07 время на обслуживание, целое, необязательное*
    public $id;                    //08 идентификатор, строка, обязательное
    public $name;                  //09 имя, строка, необязательное*
    public $address;               //10 адрес, строка, необязательное**

    public $label;                 //11 пометка, строка, необязательное*
    public $text1;                 //12 текстовое поле № 1, строка, необязательное*
    public $text2;                 //13 текстовое поле № 2, строка, необязательное*
    public $text3;                 //14 текстовое поле № 3, строка, необязательное*
    public $text4;                 //14 текстовое поле № 4, строка, необязательное*
    public $orderNumber;           //15 номер заказа, строка, необязательное*

    public $phoneNumbers;          //16 номера телефонов, строка, необязательное*
    public $costs = [];            //17 стоимость, массив объёктов типа cost, необязательное*
    public $zoneId;                //18 id зоны
    public $radius;                //19 радиус, целое, необязательное*
    public $deliveryDate;          //20 дата доставки, дата, необязательное, по умолчанию неопределено

    public $priority;              //21 приоритет следования, целое из [1, 9], необязательное*
    public $insertionPriority;     //22 приоритет вставки, целое из [1, 9], необязательное*
    public $goods = [];            //23 товары, массив объектов типа good, необязательное, по умолчанию пусто


    public function SetPoint($arOrder)
    {
        $YANDEX=$arOrder['YANDEX'];
        $row = $arOrder['ROW'];
        $order = $arOrder['ORDER'];
        // $props = $order['PROPS'];
        $PAY_SYSTEM_ID = $order['PAY_SYSTEM_ID'];
        $STATUS_ID = $order['STATUS_ID'];
        $fields = $order['FIELD'];
        if ($row['TIME_FROM']) {
            $row['TIME_FROM'] = strtotime('01.01.1970 ' . $row['TIME_FROM']) + 3 * 60 * 60;
            $row['TIME_TO'] = strtotime('01.01.1970 ' . $row['TIME_TO']) + 3 * 60 * 60;
        }


       $this->latitude = $YANDEX['YANDEX_LAT'];// 1 шарота, десятичное, необязательное**
       $this->longitude = $YANDEX['YANDEX_LON'];// 2 долгота, десятичное, необязательное**
        $this->weight = $fields['ORDER_WEIGHT']; // 3 масса, десятичное, необязательное*
        $this->volume = $fields['ORDER_VOLUME']; // 4 объём, десятичное, необязательное*
        $this->readyTime = $row['TIME_FROM']; // 5 время готовности, целое, необязательное*

        $this->dueTime = $row['TIME_TO']; // 6 крайнее время прибытия, целое, необязательное*
        $this->serviceTime = $row['TIME_IDLE']; // 7 время на обслуживание, целое, необязательное*
        $this->id = $row['ID']; // идентификатор, строка, обязательное
        $this->name = $row['FIO']; // имя, строка, необязательное*
        $this->address = $row['YANDEX_ADRESS']; // 10 адрес, строка, необязательное**

        $this->label = '';                 //11 пометка, строка, необязательное*
        $this->text1 = $row['USER_DESCRIPTION'];                 //12 текстовое поле № 1, строка, необязательное*
        $this->text2 = $row['PAID'];                 //13 текстовое поле № 2, строка, необязательное*
        $this->text3 = $row['APARTMENT'];                 //14 текстовое поле № 3, строка, необязательное*
        $this->text4 = $row['1CID'];                 //14 текстовое поле № 3, строка, необязательное*
        $this->orderNumber = $row['ID'];           //15 номер заказа, строка, необязательное*

        $this->phoneNumbers = $row['PHONE'];          //16 номера телефонов, строка, необязательное*
        $cost = new \cost();
        $cost->SetCost((float)$row['PRICE'], $PAY_SYSTEM_ID);
        $this->costs = [
            $cost,
        ];            //17 стоимость, массив объёктов типа cost, необязательное*
        $this->zoneId;                //18 id зоны
        $this->radius;                //19 радиус, целое, необязательное*
        $this->deliveryDate = $row['DATE'] ? date('Y-m-d', strtotime($row['DATE'])) : '';          //20 дата доставки, дата, необязательное, по умолчанию неопределено

        $this->priority = 1;              //21 приоритет следования, целое из [1, 9], необязательное*
        $this->insertionPriority = 1;     //22 приоритет вставки, целое из [1, 9], необязательное*
        $this->goods = [];            //23 товары, массив объектов типа good, необязательное, по умолчанию пусто
        return $this->GetXml();

    }

    public function GetXml()
    {
        $request = <<<SOAPXML
<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
                   xmlns:ns1="http://ws.vrptwserver.beltranssat.by/">
    <SOAP-ENV:Body>
        <ns1:addPoint>
            <point>
                <ns1:latitude>{$this->longitude}</ns1:latitude>
                <ns1:longitude>{$this->latitude}</ns1:longitude>
                <ns1:weight>{$this->weight}</ns1:weight>
                <ns1:volume>{$this->volume}</ns1:volume>
                <ns1:readyTime>{$this->readyTime}</ns1:readyTime>
                <ns1:dueTime>{$this->dueTime}</ns1:dueTime>
                <ns1:serviceTime>{$this->serviceTime}</ns1:serviceTime>
                <ns1:id>{$this->id}</ns1:id>
                <ns1:name>{$this->name}</ns1:name>
                <ns1:address>{$this->address}</ns1:address>
                <ns1:label>{$this->label}</ns1:label>
                <ns1:text1>{$this->text1}</ns1:text1>
                <ns1:text2>{$this->text2}</ns1:text2>
                <ns1:text3>{$this->text3}</ns1:text3>
                <ns1:text4>{$this->text4}</ns1:text4>
                <ns1:orderNumber>{$this->orderNumber}</ns1:orderNumber>
                <ns1:phoneNumbers>{$this->phoneNumbers}</ns1:phoneNumbers>
                <ns1:costs>
                    <ns1:cost>
                        <ns1:cost>{$this->costs[0]->cost}</ns1:cost>
                        <ns1:type>{$this->costs[0]->type}</ns1:type>
                    </ns1:cost>
                </ns1:costs>
                <ns1:zoneId>{$this->zoneId}</ns1:zoneId>
                <ns1:radius>{$this->radius}</ns1:radius>
                <ns1:deliveryDate>{$this->deliveryDate}</ns1:deliveryDate>
                <ns1:priority>{$this->priority}</ns1:priority>
                <ns1:insertionPriority>{$this->insertionPriority}</ns1:insertionPriority>
                <ns1:goods>
                </ns1:goods>
            </point>
        </ns1:addPoint>
    </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
SOAPXML;
        return $request;
    }

}
