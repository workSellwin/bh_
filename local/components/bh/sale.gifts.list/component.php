<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$elem_gifts = [];
$allCatalogId = [];
$arSelect = Array("ID", "NAME", "PROPERTY_GIFTS", 'PROPERTY_PRICE_TO');
$arFilter = Array("IBLOCK_ID"=>$arParams['IBLOCK_GIFTS_ID'], "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNext())
{
    $elem_gifts[$ob['ID']]['PRICE_TO'] = $ob['PROPERTY_PRICE_TO_VALUE'];
    if($arParams['PRICE'] >= $ob['PROPERTY_PRICE_TO_VALUE']){
        $elem_gifts[$ob['ID']]['ACTIVE'] = 'Y';
        $elem_gifts[$ob['ID']]['NEED'] = 'Ваши подарки за заказ от '.$ob['PROPERTY_PRICE_TO_VALUE'].' руб.';
    }else{
        $elem_gifts[$ob['ID']]['ACTIVE'] = 'N';
        $elem_gifts[$ob['ID']]['NEED'] = 'Добавьте товары в корзину еще на '.($ob['PROPERTY_PRICE_TO_VALUE'] - $arParams['PRICE']).'  руб. и выберите подарок за заказ от '.$ob['PROPERTY_PRICE_TO_VALUE'].' руб.';
    }

    $elem_gifts[$ob['ID']]['CATALOG_ID'][]=$ob['PROPERTY_GIFTS_VALUE'];
}
$arResult['GIFTS'] = $elem_gifts;



$this->IncludeComponentTemplate();