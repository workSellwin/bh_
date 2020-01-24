<?php

class ElementByUserBuy
{
    public function __construct()
    {
        \Bitrix\Main\Loader::includeModule("sale");
        \Bitrix\Main\Loader::includeModule("catalog");
        \Bitrix\Main\Loader::includeModule("iblock");
    }

    public function isBuy($id)
    {
        $result = true;
        $arPrice = $this->GetAllPriceItem($id);
        $arPriceItemGroup = array_keys($arPrice);
        $arGroupPrice = $this->GetBuyGroup();
        if (is_array($arGroupPrice) and is_array($arPriceItemGroup)) {
            $intersect = array_intersect($arGroupPrice, $arPriceItemGroup);
            if (!$intersect) {
                $result = false;
            }
        }
        return $result;
    }


    public function GetAllPriceItem($id)
    {
        $arPrice = [];
        $db_res = CPrice::GetList(
            array(),
            array("PRODUCT_ID" => $id, "CAN_BUY" => "Y")
        );
        while ($arPriceType = $db_res->Fetch()) {
            $arPrice[$arPriceType['CATALOG_GROUP_ID']] = [
                'PRICE' => $arPriceType['PRICE'],
                'CURRENCY' => $arPriceType['CURRENCY'],
                'CATALOG_GROUP_NAME' => $arPriceType['CATALOG_GROUP_NAME'],
                'CAN_BUY' => $arPriceType['CAN_BUY'],
                'CAN_ACCESS' => $arPriceType['CAN_ACCESS'],
            ];
        }
        return $arPrice;
    }


    /**
     * @return mixed
     */
    public function GetUserGroup()
    {
        global $USER;
        return $USER->GetUserGroupArray();
    }

    public function GetBuyGroup()
    {
        $arGroup = [];
        $db_res = CCatalogGroup::GetGroupsList(array("GROUP_ID" => $this->GetUserGroup(), "BUY" => "Y"));
        while ($ar_res = $db_res->Fetch()) {
            $arGroup[$ar_res["CATALOG_GROUP_ID"]] = $ar_res["CATALOG_GROUP_ID"];
        }
        if(array_intersect(array_keys($arGroup),[2,10,11,12,13,16,18])){
            unset($arGroup[15]);
        }
        return array_values($arGroup);
    }

}