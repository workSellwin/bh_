<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */


if (!\Kosmos\Multisite::showCatalogSection(Array("VARIABLES" => Array("SECTION_ID" => $arResult["IBLOCK_SECTION_ID"])))) {
    @define(ERROR_404, "Y");
    global $APPLICATION;
    $url = $APPLICATION->GetCurPage();
    $host = SITE_ID == 's1' ? 'dev.all.bh.by' : 'dev.bh.by';
    LocalRedirect('http://' . $host . $url);
}

$this->setFrameMode(true);
//$this->addExternalCss('/bitrix/css/main/bootstrap.css');

if (is_array($arResult['PROPERTIES']['NDS']['VALUE']))
    $arResult['PROPERTIES']['NDS']['VALUE'] = current($arResult['PROPERTIES']['NDS']['VALUE']);

$templateLibrary = array('popup', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

if ($arResult['PROPERTIES']['NDS']['VALUE'] == 0) {
    $arResult['PROPERTIES']['NDS']['VALUE'] = 20;
}

$templateData = array(
    'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList,
    'ITEM' => array(
        'ID' => $arResult['ID'],
        'IBLOCK_ID' => $arResult['IBLOCK_ID'],
        'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
        'JS_OFFERS' => $arResult['JS_OFFERS']
    )
);
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
    'ID' => $mainId,
    'DISCOUNT_PERCENT_ID' => $mainId . '_dsc_pict',
    'STICKER_ID' => $mainId . '_sticker',
    'BIG_SLIDER_ID' => $mainId . '_big_slider',
    'BIG_IMG_CONT_ID' => $mainId . '_bigimg_cont',
    'SLIDER_CONT_ID' => $mainId . '_slider_cont',
    'OLD_PRICE_ID' => $mainId . '_old_price',
    'PRICE_ID' => $mainId . '_price',
    'DISCOUNT_PRICE_ID' => $mainId . '_price_discount',
    'PRICE_TOTAL' => $mainId . '_price_total',
    'SLIDER_CONT_OF_ID' => $mainId . '_slider_cont_',
    'QUANTITY_ID' => $mainId . '_quantity',
    'QUANTITY_DOWN_ID' => $mainId . '_quant_down',
    'QUANTITY_UP_ID' => $mainId . '_quant_up',
    'QUANTITY_MEASURE' => $mainId . '_quant_measure',
    'QUANTITY_LIMIT' => $mainId . '_quant_limit',
    'BUY_LINK' => $mainId . '_buy_link',
    'ADD_BASKET_LINK' => $mainId . '_add_basket_link',
    'BASKET_ACTIONS_ID' => $mainId . '_basket_actions',
    'NOT_AVAILABLE_MESS' => $mainId . '_not_avail',
    'COMPARE_LINK' => $mainId . '_compare_link',
    'TREE_ID' => $mainId . '_skudiv',
    'DISPLAY_PROP_DIV' => $mainId . '_sku_prop',
    'DISPLAY_MAIN_PROP_DIV' => $mainId . '_main_sku_prop',
    'OFFER_GROUP' => $mainId . '_set_group_',
    'BASKET_PROP_DIV' => $mainId . '_basket_prop',
    'SUBSCRIBE_LINK' => $mainId . '_subscribe',
    'TABS_ID' => $mainId . '_tabs',
    'TAB_CONTAINERS_ID' => $mainId . '_tab_containers',
    'SMALL_CARD_PANEL_ID' => $mainId . '_small_card_panel',
    'TABS_PANEL_ID' => $mainId . '_tabs_panel'
);
$obName = $templateData['JS_OBJ'] = 'ob' . preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
    : $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
    : $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
    ? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
    : $arResult['NAME'];

$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers) {
    $actualItem = isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']])
        ? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]
        : reset($arResult['OFFERS']);
    $showSliderControls = false;

    foreach ($arResult['OFFERS'] as $offer) {
        if ($offer['MORE_PHOTO_COUNT'] > 1) {
            $showSliderControls = true;
            break;
        }
    }
} else {
    $actualItem = $arResult;
    $showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$skuProps = array();
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

$showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['CATALOG_SUBSCRIBE'] === 'Y' || $haveOffers);

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');
$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');

$positionClassMap = array(
    'left' => 'product-item-label-left',
    'center' => 'product-item-label-center',
    'right' => 'product-item-label-right',
    'bottom' => 'product-item-label-bottom',
    'middle' => 'product-item-label-middle',
    'top' => 'product-item-label-top'
);

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION'])) {
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}
?>
<? foreach ($arResult['SKU_PROPS'] as $key => &$value3) {
    if (isset($arResult['OFFERS_PROP'][$value3['CODE']])) {
        foreach ($value3["VALUES"] as &$value) {
            if ($value["NAME"] != "-") {
                switch ($value3["SHOW_MODE"]) {
                    case 'PICT':
                        foreach ($arResult["OFFERS"] as $value2) {
                            if ($value2["PROPERTIES"][$key]["VALUE"] == $value["XML_ID"] && !empty($value2["PREVIEW_PICTURE"]["SRC"])) {
                                $value["SRC"] = $value2["PREVIEW_PICTURE"]["SRC"];
                                break;
                            }
                        }
                        break;
                    default:
                        foreach ($arResult["OFFERS"] as $value2) {
                            if ($value2["PROPERTIES"][$key]["VALUE_ENUM_ID"] == $value["ID"] && !empty($value2["PREVIEW_PICTURE"]["SRC"])) {
                                $value["SRC"] = $value2["PREVIEW_PICTURE"]["SRC"];
                                break;
                            }
                        }
                        break;
                }
            }
        }
    }
}
// GmiPrint($arResult['SKU_PROPS']);
// GmiPrint($arResult["OFFERS"]);
global $section_id;
$section_id = $arResult["IBLOCK_SECTION_ID"];
?>

    <div class="container">
        <div class="prod cl" id="<?= $itemIds['ID'] ?>" itemscope itemtype="http://schema.org/Product">
            <meta itemprop="name" content="<?= $name ?>"/>
            <meta itemprop="category" content="<?= $arResult['CATEGORY_PATH'] ?>"/>

            <div class="prod__left">
                <div class="<?= (count($actualItem['MORE_PHOTO']) > 1) ? "loader show" : "" ?>"></div>
                <div class="prod__slider-wrp <?= (count($actualItem['MORE_PHOTO']) > 1) ? "hide" : "" ?>">
				<span class="prod-status">
					<span id="<?= $itemIds['STICKER_ID'] ?>"
						<?= (!$arResult['LABEL'] ? 'style="display: none;"' : '') ?>>
						<?
                        if ($arResult['LABEL'] && !empty($arResult['LABEL_ARRAY_VALUE'])) {
                            foreach ($arResult['LABEL_ARRAY_VALUE'] as $code => $value) {
                                ?><span class="prod-status__item" title="<?= $value ?>"><?= $value ?></span><?
                            }
                        }
                        ?>
					</span>
					<? if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y') {
                        if ($haveOffers) {
                            ?>
                            <span class="prod-status__item prod-status__item-describe"
                                  id="<?= $itemIds['DISCOUNT_PERCENT_ID'] ?>"
                                  style="display: none;">
							</span>
                            <?
                        } else {
                            if ($price['DISCOUNT'] > 0) {
                                ?>
                                <span id="<?= $itemIds['DISCOUNT_PERCENT_ID'] ?>"
                                      title="<?= -$price['PERCENT'] ?>%">
									<span class="prod-status__item prod-status__item-describe"><?= -$price['PERCENT'] ?>%</span>
								</span>
                                <?
                            }
                        }
                    } ?>
				</span>

                    <div class="product-item-detail-slider-container" id="<?= $itemIds['BIG_SLIDER_ID'] ?>">
                        <? // <span class="product-item-detail-slider-close" data-entity="close-popup"></span> ?>
                        <div class="product-item-detail-slider-block <?= ($arParams['IMAGE_RESOLUTION'] === '1by1' ? 'product-item-detail-slider-block-square' : '') ?>"
                             data-entity="images-slider-block" id="main_big_slider">
                            <? /* <span class="product-item-detail-slider-left" data-entity="slider-control-left" style="display: none;"></span>
						<span class="product-item-detail-slider-right" data-entity="slider-control-right" style="display: none;"></span>*/ ?>

                            <div class="prod__big-img <?= (!$haveOffers || count($actualItem['MORE_PHOTO']) == 1) ? "prod__big-img_main" : "" ?>"
                                 data-entity="images-container">
                                <?
                                // BX_RESIZE_IMAGE_EXACT
                                // $arFile = CFile::GetFileArray(($photo));

                                ?>

                                <?
                                if (!empty($actualItem['MORE_PHOTO'])) {
                                    foreach ($actualItem['MORE_PHOTO'] as $key => $photo) {
                                        $img_resized = CFile::ResizeImageGet($photo['ID'], array('width' => 518, 'height' => 518), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                        //krumo($actualItem);
                                        //krumo($file);

                                        //AddMessage2Log("resize - " .print_r($photo, true));
                                        ?>
                                        <div class="prod__big-img__item" data-entity="image"
                                             data-id="<?= $photo['ID'] ?>">
                                            <?/*<a data-fancybox="prod" href="<?=$photo['SRC']?>">*/
                                            ?>
                                            <img src="<?= $img_resized['src'] ?>" alt="<?= $alt ?>"
                                                 title="<?= $title ?>"<?= ($key == 0 ? ' itemprop="image"' : '') ?>>
                                            <?//</a>
                                            ?>
                                        </div>
                                        <?
                                    }
                                }

                                /*if ($arParams['SLIDER_PROGRESS'] === 'Y')
                                {
                                    ?>
                                    <div class="product-item-detail-slider-progress-bar" data-entity="slider-progress-bar" style="width: 0;"></div>
                                    <?
                                }*/
                                ?>
                            </div>

                        </div>
                        <?
                        if ($showSliderControls) {
                            if ($haveOffers) {
                                foreach ($arResult['OFFERS'] as $keyOffer => $offer) {
                                    if (!isset($offer['MORE_PHOTO_COUNT']) || $offer['MORE_PHOTO_COUNT'] <= 0)
                                        continue;

                                    $strVisible = $arResult['OFFERS_SELECTED'] == $keyOffer ? '' : 'none';
                                    ?>
                                    <div class="prod__small-img"
                                         id="<?= $itemIds['SLIDER_CONT_OF_ID'] . $offer['ID'] ?>"
                                         style="display: <?= $strVisible ?>;">
                                        <?
                                        foreach ($offer['MORE_PHOTO'] as $keyPhoto => $photo) {
                                            ?>
                                            <div class="prod__small-img__item" data-entity="slider-control"
                                                 data-value="<?= $offer['ID'] . '_' . $photo['ID'] ?>">
                                                <img src="<?= $photo['SRC'] ?>" alt="" style="height: 165px">
                                            </div>
                                            <?
                                        }
                                        ?>
                                    </div>
                                    <?
                                }
                            } else {
                                ?>
                                <div class="prod__small-img prod__small-img_main"
                                     id="<?= $itemIds['SLIDER_CONT_ID'] ?>">
                                    <?
                                    if (!empty($actualItem['MORE_PHOTO'])) {
                                        foreach ($actualItem['MORE_PHOTO'] as $key => $photo) {
                                            ?>
                                            <div class="prod__small-img__item" data-entity="slider-control"
                                                 data-value="<?= $photo['ID'] ?>">
                                                <img src="<?= $photo['SRC'] ?>" style="height: 165px">
                                            </div>
                                            <?
                                        }
                                    }
                                    ?>
                                </div>
                                <?
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="prod__right">
                <? if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS']): ?>
                    <?
                    if (!empty($arResult['DISPLAY_PROPERTIES'])) {
                        ?>
                        <div class="prod__short-info hide">
                            <?
                            foreach ($arResult['DISPLAY_PROPERTIES'] as $property) {
                                if (isset($arParams['MAIN_BLOCK_PROPERTY_CODE'][$property['CODE']])) {
                                    ?>
                                    <div class="prod__short-item"><?= $property['NAME'] ?>:
                                        <span>
										<?
                                        if ($property["CODE"] == "BRANDS") {
                                            $db_list_section = CIBlockSection::GetList(Array(), Array('IBLOCK_ID' => 7, "ACTIVE" => "Y", 'UF_BRAND' => $property["VALUE"]), false);
                                            if ($ar_result_section = $db_list_section->GetNext()) {
                                                $link_brand = $ar_result_section["SECTION_PAGE_URL"];
                                            }
                                        }
                                        ?>
                                            <?
                                            if ($property["CODE"] == "BRANDS" && strlen($link_brand) > 0):?>
                                                <a href="<?= $link_brand ?>"
                                                   target="_blank"><?= $property["VALUE"] ?></a>
                                            <? else: ?>
                                                <?= (is_array($property['DISPLAY_VALUE']) ? implode(' / ', $property['DISPLAY_VALUE']) : $property['DISPLAY_VALUE']); ?>
                                            <? endif; ?>
									</span>
                                    </div>

                                    <?
                                }
                            }
                            unset($property);
                            ?>
                        </div>
                        <?
                    } ?>
                <? endif; ?>


                <? /*<div class="prod__review-total">
				<div class="prod__review-ttl">Оценка покупателей:</div>
				<?
					$resElemCnt = CIBlockElement::GetList(
						false,
						array('IBLOCK_ID' => 8, "PROPERTY_ID_PRODUCT" => $arResult["ID"],"ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"),
						false,
						false,
						Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*")
					);

					
					$count_rating = $resElemCnt -> SelectedRowsCount();
					$sum_rating = 0;
					while($ob = $resElemCnt->GetNextElement()){
						$arProps = $ob->GetProperties();
						$sum_rating += $arProps["RATING"]["VALUE"];
					}
					$res_rating = intval($sum_rating/$count_rating);
					function NumberEnd($number, $titles) {
						$cases = array (2, 0, 1, 1, 1, 2);
						return $titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
					}
				?>
				<div class="prod__review-stars">
					<?for($i=1;$i<6;$i++):?>
						<img src="<?=($res_rating >= $i)? SITE_TEMPLATE_PATH."/images/ico-star-big-active.png":SITE_TEMPLATE_PATH."/images/ico-star-big.png"?>" alt="">
					<?endfor?>
				</div>
				<span>
					<?=$count_rating.' отзыв'.NumberEnd($count_rating, array('','а','ов'));?>
				</span>
			</div>
			*/ ?>
                <? if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS']): ?>
                    <? if ($arResult['SHOW_OFFERS_PROPS']) {
                        ?>
                        <div class="" id="<?= $itemIds['DISPLAY_MAIN_PROP_DIV'] ?>">

                        </div>
                        <?
                    }
                    ?>
                <? endif; ?>
                <div class="prod__short-info hide">
                    <div class="prod__short-item prod__short-item_color" style="display: none;">
                        Тон: <span></span>
                    </div>
                </div>
                <? if ($arResult['OFFERS']) { ?>
                    <div class="prod__short-info ">
                        <div class="prod__short-item prod__short-item_color">
                            <input class="input-certificate" id="sum-certificate" type="text" value=""
                                   placeholder="Введите сумму сетрификата от 30-500 руб">
                        </div>
                    </div>
                <? } ?>
                <? if ($haveOffers && !empty($arResult['OFFERS_PROP'])): ?>
                    <div id="<?= $itemIds['TREE_ID'] ?>">
                        <?
                        foreach ($arResult['SKU_PROPS'] as $skuProperty) {
                            if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']]))
                                continue;

                            $propertyId = $skuProperty['ID'];
                            $skuProps[] = array(
                                'ID' => $propertyId,
                                'SHOW_MODE' => $skuProperty['SHOW_MODE'],
                                'VALUES' => $skuProperty['VALUES'],
                                'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
                            );
                            ?>
                            <style>
                                .prod-status__item.prod-status__item-describe {
                                    display: none;
                                }
                            </style>
                            <div class="product-item-detail-info-container" data-entity="sku-line-block">
                                <div class="product-item-detail-info-container-title">Выберите
                                    Сертификат<?//=htmlspecialcharsEx($skuProperty['NAME'])
                                    ?>:
                                </div>
                                <div class="product-item-scu-container">
                                    <div class="product-item-scu-block">
                                        <div class="product-item-scu-list">
                                            <ul class="product-item-scu-item-list">
                                                <?
                                                foreach ($skuProperty['VALUES'] as &$value) {
                                                    $value['NAME'] = htmlspecialcharsbx($value['NAME']);
                                                    $hide = $value['SRC'] ? '' : 'hide';
                                                    if (!empty($value["ID"])):
                                                        //if ($skuProperty['SHOW_MODE'] === 'PICT'){
                                                        ?>
                                                        <li class="product-item-scu-item-color-container <?= $hide ?>"
                                                            title="<?= $value['NAME'] ?>"
                                                            data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                            data-onevalue="<?= $value['ID'] ?>">
                                                            <div class="product-item-scu-item-color-block">
                                                                <div class="product-item-scu-item-color"
                                                                     title="<?= $value['NAME'] ?>">
                                                                    <img src="<?= ($value['SRC']) ? $value['SRC'] : $value["PICT"]['SRC'] ?>"
                                                                         alt="<?= $value['NAME'] ?>">
                                                                </div>
                                                            </div>
                                                        </li>
                                                    <?
                                                    endif;
                                                    /*}
                                                    else
                                                    {
                                                        ?>
                                                        <li class="product-item-scu-item-text-container" title="<?=$value['NAME']?>"
                                                            data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
                                                            data-onevalue="<?=$value['ID']?>">
                                                            <div class="product-item-scu-item-text-block">
                                                                <div class="product-item-scu-item-text"><?=$value['NAME']?></div>
                                                            </div>
                                                        </li>
                                                        <?
                                                    }*/
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?
                        }
                        ?>
                    </div>
                <? endif; ?>

                <?
                global $USER;
                $userArGroup = CUser::GetUserGroup($USER->GetID());
                if ($arResult["PROPERTIES"]["SALON"]["VALUE"] && !in_array(10, $userArGroup)) {
                    $flagSalon = true;
                }
                ?>
                <div class="prod__price">
                    <?
                    if ($arParams['SHOW_OLD_PRICE'] === 'Y') {
                        ?>
                        <div class="prod__price-old" id="<?= $itemIds['OLD_PRICE_ID'] ?>"
                             style="display: <?= ($showDiscount ? '' : 'none') ?>;">
                            <?
                            //TODO добавляем НДС к цене 77777
                            if ($price['PRICE_TYPE_ID'] == 2) {
                                $jsParams['NDS_PRICE_OLD'] = $price['RATIO_BASE_PRICE'] / 100 * $arResult['PROPERTIES']['NDS']['VALUE'] + $price['RATIO_BASE_PRICE'];
                                echo($showDiscount ? CurrencyFormat($jsParams['NDS_PRICE_OLD'], $price['CURRENCY']) : '');
                            } else {
                                echo($showDiscount ? $price['PRINT_RATIO_BASE_PRICE'] : '');
                            }
                            ?>
                        </div>
                        <?
                    }
                    ?>
                    <div class="prod__price-current" id="<?= $itemIds['PRICE_ID'] ?>">
                        <?
                        //TODO добавляем НДС к цене 77777
                        if ($price['PRICE_TYPE_ID'] == 2) {
                            $jsParams['NDS_PRICE'] = $price['RATIO_PRICE'] / 100 * $arResult['PROPERTIES']['NDS']['VALUE'] + $price['RATIO_PRICE'];
                            echo CurrencyFormat($jsParams['NDS_PRICE'], $price['CURRENCY']);
                        } else {
                            echo $price['PRINT_RATIO_PRICE'];
                        }
                        ?>
                    </div>
                    <? /*<div id="<?=$itemIds['DISCOUNT_PRICE_ID']?>"></div>*/ ?>
                </div>
                <!-- <div class="product-item-amount">
                    <div class="product-item-amount-field-container">
                        <a class="product-item-amount-field-btn-minus" href="javascript:void(0)"></a>
                        <input class="product-item-amount-field" type="text" value="1">
                        <a class="product-item-amount-field-btn-plus" href="javascript:void(0)"></a>
                    </div>
                    <span class="prod__availability">В наличии <span>5 шт.</span></span>
                </div> -->


                <? if ($arParams['USE_PRODUCT_QUANTITY']) {
                    ?>
                    <div class="product-item-amount <?= ($flagSalon) ? "hide" : "" ?>"
                         style="<?= (!$actualItem['CAN_BUY'] ? 'display: none;' : '') ?>" data-entity="quantity-block">
                        <div class="product-item-amount-field-container">
                            <a class="product-item-amount-field-btn-minus" id="<?= $itemIds['QUANTITY_DOWN_ID'] ?>"
                               href="javascript:void(0)" rel="nofollow">
                            </a>
                            <input class="product-item-amount-field" id="<?= $itemIds['QUANTITY_ID'] ?>" type="tel"
                                   value="<?= $price['MIN_QUANTITY'] ?>">
                            <a class="product-item-amount-field-btn-plus" id="<?= $itemIds['QUANTITY_UP_ID'] ?>"
                               href="javascript:void(0)" rel="nofollow">
                            </a>
                            <noindex>
							<span id="<?= $itemIds['QUANTITY_MEASURE'] ?>" style="display: none;">
								<?= $actualItem['ITEM_MEASURE']['TITLE'] ?>
							</span>
                                <span id="<?= $itemIds['PRICE_TOTAL'] ?>" style="display: none;"></span>
                            </noindex>
                        </div>
                        <? if ($arParams['SHOW_MAX_QUANTITY'] !== 'N') {
                            if ($haveOffers) {
                                ?>
                                <span class="prod__availability" id="<?= $itemIds['QUANTITY_LIMIT'] ?>"
                                      style="display: none;">
								<?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>
								<span data-entity="quantity-limit-value"></span>
							</span>
                                <?
                            } else {
                                if (
                                    $measureRatio
                                    && (float)$actualItem['CATALOG_QUANTITY'] > 0
                                    && $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
                                    && $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
                                ) {
                                    ?>
                                    <span class="prod__availability" id="<?= $itemIds['QUANTITY_LIMIT'] ?>">
									<?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>
									<span data-entity="quantity-limit-value">
										<?
                                        if ($actualItem['CATALOG_QUANTITY'] <= 5) {
                                            if ($arParams['SHOW_MAX_QUANTITY'] === 'M') {
                                                if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR']) {
                                                    echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
                                                } else {
                                                    echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
                                                }
                                            } else {
                                                echo $actualItem['CATALOG_QUANTITY'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'];
                                            }
                                        }
                                        ?>
									</span>
								</span>
                                    <?
                                }
                            }
                        } ?>
                    </div>
                    <?
                } ?>

                <? if ($arParams['USE_PRICE_COUNT']) {
                    $showRanges = !$haveOffers && count($actualItem['ITEM_QUANTITY_RANGES']) > 1;
                    $useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';
                    ?>
                    <div class="product-item-detail-info-container" <?= $showRanges ? '' : 'style="display: none;"' ?>
                         data-entity="price-ranges-block">
                        <div class="product-item-detail-info-container-title">
                            <?= $arParams['MESS_PRICE_RANGES_TITLE'] ?>
                            <span data-entity="price-ranges-ratio-header">
							(<?= (Loc::getMessage(
                                    'CT_BCE_CATALOG_RATIO_PRICE',
                                    array('#RATIO#' => ($useRatio ? $measureRatio : '1') . ' ' . $actualItem['ITEM_MEASURE']['TITLE'])
                                )) ?>)
						</span>
                        </div>
                        <dl class="product-item-detail-properties" data-entity="price-ranges-body">
                            <?
                            if ($showRanges) {
                                foreach ($actualItem['ITEM_QUANTITY_RANGES'] as $range) {
                                    if ($range['HASH'] !== 'ZERO-INF') {
                                        $itemPrice = false;

                                        foreach ($arResult['ITEM_PRICES'] as $itemPrice) {
                                            if ($itemPrice['QUANTITY_HASH'] === $range['HASH']) {
                                                break;
                                            }
                                        }

                                        if ($itemPrice) {
                                            ?>
                                            <dt>
                                                <?
                                                echo Loc::getMessage(
                                                        'CT_BCE_CATALOG_RANGE_FROM',
                                                        array('#FROM#' => $range['SORT_FROM'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'])
                                                    ) . ' ';

                                                if (is_infinite($range['SORT_TO'])) {
                                                    echo Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
                                                } else {
                                                    echo Loc::getMessage(
                                                        'CT_BCE_CATALOG_RANGE_TO',
                                                        array('#TO#' => $range['SORT_TO'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'])
                                                    );
                                                }
                                                ?>
                                            </dt>
                                            <dd><?= ($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE']) ?></dd>
                                            <?
                                        }
                                    }
                                }
                            }
                            ?>
                        </dl>
                    </div>
                    <?
                    unset($showRanges, $useRatio, $itemPrice, $range);
                } ?>

                <div class="prod__btn-wrp " data-entity="main-button-container">
                    <div class="prod__btn-container product_id_<?= $arResult["ID"] ?> product-item-button-container"
                         id="<?= $itemIds['BASKET_ACTIONS_ID'] ?>"
                         style="display: <?= ($actualItem['CAN_BUY']) ? '' : 'none' ?>;">
                        <?
                        if ($showAddBtn) {
                            ?>
                            <a class=" btn btn-default product-item-detail-buy-button js-btn-basket"
                               id="<?= $itemIds['ADD_BASKET_LINK'] ?>"
                               href="javascript:void(0);" style="<?= ($flagSalon) ? "display: none" : "" ?>">
                                <span><?= $arParams['MESS_BTN_ADD_TO_BASKET'] ?></span>
                            </a>
                            <span class="hide btn btn_in_basket btn_ico">Товар в корзине</span>

                            <?
                        }

                        ?>

                        <? if ($flagSalon): ?>
                            <? $salon = inSalon($arResult["PROPERTIES"]["BRANDS"]["VALUE"]); ?>
                            <a class=" btn btn-default product-item-detail-buy-button"
                               data-fancybox="" data-type="iframe"
                               data-src="/ajax/salon_show.php?brand=<?= $salon["ID"] ?>"
                               href="javascript:void(0);">
                                <span>доступно для салонов</span>
                            </a>
                        <? endif; ?>
                        <a class="btn btn-default product-item-detail-buy-button"
                           id="<?= $itemIds['NOT_AVAILABLE_MESS'] ?>"
                           href="javascript:void(0)"
                           rel="nofollow" style="display: <?= (!$actualItem['CAN_BUY'] ? '' : 'none') ?>;">
                            <?= $arParams['MESS_NOT_AVAILABLE'] ?>
                        </a>
                        <a id="favorites_<?= $arResult["ID"] ?>" class="prod__favorites js-favorites-lnk"
                           data-ajax="/ajax/add_favorites.php" data-id="<?= $arResult["ID"] ?>" href="#"></a>
                        <br>
                        <a class="btn btn_border" data-fancybox="one-click" href="#one-click"
                           style="<?= ($flagSalon) ? "display: none" : "" ?>">В один клик</a>
                    </div>
                    <? if (!$arResult['CATALOG_QUANTITY'] and !$arResult['OFFERS'] and $arResult['IS_BUY_USER']) { ?>
                        <? $APPLICATION->IncludeComponent("bitrix:catalog.product.subscribe", "bh", Array(
                            "BUTTON_CLASS" => "btn btn-default product-item-detail-buy-button",
                            "BUTTON_ID" => "product-item-detail-subscribe",
                            "CACHE_TIME" => "3600",
                            "CACHE_TYPE" => "A",
                            "PRODUCT_ID" => $arResult["ID"],
                        ),
                            false
                        ); ?>
                    <? }
                    if (!$arResult['IS_BUY_USER']) {
                        ?>
                        <div class="prod__btn-wrp " data-entity="main-button-container">
                            <span class="btn btn-default product-item-detail-buy-button bx-catalog-subscribe-button"
                                  style=""><span>Недоступен</span></span>
                        </div>
                        <?
                    } ?>
                    <? if (!$actualItem['CAN_BUY'] && $flagSalon && $salon = inSalon($arResult["PROPERTIES"]["BRANDS"]["VALUE"])): ?>
                        <a class=" btn btn-default product-item-detail-buy-button"
                           data-fancybox="" data-type="iframe" data-src="/ajax/salon_show.php?brand=<?= $salon["ID"] ?>"
                           href="javascript:void(0);">
                            <span>доступно для салонов</span>
                        </a>
                    <? endif ?>

                </div>

                <div id="one-click">
                    <form class="one-click-form" data-ajax="/ajax/one_click.php">
                        <div class="popup__ttl">Купить в один клик</div>
                        <input type="hidden" name="PROP[PRODUCT_ID]" value="<?= $arResult["ID"] ?>">
                        <input type="hidden" name="PROP[PRODUCT_NAME]" value="<?= $arResult["NAME"] ?>">
                        <input type="hidden" name="PROP[PRODUCT_LINK]"
                               value="<?= $_SERVER['SERVER_NAME'] . $arResult["DETAIL_PAGE_URL"] ?>">
                        <? global $USER; ?>
                        <div class="one-click-form__inner">
                            <label>Представьтесь*</label>
                            <div class="field-wrp">
                                <input class="field chk" type="text" placeholder="Ваше имя" name="NAME"
                                       value="<?= $USER->GetFullName() ?>" autocomplete="off">
                            </div>
                            <label>Ваш номер телефона с кодом оператора*</label>
                            <div class="field-wrp">
                                <input class="field chk" type="tel" placeholder="Телефон" name="PROP[PHONE]"
                                       autocomplete="off">
                            </div>
                            <?
                            switch ($arParams['SITE_ID']) {
                                case 's1':
                                    $minOrderPrice = 50;
                                    break;
                                default:
                                    $minOrderPrice = 30;
                                    break;
                            }
                            ?>
                            <div class="one-click_text-form">Минимальная сумма заказа - <?= $minOrderPrice ?> руб.</div>
                            <input class="btn btn_black" type="submit" value="Купить ">
                        </div>
                        <div class="one-click-form__result hide">
                            Ваша заявка принята<br>
                            Менеджер свяжется с Вами для подтверждения заказа.<br><br><br><br>
                            Наше рабочее время<br>
                            ПН - ПТ 10:30 - 17:30
                        </div>
                    </form>
                    </form>
                </div>
                <?
                /*
                <div class="prod__btn-wrp" data-entity="main-button-container">
                    <div class="prod__btn-container">
                        <div id="<?=$itemIds['BASKET_ACTIONS_ID']?>" style="display: ">
                            <a class="btn btn-default product-item-detail-buy-button" id="<?=$itemIds['ADD_BASKET_LINK']?>" href="javascript:void(0);">
                                <span><?=$arParams['MESS_BTN_ADD_TO_BASKET']?></span>
                            </a>
                            <a class="prod__favorites" href="#"></a>
                        </div>
                    </div>
                    <div class="prod__btn-container" style="display: ">
                        <a class="btn btn-default product-item-detail-buy-button" id="<?=$itemIds['NOT_AVAILABLE_MESS']?>"
                            href="javascript:void(0)"
                            rel="nofollow" >
                            <span><?=$arParams['MESS_NOT_AVAILABLE']?></span>
                        </a>
                    </div>
                </div>*/ ?>

                <div class="payments"><img src="/local/templates/.default/images/payments.png" alt=""></div>

                <div class="parent_link">Вернуться в категорию "<a
                            href="<?= $arResult["SECTION"]["SECTION_PAGE_URL"] ?>"><?= $arResult['SECTION']['NAME'] ?></a>".
                </div>

                <? if ($salon = inSalon($arResult["PROPERTIES"]["BRANDS"]["VALUE"]) && !$flagSalon): ?>
                    <div class="salon-btn-wrp">
                        <a class=""
                           data-fancybox="" data-type="iframe" data-src="/ajax/salon_show.php?brand=<?= $salon["ID"] ?>"
                           href="javascript:void(0);">
                            <span>Доступен в салонах</span>
                        </a>
                    </div>
                <? endif; ?>
            </div>
        </div>


        <? //RECOMMEND
        if (!empty($arResult["PROPERTIES"]["SERIES"]["VALUE"]) || !empty($arResult["PROPERTIES"]["RECOMMEND"]["VALUE"])):
            global $arrFilter2;
            if ($arResult["PROPERTIES"]["RECOMMEND"]["VALUE"]) {
                $arrFilter2 = array("ID" => $arResult["PROPERTIES"]["RECOMMEND"]["VALUE"]);
                $title = "Сопутствующие товары";
            } elseif ($arResult["PROPERTIES"]["SERIES"]["VALUE"]) {
                $arrFilter2 = array("PROPERTY_SERIES" => $arResult["PROPERTIES"]["SERIES"]["VALUE"], "!ID" => $arResult["ID"]);
                $title = "Из этой же серии";
            }
            ?>
            <? $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "series",
            array(
                "TITLE" => $title,
                "ACTION_VARIABLE" => "action",
                "ADD_PICT_PROP" => "-",
                "ADD_PROPERTIES_TO_BASKET" => "Y",
                "ADD_SECTIONS_CHAIN" => "N",
                "ADD_TO_BASKET_ACTION" => "ADD",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "BACKGROUND_IMAGE" => "-",
                "BASKET_URL" => "/personal/basket.php",
                "BROWSER_TITLE" => "-",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "CACHE_TIME" => "36000000",
                "CACHE_TYPE" => "A",
                "COMPATIBLE_MODE" => "N",
                "COMPONENT_TEMPLATE" => "series",
                "CONVERT_CURRENCY" => "Y",
                "CURRENCY_ID" => "BYN",
                "DETAIL_URL" => "",
                "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                "DISCOUNT_PERCENT_POSITION" => "bottom-right",
                "DISPLAY_BOTTOM_PAGER" => "N",
                "DISPLAY_COMPARE" => "N",
                "DISPLAY_TOP_PAGER" => "N",
                "ELEMENT_SORT_FIELD" => "sort",
                "ELEMENT_SORT_FIELD2" => "id",
                "ELEMENT_SORT_ORDER" => "asc",
                "ELEMENT_SORT_ORDER2" => "desc",
                "ENLARGE_PRODUCT" => "PROP",
                "ENLARGE_PROP" => "-",
                "FILTER_NAME" => "arrFilter2",
                "HIDE_NOT_AVAILABLE" => "Y",
                "HIDE_NOT_AVAILABLE_OFFERS" => "Y",
                "IBLOCK_ID" => "2",
                "IBLOCK_TYPE" => "catalog",
                "INCLUDE_SUBSECTIONS" => "Y",
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
                "OFFERS_CART_PROPERTIES" => array(),
                "OFFERS_FIELD_CODE" => array(
                    0 => "",
                    1 => "",
                ),
                "OFFERS_LIMIT" => "5",
                "OFFERS_PROPERTY_CODE" => array(
                    0 => "",
                    1 => "COLOR_REF_2",
                    2 => "COLOR_REF",
                    3 => "",
                ),
                "OFFERS_SORT_FIELD" => "sort",
                "OFFERS_SORT_FIELD2" => "id",
                "OFFERS_SORT_ORDER" => "asc",
                "OFFERS_SORT_ORDER2" => "desc",
                "OFFER_ADD_PICT_PROP" => "-",
                "OFFER_TREE_PROPS" => array(),
                "PAGER_BASE_LINK_ENABLE" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_TEMPLATE" => ".default",
                "PAGER_TITLE" => "Товары",
                "PAGE_ELEMENT_COUNT" => "13",
                "PARTIAL_PRODUCT_PROPERTIES" => "N",
                "PRICE_CODE" => array(
                    0 => "SELLWIN",
                    1 => "Trade Price",
                    2 => "RTL",
                    3 => "b2b Activ",
                    4 => "b2b pro loreal pro/matrix",
                    5 => "b2b Kerastase",
                    6 => "b2b Redken",
                ),
                "PRICE_VAT_INCLUDE" => "Y",
                "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
                "PRODUCT_DISPLAY_MODE" => "Y",
                "PRODUCT_ID_VARIABLE" => "id",
                "PRODUCT_PROPERTIES" => array(),
                "PRODUCT_PROPS_VARIABLE" => "prop",
                "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false},{'VARIANT':'0','BIG_DATA':false}]",
                "PRODUCT_SUBSCRIPTION" => "Y",
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
                "RCM_TYPE" => "similar_view",
                "SECTION_CODE" => "",
                "SECTION_ID" => "",
                "SECTION_ID_VARIABLE" => "SECTION_ID",
                "SECTION_URL" => "",
                "SECTION_USER_FIELDS" => array(
                    0 => "",
                    1 => "",
                ),
                "SEF_MODE" => "N",
                "SET_BROWSER_TITLE" => "N",
                "SET_LAST_MODIFIED" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_STATUS_404" => "N",
                "SET_TITLE" => "N",
                "SHOW_404" => "N",
                "SHOW_ALL_WO_SECTION" => "Y",
                "SHOW_CLOSE_POPUP" => "N",
                "SHOW_DISCOUNT_PERCENT" => "Y",
                "SHOW_FROM_SECTION" => "N",
                "SHOW_MAX_QUANTITY" => "N",
                "SHOW_OLD_PRICE" => "Y",
                "SHOW_PRICE_COUNT" => "1",
                "SHOW_SLIDER" => "N",
                "TEMPLATE_THEME" => "blue",
                "USE_ENHANCED_ECOMMERCE" => "N",
                "USE_MAIN_ELEMENT_SECTION" => "N",
                "USE_PRICE_COUNT" => "N",
                "USE_PRODUCT_QUANTITY" => "N",
                "CUSTOM_FILTER" => "",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO"
            ),
            false
        ); ?>
        <? endif ?>
    </div>

    <div class="prod-info__description">
        <div class="container">
            <div class="js-tabs">
                <a class="js-tabs__lnk js-tabs__lnk_active" href="#description">Описание</a>
                <a class="js-tabs__lnk" href="#characteristics">Характеристики</a>
                <a class="js-tabs__lnk" href="#review">Отзывы</a>
            </div>
            <div class="tab show" id="description">
                <div class="description-txt">
                    <?
                    if ($arResult['PREVIEW_TEXT'] != '' && ($arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'S' || ($arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'E' && $arResult['DETAIL_TEXT'] == ''))) echo $arResult['PREVIEW_TEXT_TYPE'] === 'html' ? $arResult['PREVIEW_TEXT'] : '<p>' . $arResult['PREVIEW_TEXT'] . '</p>';
                    if ($arResult['DETAIL_TEXT'] != '') echo $arResult['DETAIL_TEXT_TYPE'] === 'html' ? $arResult['DETAIL_TEXT'] : '<p>' . $arResult['DETAIL_TEXT'] . '</p>';
                    ?>
                    <? if (!empty($arResult['PROPERTIES']['VIDEO_YOUTUBE']['VALUE'])): ?>
                        <div class="video-block-section">
                            <div class="video-block">
                                <iframe width="560" height="315"
                                        src="https://www.youtube.com/embed/<?= $arResult['PROPERTIES']['VIDEO_YOUTUBE']['VALUE'] ?>"
                                        frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                            </div>
                        </div>
                    <? endif; ?>
                </div>
            </div>
            <div class="tab" id="characteristics">
                <div class="char">
                    <? foreach ($arResult['DISPLAY_PROPERTIES'] as $property): ?>
                        <div class="char__item"><?= $property['NAME'] ?>:</div>
                        <div class="char__item">
                            <?= (is_array($property['DISPLAY_VALUE'])
                                ? implode(' / ', $property['DISPLAY_VALUE'])
                                : $property['DISPLAY_VALUE']
                            ) ?>
                        </div>
                        <? unset($property); ?>
                    <? endforeach ?>
                </div>
            </div>
            <div class="tab" id="review">
                <? global $arrFilter3;
                $arrFilter3 = array("PROPERTY_ID_PRODUCT" => $arResult["ID"]); ?>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    "reviews_product",
                    Array(
                        "ACTIVE_DATE_FORMAT" => "d.m.Y",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "AJAX_MODE" => "Y",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "CACHE_TIME" => "3600",
                        "CACHE_TYPE" => "A",
                        "CHECK_DATES" => "Y",
                        "COMPONENT_TEMPLATE" => "reviews_product",
                        "DETAIL_URL" => "",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        "DISPLAY_DATE" => "Y",
                        "DISPLAY_NAME" => "Y",
                        "DISPLAY_PICTURE" => "N",
                        "DISPLAY_PREVIEW_TEXT" => "Y",
                        "DISPLAY_TOP_PAGER" => "N",
                        "FIELD_CODE" => array(0 => "", 1 => "",),
                        "FILTER_NAME" => "arrFilter3",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                        "IBLOCK_ID" => "8",
                        "IBLOCK_TYPE" => "catalog",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "INCLUDE_SUBSECTIONS" => "N",
                        "MESSAGE_404" => "",
                        "NEWS_COUNT" => "2",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_TEMPLATE" => "main",
                        "PAGER_TITLE" => "Новости",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "PROPERTY_CODE" => array(0 => "RATING", 1 => "",),
                        "SET_BROWSER_TITLE" => "N",
                        "SET_LAST_MODIFIED" => "N",
                        "SET_META_DESCRIPTION" => "N",
                        "SET_META_KEYWORDS" => "N",
                        "SET_STATUS_404" => "N",
                        "SET_TITLE" => "N",
                        "SHOW_404" => "N",
                        "SORT_BY1" => "ACTIVE_FROM",
                        "SORT_BY2" => "SORT",
                        "SORT_ORDER1" => "DESC",
                        "SORT_ORDER2" => "ASC",
                        "STRICT_SECTION_CHECK" => "N"
                    )
                ); ?>
                <div class="review-form-ttl">Оставьте свой отзыв</div>
                <form class="review-form" data-ajax="/ajax/add_reviews.php">
                    <input type="hidden" name="id_product" value="<?= $arResult["ID"] ?>">
                    <div class="field-wrp"><input class="field chk" type="text" name="name" placeholder="Имя"></div>
                    <div class="field-wrp"><input class="field chk" type="email" name="email" placeholder="E-mail">
                    </div>
                    <div class="review-stars-wrp">
                        <span>Оценка: </span>
                        <div class="review-stars">
                            <select id="main-rating" name="rating" style="display: none;">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option selected="" value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="textarea-wrp">
                        <textarea class="textarea chk" name="reviews" placeholder="Текст отзыва"></textarea>
                    </div>
                    <input class="btn btn_black" type="submit" value="Оставить отзыв">
                </form>
                <script>
                    $(function () {
                        $('#main-rating').barrating({
                            theme: 'fontawesome-stars-o'
                        });
                        var currentRating = $('#main-rating-2').data('current-rating');
                        $('#main-rating-2').barrating({
                            theme: 'fontawesome-stars-o',
                            initialRating: currentRating,
                            hoverState: false,
                            readonly: true
                        });
                    });
                </script>
            </div>
        </div>
    </div>


<? /*
$APPLICATION->IncludeComponent(
	'bitrix:catalog.set.constructor',
	'.default',
	array(
		'IBLOCK_ID' => $arResult['OFFERS_IBLOCK'],
		'ELEMENT_ID' => $offerId,
		'PRICE_CODE' => $arParams['PRICE_CODE'],
		'BASKET_URL' => $arParams['BASKET_URL'],
		'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
		'CACHE_TYPE' => $arParams['CACHE_TYPE'],
		'CACHE_TIME' => $arParams['CACHE_TIME'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
		'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME'],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID']
	),
	$component,
	array('HIDE_ICONS' => 'Y')
);*/
?>


    <div class="row">
        <div class="col-xs-12">
            <?
            if ($arResult['CATALOG'] && $actualItem['CAN_BUY'] && \Bitrix\Main\ModuleManager::isModuleInstalled('sale')) {
                $APPLICATION->IncludeComponent(
                    'bitrix:sale.prediction.product.detail',
                    '.default',
                    array(
                        'BUTTON_ID' => $showBuyBtn ? $itemIds['BUY_LINK'] : $itemIds['ADD_BASKET_LINK'],
                        'POTENTIAL_PRODUCT_TO_BUY' => array(
                            'ID' => isset($arResult['ID']) ? $arResult['ID'] : null,
                            'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
                            'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
                            'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
                            'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,

                            'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][0]['ID']) ? $arResult['OFFERS'][0]['ID'] : null,
                            'SECTION' => array(
                                'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
                                'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
                                'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
                                'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
                            ),
                        )
                    ),
                    $component,
                    array('HIDE_ICONS' => 'Y')
                );
            }
            ?>
        </div>
    </div>


<?
if ($haveOffers) {
    foreach ($arResult['JS_OFFERS'] as $offer) {
        $currentOffersList = array();

        if (!empty($offer['TREE']) && is_array($offer['TREE'])) {
            foreach ($offer['TREE'] as $propName => $skuId) {
                $propId = (int)substr($propName, 5);

                foreach ($skuProps as $prop) {
                    if ($prop['ID'] == $propId) {
                        foreach ($prop['VALUES'] as $propId => $propValue) {
                            if ($propId == $skuId) {
                                $currentOffersList[] = $propValue['NAME'];
                                break;
                            }
                        }
                    }
                }
            }
        }

        $offerPrice = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']];
        ?>
        <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
				<meta itemprop="sku" content="<?= htmlspecialcharsbx(implode('/', $currentOffersList)) ?>"/>
				<meta itemprop="price" content="<?= $offerPrice['RATIO_PRICE'] ?>"/>
				<meta itemprop="priceCurrency" content="<?= $offerPrice['CURRENCY'] ?>"/>
				<link itemprop="availability"
                      href="http://schema.org/<?= ($offer['CAN_BUY'] ? 'InStock' : 'OutOfStock') ?>"/>
			</span>
        <?
    }

    unset($offerPrice, $currentOffersList);
} else {
    ?>
    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
			<meta itemprop="price" content="<?= $price['RATIO_PRICE'] ?>"/>
			<meta itemprop="priceCurrency" content="<?= $price['CURRENCY'] ?>"/>
			<link itemprop="availability"
                  href="http://schema.org/<?= ($actualItem['CAN_BUY'] ? 'InStock' : 'OutOfStock') ?>"/>
		</span>
    <?
}
?>
    </div>
<?
if ($haveOffers) {
    $offerIds = array();
    $offerCodes = array();

    $useRatio = $arParams['USE_RATIO_IN_RANGES'] === 'Y';

    foreach ($arResult['JS_OFFERS'] as $ind => &$jsOffer) {
        $offerIds[] = (int)$jsOffer['ID'];
        $offerCodes[] = $jsOffer['CODE'];

        $fullOffer = $arResult['OFFERS'][$ind];
        $measureName = $fullOffer['ITEM_MEASURE']['TITLE'];

        $strAllProps = '';
        $strMainProps = '';
        $strPriceRangesRatio = '';
        $strPriceRanges = '';

        if ($arResult['SHOW_OFFERS_PROPS']) {
            if (!empty($jsOffer['DISPLAY_PROPERTIES'])) {
                foreach ($jsOffer['DISPLAY_PROPERTIES'] as $property) {
                    $current = '<div class="prod__short-item">' . $property['NAME'] . ': <span>' . (
                        is_array($property['VALUE'])
                            ? implode(' / ', $property['VALUE'])
                            : $property['VALUE']
                        ) . '</span></div>';
                    $strAllProps .= $current;

                    if (isset($arParams['MAIN_BLOCK_OFFERS_PROPERTY_CODE'][$property['CODE']])) {
                        $strMainProps .= $current;
                    }
                }

                unset($current);
            }
        }

        if ($arParams['USE_PRICE_COUNT'] && count($jsOffer['ITEM_QUANTITY_RANGES']) > 1) {
            $strPriceRangesRatio = '(' . Loc::getMessage(
                    'CT_BCE_CATALOG_RATIO_PRICE',
                    array('#RATIO#' => ($useRatio
                            ? $fullOffer['ITEM_MEASURE_RATIOS'][$fullOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']
                            : '1'
                        ) . ' ' . $measureName)
                ) . ')';

            foreach ($jsOffer['ITEM_QUANTITY_RANGES'] as $range) {
                if ($range['HASH'] !== 'ZERO-INF') {
                    $itemPrice = false;

                    foreach ($jsOffer['ITEM_PRICES'] as $itemPrice) {
                        if ($itemPrice['QUANTITY_HASH'] === $range['HASH']) {
                            break;
                        }
                    }

                    if ($itemPrice) {
                        $strPriceRanges .= '<dt>' . Loc::getMessage(
                                'CT_BCE_CATALOG_RANGE_FROM',
                                array('#FROM#' => $range['SORT_FROM'] . ' ' . $measureName)
                            ) . ' ';

                        if (is_infinite($range['SORT_TO'])) {
                            $strPriceRanges .= Loc::getMessage('CT_BCE_CATALOG_RANGE_MORE');
                        } else {
                            $strPriceRanges .= Loc::getMessage(
                                'CT_BCE_CATALOG_RANGE_TO',
                                array('#TO#' => $range['SORT_TO'] . ' ' . $measureName)
                            );
                        }

                        $strPriceRanges .= '</dt><dd>' . ($useRatio ? $itemPrice['PRINT_RATIO_PRICE'] : $itemPrice['PRINT_PRICE']) . '</dd>';
                    }
                }
            }

            unset($range, $itemPrice);
        }

        $jsOffer['DISPLAY_PROPERTIES'] = $strAllProps;
        $jsOffer['DISPLAY_PROPERTIES_MAIN_BLOCK'] = $strMainProps;
        $jsOffer['PRICE_RANGES_RATIO_HTML'] = $strPriceRangesRatio;
        $jsOffer['PRICE_RANGES_HTML'] = $strPriceRanges;
    }

    $templateData['OFFER_IDS'] = $offerIds;
    $templateData['OFFER_CODES'] = $offerCodes;
    unset($jsOffer, $strAllProps, $strMainProps, $strPriceRanges, $strPriceRangesRatio, $useRatio);

    $jsParams = array(
        'CONFIG' => array(
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => true,
            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
            'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
            'OFFER_GROUP' => $arResult['OFFER_GROUP'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
            'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
            'USE_STICKERS' => true,
            'USE_SUBSCRIBE' => $showSubscribe,
            'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
            'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
            'ALT' => $alt,
            'TITLE' => $title,
            'MAGNIFIER_ZOOM_PERCENT' => 200,
            'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                : null
        ),
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'VISUAL' => $itemIds,
        'DEFAULT_PICTURE' => array(
            'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
            'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
        ),
        'PRODUCT' => array(
            'ID' => $arResult['ID'],
            'ACTIVE' => $arResult['ACTIVE'],
            'NAME' => $arResult['~NAME'],
            'CATEGORY' => $arResult['CATEGORY_PATH']
        ),
        'BASKET' => array(
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'BASKET_URL' => $arParams['BASKET_URL'],
            'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
        ),
        'OFFERS' => $arResult['JS_OFFERS'],
        'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
        'TREE_PROPS' => $skuProps
    );
} else {
    $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
    if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !$emptyProductProperties) {
        ?>
        <div id="<?= $itemIds['BASKET_PROP_DIV'] ?>" style="display: none;">
            <?
            if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])) {
                foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo) {
                    ?>
                    <input type="hidden" name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
                           value="<?= htmlspecialcharsbx($propInfo['ID']) ?>">
                    <?
                    unset($arResult['PRODUCT_PROPERTIES'][$propId]);
                }
            }

            $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
            if (!$emptyProductProperties) {
                ?>
                <table>
                    <?
                    foreach ($arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo) {
                        ?>
                        <tr>
                            <td><?= $arResult['PROPERTIES'][$propId]['NAME'] ?></td>
                            <td>
                                <?
                                if (
                                    $arResult['PROPERTIES'][$propId]['PROPERTY_TYPE'] === 'L'
                                    && $arResult['PROPERTIES'][$propId]['LIST_TYPE'] === 'C'
                                ) {
                                    foreach ($propInfo['VALUES'] as $valueId => $value) {
                                        ?>
                                        <label>
                                            <input type="radio"
                                                   name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]"
                                                   value="<?= $valueId ?>" <?= ($valueId == $propInfo['SELECTED'] ? '"checked"' : '') ?>>
                                            <?= $value ?>
                                        </label>
                                        <br>
                                        <?
                                    }
                                } else {
                                    ?>
                                    <select name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propId ?>]">
                                        <?
                                        foreach ($propInfo['VALUES'] as $valueId => $value) {
                                            ?>
                                            <option value="<?= $valueId ?>" <?= ($valueId == $propInfo['SELECTED'] ? '"selected"' : '') ?>>
                                                <?= $value ?>
                                            </option>
                                            <?
                                        }
                                        ?>
                                    </select>
                                    <?
                                }
                                ?>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                </table>
                <?
            }
            ?>
        </div>

        <?
    }

    $jsParams = array(
        'CONFIG' => array(
            'USE_CATALOG' => $arResult['CATALOG'],
            'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
            'SHOW_PRICE' => !empty($arResult['ITEM_PRICES']),
            'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
            'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
            'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
            'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
            'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
            'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
            'USE_STICKERS' => true,
            'USE_SUBSCRIBE' => $showSubscribe,
            'ALT' => $alt,
            'TITLE' => $title,
            'MAGNIFIER_ZOOM_PERCENT' => 200,
            'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
            'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
            'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
                ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
                : null
        ),
        'VISUAL' => $itemIds,
        'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
        'PRODUCT' => array(
            'ID' => $arResult['ID'],
            'ACTIVE' => $arResult['ACTIVE'],
            'PICT' => reset($arResult['MORE_PHOTO']),
            'NAME' => $arResult['~NAME'],
            'SUBSCRIPTION' => true,
            'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
            'ITEM_PRICES' => $arResult['ITEM_PRICES'],
            'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
            'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
            'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
            'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
            'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
            'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
            'SLIDER' => $arResult['MORE_PHOTO'],
            'CAN_BUY' => $arResult['CAN_BUY'],
            'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
            'QUANTITY_FLOAT' => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
            'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
            'STEP_QUANTITY' => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
            'CATEGORY' => $arResult['CATEGORY_PATH']
        ),
        'BASKET' => array(
            'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
            'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
            'EMPTY_PROPS' => $emptyProductProperties,
            'BASKET_URL' => $arParams['BASKET_URL'],
            'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
            'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
        )
    );
    unset($emptyProductProperties);
}

if ($arParams['DISPLAY_COMPARE']) {
    $jsParams['COMPARE'] = array(
        'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
        'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
        'COMPARE_PATH' => $arParams['COMPARE_PATH']
    );
}
$jsParams['NDS'] = $arResult['PROPERTIES']['NDS']['VALUE'];

?>
    <script>
        BX.message({
            ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
            TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
            TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
            BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
            BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
            BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
            BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
            BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
            TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
            COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
            COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
            COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
            BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
            PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
            PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
            RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
            RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
            SITE_ID: '<?=SITE_ID?>'
        });

        var <?=$obName?> =
        new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
    </script>
<?
unset($actualItem, $itemIds, $jsParams);