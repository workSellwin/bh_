<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
/** @var CBitrixComponent $component */
/**
 * @var array $arCurSection
 */
$this->setFrameMode(true);
global $filtS, $sf2;
?>
<div class="container">

<?
$componentName = "bitrix:search.page";
if( $USER->IsAdmin() ){
    $componentName = "bh:search.page";
}

$arElements = $APPLICATION->IncludeComponent(
    $componentName,
	"main_new",
	Array(
		"RESTART" => $arParams["RESTART"],
		"NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"],
		"USE_LANGUAGE_GUESS" => $arParams["USE_LANGUAGE_GUESS"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
		"arrFILTER" => array("iblock_".$arParams["IBLOCK_TYPE"]),
		"arrFILTER_iblock_".$arParams["IBLOCK_TYPE"] => array($arParams["IBLOCK_ID"]),
		"USE_TITLE_RANK" => "N",
		"DEFAULT_SORT" => "rank",
		"FILTER_NAME" => "searchFilter",
		"SHOW_WHERE" => "N",
		"arrWHERE" => array(),
		"SHOW_WHEN" => "N",
		"PAGE_RESULT_COUNT" => $arParams["PAGE_RESULT_COUNT"],
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGE_RESULT_COUNT" => "500",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "N",
        "REGEXP_FOR_EXCEPTION" => $arParams['SEARCH_REGEXP_FOR_EXCEPTION'],
	),
	$component,
	array('HIDE_ICONS' => 'N')
);



if (!empty($arElements) && is_array($arElements))
{
		global $searchFilter111;
		$searchFilter111 = array(
			"=ID" => $arElements,
		);
		$arParams["ELEMENT_SORT_FIELD"] = "HAS_PREVIEW_PICTURE";//"catalog_PRICE_1";
		$arParams["ELEMENT_SORT_ORDER"] = "desc";
		$arParams["ELEMENT_SORT_FIELD2"] = "HAS_DETAIL_PICTURE";
		$arParams["ELEMENT_SORT_ORDER2"] = "desc";

		//$searchFilter = array_merge($searchFilter, Array("SECTION_ID" => \Kosmos\Multisite::getCatalogSections()));

   // PR($searchFilter111);

    //----------------------------------------------------------------------------------------------------------------------------
    ?>

<div class="catalog cl">
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
            "bh.by:search_smart_filter",
            "main_search",
            array(
                "ID_ELEM" => $arElements,
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
               // "SECTION_ID" => $arCurSection['ID'],
                "FILTER_NAME" => 'sf2',
                "PRICE_CODE" => $arParams["PRICE_CODE"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "SAVE_IN_SESSION" => "N",
                "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                "XML_EXPORT" => "Y",
                "SECTION_TITLE" => "NAME",
                "SECTION_DESCRIPTION" => "DESCRIPTION",
                //'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                "TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                "SEF_MODE" => $arParams["SEF_MODE"],
                "SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"],
                //"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                "POPUP_POSITION" => "right",
                "SHOW_ALL_WO_SECTION" => 'Y'
            ),
            $component,
            array('HIDE_ICONS' => 'N')
        );

        global $sf2;
        $filtS = array_merge ($searchFilter111, $sf2);
        ?>
    </div>


<?
// $arParams["ELEMENT_SORT_FIELD"] = "HAS_PREVIEW_PICTURE";//"catalog_PRICE_1";
// $arParams["ELEMENT_SORT_ORDER"] = "desc";
// $arParams["ELEMENT_SORT_FIELD2"] = "HAS_DETAIL_PICTURE";
// $arParams["ELEMENT_SORT_ORDER2"] = "desc";



?>


    <div id="MY_AJAX_FILTER">
        <? if (isset($_REQUEST['my_ajax']) && $_REQUEST['my_ajax'] == 'Y'):
            $GLOBALS['APPLICATION']->RestartBuffer();

            if ($arParams['DATA_LIST'] == 'S') { ?>

                <style>
                    .prod-text {
                        display: none;
                    }
                    .new_views_item .product__col {
                        height: 300px;
                    }

                    @media only screen and (min-width: 1200px) {

                        .new_views_item .product-item-image-alternative {
                            display: none !important;
                        }



                        .new_views_item .product-item-scu-item-color {
                            background-size: contain;
                            background-repeat: no-repeat;
                            background-position: center;
                        }

                        .prod-text {
                            display: block;
                        }

                        .new_views_item .product__col {
                            width: 100% !important;
                        }

                        .new_views_item {
                            display: flex;
                            flex-flow: nowrap;
                            justify-content: space-between;
                            min-height: 300px;
                        }

                        .new_views_item .product-item-image-slider-slide-container {
                            display: none;
                        }

                        .new_views_item .product-item-image-wrapper {
                            display: inline;
                        }

                        .new_views_item .product-item-image-wrapper img {
                            margin: 0;
                            position: initial;
                        }

                        .new_views_item .my_views_item_span {
                            position: relative;
                        }

                        .new_views_item .child {
                            width: 40%;
                            height: 40%;
                        }

                        .new_views_item .child-20 {
                            width: 20%;
                        }

                        .new_views_item .child-30 {
                            width: 30%;
                        }

                        .new_views_item .child-40 {
                            width: 40%;
                        }

                        .new_views_item .child-50 {
                            width: 50%;
                        }

                        .new_views_item .child-60 {
                            width: 60%;
                        }

                        .new_views_item .child-50 .prod-name {
                            text-align: left;
                            padding: 0;
                            margin-top: 30px;
                            margin-bottom: 0;
                            font-weight: 600;
                            margin-left: 5px;
                        }

                        .new_views_item .child-50 .prod-text {
                            text-align: left;
                            font-weight: 300;
                            margin-left: 5px;
                        }

                        .new_views_item .child-center {
                            align-self: center;
                        }

                        .new_views_item .prod-price:before {
                            display: none;
                        }
                        .new_views_item .hover{
                            height: auto!important;
                        }
                        .new_views_item .prod__item-container{
                            height: auto!important;
                        }
                    }

                </style>
                <?
            }


        endif; ?>


        <? if ($arParams['DATA_LIST'] == 'S') { ?>

            <style>
                .prod-text {
                    display: none;
                }

                .new_views_item .product__col {
                    height: 300px;
                }

                @media only screen and (min-width: 1200px) {

                    .new_views_item .product-item-image-alternative {
                        display: none !important;
                    }

                    .new_views_item .product-item-scu-item-color {
                        background-size: contain;
                        background-repeat: no-repeat;
                        background-position: center;
                    }

                    .prod-text {
                        display: block;
                    }

                    .new_views_item .product__col {
                        width: 100% !important;
                    }

                    .new_views_item {
                        display: flex;
                        flex-flow: nowrap;
                        justify-content: space-between;
                        min-height: 300px;
                    }

                    .new_views_item .product-item-image-slider-slide-container {
                        display: none;
                    }

                    .new_views_item .product-item-image-wrapper {
                        display: inline;
                    }

                    .new_views_item .product-item-image-wrapper img {
                        margin: 0;
                        position: initial;
                    }

                    .new_views_item .my_views_item_span {
                        position: relative;
                    }

                    .new_views_item .child {
                        width: 40%;
                        height: 40%;
                    }

                    .new_views_item .child-20 {
                        width: 20%;
                    }

                    .new_views_item .child-30 {
                        width: 30%;
                    }

                    .new_views_item .child-40 {
                        width: 40%;
                    }

                    .new_views_item .child-50 {
                        width: 50%;
                    }

                    .new_views_item .child-60 {
                        width: 60%;
                    }

                    .new_views_item .child-50 .prod-name {
                        text-align: left;
                        padding: 0;
                        margin-top: 30px;
                        margin-bottom: 0;
                        font-weight: 600;
                        margin-left: 5px;
                    }

                    .new_views_item .child-50 .prod-text {
                        text-align: left;
                        font-weight: 300;
                        margin-left: 5px;
                    }

                    .new_views_item .child-center {
                        align-self: center;
                    }

                    .new_views_item .prod-price:before {
                        display: none;
                    }
                }

            </style>
            <?
        } ?>

        <div class=" <?= $arParams['DATA_LIST'] == 'S' ? 'new_views_item' : '' ?>">
            <div class="product" <?= $arParams['DATA_LIST'] == 'S' ? 'style="margin-left:0; width: 100%;"' : '' ?>>

                <? $APPLICATION->ShowViewContent('popular-link'); ?>


                <div class="schema-tags">
                    <? $APPLICATION->ShowViewContent('prop_filter'); ?>
                </div>

                <div class="product__sort">
                    <div>
                        <? if ($_GET["sort"] == "shows" && $_GET["method"] == "desc"): ?>
                            <a class="product__sort-lnk product__sort-lnk_active"
                               href="<?//= $arResult["SECTION_PAGE_URL"] ?>sort=shows&method=asc">По популярности</a>
                        <? else: ?>
                            <a class="product__sort-lnk" href="<?//= $arResult["SECTION_PAGE_URL"] ?>sort=shows&method=desc">По популярности</a>
                        <? endif; ?>
                        <? if ($_GET["sort"] == "property_newproduct" && $_GET["method"] == "desc"): ?>
                            <a class="product__sort-lnk product__sort-lnk_active"
                               href="<?//= $arResult["SECTION_PAGE_URL"] ?>sort=property_newproduct&method=asc">По новинкам</a>
                        <? else: ?>
                            <a class="product__sort-lnk"
                               href="<?//= $arResult["SECTION_PAGE_URL"] ?>sort=property_newproduct&method=desc">По новинкам</a>
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
                               href="<?//= $arResult["SECTION_PAGE_URL"] ?>sort=<?= $SORT_PRICE_CODE ?>&method=asc">По
                                цене</a>
                        <? else: ?>
                            <a class="product__sort-lnk"
                               href="<?//= $arResult["SECTION_PAGE_URL"] ?>sort=<?= $SORT_PRICE_CODE ?>&method=desc">По
                                цене</a>
                        <? endif; ?>

                        <? if ($_GET["sort"] == $SORT_DISCOUNT_CODE && $_GET["method"] == "desc"): ?>
                            <a class="product__sort-lnk product__sort-lnk_active"
                               href="<?//= $arResult["SECTION_PAGE_URL"] ?>sort=<?= $SORT_DISCOUNT_CODE ?>&method=asc">По
                                скидке</a>
                        <? else: ?>
                            <a class="product__sort-lnk"
                               href="<?//= $arResult["SECTION_PAGE_URL"] ?>sort=<?= $SORT_DISCOUNT_CODE ?>&method=desc">По
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

                <div class="b-tools  g-box_lseparator">

                    <ul class="tools-view">
                        <li class="tools-view__item cr-tools-view__first <?= $arParams['DATA_LIST'] == 'S' ? 'cr-tools-view__active' : '' ?>"
                            data-list="S">
                            <a class="tools-view__link j-tools_output icon-view-list">Список</a>
                        </li>
                        <li class="tools-view__item cr-tools-view__last <?= $arParams['DATA_LIST'] == 'P' ? 'cr-tools-view__active' : '' ?>"
                            data-list="P">
                            <a class="tools-view__link j-tools_output icon-view-grid">Сетка</a>
                        </li>
                    </ul>
                    <div style="clear: both"></div>
                    <div class="amountPerPage">
                        <? $arNumPage = array(50, 100, 200); ?>
                        <span class="title">Показывать по:</span>
                        <? foreach ($arNumPage as $val): ?>
                            <a class="unit <?= $arParams['NUM_PAGE'] == $val ? 'active' : '' ?>" data-num-page="<?= $val ?>"
                               href="#"><?= $val ?></a>
                        <? endforeach; ?>
                    </div>

                </div>


                <?


                $APPLICATION->IncludeComponent(
                    "bitrix:catalog.section",
                    "cat_new_views",
                    array(
                         "DATA_LIST" => $arParams['DATA_LIST'],
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                        "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                        "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                        "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                        "PAGE_ELEMENT_COUNT" => $arParams["NUM_PAGE"],
                        "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                        "PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
                        "PROPERTY_CODE_MOBILE" => $arParams["PROPERTY_CODE_MOBILE"],
                        "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                        "OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
                        "OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
                        "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                        "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                        "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                        "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                        "OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
                        "SECTION_URL" => $arParams["SECTION_URL"],
                        "DETAIL_URL" => $arParams["DETAIL_URL"],
                        "BASKET_URL" => $arParams["BASKET_URL"],
                        "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                        "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                        "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                        "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
                        "PRICE_CODE" => $arParams["PRICE_CODE"],
                        "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                        "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                        "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                        "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                        "USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
                        "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                        "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                        "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                        "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                        "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                        'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
                        "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                        "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                        "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                        "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                        "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                        "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                        "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                        "LAZY_LOAD" => $arParams["LAZY_LOAD"],
                        "MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
                        "LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],
                        "FILTER_NAME" => "filtS",
                        "SECTION_ID" => "",
                        "SECTION_CODE" => "",
                        "SECTION_USER_FIELDS" => array(),
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "SHOW_ALL_WO_SECTION" => "Y",
                        "META_KEYWORDS" => "",
                        "META_DESCRIPTION" => "",
                        "BROWSER_TITLE" => "Поиск",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "SET_TITLE" => "Y",
                        "SET_STATUS_404" => "N",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "N",

                        'LABEL_PROP' => $arParams['LABEL_PROP'],
                        'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
                        'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],
                        'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                        'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                        'PRODUCT_BLOCKS_ORDER' => $arParams['PRODUCT_BLOCKS_ORDER'],
                        'PRODUCT_ROW_VARIANTS' => $arParams['PRODUCT_ROW_VARIANTS'],
                        'ENLARGE_PRODUCT' => $arParams['ENLARGE_PRODUCT'],
                        'ENLARGE_PROP' => $arParams['ENLARGE_PROP'],
                        'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
                        'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
                        'SLIDER_PROGRESS' => $arParams['SLIDER_PROGRESS'],

                        'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                        'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                        'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                        'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                        'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                        'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                        'MESS_SHOW_MAX_QUANTITY' => $arParams['~MESS_SHOW_MAX_QUANTITY'],
                        'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
                        'MESS_RELATIVE_QUANTITY_MANY' => $arParams['~MESS_RELATIVE_QUANTITY_MANY'],
                        'MESS_RELATIVE_QUANTITY_FEW' => $arParams['~MESS_RELATIVE_QUANTITY_FEW'],
                        'MESS_BTN_BUY' => $arParams['~MESS_BTN_BUY'],
                        'MESS_BTN_ADD_TO_BASKET' => $arParams['~MESS_BTN_ADD_TO_BASKET'],
                        'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                        'MESS_BTN_DETAIL' => $arParams['~MESS_BTN_DETAIL'],
                        'MESS_NOT_AVAILABLE' => $arParams['~MESS_NOT_AVAILABLE'],
                        'MESS_BTN_COMPARE' => $arParams['~MESS_BTN_COMPARE'],

                        'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
                        'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
                        'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY'],

                        'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
                        'ADD_TO_BASKET_ACTION' => (isset($arParams['ADD_TO_BASKET_ACTION']) ? $arParams['ADD_TO_BASKET_ACTION'] : ''),
                        'SHOW_CLOSE_POPUP' => (isset($arParams['SHOW_CLOSE_POPUP']) ? $arParams['SHOW_CLOSE_POPUP'] : ''),
                        'COMPARE_PATH' => $arParams['COMPARE_PATH'],
                        'COMPARE_NAME' => $arParams['COMPARE_NAME']
                    ),
                    $arResult["THEME_COMPONENT"],
                    array('HIDE_ICONS' => 'Y')
                );


                ?>

                <? $GLOBALS['CATALOG_CURRENT_SECTION_ID'] = $intSectionID; ?>
            </div>

        </div>
        <? if (isset($_REQUEST['my_ajax']) && $_REQUEST['my_ajax'] == 'Y'):
            die();
        endif;
        ?>
    </div>


    <script>
        var i = 1;
        $('body').on('click', '.schema-tags__item', function () {

            if (i == 1) {
                prop_click($(this));
            }
            i++;
        });


        $('body').on('click', '.tools-view .j-tools_output', function () {
            if (!$(this).parent().hasClass('cr-tools-view__active')) {
                $('.product__list').css('opacity', '0.5');
                var data_list = $(this).parent().attr('data-list');
                $.post(
                    window.location.href,
                    {
                        data_list: data_list,
                        my_ajax: 'Y'
                    },
                    onAjaxSuccess
                );

                function onAjaxSuccess(data) {
                    $('#MY_AJAX_FILTER').html(data);
                    $('.product__list').css('opacity', '1');
                }
            }

        });

        $('body').on('click', '.amountPerPage a', function () {
            $('.product__list').css('opacity', '0.5');
            var data_num_page = $(this).attr('data-num-page');
            $.post(
                window.location.href,
                {
                    data_num_page: data_num_page,
                    my_ajax: 'Y'
                },
                onAjaxSuccess
            );

            function onAjaxSuccess(data) {
                $('#MY_AJAX_FILTER').html(data);
                $('.product__list').css('opacity', '1');
            }

            return false;
        });


        $('body').on('click', '.product__sort .product__sort-lnk', function () {
            var params_sort = $(this).attr('href');
            var url = window.location.href+'&'+params_sort;
            location=url;
            return false;
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
</div> </div><?
}
elseif (is_array($arElements))
{
	echo GetMessage("CT_BCSE_NOT_FOUND");
}
?>

</div>
<?$APPLICATION->SetPageProperty('title', "Поиск"); ?>
<?$APPLICATION->SetTitle("Поиск"); ?>