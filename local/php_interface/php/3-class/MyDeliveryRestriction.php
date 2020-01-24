<?php




class MyDeliveryRestriction extends \Bitrix\Sale\Delivery\Restrictions\Base
{

    public static function getClassTitle()
    {
        return 'Ограничивает службу доставки по времени до 16.00';
    }

    public static function getClassDescription()
    {
        return 'Ограничивает службу доставки по времени до 16.00';
    }

    protected static function extractParams(\Bitrix\Sale\Shipment $shipment)
    {
        return null;
    }

    public static function getParamsStructure($deliveryId = 0)
    {
        return array();
    }

    public static function check($shipmentParams, array $restrictionParams, $deliveryId = 0)
    {
        $date_insert_h = date('H'); // часы создания заказа
        $date_insert_i = date('i'); // минуты создания заказа
        $insertMinut = $date_insert_h * 60 + $date_insert_i;

        /*if($deliveryId == 43){
            if ($insertMinut >= 16 * 60 + 0) {
                return false;
            }
        }*/


        return true;
    }

}