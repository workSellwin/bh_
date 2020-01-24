<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();


if (is_null($arResult["DESCRIPTION"]))
{
	$arResult["DESCRIPTION"] = $arParams["~DESCRIPTION"];
}

// если это страница фильтрации, то возможно надо вывести другое описание
// а если другого описания нет, то ничего не выводить
if(strpos($_SERVER['REQUEST_URI'],'filter') !== false)
{
    //$arResult["DESCRIPTION"] = "";
    $currPage = urldecode($_SERVER['REQUEST_URI']);
    if(strpos($currPage,'?') !== false)
        $currPage = stristr($currPage,'?',true);
    $arSelect = Array("ID", "IBLOCK_ID", "DETAIL_TEXT");
    $arFilter = Array("IBLOCK_ID" => CATALOG_IBLOCK_ID_FITER_SEO_DATA, "ACTIVE" => "Y", "PROPERTY_LINK" => $currPage);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 1), $arSelect);
    $arFields = $res->GetNext();
    $arResult["DESCRIPTION"] = ($arFields["DETAIL_TEXT"] != "") ? $arFields["DETAIL_TEXT"] : "";

}

if($arResult['UF_POPULAR']>0) {
	$arFilter = Array("IBLOCK_ID" => 24, 'ID' => $arResult['UF_POPULAR'],"ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount" => 1));
	if ($ob = $res->GetNextElement()) {
		$arResult['POPULAR_LINK'] = $ob->GetFields();
		$arResult['POPULAR_LINK']['PROPERTIES'] = $ob->GetProperties();
	}
}


$ob = new ElementByUserBuy();

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['IS_BUY_USER'] = $ob->isBuy($arItem['ID']);

    if (!$arItem['IS_BUY_USER']) {
        $arItem['CATALOG_QUANTITY'] = 0;
        $arItem['CAN_BUY'] = '';
        $arItem['ITEM_PRICES'] = [];
    }
}
