<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$priceType = 0;
foreach ($arResult['BASKET'] as &$item) {
	$item['GMI_SUM'] = $item['PRICE'] * $item['QUANTITY'];
	$item['GMI_SUM_NDS'] = $item['GMI_SUM']/100 * Mitlab::getProductNds($item['PRODUCT_ID']) + $item['GMI_SUM'];
	$item['GMI_SUM_NDS_FORMAT'] = CurrencyFormat($item['GMI_SUM_NDS'], $item['CURRENCY']);
	$arResult['GMI_ALL_SUM_NDS'] += $item['GMI_SUM_NDS'];
}

$arResult['GMI_ALL_TOTAL_SUM_NDS'] = $arResult['GMI_ALL_SUM_NDS'] + $arResult['PRICE_DELIVERY'];
$arResult['GMI_ALL_TOTAL_SUM_NDS_FORMAT'] = CurrencyFormat($arResult['GMI_ALL_TOTAL_SUM_NDS'], $arResult['CURRENCY']);
?>