<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

/**
 * @global CMain $APPLICATION
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arResult
 * @var array $arCurSection
 */
?>
<div class="catalog-left">
    <div class="total-prod">
        <?
        $activeElements = CIBlockSection::GetSectionElementsCount($arResult["VARIABLES"]["SECTION_ID"], Array("CNT_ACTIVE" => "Y"));
        if ($activeElements > 0)
            echo $activeElements . " товаров в категории";
        ?>

    </div>
    <? $APPLICATION->IncludeComponent("bitrix:menu", "catalog_left", array(
        "ALLOW_MULTI_SELECT" => "N",
        "CHILD_MENU_TYPE" => "catalog",
        "COMPONENT_TEMPLATE" => "catalog_left",
        "DELAY" => "N",
        "MAX_LEVEL" => "4",
        "MENU_CACHE_GET_VARS" => "",
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_TYPE" => "N",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "ROOT_MENU_TYPE" => "catalog",
        "USE_EXT" => "Y"
    ),
        false,
        array(
            "ACTIVE_COMPONENT" => "Y"
        )
    ); ?>
    <?
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.smart.filter",
        "main",
        array(
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "SECTION_ID" => $arCurSection['ID'],
            "FILTER_NAME" => $arParams["FILTER_NAME"],
            "PRICE_CODE" => $arParams["PRICE_CODE"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "SAVE_IN_SESSION" => "N",
            "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
            "XML_EXPORT" => "Y",
            "SECTION_TITLE" => "NAME",
            "SECTION_DESCRIPTION" => "DESCRIPTION",
            'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
            "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
            'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
            "SEF_MODE" => $arParams["SEF_MODE"],
            "SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"],
            "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
            "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
            "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
            "POPUP_POSITION" => "right"
        ),
        $component,
        array('HIDE_ICONS' => 'Y')
    );

    ?>
</div>
<?
// $arParams["ELEMENT_SORT_FIELD"] = "HAS_PREVIEW_PICTURE";//"catalog_PRICE_1";
// $arParams["ELEMENT_SORT_ORDER"] = "desc";
// $arParams["ELEMENT_SORT_FIELD2"] = "HAS_DETAIL_PICTURE";
// $arParams["ELEMENT_SORT_ORDER2"] = "desc";
?>


<div id="MY_AJAX_FILTER">
    <?
    if (isset($_REQUEST['my_ajax']) && $_REQUEST['my_ajax'] == 'Y'):
        $GLOBALS['APPLICATION']->RestartBuffer();
    endif; ?>
    <div class="product">

        <? $APPLICATION->ShowViewContent('popular-link'); ?>


        <div class="schema-tags">
            <? $APPLICATION->ShowViewContent('prop_filter'); ?>
        </div>

        <div class="product__sort">
            <div>
                <? if ($_GET["sort"] == "shows" && $_GET["method"] == "desc"): ?>
                    <a class="product__sort-lnk product__sort-lnk_active"
                       href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=shows&method=asc">По популярности</a>
                <? else: ?>
                    <a class="product__sort-lnk" href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=shows&method=desc">По
                        популярности</a>
                <? endif; ?>
                <? if ($_GET["sort"] == "property_newproduct" && $_GET["method"] == "desc"): ?>
                    <a class="product__sort-lnk product__sort-lnk_active"
                       href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=property_newproduct&method=asc">По новинкам</a>
                <? else: ?>
                    <a class="product__sort-lnk"
                       href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=property_newproduct&method=desc">По новинкам</a>
                <? endif; ?>
                <? /*if($_GET["sort"] == "catalog_PRICE_1" && $_GET["method"] == "desc"):?>
			<a class="product__sort-lnk product__sort-lnk_active" href="<?=$arResult["SECTION_PAGE_URL"]?>?sort=catalog_PRICE_1&method=asc">По цене</a>
		<?else:?>
			<a class="product__sort-lnk" href="<?=$arResult["SECTION_PAGE_URL"]?>?sort=catalog_PRICE_1&method=desc">По цене</a>
		<?endif;*/ ?>
                <?
                $SORT_PRICE_CODE = 'PROPERTY_PRICE_SORT_2';
                $SORT_DISCOUNT_CODE = 'PROPERTY_PRICE_SORT_2_DISCOUNT';
                $arGroup = $USER->GetUserGroup($USER->GetID());
                if (in_array(14, $arGroup) !== false) {
                    $SORT_PRICE_CODE = 'PROPERTY_PRICE_SORT_14';
                    $SORT_DISCOUNT_CODE = 'PROPERTY_PRICE_SORT_14_DISCOUNT';
                } elseif (in_array(10, $arGroup) !== false) {
                    $SORT_PRICE_CODE = 'PROPERTY_PRICE_SORT_10';
                    $SORT_DISCOUNT_CODE = 'PROPERTY_PRICE_SORT_10_DISCOUNT';
                }
                ?>
                <? if ($_GET["sort"] == $SORT_PRICE_CODE && $_GET["method"] == "desc"): ?>
                    <a class="product__sort-lnk product__sort-lnk_active"
                       href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=<?= $SORT_PRICE_CODE ?>&method=asc">По цене</a>
                <? else: ?>
                    <a class="product__sort-lnk"
                       href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=<?= $SORT_PRICE_CODE ?>&method=desc">По цене</a>
                <? endif; ?>

                <? if ($_GET["sort"] == $SORT_DISCOUNT_CODE && $_GET["method"] == "desc"): ?>
                    <a class="product__sort-lnk product__sort-lnk_active"
                       href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=<?= $SORT_DISCOUNT_CODE ?>&method=asc">По
                        скидке</a>
                <? else: ?>
                    <a class="product__sort-lnk"
                       href="<?= $arResult["SECTION_PAGE_URL"] ?>?sort=<?= $SORT_DISCOUNT_CODE ?>&method=desc">По
                        скидке</a>
                <? endif; ?>
            </div>
        </div>
        <div style="clear: right"></div>

        <? if ($_GET["sort"] == "shows" || $_GET["sort"] == "property_newproduct" || $_GET["sort"] == $SORT_PRICE_CODE || $_GET["sort"] == $SORT_DISCOUNT_CODE) {
            $arParams["ELEMENT_SORT_FIELD"] = $_GET["sort"];
            $arParams["ELEMENT_SORT_ORDER"] = $_GET["method"];
            //PROPERTY_MAXIMUM_PRICE
        } ?>
        <? $APPLICATION->ShowViewContent('section_banner'); ?>
        <?
        // global $arrFilter;
        // $arrFilter = array('!CATALOG_PRICE_1' => false,'!CATALOG_PRICE_1'=>0);
        //GmiPrint($arResult);
        ?>




        <? $intSectionID = $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "cat",//"scrol_load",//"main",
            array(
                'SECTION_USER_FIELDS' => array(0 => 'UF_YOUTUBE', 1 => 'UF_POPULAR'),
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
                "PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
                "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
                "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
                "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
                "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
                "BASKET_URL" => $arParams["BASKET_URL"],
                "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "SET_TITLE" => $arParams["SET_TITLE"],
                "MESSAGE_404" => $arParams["~MESSAGE_404"],
                "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                "SHOW_404" => $arParams["SHOW_404"],
                "FILE_404" => $arParams["FILE_404"],
                "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                "PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
                "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                "PRICE_CODE" => $arParams["PRICE_CODE"],
                "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

                "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

                "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
                "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
                "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                "LAZY_LOAD" => $arParams["LAZY_LOAD"],
                "MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
                "LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],

                "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
                "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

                "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
                "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["element"],
                "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

                'LABEL_PROP' => $arParams['LABEL_PROP'],
                'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
                'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
                'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                'PRODUCT_DISPLAY_MODE' => "Y",
                'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
                'PRODUCT_ROW_VARIANTS' => $arParams['LIST_PRODUCT_ROW_VARIANTS'],
                'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
                'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
                'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
                'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
                'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

                'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
                'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
                'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
                'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
                'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
                'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
                'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
                'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
                'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
                'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
                'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

                'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
                'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
                'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

                'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                "ADD_SECTIONS_CHAIN" => "Y",
                'ADD_TO_BASKET_ACTION' => $basketAction,
                'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                'COMPARE_PATH' => $arResult['FOLDER'] . $arResult['URL_TEMPLATES']['compare'],
                'COMPARE_NAME' => $arParams['COMPARE_NAME'],
                'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
                'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
                'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : '')
            ),
            $component
        ); ?>

        <? $GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID; ?>
    </div>

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

    <? if (isset($_REQUEST['my_ajax']) && $_REQUEST['my_ajax'] == 'Y'):
        die();
    endif;
    ?>
</div>
