<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

$ob = new ElementByUserBuy();

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['IS_BUY_USER'] = $ob->isBuy($arItem['ID']);

    if (!$arItem['IS_BUY_USER']) {
        $arItem['CATALOG_QUANTITY'] = 0;
        $arItem['CAN_BUY'] = '';
        $arItem['ITEM_PRICES'] = [];
    }
}