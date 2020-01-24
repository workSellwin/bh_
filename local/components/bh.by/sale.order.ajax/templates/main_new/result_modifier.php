<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */
if($arParams['AJAX_MAIN'] == 'Y'){

   // PR($arResult['JS_DATA']['ORDER_PROP']['properties']); die();
}
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
//PR($arResult['JS_DATA']['ORDER_PROP']['properties']);

// Yauheni_4---------------------
//ReplaseTimeInterval40($arResult['JS_DATA']['ORDER_PROP']['properties']);
//-----------------------------


$dt = new DateTime();

if($_GET['print'] == 'yes'){
    //pr($arResult['JS_DATA']['ORDER_PROP']['properties'][4]['OPTIONS']);
    $dt = DateTime::createFromFormat('Y-m-d', '2020-01-04');
}

$formatData = $dt->format('Y-m-d');

if($formatData == '2020-01-04'){
//    unset($arResult['JS_DATA']['ORDER_PROP']['properties'][4]['OPTIONS']['enum-1']);
//    unset($arResult['JS_DATA']['ORDER_PROP']['properties'][4]['OPTIONS']['enum-2']);
}
else{
    unset($arResult['JS_DATA']['ORDER_PROP']['properties'][4]['OPTIONS']['enum-3']);
}


