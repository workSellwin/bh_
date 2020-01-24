<?php

/**
 * Class CreateDiscountСertificate
 */
class CreateDiscountСertificate
{

    protected $arDiscount = [];


    public function __construct()
    {
        \CModule::IncludeModule("catalog");
        $this->GetAllDiscount();
    }

    /**
     * @param $arFields
     * @return int
     */
    protected function DiscountAdd($arFields)
    {
        return (int)\CSaleDiscount::Add($arFields);
    }

    /**
     * @param $sum
     * @return string
     * @throws Exception
     */
    public function GetCode($sum)
    {
        $arId = $this->GetIdDiscount($sum);
        $code = [];
        foreach ($arId as $id) {
            if ($id) {
                $newCode = CatalogGenerateCoupon();
                $code[] = $newCode;
                $this->CouponAdd( self::CouponAddFields($id, $newCode, $sum) );
            }
        }
        return implode(' ,', $code);
    }

    /**
     * @param $id
     * @param $code
     * @param string $description
     * @return array
     */
    protected function CouponAddFields($id, $code, $description = "")
    {
        return [
            "DISCOUNT_ID" => $id,
            "COUPON" => $code,
            "ACTIVE" => "Y",
            "ACTIVE_FROM" => '',
            "ACTIVE_TO" => '',
            "TYPE" => 2,
            "USER_ID" => '',
            "DESCRIPTION" => $description,
        ];
    }

    /**
     * @param $arFields
     * @return \Bitrix\Main\Entity\AddResult
     * @throws Exception
     */
    protected static function CouponAdd($arFields)
    {
        return \Bitrix\Sale\Internals\DiscountCouponTable::add($arFields);
    }

    /**
     * @param $sum
     * @return array
     */
    protected function GetIdDiscount($sum)
    {
        $arIds = [];
        foreach (['s1', 's2'] as $s) {
            $arIds[] = $this->getSiD($s, $sum);
        }
        return $arIds;
    }

    /**
     * @param $s
     * @param $sum
     * @return int
     */
    protected function getSiD($s, $sum)
    {
        if (!$id = $this->arDiscount[$s][self::mask($sum)]) {
            $id = $this->DiscountAdd(self::DiscountAddFields($s, $sum));
        }
        return $id;
    }

    /**
     * @param $s
     * @param $sum
     * @return array
     */
    protected function DiscountAddFields($s, $sum)
    {
        return array(
            'LID' => $s,
            'NAME' => self::mask($sum),
            'ACTIVE_FROM' => '',
            'ACTIVE_TO' => '',
            'ACTIVE' => 'Y',
            'SORT' => '1',
            'PRIORITY' => '7788',
            'LAST_DISCOUNT' => 'N',
            'LAST_LEVEL_DISCOUNT' => 'N',
            'XML_ID' => '',
            'CONDITIONS' => array(
                'CLASS_ID' => 'CondGroup',
                'DATA' => array(
                    'All' => 'AND',
                    'True' => 'True',
                ),
                'CHILDREN' => array(),
            ),
            'ACTIONS' => array(
                'CLASS_ID' => 'CondGroup',
                'DATA' => array('All' => 'AND',),
                'CHILDREN' => array(
                    0 => array(
                        'CLASS_ID' => 'ActSaleBsktGrp',
                        'DATA' => array(
                            'Type' => 'Discount',
                            'Value' => $sum,
                            'Unit' => 'CurAll',
                            'Max' => 0,
                            'All' => 'AND',
                            'True' => 'True',
                        ),
                        'CHILDREN' => array(/* array(
                                'CLASS_ID' => 'CondBsktAppliedDiscount',
                                'DATA' => array(
                                    'value' => 'Y',
                                ),
                            ),*/
                        ),
                    ),
                ),
            ),
            'USER_GROUPS' => array(0 => 2),
            'COUPON_ADD' => 'Y',
            'COUPON_COUNT' => '1',
            'COUPON' => [
                'TYPE' => 'O',
                'ACTIVE_FROM ' => '',
                'ACTIVE_TO' => '',
                'MAX_UXE' => '0',
            ]
        );
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     */
    protected function GetAllDiscount()
    {
        $arResult = [];
        $dbProductDiscounts = \Bitrix\Sale\Internals\DiscountTable::getList([]);
        while ($arD = $dbProductDiscounts->Fetch()) {
            $arResult[$arD['LID']][$arD['NAME']] = $arD['ID'];
        }
        $this->arDiscount = $arResult;
    }

    /**
     * @param $sum
     * @return string
     */
    public static function mask($sum)
    {
        return "Сертификат на {$sum} руб.";
    }

}