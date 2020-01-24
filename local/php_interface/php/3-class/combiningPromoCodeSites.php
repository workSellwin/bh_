<?php

/**
 * Class CombiningPromoCodeSites
 */
class CombiningPromoCodeSites
{

    /**
     * @var string
     */
    protected $THIS_SITE;
    /**
     * @var string
     */
    protected $OTHER_SITE;

    /**
     * CombiningPromoCodeSites constructor.
     */
    public function __construct()
    {
        CModule::IncludeModule("sale");
        Cmodule::IncludeModule('catalog');
        Cmodule::IncludeModule('iblock');
        $this->THIS_SITE = SITE_ID;
        $this->OTHER_SITE = SITE_ID == 's1' ? 's2' : 's1';
    }

    /**
     * @param $code
     * @return array
     */
    public function GetCoupons($code)
    {
        $COMMENTS = self::GetCommentsIsCoupons($code);
        return self::GetCodeCoupons($COMMENTS);
    }

    /**
     * @param $code
     * @return string
     */
    public static function GetCommentsIsCoupons($code)
    {
        $COMMENTS = '';
        $res = \Bitrix\Sale\Order::getList([
            'select' => ['COMMENTS'],
            'filter' => ['?COMMENTS' => $code]
        ]);
        if ($data = $res->fetch()) {
            $COMMENTS = $data['COMMENTS'];
        }
        return $COMMENTS;
    }

    /**
     * @param $s
     * @return array
     */
    public static function GetCodeCoupons($s)
    {
        $arD = [];
        if ($s) {
            $s = str_replace(',', '', $s);
            $arD = explode(' ', $s);
            $arD = array_filter($arD, function ($k) {
                return strpos($k, 'CP-') !== false ? true : false;
            });
        }
        return array_values($arD);
    }

    /**
     * @param $coupon
     * @return mixed
     */
    public function Hendlers($coupon)
    {
        $result = $coupon;
        $arCoupon = self::GetDiscountCoupon($coupon);
        if ($arCoupon['ID'] > 0 and $arCoupon['ACTIVE'] == 'Y') {
            $discountSite = self::GetDiscountSite($arCoupon['DISCOUNT_ID']);
            if ($this->THIS_SITE != $discountSite) {
                $arCoupon = self::GetCoupons($coupon);
                if (in_array($coupon, $arCoupon)) {
                    $arCoupon2 = array_flip($arCoupon);
                    unset($arCoupon2[$coupon]);
                    $arCoupon2 = array_flip($arCoupon2);
                    $arCoupon2 = array_values($arCoupon2);
                    $result = $arCoupon2[0];
                }
            }
        }
        return $result;
    }


    public function GetDiscountCoupon($code)
    {
        $arResult = [];
        $res = Bitrix\Sale\Internals\DiscountCouponTable::getList(array(
            'select' => ['ID', 'DISCOUNT_ID', 'COUPON', 'ACTIVE'],
            'filter' => ['COUPON' => $code]
        ));
        if ($data = $res->fetch()) {
            $arResult = $data;
        }
        return $arResult;
    }

    public function GetDiscountSite($id)
    {
        $site = '';
        $res = Bitrix\Sale\Internals\DiscountTable::getList([
            'filter' => ['ID' => $id]
        ]);
        if ($data = $res->Fetch()) {
            $site = $data['LID'];
        }
        return $site;
    }

}