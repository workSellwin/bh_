<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */
?>
<div class="prod-item" >
	<span class="prod-status" id="<?=$itemIds['STICKER_ID']?>">
		<?if ($item['LABEL']){
			if (!empty($item['LABEL_ARRAY_VALUE'])){
                unset($item['LABEL_ARRAY_VALUE']['SALE']);
                unset($item['LABEL_ARRAY_VALUE']['SPECIALOFFER']);
				foreach ($item['LABEL_ARRAY_VALUE'] as $code => $value){

                    if ($code == 'NEWPRODUCT'):?>
                        <span title="<?= $value ?>"
                              class="prod-status__item" <?= (!isset($item['LABEL_PROP_MOBILE'][$code]) ? ' style="display:none"' : '') ?> style="background-color: #5bccd8;"><?= $value ?></span>

                    <?elseif($code == 'SALELEADER'):?>
                        <div<?= (!isset($item['LABEL_PROP_MOBILE'][$code]) ? ' class="hide"' : '') ?>>
                            <span title="<?= $value ?>"
                                  class="prod-status__item <?= (!isset($item['LABEL_PROP_MOBILE'][$code]) ? 'hide' : '') ?>" style="background-color: #b0ea24;"><?= $value ?></span>
                        </div>

                    <?else:?>
                    <span title="<?= $value ?>"
                          class="prod-status__item" <?= (!isset($item['LABEL_PROP_MOBILE'][$code]) ? ' style="display:none"' : '') ?>"><?= $value ?></span>

                <?endif;
				}
			}
		}?>
		<?if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y'):?>
			<span id="<?=$itemIds['DSC_PERC']?>" class="prod-status__item prod-status__item-describe" style="background-color: #f42561 ;display: <?=($price['PERCENT'] > 0 ? '' : 'none')?>;"><?=-$price['PERCENT']?>%</span>
		<?endif;?>
    <? ShowLabelFavourably('catalog.item:cat_new_views', $item['PROPERTIES']) ?>
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
