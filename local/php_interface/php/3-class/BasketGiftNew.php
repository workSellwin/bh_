<?php

use Bitrix\Sale;


class BasketGiftNew
{
    use InitMainTrait;

    public $objBasket;
    public $PriseProductS1;
    public $ProdBasketId = [];
    public $CatalogIblockId = 2;
    public $DiscountElemId = [];
    public $DiscountId = [];
    public $FlagBasketGiftId = false;
    public $arDiscounts = [];
    public $price;


    public function __construct($PROD_BASKET_ID =false, $PRICE_GIFTS_ALL=false)
    {
        $this->includeModules();
        $this->objBasket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());;
        $this->getElementTableGifts();
        $this->price = $PRICE_GIFTS_ALL;

        //получение ID товаров в карзине
        if(!empty($PROD_BASKET_ID)){
            $this -> ProdBasketId = $PROD_BASKET_ID;
        }else{
            foreach ($this->objBasket as $basketItem) {
                $this -> ProdBasketId[$basketItem->getProductId()]['ID']=$basketItem->getProductId();
                $this -> ProdBasketId[$basketItem->getProductId()]['PRISE']=$basketItem->getPrice();
            }
        }
    }

    /**
     * @return Sale\BasketBase
     */
    public  function  getObjBasket(){
        return $this->objBasket;
    }

    /**
     * @return array
     * элементы из таблицы подарков
     */
    public function getElementTableGifts(){
        $ob = new \ Bitrix\Sale\Discount\Gift\RelatedDataTable();
        foreach ($ob::getList(['select'=>['*']])->fetchAll() as $v){
            $this->DiscountElemId[$v['ELEMENT_ID']] = $v['DISCOUNT_ID'];
            $this->DiscountId[$v['DISCOUNT_ID']] = $v['DISCOUNT_ID'];
        }
        return $this->DiscountElemId;
    }

    /**
     * @param $price
     * @return array
     * получение скидок
     */
    public function getListDiscountGifts(){

        //скидки подарков
        $arOrder = [
            'SORT' => 'ASC',
        ];
        $arFilter = [
            'ACTIVE' => 'Y',
            'ID' => $this->DiscountId,
        ];

        $rsDiscounts = CSaleDiscount::GetList($arOrder, $arFilter, false, false, []);
        $arDiscounts = [];
        while ($arDiscount = $rsDiscounts->Fetch()) {
            $arDiscounts[$arDiscount['ID']] = $arDiscount;
        }

        foreach ($arDiscounts as $k => &$v){
            $PRICE_GIFT = explode(' ', trim($v['NAME']));
            $v['ACTIV_GIFT'] = $this->FlagBasketGiftId ? 'N' :'Y';
            $v['GIFT_VAL'] = $PRICE_GIFT[1];
            $v['NEED'] = 'Ваши подарки за заказ от '.$PRICE_GIFT[1].' руб.';
        }
        return $this->arDiscounts = $arDiscounts;

    }



}