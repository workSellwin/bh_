<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

$ob = new DiscountNeighbour();
if (!$ob->isView()) {
    echo <<<STYLE
<style>
[data-property-id-row="38"]{
   display: none;
}
</style>
STYLE;

}

foreach ($arResult['JS_DATA']['ORDER_PROP']['properties'] as &$prop){
    if($prop['ID']==41){
        if(!empty($prop['OPTIONS'])){
            $date_insert_h = date('H'); // часы создания заказа
            $date_insert_i = date('i'); // минуты создания заказа
            $insertMinut = $date_insert_h * 60 + $date_insert_i;
            if ($insertMinut >= 10 * 60 + 0 && $insertMinut <= 16 * 60 + 0) {
                unset($prop['OPTIONS']['e12']);
                unset($prop['OPTIONS']['e13']);
                unset($prop['OPTIONS']['e14']);
                unset($prop['OPTIONS']['e15']);
                unset($prop['OPTIONS']['e16']);
                unset($prop['OPTIONS']['e17']);
            }
        }
    }
}
