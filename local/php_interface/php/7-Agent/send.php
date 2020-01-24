<?php

/**
 * @return string
 * @throws \Bitrix\Main\ArgumentException
 * @throws \Bitrix\Main\SystemException
 */
function sendOrderAgent()
{
    CModule::IncludeModule('highloadblock');
    CModule::IncludeModule("sale");
    Cmodule::IncludeModule('catalog');
    Cmodule::IncludeModule('iblock');
    $entity_data_class = GetEntityDataClass(4);
    ob_start();
    $rsData = $entity_data_class::getList(array("select" => array("*"), 'filter' => array('UF_FLAG' => '0', 'UF_JSON_STRING' => false)));
    while ($el = $rsData->fetch()) {
        unset($resToJson);
        print_r($el);
        $resToJson["uuid"] = "id:" . $el["UF_ID_ORDER"];
        $resToJson["posted_datetime"] = $el["UF_DATE"];
        $resToJson["wave"] = $el["UF_WAVE"];


        $order = Sale\Order::load($el["UF_ID_ORDER"]);
        $basket = $order->getBasket();
        $basketItems = $basket->getBasketItems();
        foreach ($basket as $basketItem) {
            $arItems["ID"] = $basketItem->getId();
            $arItems["PRODUCT_ID"] = $basketItem->getProductId();
            $arItems["NAME"] = $basketItem->getField('NAME');
            $arItems["PRODUCT_XML_ID"] = $basketItem->getField('PRODUCT_XML_ID');
            $arItems["QUANTITY"] = $basketItem->getQuantity();

            $res = CIBlockElement::GetByID($arItems["PRODUCT_ID"]);
            if ($ar_res = $res->GetNext()) {
                $arItems["PRODUCT_XML_ID_NEW"] = $ar_res["XML_ID"];
            } else {
                $arItems["PRODUCT_XML_ID_NEW"] = $arItems["PRODUCT_XML_ID"];
            }

            $arResult[] = $arItems;
            $resToJson["spec"][] = array("goods" => $arItems["PRODUCT_XML_ID_NEW"], "count" => $arItems["QUANTITY"]);
        }

        print_r($resToJson);
        $jsonDate = json_encode($resToJson);
        print_r($jsonDate);


        $httpClient = new \Bitrix\Main\Web\HttpClient();
        $httpClient->waitResponse(true);
        $httpClient->setTimeout(10);
        $httpClient->setStreamTimeout(10);
        $httpClient->setHeader('Content-Type', 'application/json', true);
        $httpClient->post('http://evesell.sellwin.by/eve-adapter-sellwin/doc/store/post', $jsonDate);
        $jsonRes = json_decode($httpClient->getResult(), true);
        print_r(' ответ сервера ');
        print_r($jsonRes);
        if ($jsonRes["response"]) {
            $entity_data_class::delete($el["ID"]);
        }
        print_r($jsonRes);
    }

    $rsData = $entity_data_class::getList(array("select" => array("*"), 'filter' => array('UF_FLAG' => '0', '!UF_JSON_STRING' => false)));
    while ($el = $rsData->fetch()) {
        if ($el['UF_JSON_STRING']) {
            $jsonDate = $el['UF_JSON_STRING'];
            $httpClient = new \Bitrix\Main\Web\HttpClient();
            $httpClient->waitResponse(true);
            $httpClient->setTimeout(10);
            $httpClient->setStreamTimeout(10);
            $httpClient->setHeader('Content-Type', 'application/json', true);
            $httpClient->post('http://evesell.sellwin.by/eve-adapter-sellwin/doc/json/order/post', $jsonDate);
            $jsonRes = json_decode($httpClient->getResult(), true);
            AddMessage2Log("\n\n\n\n\n Повторная отправка заказа в систему №" . $el["UF_ID_ORDER"] . "\n\n Запрос\n" . $jsonDate . "\n\nОтвет сервера\n\n" . $jsonRes);
            if ($jsonRes["response"]) {
                $entity_data_class::delete($el["ID"]);
            }
            print_r($jsonRes);
        }
    }
    $res89 = ob_get_clean();
    AddMessage2Log("\n------- запустился --------\n" . $res89);
    return "sendOrderAgent();";
}


function sendOrder1cAgent()
{

}


function CreatDiscountCoupon(){
    $coupon = new createDiscountCoupon([1895, 1896]);
    $text_coupon = $coupon->GetCode();
    $coupon -> sendEmail('d.maznyak@sellwin.by, dianabsu11@gmail.com', $text_coupon);

    return "CreatDiscountCoupon();";
}



/**
 * функция рассылки сообщений пользователем об изменении цены
 *
 * @return string
 */
function PriceMonitorAgent()
{
    $arData = PriceMonitor::getInstance()->GetAll();
    //1 получим все цены!
    \CModule::IncludeModule("catalog");
    \CModule::IncludeModule("iblock");
    $arName=[];
    $arSelect = Array("ID", "IBLOCK_ID", "NAME");
    $arFilter = Array("IBLOCK_ID"=>2, "ID"=>array_column($arData,'UF_PRODUCT_ID'));
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
    while($arFields = $res->GetNext()){
        $arName[$arFields['ID']]=$arFields['NAME'];
    }

    foreach ($arData as &$data) {
        $arPrice = \CCatalogProduct::GetOptimalPrice($data['UF_PRODUCT_ID'], 1, [2], 'N', 's1');
        $price = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
        $data['NEW_PRICE'] = $price;
        $data['NAME'] = $arName[$data['UF_PRODUCT_ID']];
    }
    $arData = array_filter($arData, function ($var) {
        return $var['NEW_PRICE'] != $var['UF_PRICE'];
    });
    if ($arData) {
        $arUserData = [];
        $ob= PriceMonitor::getInstance()->GetObj();
        foreach ($arData as $data) {
            $arUserData[$data['UF_USER_ID']][] = $data;
        }
        foreach ($arUserData as $userID => $arProduct) {
            $TEXT = ShowTablePriceMonitor($arProduct);
            $rsUser = CUser::GetByID($userID);
            $arUser = $rsUser->Fetch();
            $userEmail = $arUser['EMAIL'];
            foreach ($arProduct as $std){
                if($std['UF_PRICE']>$data['NEW_PRICE'] and $arUser['PERSONAL_PHONE']){
                    $ob->SendSms([
                        $data['NAME'],
                        $data['UF_PRICE'],
                        $data['NEW_PRICE'],
                    ],$arUser['PERSONAL_PHONE']);
                }
            }
            \CEvent::Send("PRICE_MONITOR", ['s1'], [
                'USER_EMAIL' => $userEmail,
                'TEXT' => $TEXT,
            ]);
            foreach ($arProduct as $prod){
                $ob::update($prod['ID'],['UF_PRICE'=>$prod['NEW_PRICE']]);
            }
        }
    }
    return "PriceMonitorAgent();";
}


function ShowTablePriceMonitor($arData){
    $table='<table>';
    $table.='<tr>
<th>Товар</th>
<th>Старая цена</th>
<th>Новая цена</th>
</tr>';
    foreach ($arData as $data){
        $table.="<tr>
<td>{$data['NAME']}</td>
<td>{$data['UF_PRICE']}</td>
<td>{$data['NEW_PRICE']}</td>
</tr>";
    }
    $table.='</table>';
    return $table;
}

