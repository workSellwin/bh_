<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixBasketComponent $component */
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
);
//$this->addExternalCss($templateData['TEMPLATE_THEME']);

$curPage = $APPLICATION->GetCurPage().'?'.$arParams["ACTION_VARIABLE"].'=';
$arUrls = array(
	"delete" => $curPage."delete&id=#ID#",
	"delay" => $curPage."delay&id=#ID#",
	"add" => $curPage."add&id=#ID#",
);
unset($curPage);

$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

$arBasketJSParams = array(
	'SALE_DELETE' => GetMessage("SALE_DELETE"),
	'SALE_DELAY' => GetMessage("SALE_DELAY"),
	'SALE_TYPE' => GetMessage("SALE_TYPE"),
	'TEMPLATE_FOLDER' => $templateFolder,
	'DELETE_URL' => $arUrls["delete"],
	'DELAY_URL' => $arUrls["delay"],
	'ADD_URL' => $arUrls["add"],
	'EVENT_ONCHANGE_ON_START' => (!empty($arResult['EVENT_ONCHANGE_ON_START']) && $arResult['EVENT_ONCHANGE_ON_START'] === 'Y') ? 'Y' : 'N',
	'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
	'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
	'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY']
);
?>
<script >
	var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams);?>;
</script>
<?
$APPLICATION->AddHeadScript($templateFolder."/script.js");


if (CSite::InGroup(array(10,11,1))):
?>
	<div class="search-page">
		<form action="" method="get" data-ajax="/ajax/add_basket_barcode.php">
			<?=bitrix_sessid_post()?>
			<button type="submit" class="btn">Искать</button>
			<div class="field-search-wrp"><input type="text" name="BAR_CODE" class="field chk" value="" placeholder="Введите штрих код..."></div>
		</form><br>
	</div>


	<div class="add-file-csv-wrp">
		<form action="" enctype="multipart/form-data" id="add_product_by_csv">
			<?=bitrix_sessid_post()?>
			<button type="submit" class="btn">Добавить</button>
			<div class="add-file-csv">
				<span class="note-file">Выберите файл в формате CSV</span>
				<input name="file" class="field cart-search-select-file" value="" placeholder="Выберите файл в формате CSV" type="file" accept=".csv">
			</div>

		</form>
	</div>
	<br>
	<?if(count($arResult['ITEMS']['AnDelCanBuy'])>0):?>
		<div class="form-clear-cart-wrp">
			<form action="" method="get" data-ajax="/ajax/clearBascket.php" style="text-align: right;">
				<input type="hidden" name="CLEAR" value="Y">
				<?=bitrix_sessid_post()?>
				<button type="submit" class="btn">Очистить корзину</button>
			</form>
		</div>
	<?endif;?>
<?
endif;




/*if($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'TOP')
{
	$APPLICATION->IncludeComponent(
		"bitrix:sale.gift.basket",
		".default",
		array(
			"SHOW_PRICE_COUNT" => 1,
			"PRODUCT_SUBSCRIPTION" => 'N',
			'PRODUCT_ID_VARIABLE' => 'id',
			"PARTIAL_PRODUCT_PROPERTIES" => 'N',
			"USE_PRODUCT_QUANTITY" => 'N',
			"ACTION_VARIABLE" => "actionGift",
			"ADD_PROPERTIES_TO_BASKET" => "Y",

			"BASKET_URL" => $APPLICATION->GetCurPage(),
			"APPLIED_DISCOUNT_LIST" => $arResult["APPLIED_DISCOUNT_LIST"],
			"FULL_DISCOUNT_LIST" => $arResult["FULL_DISCOUNT_LIST"],

			"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_SHOW_VALUE"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

			'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
			'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
			'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],
			'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
			'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
			'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
			'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
			'SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
			'SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
			'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],
			'MESS_BTN_DETAIL' => $arParams['GIFTS_MESS_BTN_DETAIL'],
			'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
			'CONVERT_CURRENCY' => $arParams['GIFTS_CONVERT_CURRENCY'],
			'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],

			"LINE_ELEMENT_COUNT" => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
		),
		false
	);
}
*/
// if (strlen($arResult["ERROR_MESSAGE"]) <= 0)
// {
	?>
	<div id="warning_message">
		<?
		if (!empty($arResult["WARNING_MESSAGE"]) && is_array($arResult["WARNING_MESSAGE"]))
		{
			foreach ($arResult["WARNING_MESSAGE"] as $v)
				ShowError($v);
		}
		?>
	</div>
	<?

	$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
	$normalHidden = ($normalCount == 0) ? 'style="display:none;"' : '';

	$delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
	$delayHidden = ($delayCount == 0) ? 'style="display:none;"' : '';

	$subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
	$subscribeHidden = ($subscribeCount == 0) ? 'style="display:none;"' : '';

	$naCount = count($arResult["ITEMS"]["nAnCanBuy"]);
	$naHidden = ($naCount == 0) ? 'style="display:none;"' : '';

	foreach (array_keys($arResult['GRID']['HEADERS']) as $id)
	{
		$data = $arResult['GRID']['HEADERS'][$id];
		$headerName = (isset($data['name']) ? (string)$data['name'] : '');
		if ($headerName == '')
			$arResult['GRID']['HEADERS'][$id]['name'] = GetMessage('SALE_'.$data['id']);
		unset($headerName, $data);
	}
	unset($id);

	?>
		<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">
				<?include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");?>
			<input type="hidden" name="BasketOrder" value="BasketOrder" />
		</form>
<?if(count($arResult['ITEMS']['AnDelCanBuy'])>0 && CSite::InGroup(array(10,11,1))):?>
	<div class="form-clear-cart-wrp">
		<form action="" method="get" data-ajax="/ajax/clearBascket.php" style="text-align: right;">
			<input type="hidden" name="CLEAR" value="Y">
			<?=bitrix_sessid_post()?>
			<button type="submit" class="btn">Очистить корзину</button>
		</form>
	</div>
	<br>
<?endif;?>
	<?

	if($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'BOTTOM')
	{
		?>
		<div style="margin-top: 35px;"><? $APPLICATION->IncludeComponent("bitrix:sale.gift.basket", "main", Array(
	"SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
		"PRODUCT_SUBSCRIPTION" => "N",	// Разрешить оповещения для отсутствующих товаров
		"PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
		"PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить частично заполненные свойства
		"USE_PRODUCT_QUANTITY" => "N",	// Разрешить указание количества товара
		"ACTION_VARIABLE" => "actionGift",	// Название переменной, в которой передается действие
		"ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
		"BASKET_URL" => $APPLICATION->GetCurPage(),	// URL, ведущий на страницу с корзиной покупателя
		"APPLIED_DISCOUNT_LIST" => $arResult["APPLIED_DISCOUNT_LIST"],
		"FULL_DISCOUNT_LIST" => $arResult["FULL_DISCOUNT_LIST"],
		"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],	// Цветовая тема
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_SHOW_VALUE"],	// Включать НДС в цену
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],	// Учитывать права доступа
		"BLOCK_TITLE" => $arParams["GIFTS_BLOCK_TITLE"],	// Текст заголовка "Подарки"
		"HIDE_BLOCK_TITLE" => $arParams["GIFTS_HIDE_BLOCK_TITLE"],	// Скрыть заголовок "Подарки"
		"TEXT_LABEL_GIFT" => $arParams["GIFTS_TEXT_LABEL_GIFT"],	// Текст метки "Подарка"
		"PRODUCT_QUANTITY_VARIABLE" => $arParams["GIFTS_PRODUCT_QUANTITY_VARIABLE"],	// Название переменной, в которой передается количество товара
		"PRODUCT_PROPS_VARIABLE" => $arParams["GIFTS_PRODUCT_PROPS_VARIABLE"],	// Название переменной, в которой передаются характеристики товара
		"SHOW_OLD_PRICE" => $arParams["GIFTS_SHOW_OLD_PRICE"],	// Показывать старую цену
		"SHOW_DISCOUNT_PERCENT" => $arParams["GIFTS_SHOW_DISCOUNT_PERCENT"],	// Показывать процент скидки
		"SHOW_NAME" => $arParams["GIFTS_SHOW_NAME"],	// Показывать название
		"SHOW_IMAGE" => $arParams["GIFTS_SHOW_IMAGE"],	// Показывать изображение
		"MESS_BTN_BUY" => $arParams["GIFTS_MESS_BTN_BUY"],	// Текст кнопки "Выбрать"
		"MESS_BTN_DETAIL" => $arParams["GIFTS_MESS_BTN_DETAIL"],	// Текст кнопки "Подробнее"
		"PAGE_ELEMENT_COUNT" => $arParams["GIFTS_PAGE_ELEMENT_COUNT"],	// Количество элементов на странице
		"CONVERT_CURRENCY" => $arParams["GIFTS_CONVERT_CURRENCY"],	// Показывать цены в одной валюте
		"HIDE_NOT_AVAILABLE" => $arParams["GIFTS_HIDE_NOT_AVAILABLE"],	// Не отображать товары, которых нет на складах
		"LINE_ELEMENT_COUNT" => $arParams["GIFTS_PAGE_ELEMENT_COUNT"],	// Количество элементов, выводимых в одной строке
	),
	false
); ?>
		</div><?
	}
	$APPLICATION->IncludeComponent(
	"bitrix:sale.order.ajax", 
	"main", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADDITIONAL_PICT_PROP_2" => "-",
		"ADDITIONAL_PICT_PROP_3" => "-",
		"ALLOW_APPEND_ORDER" => "Y",
		"ALLOW_AUTO_REGISTER" => "Y",
		"ALLOW_NEW_PROFILE" => "Y",
		"ALLOW_USER_PROFILES" => "Y",
		"BASKET_IMAGES_SCALING" => "adaptive",
		"BASKET_POSITION" => "after",
		"COMPATIBLE_MODE" => "N",
		"COMPONENT_TEMPLATE" => "main",
		"COUNT_DELIVERY_TAX" => "N",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"DELIVERIES_PER_PAGE" => "9",
		"DELIVERY_FADE_EXTRA_SERVICES" => "N",
		"DELIVERY_NO_AJAX" => "H",
		"DELIVERY_NO_SESSION" => "Y",
		"DELIVERY_TO_PAYSYSTEM" => "d2p",
		"DISABLE_BASKET_REDIRECT" => "Y",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"PATH_TO_AUTH" => "/auth/",
		"PATH_TO_BASKET" => "/personal/cart/",
		"PATH_TO_ORDER" => "/personal/order/make/",
		"PATH_TO_PAYMENT" => "/personal/order/payment/",
		"PATH_TO_PERSONAL" => "/personal/order/",
		"PAY_FROM_ACCOUNT" => "N",
		"PAY_SYSTEMS_PER_PAGE" => "9",
		"PICKUPS_PER_PAGE" => "5",
		"PICKUP_MAP_TYPE" => "yandex",
		"PRODUCT_COLUMNS_HIDDEN" => array(
		),
		"PRODUCT_COLUMNS_VISIBLE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "PROPS",
		),
		"PROPS_FADE_LIST_1" => array(
			0 => "1",
			1 => "16",
		),
		"PROP_1" => "",
		"SEND_NEW_USER_NOTIFY" => "N",
		"SERVICES_IMAGES_SCALING" => "adaptive",
		"SET_TITLE" => "N",
		"SHOW_ACCOUNT_NUMBER" => "Y",
		"SHOW_BASKET_HEADERS" => "N",
		"SHOW_COUPONS_BASKET" => "Y",
		"SHOW_COUPONS_DELIVERY" => "N",
		"SHOW_COUPONS_PAY_SYSTEM" => "N",
		"SHOW_DELIVERY_INFO_NAME" => "Y",
		"SHOW_DELIVERY_LIST_NAMES" => "Y",
		"SHOW_DELIVERY_PARENT_NAMES" => "N",
		"SHOW_MAP_IN_PROPS" => "N",
		"SHOW_NEAREST_PICKUP" => "N",
		"SHOW_NOT_CALCULATED_DELIVERIES" => "L",
		"SHOW_ORDER_BUTTON" => "always",
		"SHOW_PAY_SYSTEM_INFO_NAME" => "Y",
		"SHOW_PAY_SYSTEM_LIST_NAMES" => "Y",
		"SHOW_PICKUP_MAP" => "N",
		"SHOW_STORES_IMAGES" => "N",
		"SHOW_TOTAL_ORDER_BUTTON" => "N",
		"SHOW_VAT_PRICE" => "Y",
		"SKIP_USELESS_BLOCK" => "Y",
		"SPOT_LOCATION_BY_GEOIP" => "N",
		"TEMPLATE_LOCATION" => "popup",
		"TEMPLATE_THEME" => "site",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "1",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "Y",
		"USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
		"USE_CUSTOM_ERROR_MESSAGES" => "N",
		"USE_CUSTOM_MAIN_MESSAGES" => "N",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_PRELOAD" => "Y",
		"USE_PREPAYMENT" => "N",
		"USE_YM_GOALS" => "N",
		"PROPS_FADE_LIST_2" => array(
		),
		"USE_PHONE_NORMALIZATION" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);
// }
// else
// {
// 	ShowError($arResult["ERROR_MESSAGE"]);
// }
