<?php


namespace Lui\Delivery;


class OrdersData
{
    /**
     * Массив ID от базы выписки!
     *
     * @var array
     */
    protected $ar1CID = [];

    public function __construct()
    {
        $this->GetInitData();
    }

    public function GetOrders($from)
    {
        $arResult = [];
        $parameters = $this->GetFilterParams($from);
        $dbRes = \Bitrix\Sale\Order::getList($parameters);
        while ($ar = $dbRes->fetch()) {
            $arResult[] = $ar['ID'];
        }
        return $arResult;
    }

    public function GetFilterParams($from)
    {
        $parameters = $this->GetBaseParamsFilter($from);
        /*      if (!is_numeric($time) and $time) {
                  $parameters = $this->GetTimeParamsFilter($time, $parameters);
              }*/
        return $parameters;
    }


    public function GetBaseParamsFilter($from)
    {
        return [
            'select' => ['ID'],
            'filter' => [
                'PERSON_TYPE_ID' => 1,
                '=PROP_DATE.CODE' => 'DATE',
                '=PROP_DATE.VALUE' => date('d.m.Y', strtotime($from)),// date('d.m.Y'),
                "@STATUS_ID" => ["N", "GO", 'DD', 'PK', 'PP'],
            ],
            'order' => [
                "DATE_INSERT" => "DESC"
            ],
            'limit' => 200,
            'runtime' => [
                'PROP_DATE' => [
                    'data_type' => 'Bitrix\Sale\Internals\OrderPropsValueTable',
                    'reference' => array(
                        'ref.ORDER_ID' => 'this.ID',
                    ),
                    'join_type' => 'left'
                ],
            ],
        ];

    }

    public function GetTimeParamsFilter($time, $parameters)
    {
        $parameters['filter']['=PROP_TIME.CODE'] = 'TIME';
        $parameters['filter']['=PROP_TIME.VALUE'] = $time;
        $parameters['runtime']['PROP_TIME'] = [
            'data_type' => 'Bitrix\Sale\Internals\OrderPropsValueTable',
            'reference' => array(
                'ref.ORDER_ID' => 'this.ID',
            ),
            'join_type' => 'left'
        ];
        return $parameters;
    }


    /*** DATA ***/

    /**
     * @param array $arID
     * @param array $arHead
     * @return array
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public function GetData(array $arID = [], array $arHead)
    {
        $arResult = [];
        foreach ($arID as $id) {
            $ob = $order = \Bitrix\Sale\Order::load($id);
            $arOrder = $this->GetOrderData($ob);
            $arYandex = $this->GetYandexOrder($arOrder);
            foreach ($arHead as $field => $name) {
                $arResult[$id]['ROW'][$field] = $this->SetField($arOrder, $field, $arYandex);
            }
            $arResult[$id]['ORDER'] = $arOrder;
            $arResult[$id]['YANDEX'] = $arYandex;
        }
        return $arResult;
    }

    public function GetYandexOrder($arOrder)
    {
        $arData = [];
        if ($arOrder['PROPS']['RESPONSE_YANDEX']) {
            $arData['YANDEX_Q'] = $arOrder['PROPS']['REQUEST_YANDEX'];
            $arData['YANDEX_ADRESS'] = $arOrder['PROPS']['RESPONSE_YANDEX'];
            $arData['YANDEX_LON'] = $arOrder['PROPS']['LATITUDE'];
            $arData['YANDEX_LAT'] = $arOrder['PROPS']['LONGITUDE'];
            $arData['YANDEX_AR'] = [];
        } else {
            $ob = new \Lui\Delivery\YandexApi();
            $q = $ob->GetQuery($arOrder['PROPS']);
            $arData['YANDEX_Q'] = $q;
            $arYandex = $ob->GetDataYandex($q);
            if ($arYandex['Point']) {
                $YANDEX_ADRESS = $arYandex['Address']['formatted'];
                $YANDEX_ADRESS = str_replace('Беларусь,', '', $YANDEX_ADRESS);
                $arPoint = explode(' ', $arYandex['Point']);
                $YANDEX_LAT = $arPoint[0];
                $YANDEX_LON = $arPoint[1];
            }
            $arData['YANDEX_ADRESS'] = $YANDEX_ADRESS;
            $arData['YANDEX_LON'] = $YANDEX_LON;
            $arData['YANDEX_LAT'] = $YANDEX_LAT;
            $arData['YANDEX_AR'] = $arYandex;
        }

        return $arData;
    }

    public function GetYandex($arOrder)
    {
        $arData = [];
        $arYandex = [];
        $YANDEX_ADRESS = '';
        $YANDEX_LON = '';
        $YANDEX_LAT = '';
        if ($arOrder['PROPS']['REQUEST_YANDEX']) {
            $arData['YANDEX_Q'] = $arOrder['PROPS']['REQUEST_YANDEX'];
            $YANDEX_ADRESS = $arOrder['PROPS']['RESPONSE_YANDEX'];
            $YANDEX_LON = $arOrder['PROPS']['LATITUDE'];
            $YANDEX_LAT = $arOrder['PROPS']['LONGITUDE'];
        } else {
            $ob = new \Lui\Delivery\YandexApi();
            $q = $ob->GetQuery($arOrder['PROPS']);
            $arData['YANDEX_Q'] = $q;
            $arYandex = $ob->GetDataYandex($q);
            if ($arYandex['Point']) {
                $YANDEX_ADRESS = $arYandex['Address']['formatted'];
                $YANDEX_ADRESS = str_replace('Беларусь,', '', $YANDEX_ADRESS);
                $arPoint = explode(' ', $arYandex['Point']);
                $YANDEX_LAT = $arPoint[0];
                $YANDEX_LON = $arPoint[1];
            }
        }
        $arData['YANDEX_ADRESS'] = $YANDEX_ADRESS;
        $arData['YANDEX_LON'] = $YANDEX_LON;
        $arData['YANDEX_LAT'] = $YANDEX_LAT;
        $arData['YANDEX_AR'] = $arYandex;
        return $arData;
    }


    public function SetField($arOrder, $field, $arYandex = [])
    {
        $id = $arOrder['ID'];
        $prop = $arOrder['PROPS'];
        $fields = $arOrder['FIELD'];
        switch ($field) {
            case '1CID':
                $val = $this->ar1CID[$id] ? $this->ar1CID[$id] : Code1cGetSellwin::GetID($id);
                break;
            case 'ID':
                $val = $id;
                break;
            case 'DATE':
                $val = $prop['DATE'];
                break;
            case 'TIME_FROM':
                $dataFromTo = explode('-', $prop['TIME']);
                $val = str_replace('.', ':', $dataFromTo[0]);
                break;
            case 'TIME_TO':
                $dataFromTo = explode('-', $prop['TIME']);
                $val = str_replace('.', ':', $dataFromTo[1]);
                break;
            case 'UNLOADED_ORDER':
                $val = $prop['UNLOADED_ORDER'];
                break;
            case 'PHONE':
                $val = ' ' . $prop['PHONE'];
                break;
            case 'FIO':
                $val = $prop['FIO'];
                break;
            case 'DELIVERY':
                $delivery_name = '';
                $DELIVERY_ID = $arOrder['DELIVERY_ID'];
                if ($DELIVERY_ID) {
                    $delivery = \Bitrix\Sale\Delivery\Services\Manager::getById($DELIVERY_ID);
                    /*if (isset($delivery['PARENT_ID']) && $delivery['PARENT_ID'] > 0) {
                        $delivery_parent = \Bitrix\Sale\Delivery\Services\Manager::getById($delivery['PARENT_ID']);
                        $delivery_name = $delivery_parent['NAME'] . ' - ';
                    }*/
                    $delivery_name .= $delivery['NAME'];
                }
                $val = $delivery_name;
                break;
            case 'CITY':
                $val = $prop['CITY'];
                break;
            case 'PRIORITY':
                $val = $prop['PRIORITY'];
                break;
            case 'PRICE':
                $val = floatval($fields['PRICE']);
                break;
            case 'USER_DESCRIPTION':
                $val = $fields['USER_DESCRIPTION'];
                break;
            case 'PAID':
                $val = $arOrder['PAID'] ? 'Да' : '-';
                break;
            case 'YANDEX_ADRESS':
                $val = $arYandex['YANDEX_ADRESS'];
                break;
            case 'APARTMENT':
                $val = $prop['ROOM'];
                break;
            case 'YANDEX_LON':
                $val = $arYandex['YANDEX_LON'];
                break;
            case 'YANDEX_LAT':
                $val = $arYandex['YANDEX_LAT'];
                break;
            case 'YANDEX_Q':
                $val = $arYandex['YANDEX_Q'];
                break;
            case 'TIME_IDLE':
                if (in_array($arOrder['DELIVERY_ID'], [4, 9, 19, 26, 27, 28, 29, 30, 31, 32])) {
                    $val = 60;
                } else {
                    $val = 300;
                }
                break;
            default:
                $val = null;
                break;
        }
        return $val;
    }

    public
    function GetInitData()
    {
        $this->ar1CID = Code1cGetSellwin::GetAllActive();
    }


    public
    function GetOrderData($ob)
    {
        $arData = [];
        $arData['ID'] = $ob->GetID();
        $arData['DELIVERY_ID'] = $ob->getField('DELIVERY_ID');
        $arData['PAY_SYSTEM_ID'] = $ob->getField('PAY_SYSTEM_ID');
        $arData['STATUS_ID'] = $ob->getField('STATUS_ID');
        $arData['PAID'] = $ob->isPaid();
        $propertyCollection = $ob->getPropertyCollection();
        $propsData = [];
        foreach ($propertyCollection as $propertyItem) {
            $codeF = $propertyItem->getField("CODE");
            switch ($codeF) {
                case 'EMAIL':
                    $propsData[$codeF] = trim($propertyItem->getValue());
                    break;
                default:
                    $propsData[$codeF] = trim($propertyItem->getViewHtml());
                    break;
            }
        }

        $arData['PROPS'] = $propsData;
        foreach ($ob->getAvailableFields() as $code) {
            $arData['FIELD'][$code] = $ob->getField($code);
        }
        return $arData;
    }

    public
    function validation($arOrders)
    {

        foreach ($arOrders as $id => &$order) {
            $order['CONFIG'] = $this->GetConf($order);
        }
        return $arOrders;
    }

    protected
    function GetConf($order)
    {
        $arData = [];
        $arData['STYLE'] = in_array($order['ORDER']['FIELD']['STATUS_ID'], ['PP', 'DD']) ? 'style="background: yellow;"' : '';
        $arData['ERROR'] = 'N';
        // if ($_REQUEST['IS_YANDEX']) {
        //  $ob = new \Lui\Delivery\YandexApi();
        /*if (!$ob->isValidAddress($order['YANDEX']['YANDEX_AR'])) {
            $arData['STYLE'] = 'style="background: #ff7272;"';
            $arData['ERROR'] = 'Y';
        }*/
        //   }

        return $arData;
    }


    public
    function deliveryRB($arOrders)
    {
        foreach ($arOrders as $id => &$order) {
            $row = $order['ROW'];
            if (self::isRB($order['ORDER']['DELIVERY_ID'])) {
                $row['USER_DESCRIPTION'] = 'ГЛОБЕЛ';
                $row['YANDEX_ADRESS'] = 'г. Минск, переулок Липковский, д.9/3';
                $row['TIME_FROM'] = '11:00';
                $row['TIME_TO'] = '12:00';
                $row['PRICE'] = '0';
                $row['TIME_IDLE'] = 60;
                // 00:00:
                //$row['YANDEX_LAT'] = '27.704324';
                // $row['YANDEX_LON'] = '53.919968';
                $order['YANDEX']['YANDEX_LAT'] = '27.704324';
                $order['YANDEX']['YANDEX_LON'] = '53.919968';
                if (in_array($order['ORDER']['STATUS_ID'], ['PP', 'DD']) !== false) {
                    $row['USER_DESCRIPTION'] .= ' | ПОВТОРНАЯ ДОСТАВКА';
                    $row['ID'] .= '-'.date('d.m.Y-H.i');

                }
                $row['FIO'] = '';
                $row['APARTMENT'] = '';
                $order['ROW'] = $row;
            } else {
                if (in_array($order['ORDER']['STATUS_ID'], ['PP', 'DD']) !== false) {
                    $order['ROW']['USER_DESCRIPTION'] .= ' | ПОВТОРНАЯ ДОСТАВКА';
                    $order['ROW']['ID'] .= '-'.date('d.m.Y-H.i');
                }
            }
        }
        return $arOrders;
    }


    public
    static function isRB($DELIVERY_ID)
    {
        return in_array($DELIVERY_ID, [4, 9, 19, 26, 27, 28, 29, 30, 31, 32]);
    }

    public
    static function isMinsk($DELIVERY_ID)
    {
        return in_array($DELIVERY_ID, [2, 6, 7, 8, 10, 11, 12, 14, 15, 16, 17, 20, 21, 43]);
    }

}
