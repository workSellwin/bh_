<?php

/**
 * Class LuiPredictionManager
 *
 * bitrix/modules/sale/lib/discount/prediction/manager.php
 *
 */
class LuiPredictionManager
{
    /**
     * LUIWADJOGS core line 390
     * findFirstPredictionDiscount
     * if (!LuiPredictionManager::isVaidGroup($discount)) return null;
     * @param $discount
     * @return bool
     */
    public static function isVaidGroup($discount)
    {
        global $USER;
        $result = true;
        $arGroup = explode(',', $USER->GetGroups());
        $CondCatalogPriceType = $discount['ACTIONS']['CHILDREN'][0]['CHILDREN'][1]['CHILDREN'][2]['DATA']['value'][0];
        if($CondCatalogPriceType){
            $db_res = \CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID" => $CondCatalogPriceType, "BUY" => "Y"));
            $arUG = [];
            while ($ar_res = $db_res->Fetch()) {
                $arUG[] = $ar_res['GROUP_ID'];
            }
            if (!array_intersect($arGroup, $arUG)) $result = false;
        }
      //  return true;
        return $result;
    }
}
