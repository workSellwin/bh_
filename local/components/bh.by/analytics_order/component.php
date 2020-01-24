<?
use Bitrix\Sale;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

function setSessionAnalyticsOrderId($ORDER_ID)
{
    $_SESSION['ANALYTICS_ORDER_ID'][$ORDER_ID] = 'Y';
}

function getSessionAnalyticsOrderId($ORDER_ID){
    if(isset($_SESSION['ANALYTICS_ORDER_ID']) && !empty($_SESSION['ANALYTICS_ORDER_ID'])){
        if($_SESSION['ANALYTICS_ORDER_ID'][$ORDER_ID] == 'Y'){
            return false;
        }else{
            return true;
        }
    }else{
        return true;
    }
}

function getTypeDelivery($deliveryId)
{
    $arIdMinsk = [2,3,6,7,8,10,11,12,16,17,20,21];
    $arIdRb = [4,9,19,26,27,28,29,30,31,32];
    if(in_array($deliveryId, $arIdMinsk)){
        return 'minsk';
    }
    if(in_array($deliveryId, $arIdRb)){
        return 'rb';
    }
}

function getTypePerson($personTypeId)
{
    switch ($personTypeId) {
        case 1:
            return 'fiz';
        case 2:
            return 'ur';
    }
}

function getActionName($ORDER_ID)
{

    $order = Sale\Order::load($ORDER_ID);
    $personTypeId = $order->getPersonTypeId();
    $deliverySystemId = $order->getDeliverySystemId();
    $deliveryCollection = $order->getShipmentCollection();

    $action = [];
    $getTypeDelivery = getTypeDelivery($deliverySystemId[0]);

    $action['ACTION_PERSON_PREF'] =  getTypePerson($personTypeId);
    $action['ACTION_DELIVERY_PREF'] = $getTypeDelivery;

    $action['ACTION_PERSON'] = 'action_'. $action['ACTION_PERSON_PREF'];
    $action['ACTION_DELIVERY'] = $action['ACTION_PERSON'] . '_' . $getTypeDelivery;

    $action['NAME_DELIVERY'] = $deliveryCollection->current()->getField('DELIVERY_NAME');

    return $action;
}


if (is_numeric($arParams['ORDER_ID'])) {

    if (getSessionAnalyticsOrderId($arParams['ORDER_ID'])) {
        setSessionAnalyticsOrderId($arParams['ORDER_ID']);
        $arResult['ACTION'] = getActionName($arParams['ORDER_ID']);
        $arResult['ANALYTICS_ORDER_ACTIVE'] = 'Y';
    } else {
        $arResult['ANALYTICS_ORDER_ACTIVE'] = 'N';
    }

} else {
    $arResult['ANALYTICS_ORDER_ACTIVE'] = 'N';
}

$this->IncludeComponentTemplate();

?>