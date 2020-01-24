<?php


class Testing
{
    public function SetOrderPriority($orderId, $test = true)
    {
        CModule::IncludeModule("sale");
        $order = \Bitrix\Sale\Order::load($orderId);
        $propertyCollection = $order->getPropertyCollection();
        foreach ($propertyCollection as $propertyItem) {
            if (!empty($propertyItem->getField("CODE"))) {
                $propsData[$propertyItem->getField("CODE")] = trim($propertyItem->getValue());
            }
        }

        $ob = new PriorityOrderNew($propsData, $order->getPersonTypeId(), $order->getField('DELIVERY_ID'));
        $arDate = $ob->setPriorityOrder();
        if ($test) {
            PR($arDate);
            PR($propsData);
           PR($order->getField('DATE_INSERT')->getTimestamp());
        } else {

            $isDate = false;
            $isTime = false;
            $isPrior = false;
            foreach ($propertyCollection as $propertyItem) {

                switch ($propertyItem->getField("CODE")) {

                    case 'PRIORITY':// Установка Приоритета!
                        if(!$propsData['priority']){
                            $propertyItem->setField("VALUE", $arDate['priority']);
                        }
                        $isPrior = true;
                        break;
                    case 'TIME':// Установка Времени!
                        if(!$propsData['timeOrder']){
                            $propertyItem->setField("VALUE", $arDate['timeOrder']);
                        }
                        $isTime = true;
                        break;
                    case 'DATE':// Установка Даты!
                        if(!$propsData['dateOrder']){
                            $propertyItem->setField("VALUE", $arDate['dateOrder']);
                        }
                        $isDate = true;
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

            $order->save();

        }

    }

}
