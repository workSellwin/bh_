<?php

use InitMainTrait;

class createDiscountCoupon
{

    protected $arDiscountID = [];

    public function __construct($arDiscountID)
    {
        InitMainTrait::includeModules();
        $this->arDiscountID = $arDiscountID;
    }

    /**
     * @param $sum
     * @return string
     * @throws Exception
     */
    public function GetCode()
    {
        $code = [];
        foreach ($this->arDiscountID as $id) {
            if ($id) {
                $newCode = CatalogGenerateCoupon();
                $code[] = 'ID скидки: '.$id.' - Код купона: '.$newCode;
                $this->CouponAdd(self::CouponAddFields($id, $newCode));
            }
        }

        return implode(' ,', $code);
    }

    /**
     * @param $id
     * @param $code
     * @return array
     */
    protected function CouponAddFields($id, $code)
    {
        return [
            "DISCOUNT_ID" => $id,
            "COUPON" => $code,
            "ACTIVE" => "Y",
            "ACTIVE_FROM" => new \Bitrix\Main\Type\DateTime(date('d.m.Y 09:00:00')),
            "ACTIVE_TO" => new \Bitrix\Main\Type\DateTime(date('d.m.Y 09:00:00', strtotime(date('d.m.Y'). ' + 3 days'))),
            "TYPE" => 2,
            "USER_ID" => '',
            "DESCRIPTION" => '',
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
     * @param $email
     * @param $coupon
     */
    public function sendEmail($email, $coupon){
        $message = "Купоны сгенерированы!!! ".$coupon;
        mail($email, 'Генерация купонов на сайте bh.by', $message);
    }
}