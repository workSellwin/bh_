<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
    <style>
        .brend-detal {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            font-size: 20px;
            margin-bottom: 40px;
            border: 1px solid;
        }

        .brend-img {
            min-width: 250px;
            max-width: 250px;
            margin-right: 10px;
            padding-top: 13px;
            padding-left: 13px;
            padding-bottom: 13px;

        }

        .brend-text {
            width: 700px;
            font-size: 16px;
            padding: 13px 13px 13px 0;
        }
    </style>
<?
$arBrendElem = array();
$arSelect = Array('ID', 'NAME', 'PROPERTY_BRAND', 'PROPERTY_BRAND', 'PROPERTY_SERIES.NAME', 'PROPERTY_SERIES.ID', 'PROPERTY_SITE', 'DETAIL_PAGE_URL');
$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "CODE" => $arResult['BREND_CODE'], "ACTIVE" => "Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
while ($ob = $res->GetNext()) {
    $arBrendElem['BREND'] = $ob;
    if ($ob['PROPERTY_SERIES_ID']) {
        $arBrendElem['SER_ID'][$ob['PROPERTY_SERIES_ID']] = $ob['PROPERTY_SERIES_NAME'];
    }

}

$arSeriiElem = array();
$arSelect = Array('ID', 'NAME', 'CODE', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'ELEMENT_META_*');
$arFilter = Array("IBLOCK_ID" => 36, 'CODE' => $arResult['SERII_CODE']);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
$meta_gate = [];
while ($ob = $res->GetNext()) {
    $arSeriiElem = $ob;
    $arSeriiElem['PREVIEW_PICTURE'] = CFile::GetPath($ob["PREVIEW_PICTURE"]);

    $ipropValues = new Bitrix\Iblock\InheritedProperty\ElementValues(36, $ob['ID']);
    $meta_gate = $ipropValues->getValues();

    //PR($meta_gate);
}


if (!empty($arSeriiElem)) {
    global $arrFilter;

    $arrFilter["PROPERTY"] = array("BRANDS" => $arBrendElem['BREND']['PROPERTY_BRAND_VALUE']);

    $arrFilter["PROPERTY"] = array(
        "LINK_BRANDS" => $arBrendElem['BREND']['ID'],
        "LINK_SERIES" => $arSeriiElem['ID'],
    );

    ?>


    <div class="catalog stock cl">
        <div class="catalog-left">

            <?
            $APPLICATION->IncludeComponent(
                "bh.by:serii_smart_filter",
                "main",
                array(
                    "IBLOCK_TYPE" => "",
                    "IBLOCK_ID" => "2",
                    "FILTER_NAME" => "arrFilter",
                    "PRICE_CODE" => array(
                        0 => "Trade Price",
                        1 => "RTL",
                    ),
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => "N",
                    "SAVE_IN_SESSION" => "N",
                    "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                    "XML_EXPORT" => "N",
                    "SECTION_TITLE" => "NAME",
                    "SECTION_DESCRIPTION" => "DESCRIPTION",
                    "HIDE_NOT_AVAILABLE" => "Y",
                    "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                    "CONVERT_CURRENCY" => "N",
                    "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                    "SEF_MODE" => "N",
                    "SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"],
                    "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                    "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                    "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                    "SHOW_ALL_WO_SECTION" => "Y",
                    "COMPONENT_TEMPLATE" => "main",
                    "SECTION_ID" => "",
                    "SECTION_CODE" => "",
                    "DISPLAY_ELEMENT_COUNT" => "Y",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                    "BRANDS_ELEM_FILTER" => $arrFilter['PROPERTY']['BRANDS'],
                    "SERII_ELEM_FILTER" => $arSeriiElem['NAME'],
                ),
                false
            );
            ?>
        </div>
        <div class="product">

            <? if ($arSeriiElem['PREVIEW_PICTURE'] || $arSeriiElem['PREVIEW_TEXT']): ?>
                <div class="brend-detal">
                    <? if ($arSeriiElem['PREVIEW_PICTURE']): ?>
                        <div class="brend-img"><img src="<?= $arSeriiElem['PREVIEW_PICTURE'] ?>"></div>
                    <? endif; ?>
                    <? if ($arSeriiElem['PREVIEW_TEXT']): ?>
                        <div class="brend-text"><?= $arSeriiElem['PREVIEW_TEXT'] ?></div>
                    <? endif; ?>
                </div>
            <? endif; ?>


            <?

            global $filter_serii;
            $filter_serii['CODE'] = $arResult['SERII_CODE'];
            $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "null",
                array(
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "N",
                    "CACHE_TIME" => "360000",
                    "CACHE_TYPE" => "A",
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "DISPLAY_BOTTOM_PAGER" => "Y",
                    "DISPLAY_DATE" => "Y",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "DISPLAY_TOP_PAGER" => "N",
                    "FIELD_CODE" => array(
                        0 => "PREVIEW_TEXT",
                        1 => "PREVIEW_PICTURE",
                        2 => "",
                    ),
                    "FILTER_NAME" => "filter_serii",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "IBLOCK_ID" => "36",
                    "IBLOCK_TYPE" => "handbook",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "MESSAGE_404" => "",
                    "NEWS_COUNT" => "10",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "PAGER_TITLE" => "Новости",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "PROPERTY_CODE" => array(
                        0 => "",
                        1 => "POSITION",
                        2 => "LINK",
                        3 => "",
                    ),
                    "SET_BROWSER_TITLE" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_STATUS_404" => "N",
                    "SET_TITLE" => "Y",
                    "SHOW_404" => "N",
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER1" => "DESC",
                    "SORT_ORDER2" => "ASC",
                    "STRICT_SECTION_CHECK" => "N",
                    "COMPONENT_TEMPLATE" => "null"
                ),
                false
            ); ?>


            <div id="MY_AJAX_FILTER">
                <?
                if (isset($_REQUEST['my_ajax']) && $_REQUEST['my_ajax'] == 'Y'):
                    $GLOBALS['APPLICATION']->RestartBuffer();
                    ?>
                    <script>
                        $('.brend-detal').hide();
                    </script>
                <?
                endif; ?>

                <div class="schema-tags">
                    <? $APPLICATION->ShowViewContent('prop_filter'); ?>
                </div>

                <?

                $arrFilter["PROPERTY"] = array("LINK_BRANDS" => $arBrendElem['BREND']['ID'], 'LINK_SERIES' => $arSeriiElem['ID']);
                $APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"cat", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "-",
		"DESCRIPTION" => $arResult["SECTION"]["DESCRIPTION"],
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "/personal/cart/",
		"BROWSER_TITLE" => "NAME",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPATIBLE_MODE" => "Y",
		"COMPONENT_TEMPLATE" => "cat",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISCOUNT_PERCENT_POSITION" => "bottom-right",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_COMPARE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "PROP",
		"ENLARGE_PROP" => "-",
		"FILTER_NAME" => "arrFilter",
		"HIDE_NOT_AVAILABLE" => "Y",
		"HIDE_NOT_AVAILABLE_OFFERS" => "Y",
		"IBLOCK_ID" => "2",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_TYPE_ID" => "catalog",
		"INCLUDE_SUBSECTIONS" => "A",
		"LABEL_PROP" => array(
			0 => "NEWPRODUCT",
			1 => "SALELEADER",
			2 => "SPECIALOFFER",
			3 => "SALE",
		),
		"LABEL_PROP_MOBILE" => array(
			0 => "NEWPRODUCT",
			1 => "SALELEADER",
			2 => "SPECIALOFFER",
			3 => "SALE",
		),
		"LABEL_PROP_POSITION" => "top-left",
		"LAZY_LOAD" => "N",
		"LINE_ELEMENT_COUNT" => "3",
		"LOAD_ON_SCROLL" => "N",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_LAZY_LOAD" => "Показать ещё",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_CART_PROPERTIES" => array(
			0 => "COLOR_REF,SIZES_SHOES,SIZES_CLOTHES",
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_LIMIT" => "0",
		"OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR_REF_2",
			1 => "COLOR_REF",
			2 => "",
		),
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER" => "desc",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFER_ADD_PICT_PROP" => "-",
		"OFFER_TREE_PROPS" => array(
		),
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "main",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "12",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "SELLWIN",
			1 => "Trade Price",
			2 => "RTL",
			3 => "b2b Activ",
			4 => "b2b pro loreal pro/matrix",
			5 => "b2b Kerastase",
			6 => "b2b Redken",
			7 => "BASE",
			8 => "OPT",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "N",
		"PROPERTY_CODE" => array(
			0 => "NEWPRODUCT",
			1 => "SALELEADER",
			2 => "SPECIALOFFER",
			3 => "SALE",
			4 => "",
		),
		"PROPERTY_CODE_MOBILE" => array(
			0 => "NEWPRODUCT",
			1 => "SALELEADER",
			2 => "SPECIALOFFER",
			3 => "SALE",
		),
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
		"RCM_TYPE" => "personal",
		"SECTION_CODE" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SEF_MODE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "N",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_FROM_SECTION" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "N",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "site",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"FILE_404" => "",
		"SEF_RULE" => "",
		"SECTION_CODE_PATH" => "",
		"SECTION_ID" => $_REQUEST["SECTION_ID"]
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
); ?>


                <script>
                    var i = 1;
                    $('body').on('click', '.schema-tags__item', function () {

                        if (i == 1) {
                            prop_click($(this));
                        }
                        i++;
                    });


                    function prop_click($this) {
                        var data_prop_id = $($this).attr('data-prop-id');
                        var data_price = $($this).attr('data-price');
                        if (data_price == 'Y') {
                            var data_price_value = $($this).attr('data-price-value');
                            setTimeout(function () {
                                $('input#' + data_prop_id).val(data_price_value);
                                $('input#' + data_prop_id).trigger("click");
                                $('input#' + data_prop_id).trigger("keyup");
                            }, 1);
                        } else {
                            setTimeout(function () {
                                $('input#' + data_prop_id).trigger("click");
                            }, 10);
                        }
                    }
                </script>

                <?
                if (isset($_REQUEST['my_ajax']) && $_REQUEST['my_ajax'] == 'Y'):
                    die();
                endif;
                ?>
            </div>
        </div>
    </div>


    <?
    $APPLICATION->AddChainItem($arBrendElem['BREND']["NAME"], $arBrendElem['BREND']['DETAIL_PAGE_URL']);
    $APPLICATION->AddChainItem($arSeriiElem["NAME"], "");
    $APPLICATION->SetTitle($arSeriiElem["NAME"]);


   /* $ipropTemplates->set(array(
        "ELEMENT_META_TITLE" => $row['seo_title'],
        "ELEMENT_META_KEYWORDS" => $row['seo_keywords'],
        "ELEMENT_META_DESCRIPTION" => $row['seo_description'],
    ));*/



    $APPLICATION->SetPageProperty("title", $meta_gate["ELEMENT_META_TITLE"]);
    $APPLICATION->SetPageProperty("keywords", $meta_gate["ELEMENT_META_KEYWORDS"]);
    $APPLICATION->SetPageProperty("description", $meta_gate["ELEMENT_META_DESCRIPTION"]);

    //PR($_SEO);
    ?>

<? } else {
    @define(ERROR_404, "Y");
}