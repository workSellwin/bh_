<?php

class SyncBasket
{
    protected $FUSER_ID;
    protected $THIS_SITE;
    protected $OTHER_SITE;
    protected $arBasketThis = [];
    protected $arBasketOther = [];

    /**
     * SyncBasket constructor.
     */
    public function __construct()
    {
        CModule::IncludeModule("sale");
        Cmodule::IncludeModule('catalog');
        $this->FUSER_ID = \Bitrix\Sale\Fuser::getId();
        $this->THIS_SITE = SITE_ID;
        $this->OTHER_SITE = SITE_ID == 's1' ? 's2' : 's1';
        $this->arBasketThis = $this->GetItemBasket($this->THIS_SITE);
        $this->arBasketOther = $this->GetItemBasket($this->OTHER_SITE);
    }

    /**
     *Вызывается после добавления записи в корзину.
     *
     * @param $id
     * @param $arFields
     */
    public static function HandlerOnBasketAdd($id, $arFields)
    {
        $ob = new self();
        if ($_REQUEST['action'] == 'ADD2BASKET' and $arFields['LID'] == $ob->GetThisSite()) {
            $ob->BasketAdd($arFields);
        }
    }


    public static function HandlerOnBeforeBasketAdd(&$arFields){
        $arFields['LID']='s1';
    }

    /**
     * Возвращает ID товара во 2 корзине
     *
     * @param $id
     * @return bool
     */
    public function GetIdOther($id)
    {
        $otherId = false;
        if ($prodID = $this->arBasketThis[$id]['PRODUCT_ID']) {
            $arBasketOther = array_column($this->arBasketOther, 'ID', 'PRODUCT_ID');
            $otherId = $arBasketOther[$prodID];
        }
        return $otherId;
    }

    /**
     * Вызывается после изменения записи в корзине.
     *
     * @param $id
     * @param $arFields
     */
    public static function HandlerOnBasketUpdate($id, $arFields)
    {
        $ob = new self();
        if ($arFields['LID'] == $ob->GetThisSite()) {
            if ($otherId = $ob->GetIdOther($id)) {
                $ob->BasketUpdate($otherId, $arFields);
            }
        }
    }

    /**
     * Вызывается перед удалением записи из корзины, может быть использовано для отмены.
     *
     * @param $id
     */
    public static function HandlerOnBasketDelete($id)
    {
        $ob = new self();
        $otherId = $ob->GetIdOther($id);
        if ($otherId) {
            \CSaleBasket::Delete($otherId);
        }
    }

    /**
     * @param $LID
     * @return array
     */
    protected function GetItemBasket($LID)
    {
        $arBasketItems = [];
        $arFilter = [
            "FUSER_ID" => $this->FUSER_ID,
            "LID" => $LID,
            "ORDER_ID" => "NULL"
        ];
        $dbBasketItems = CSaleBasket::GetList(
            ["NAME" => "ASC", "ID" => "ASC"],
            $arFilter,
            false,
            false,
            ["ID", "MODULE", "NAME", "PRODUCT_ID", "QUANTITY", "PRICE", "LID"]
        );
        while ($arItems = $dbBasketItems->Fetch()) {
            $arBasketItems[$arItems['ID']] = $arItems;
        }
        return $arBasketItems;
    }

    /**
     * @param $arFields
     */
    protected function BasketAdd($arFields)
    {
        $arFields['LID'] = $this->OTHER_SITE;
        \CSaleBasket::Add($arFields);
    }


    /**
     * @param $otherId
     * @param $arFields
     */
    public function BasketUpdate($otherId, $arFields)
    {
        $arFields['LID'] = $this->OTHER_SITE;
        \CSaleBasket::Update($otherId, $arFields);
    }

    /**
     * @return string
     */
    public function GetOtherSite()
    {
        return $this->OTHER_SITE;
    }

    /**
     * @return string
     */
    public function GetThisSite()
    {
        return $this->THIS_SITE;
    }

    /**
     * удаляем корзину противоположного сайта
     */
    public function DeleteOldBasket()
    {
        $basketRes = \Bitrix\Sale\Internals\BasketTable::getList(array(
            'filter' => array(
                'FUSER_ID' => $this->FUSER_ID,
                'ORDER_ID' => null,
                'LID' => $this->OTHER_SITE,
            )
        ));
        while ($item = $basketRes->fetch()) {
            \Bitrix\Sale\Internals\BasketTable::delete($item['ID']);
        }
    }

}