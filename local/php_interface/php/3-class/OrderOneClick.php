<?php

class OrderOneClick
{
    protected $name;
    protected $phone;

    /**
     * OrderOneClick constructor.
     */
    public function __construct()
    {
        \Bitrix\Main\Loader::includeModule("sale");
        \Bitrix\Main\Loader::includeModule("catalog");
        \Bitrix\Main\Loader::includeModule("iblock");
    }

    /**
     * @param $id
     * @param $name
     * @param $phone
     * @return mixed
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\SystemException
     */
    public function Order($id, $name, $phone)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->isUser();
        $basket = $this->CreateBasket([$this->GetItem($id)]);
        $order = $this->AddOrder($basket);

        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem(
            \Bitrix\Sale\Delivery\Services\Manager::getObjectById(1)
        );
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        /** @var \Bitrix\Sale\BasketItem $basketItem */
        foreach ($basket as $basketItem) {
            $item = $shipmentItemCollection->createItem($basketItem);
            $item->setQuantity($basketItem->getQuantity());
        }

        // Создание оплаты
        $paymentCollection = $order->getPaymentCollection();
        $payment = $paymentCollection->createItem(
            \Bitrix\Sale\PaySystem\Manager::getObjectById(1)
        );
        $payment->setField("SUM", $order->getPrice());
        $payment->setField("CURRENCY", $order->getCurrency());
        $result = $order->save();
        return $result;
    }

    /**
     * @param $basket
     * @return \Bitrix\Sale\Order
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    public function AddOrder($basket)
    {
        global $USER;
        $userId = $USER->GetID();
        $order = \Bitrix\Sale\Order::create(SITE_ID, $userId);
        $order->setPersonTypeId(1);
        $order->setBasket($basket);
        return $order;
    }

    /**
     *
     */
    public function isUser()
    {
        global $USER;
        if (!is_object($USER) or !$USER->GetID()) {
            $id = $this->AddUser($this->name, $this->phone);
            $USER->Authorize($id);
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function GetItem($id)
    {
        global $USER;
        $arPrice = CCatalogProduct::GetOptimalPrice($id, 1, $USER->GetUserGroupArray());
        $res = \CIBlockElement::GetByID($id);
        $ar_res = $res->GetNext();
        $arItem = ['PRODUCT_ID' => $id,
            'NAME' => $ar_res['NAME'],
            'PRICE' => $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'],
            'CURRENCY' => 'BYN',
            'QUANTITY' => 1
        ];
        return $arItem;
    }

    /**
     * @param $name
     * @param $phone
     * @return mixed
     */
    public function AddUser($name, $phone)
    {
        $new_password = randString(7);
        $email = randString(6);
        $user = new \CUser;
        $email = $email . '@gmail.com';
        $arFields = Array(
            "NAME" => $name,
            "EMAIL" => $email,
            "LOGIN" => $email,
            "PHONE" => $phone,
            "ACTIVE" => "Y",
            "GROUP_ID" => array(3, 5, 4),
            "PASSWORD" => $new_password,
            "CONFIRM_PASSWORD" => $new_password,
        );
        $ID = $user->Add($arFields);
        return $ID;
    }

    /**
     * BASKET
     */

    /**
     * @param $arItems
     * @return mixed
     */
    protected function CreateBasket($arItems)
    {
        // Создаем и наполняем корзину
        $basket = \Bitrix\Sale\Basket::create(SITE_ID);
        foreach ($arItems as $i => $arItem) {
            $basketItem = $basket->createItem("catalog", $arItem['PRODUCT_ID']);
            $basketItem->setFields($arItem);
        }
        return $basket;
    }


}