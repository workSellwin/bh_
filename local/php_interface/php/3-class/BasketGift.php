<?php

use Bitrix\Sale;


class BasketGift
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


    public function __construct($PROD_BASKET_ID =false)
    {
        $this->includeModules();
        $this->objBasket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());;

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
     * @return int|mixed
     * получаем цену товаров S1 из карзины
     */
    public function getPriseProductS1(){
        //товары s1
        $prodS1 = $this->definitionProdForSite($this -> ProdBasketId);
        //товары из таблицы подарков
        $ElementTableGifts = $this->getElementTableGifts();
        //исключаем подарок если он лежит в карзине
        $prodS1 = $this->delGiftPrise($ElementTableGifts, $prodS1);
        //подсчитываем prise товаров s1
        $price = $this->getPriseElemBasketS1($prodS1);

        return $price;
    }

    /**
     * @param $arProdID
     * @return array
     * определяем какие товары из S1
     */
    protected function definitionProdForSite($arProdID){
        if(!empty($arProdID)){
            $arIdElem = [];
            //массив элементов
            foreach ($arProdID as $v){
                $arIdElem[]=$v['ID'];
            }
            $section_id = [];
            $prod = [];
            //получаем элементы ------------------------------------------------------------------
            $arSelect = Array("ID", 'IBLOCK_ID', "NAME", 'ACTIVE', 'IBLOCK_SECTION_ID');
            $arFilter = Array("IBLOCK_ID"=>$this->CatalogIblockId, 'ID' => $arIdElem);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            while($ob = $res->GetNext())
            {
                $prod[$ob['ID']]['SECTION_ID'] = $ob['IBLOCK_SECTION_ID'];
                $prod[$ob['ID']]['ID'] = $ob['ID'];
                $section_id[$ob['IBLOCK_SECTION_ID']]=$ob['IBLOCK_SECTION_ID'];
            }

            //получаем разделы ---------------------------------------------------------------------
            if(!empty($section_id)){

                $section_id_site = [];
                $arFilter = Array('IBLOCK_ID'=>$this->CatalogIblockId, 'ID' => $section_id);
                $Select = array('NAME', 'ID', 'CODE', 'UF_SITE' );
                $db_list = CIBlockSection::GetList(Array(), $arFilter, false, $Select );
                while($ar_result = $db_list->GetNext())
                {
                    if(!empty($ar_result['UF_SITE'])){
                        if(count($ar_result['UF_SITE']) == 2){
                            foreach ($ar_result['UF_SITE'] as $val){
                                $section_id_site['s1s2'][$ar_result['ID']] = $ar_result['ID'];
                            }
                        }else{
                            foreach ($ar_result['UF_SITE'] as $val){
                                if($val == 3){
                                    $section_id_site['s1'][$ar_result['ID']] = $ar_result['ID'];
                                }if($val == 4){
                                    $section_id_site['s2'][$ar_result['ID']] = $ar_result['ID'];
                                }
                            }
                        }
                    }
                }
            }

            //получаем товары s1 -------------------------------------------------------------
            $prod_site_s1 = [];
            if(!empty($section_id_site['s1'])){
                foreach ($section_id_site['s1'] as $val){
                    foreach ($prod as $item){
                        if($val == $item['SECTION_ID']){
                            $prod_site_s1[$item['ID']] = $arProdID[$item['ID']];
                        }
                    }
                }
            }
            return $prod_site_s1;
        }
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
            //подарок лежит в карзине
            if(!empty($this -> ProdBasketId)){
                if($this -> ProdBasketId[$v['ELEMENT_ID']]){
                    if($this -> ProdBasketId[$v['ELEMENT_ID']]['DISCOUNT_PRICE'] == 100){
                        $this->FlagBasketGiftId = true;
                    }
                }
            }
        }
        return $this->DiscountElemId;
    }

    /**
     * @param $ElementTableGifts
     * @param $prodS1
     * @return mixed
     * исключаем подарок
     */
    private function delGiftPrise($ElementTableGifts, $prodS1){
        if(!empty($prodS1)){
            foreach ($prodS1 as $item){
                if($ElementTableGifts[$item['ID']]){
                    unset($prodS1[$item['ID']]);
                }
            }
        }
        return $prodS1;
    }

    /**
     * @param $arElemBasket
     * @return int|mixed
     * стоимость всех товаров s1 в карзине
     */
    protected function getPriseElemBasketS1($arElemBasket){
        if(!empty($arElemBasket)){
            $price = 0;
            foreach ($arElemBasket as $item){
                $price += $item['PRISE'];
            }
            return $price;
        }
    }

    /**
     * @param $price
     * @return array
     * получение скидок
     */
    public function getListDiscountGifts($price){
        if($price){
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

                if($price >= $PRICE_GIFT[1]){
                    $v['ACTIV_GIFT'] = $this->FlagBasketGiftId ? 'N' :'Y';
                    $v['GIFT_VAL'] = $PRICE_GIFT[1];
                    $v['NEED'] = 'Ваши подарки за заказ от '.$PRICE_GIFT[1].' руб.';
                }else{
                    $v['ACTIV_GIFT'] = 'N';
                    $v['GIFT_VAL'] = $PRICE_GIFT[1];
                    $v['NEED'] = 'Добавьте товары в корзину еще на '.($PRICE_GIFT[1] - $price).'  руб. и выберите подарок за заказ от '.$PRICE_GIFT[1].' руб.';
                }
            }
            return $this->arDiscounts = $arDiscounts;
        }
    }



}