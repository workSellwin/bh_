<?php

use Bitrix\Main;
use Bitrix\Main\Event;
use Bitrix\Sale;


/**
 * @param $ID
 * @param $arOrder
 * @param $arParams
 * @throws Exception
 */
function PriorityOrder($ID, $arOrder, $arParams)
{
    $priority = new PriorityOrder($ID);
    $priority->setPriorityOrder();
    $ob = new SendOrderSms($ID);
    $ob->Send();
}


/**
 * @param $ID
 * @param $arOrder
 * @param $arParams
 * @throws Exception
 */
function SenderSms($ID, $arOrder = [], $arParams = [])
{
    $ob = new SendOrderSms($ID);
    $ob->Send();
}


function addGiftsFunction(Main\Event $event)
{
    /** @var \Bitrix\Sale\Order $order */
    $order = $event->getParameter("ENTITY");
//добавляем подарок в заказ
    if (isset($_REQUEST['ELEM_GIFTS_ID'])) {


        $basket = $order->getBasket();

        if ($item = $basket->getExistsItem('catalog', $_REQUEST['ELEM_GIFTS_ID'])) {
            //$item->setField('QUANTITY', $item->getQuantity() + 1);
        } else {
            $res = CIBlockElement::GetByID($_REQUEST['ELEM_GIFTS_ID']);
            $ar_res = $res->GetNext();
            $item = $basket->createItem('catalog', $_REQUEST['ELEM_GIFTS_ID'], $basketCode = null);
            $item->setFields(array(
                'QUANTITY' => 1,
                'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
                //'PRICE' => 0,
                //'NAME' => '(ПОДАРОК) '. $ar_res['NAME'],
                //'CUSTOM_PRICE' => 'Y',
            ));
        }

        /*$discount = $order->getDiscount();
        \Bitrix\Sale\DiscountCouponsManager::clearApply(true);
        \Bitrix\Sale\DiscountCouponsManager::useSavedCouponsForApply(true);
        $discount->setOrderRefresh(true);
        $discount->setApplyResult(array());*/

        $basket->refreshData();
        //$discount->calculate();

        $basket->save();


    }
}


/**
 * @param Main\Event $event
 * @throws Main\ArgumentOutOfRangeException
 * @throws Main\NotImplementedException
 */
function PriorityOrderFunction(Main\Event $event)
{

    /** @var \Bitrix\Sale\Order $order */
    $order = $event->getParameter("ENTITY");

    if (!$order->getId()) {


        /** @var \Bitrix\Sale\PropertyValueCollection $propertyCollection */
        $propertyCollection = $order->getPropertyCollection();

        $propsData = [];
        /**
         * Собираем все свойства и их значения в массив
         * @var \Bitrix\Sale\PropertyValue $propertyItem
         */
        foreach ($propertyCollection as $propertyItem) {
            if (!empty($propertyItem->getField("CODE"))) {
                $propsData[$propertyItem->getField("CODE")] = trim($propertyItem->getValue());
            }
        }

        $ob = new PriorityOrderNew($propsData, $order->getPersonTypeId(), $order->getField('DELIVERY_ID'));
        $arDate = $ob->setPriorityOrder();

        $dayofweek = date('w', strtotime($arDate['dateOrder']));
        //если субота день доставки изменяем на пятницу
        if ($dayofweek == 6) {
            $arDate['dateOrder'] = date('d.m.Y', strtotime($arDate['dateOrder'] . ' - 1 days'));
            $arDate['priority'] = 'priority-3';
        }

        $isDate = false;
        $isTime = false;
        $isPrior = false;
        foreach ($propertyCollection as $propertyItem) {


            /**
             * Пропуск установки даты доставки для юр лиц
             */
            if ($order->getPersonTypeId() == 2 and $propertyItem->getField("CODE") == 'DATE') {
                if ($propsData['dateOrder']) {
                    $isDate = true;
                }
                continue;
            }


            switch ($propertyItem->getField("CODE")) {

                case 'PRIORITY':// Установка Приоритета!
                    if (!$propsData['priority']) {
                        $propertyItem->setField("VALUE", $arDate['priority']);
                    }
                    $isPrior = true;
                    break;
                case 'TIME':// Установка Времени!
                    if (!$propsData['timeOrder']) {
                        $propertyItem->setField("VALUE", $arDate['timeOrder']);
                    }
                    $isTime = true;
                    break;
                case 'DATE':// Установка Даты!
                    if (!$propsData['dateOrder'] or $arDate['dateOrder'] != $propsData['dateOrder']) {
                        $propertyItem->setField("VALUE", $arDate['dateOrder']);
                    }
                    $isDate = true;
                    break;
                case 'DELIVERY_SATURDAY':// Установка флага доставки в субботу!
                    if ($dayofweek == 6) {
                        $propertyItem->setField("VALUE", 'Да');
                    }
                    break;
                case 'CERTIFICATE':// Установка флага Сертификат
                    $basket = $order->getBasket();
                    $cerY = false;
                    foreach ($basket as $basketItem) {
                        $n = $basketItem->getField('NAME');
                        if (strpos($n, 'Подарочный сертификат') !== false) {
                            $cerY = true;
                            break;
                        }
                    }
                    if ($cerY) {
                        $propertyItem->setField("VALUE", 'Y');
                    } else {
                        $propertyItem->setField("VALUE", 'N');
                    }
                    break;
            }
        }

        if (!$isPrior and $order->getPersonTypeId() == 2) {
            $propertyValue = \Bitrix\Sale\PropertyValue::create($propertyCollection, [
                'ID' => 31,
                'NAME' => 'Приоритет',
                'TYPE' => 'STRING',
                'CODE' => 'PRIORITY',
            ]);
            $propertyValue->setField('VALUE', $arDate['priority']);
            $propertyCollection->addItem($propertyValue);
        }

        if (!$isDate and !$propsData['DATE']) {
            $idProp = 13;
            if ($order->getPersonTypeId() == 2) {
                $idProp = 21;
            }
            $propertyValue = \Bitrix\Sale\PropertyValue::create($propertyCollection, [
                'ID' => $idProp,
                'NAME' => 'Дата получения заказа',
                'TYPE' => 'DATE',
                'CODE' => 'DATE',
            ]);
            $propertyValue->setField('VALUE', $arDate['dateOrder']);
            $propertyCollection->addItem($propertyValue);
        }

        SetYandexDateOrder($order);

    } elseif ($orderId = $order->getId()) {

        $newStatus = $order->getField('STATUS_ID');
        $orderOld = Bitrix\Sale\Order::load($order->getId());
        $oldStatus = $orderOld->getField('STATUS_ID');
        if ($newStatus == 'N' and $oldStatus == 'PK') {
            SetYandexDateOrder($order);
        }
    }
}

function SetYandexDateOrder(Bitrix\Sale\Order $order)
{
    $arDelivery = [2, 4, 7, 9, 19];
    if ($order->getPersonTypeId() == 1 and in_array($order->getField('DELIVERY_ID'), $arDelivery)) {
        \Cmodule::IncludeModule('lui.delivery');
        $ya = new \Lui\Delivery\YandexApi();
        $ob = new \Lui\Delivery\OrdersData();
        $arOrderZ = $ob->GetOrderData($order);
        $arYandex = $ob->GetYandex($arOrderZ);
        if (!$ya->isValidAddress($arYandex['YANDEX_AR'])) {
            $order->setField('STATUS_ID', 'PK');
        }
        $propertyCollection = $order->getPropertyCollection();
        $propertyCollection->getItemByOrderPropertyId(34)->setValue($arYandex['YANDEX_LON']);// Longitude
        $propertyCollection->getItemByOrderPropertyId(35)->setValue($arYandex['YANDEX_LAT']);// Latitude
        $propertyCollection->getItemByOrderPropertyId(36)->setValue($arYandex['YANDEX_Q']);// Запрос в яндекс
        $propertyCollection->getItemByOrderPropertyId(37)->setValue($arYandex['YANDEX_ADRESS']);// Ответ от Яндекса
    }
}


/**
 * OnSaleStatusOrder
 *
 * @param $ID
 * @param $Val
 * @throws \Bitrix\Main\ArgumentNullException
 * @throws \Bitrix\Main\LoaderException
 * @throws \Bitrix\Main\NotImplementedException
 */
function OnSaleStatusOrderHandler($ID, $Val)
{

    switch ($Val) {
        case 'SW':
            //break;
            /*
             * Отправка на склад заказа юр. лица при смене статуса на SW (отправить на склад)
             */

            \Bitrix\Main\Loader::includeModule('iblock');
            \Bitrix\Main\Loader::includeModule('sale');

            $order = \Bitrix\Sale\Order::load($ID);
            $typeID = $order->getPersonTypeId();

            switch ((int)$typeID) {
                /*
                 * Юридическое лицо
                 */
                case 2:

                    $arResult = Mitlab::getOrderData($ID, $order);
                    foreach ($arResult['ITEMS_TO_STORAGE'] as $itemStorage) {
                        $jsonDate = json_encode($itemStorage);
                        $httpClient = new \Bitrix\Main\Web\HttpClient();
                        $httpClient->waitResponse(true);
                        $httpClient->setTimeout(10);
                        $httpClient->setStreamTimeout(10);
                        $httpClient->setHeader('Content-Type', 'application/json', true);
                        $httpClient->post('http://evesell.sellwin.by/eve-adapter-sellwin/doc/json/order/post', $jsonDate);
                        $jsonRes = json_decode($httpClient->getResult(), true);
                        ob_start();
                        print_r($jsonDate);
                        print_r($jsonRes);
                        $writeToLog = ob_get_clean();
                        AddMessage2Log("\n\n\n\n\n Отправка заказа в систему №" . $order->getId() . "\n\nОтвет сервера\n\n" . $writeToLog);
                        if (!$jsonRes["response"]) {
                            $entity_data_class = GetEntityDataClass(4);
                            $result = $entity_data_class::add(array(
                                'UF_FLAG' => '0',
                                'UF_ID_ORDER' => $ID,
                                'UF_JSON_STRING' => $jsonDate
                            ));
                        }
                    }


                    break;
                default:
                    break;
            }
            break;
        case 'DD':
            if (CModule::IncludeModule('mlife.smsservices')) {
                CModule::IncludeModule('sale');
                $obSmsServ = new CMlifeSmsServices();
                $code = 'delay' . $ID;
                $text = 'Приносим извинения за задержку доставки Вашего заказа! В качестве извинений за причиненные неудобства дарим Вам скидку на следующий заказ 10% по промокоду ' . $code;
                $phone = GetPhoneUserForOrder($ID);
                if ($phone) {
                    $arSend = $obSmsServ->sendSms($phone, $text, 0, 'BH.BY');
                    if ($arSend->error) {
                        AddMessage2Log('Задержка доставки Ошибка отправки смс: ' . $arSend->error . ', код ошибки: ' . $arSend->error_code);
                    } else {
                        AddMessage2Log('Задержка доставки Сообщение успешно отправлено, Стоимость рассылки:' . $arSend->cost . ' руб.');
                    }
                }
                if ($code) {
                    addDiscountCoupon(491, $code);
                }
            }
            break;
        default;
            break;
    }
}


function addDiscountCoupon($id, $code)
{
    CModule::IncludeModule('sale');
    $arFields = [
        "DISCOUNT_ID" => $id,
        "COUPON" => $code,
        "ACTIVE" => "Y",
        "ACTIVE_FROM" => '',
        "ACTIVE_TO" => '',
        "TYPE" => 2,
        "USER_ID" => '',
        "DESCRIPTION" => '',
    ];
    \Bitrix\Sale\Internals\DiscountCouponTable::add($arFields);
}

/**
 * OnBeforeOrderAdd
 *
 * @param $arFields
 * @return bool
 */
function OnBeforeOrderAddHandler(&$arFields)
{
    global $APPLICATION;
    $e = new CAdminException(['id' => 'data', 'text' => 'data']);
    // ob_start();
    // print_r($arFields);
    // $res99 = ob_get_clean();
    // AddMessage2Log($res99);
    // $db_vars = CSaleLocation::GetList( array(),array("CODE" => $arFields["ORDER_PROP"][6],"LID" => "ru"),false,false,array());
    // if($vars = $db_vars->Fetch()){
    //  $resCity = $vars["CITY_NAME"];
    // }
    // $arFields["ORDER_PROP"]["17"] = $resCity." ".$arFields["ORDER_PROP"][16]." д.".$arFields["ORDER_PROP"][14]." кв.".$arFields["ORDER_PROP"][15];

    /*if($arFields["PRICE"]<=30){
        $APPLICATION->throwException("Минимальная сумма заказа 30 руб.");
        return false;
    }else{*/
    Cmodule::IncludeModule('catalog');
    Cmodule::IncludeModule('iblock');
    foreach ($arFields["BASKET_ITEMS"] as $key => $value) {
        $res = CIBlockElement::GetByID($value["PRODUCT_ID"]);
        if ($ar_res = $res->GetNext()) $PRODUCT_XML_ID = $ar_res["XML_ID"];

        $json = file_get_contents('http://evesell.sellwin.by/eve-adapter-sellwin/stockforstorecount/json?good=' . $PRODUCT_XML_ID);
        $jsonRes = json_decode($json, true);

        if (!empty($jsonRes["dataStore"][0]["count"])) {
            CCatalogProduct::Update($value["PRODUCT_ID"], array('QUANTITY' => $jsonRes["dataStore"][0]["count"]));
            if (($value["QUANTITY"] > $jsonRes["dataStore"][0]["count"]) || $jsonRes["dataStore"][0]["count"] == 0) {
                $err = true;
                if ($jsonRes["dataStore"][0]["count"] == 0) {
                    $count = 0;
                } else {
                    $count = $jsonRes["dataStore"][0]["count"];
                }
                $e->AddMessage(
                    array(
                        "text" => 'К сожалению товар ' . $value["NAME"] . ' на данный момент на складе в количестве: ' . $count . ' шт.',
                    )
                );
            }
        }
        unset($PRODUCT_XML_ID);
    }
    if ($err) {
        $APPLICATION->ThrowException($e);
        return false;
    }


    if (isset($_SESSION['ERROR_ORDER_PROP_38'])) {
        $ek = new CAdminException();
        $ek->AddMessage(
            array(
                "text" => 'Неверный номер карты Купилка или Моцная карта',
            )
        );
        $APPLICATION->ThrowException($ek);
        return false;
    }

    //}
    //return true;
}

/**
 * OnOrderAdd
 *
 * @param $ID
 * @param $arFields
 */
function OnOrderAddHandler($ID, $arFields)
{
    if ($arFields["ORDER_PROP"][10] == "Y" && CModule::IncludeModule('subscribe')) {

        $subscr = CSubscription::GetList(
            array("ID" => "ASC"),
            array("RUBRIC" => 2, "CONFIRMED" => "Y", "ACTIVE" => "Y", "EMAIL" => $arFields["ORDER_PROP"][2])
        );
        if (($subscr_arr = $subscr->Fetch())) {
            $flag = false;
        }

        if (empty($flag)) {
            $arFields2 = Array(
                "USER_ID" => false,
                "FORMAT" => "html",
                "EMAIL" => $arFields["ORDER_PROP"][2],
                "ACTIVE" => "Y",
                "SEND_CONFIRM" => "N",
                "CONFIRMED" => "Y",
                "RUB_ID" => array(2)
            );
            $subscr2 = new CSubscription;

            $ID2 = $subscr2->Add($arFields2);
        }
    }
}


/**
 * OnSaleComponentOrderOneStepComplete
 *
 * Добовление купона при оформлении заказа если есть товар Подарочный сертификат
 *
 * @param $ID
 * @param $arOrder
 * @param $arParams
 * @throws \Bitrix\Main\ArgumentException
 * @throws \Bitrix\Main\ArgumentNullException
 * @throws \Bitrix\Main\ArgumentOutOfRangeException
 * @throws \Bitrix\Main\NotImplementedExceptionCAdminException
 */
function HandlerOnSaleComponentOrderOneStepComplete($ID, $arOrder, $arParams)
{
    if ($ID) {
        \CModule::IncludeModule("sale");
        $dbBasketItems = \CSaleBasket::GetList(array(), array("ORDER_ID" => $ID), false, false, array());
        $arAddСertificate = [];
        while ($arItem = $dbBasketItems->Fetch()) {
            if (strpos($arItem['NAME'], 'Подарочный сертификат') !== false) {
                $arAddСertificate[] = (int)$arItem['PRICE'];
            }
        }
        $arCode = [];
        if (!empty($arAddСertificate)) {
            $ob = new CreateDiscountСertificate();
            foreach ($arAddСertificate as $sum) {
                $c = $ob->GetCode($sum);
                if ($c) {
                    $arCode[$c] = $sum;
                }
            }
            $text = "Сертификат на сумму ";
            foreach ($arCode as $c => $s) {
                $text .= "{$s} BVN Код {$c} | ";
            }
            $order = \Bitrix\Sale\Order::load($ID);
            $order->setField("COMMENTS", $text);
            $order->save();
        }
        // Чистим копию корзины
        $ob = new SyncBasket();
        $ob->DeleteOldBasket();
    }

}


/**
 * OnOrderNewSendEmail
 *
 * Убираем текст в письме для торгового предложения
 *
 * @param $ID
 * @param $eventName
 * @param $arFields
 */
function HandlerOnOrderNewSendEmail($ID, &$eventName, &$arFields)
{

    $separator = '<br>';
    $ob = \Bitrix\Sale\Order::load($ID);
    $basketList = '';
    $basket = $ob->getBasket();
    if ($basket) {
        $arBasket = [];
        $basketItems = $basket->getBasketItems();
        foreach ($basketItems as $item) {
            $arBasket[$item->getId()] = ['URL' => $item->getField('DETAIL_PAGE_URL')];
        }
        $basketTextList = $basket->getListOfFormatText();
        if (!empty($basketTextList)) {
            foreach ($basketTextList as $basketItemCode => $basketItemData) {
                if ($arBasket[$basketItemCode]['URL']) {
                    $url = $arBasket[$basketItemCode]['URL'];
                    $basketItemData .= " <a href='{$url}#review'>Оставить отзыв</a>";
                }
                $basketList .= $basketItemData . $separator;
            }
        }
    }
    $arFields['ORDER_LIST'] = $basketList;

    $s = &$arFields['ORDER_LIST'];
    if ($start = strpos($s, '[Для') and $finish = strpos($s, '.)]')) {
        $s = substr($s, 0, $start) . substr($s, $finish + 4);
    }
}


function GetPhoneUserForOrder($id)
{
    $phone = '';
    CModule::IncludeModule('sale');
    $obProps = Bitrix\Sale\Internals\OrderPropsValueTable::getList(array('filter' => array('ORDER_ID' => $id, 'CODE' => 'PHONE')));
    if ($prop = $obProps->Fetch()) {
        $phone = $prop['VALUE'];
    }
    return $phone;
}


// службы доставки
function MyDeliveryFunction()
{
    return new \Bitrix\Main\EventResult(
        \Bitrix\Main\EventResult::SUCCESS,
        array(
            'MyDeliveryRestriction' => '/local/php_interface/php/3-class/MyDeliveryRestriction.php',
        )
    );
}

//безноминальный купон
function OnSaleBasketBeforeSavedHandler(Event $event)
{
    if ($_GET['action'] == 'ADD2BASKET' && $_GET['id'] == CATALOG_CUSTOM_CERT_NON_NOMINEE && intval($_POST['cert_summ']) > 0) {

        $parameters = $event->getParameters();
        $basket = $parameters['ENTITY'];

        foreach ($basket as $basketItem) {

            if ($basketItem->getProductId() == CATALOG_CUSTOM_CERT_NON_NOMINEE) {

                $basketItem->setFields(array(
                    'PRICE' => $_POST['cert_summ'],
                    'CUSTOM_PRICE' => 'Y',
                ));
            }
        }
    }
}

function deactivateUsedCoupon($ID, $arOrder, $arParams)
{

    if (intval($ID) > 0) {

        Cmodule::IncludeModule('sale');
        $order = \Bitrix\Sale\Order::load($ID);
        $couponList = $order->getDiscount()->getApplyResult()['COUPON_LIST'];

        foreach ($couponList as $coupon) {
            //TYPE == 2 - одноразовый
            if ($coupon['TYPE'] == 2 && $coupon['APPLY'] == 'Y') {
                \Bitrix\Sale\Internals\DiscountCouponTable::update($coupon['COUPON_ID'], ['ACTIVE' => 'N']);
            }
        }
    }
}

/*
 * отмена заказа
 */
function OnSaleBeforeCancelOrderHandler($ID, $val)
{

    if ($val == 'Y' && intval($ID) > 0) {
        $order = Sale\Order::load($ID);

        if (!$order->isPaid() && !$order->isCanceled()) {//не оплачен и не отменён
            //CL - статус отменён
            $objResult = $order->setField('STATUS_ID', 'CL');

            if ($objResult->isSuccess()) {
                $order->save();
            }
        } else {
            return false;
        }
    }
}
