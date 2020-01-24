<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main;
$this->setFrameMode(true);
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogProductsViewedComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

if (isset($arResult['ITEM']))
{
	$item = $arResult['ITEM'];
	$areaId = $arResult['AREA_ID'];

	$obName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $areaId);
	$isBig = isset($arResult['BIG']) && $arResult['BIG'] === 'Y';

	$productTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
		? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
		: $item['NAME'];

	$imgTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
		? $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
		: $item['NAME'];

	$skuProps = array();

	$haveOffers = !empty($item['OFFERS']);
	if ($haveOffers)
	{
		$actualItem = isset($item['OFFERS'][$item['OFFERS_SELECTED']])
			? $item['OFFERS'][$item['OFFERS_SELECTED']]
			: reset($item['OFFERS']);
	}
	else
	{
		$actualItem = $item;
	}

	if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers)
	{
		$price = $item['ITEM_START_PRICE'];
		$minOffer = $item['OFFERS'][$item['ITEM_START_PRICE_SELECTED']];
		$measureRatio = $minOffer['ITEM_MEASURE_RATIOS'][$minOffer['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
		$morePhoto = $item['MORE_PHOTO'];
	}
	else
	{
		$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
		$measureRatio = $price['MIN_QUANTITY'];
		$morePhoto = $actualItem['MORE_PHOTO'];
	}

	?>

	<div class="product__col" id="<?=$areaId?>" data-entity="item">
		<div class="prod-item" >
			<span class="prod-status">
				<?if ($item['LABEL']){
					if (!empty($item['LABEL_ARRAY_VALUE'])){
                        unset($item['LABEL_ARRAY_VALUE']['SALE']);
                        unset($item['LABEL_ARRAY_VALUE']['SPECIALOFFER']);
						foreach ($item['LABEL_ARRAY_VALUE'] as $code => $value){
                            if ($code == 'NEWPRODUCT'):?>
                                <span title="<?= $value ?>"
                                      class="prod-status__item" <?= (!isset($item['LABEL_PROP_MOBILE'][$code]) ? ' style="display:none"' : '') ?> style="background-color: #5bccd8;"><?= $value ?></span>
                                <?
                            else:?>
                                <span title="<?= $value ?>"
                                  class="prod-status__item" <?= (!isset($item['LABEL_PROP_MOBILE'][$code]) ? ' style="display:none"' : '') ?>"><?= $value ?></span>

                            <?endif;
						}
					}
				}?>
				<?if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y'):?>
					<span class="prod-status__item prod-status__item-describe" style="background-color: #f42561 ; display: <?=($price['PERCENT'] > 0 ? '' : 'none')?>;"><?=-$price['PERCENT']?>%</span>
				<?endif;?>
			</span>
			<a class="favorites-lnk" href="#"></a>
			<a class="prod-img" href="<?=$item['DETAIL_PAGE_URL']?>"><span><img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="<?=$imgTitle?>"></span></a>
			<a class="prod-name" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$productTitle?>"><?=$productTitle?></a>
			<div class="prod-price" data-entity="price-block">
				<div class="prod-price__old" <?=($price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? 'style="display: none;"' : '')?>>
					<?=$price['PRINT_RATIO_BASE_PRICE']?>
				</div>
				<div class="prod-price__new">
					<?if (!empty($price)){
						if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers){
							echo Loc::getMessage(
								'CT_BCI_TPL_MESS_PRICE_SIMPLE_MODE',
								array(
									'#PRICE#' => $price['PRINT_RATIO_PRICE'],
									'#VALUE#' => $measureRatio,
									'#UNIT#' => $minOffer['ITEM_MEASURE']['TITLE']
								)
							);
						}
						else{
							echo $price['PRINT_RATIO_PRICE'];
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<?
	unset($item, $actualItem, $minOffer, $jsParams);
}