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
 */

$this->setFrameMode(true);
//$this->addExternalCss('/bitrix/css/main/bootstrap.css');

if (!empty($arResult['NAV_RESULT']))
{
	$navParams =  array(
		'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
		'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
		'NavNum' => $arResult['NAV_RESULT']->NavNum
	);
}
else
{
	$navParams = array(
		'NavPageCount' => 1,
		'NavPageNomer' => 1,
		'NavNum' => $this->randString()
	);
}

$showTopPager = false;
$showBottomPager = false;
$showLazyLoad = false;

if ($arParams['PAGE_ELEMENT_COUNT'] > 0 && $navParams['NavPageCount'] > 1)
{
	$showTopPager = $arParams['DISPLAY_TOP_PAGER'];
	$showBottomPager = $arParams['DISPLAY_BOTTOM_PAGER'];
	$showLazyLoad = $arParams['LAZY_LOAD'] === 'Y' && $navParams['NavPageNomer'] != $navParams['NavPageCount'];
}


$elementEdit = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_EDIT');
$elementDelete = CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'ELEMENT_DELETE');
$elementDeleteParams = array('CONFIRM' => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));


$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($navParams['NavNum']));
$containerName = 'container-'.$navParams['NavNum'];
?>

<?if(!empty($arResult["PICTURE"]["SRC"])):?>
	<?$this->SetViewTarget('section_banner');?>
		<div class="catalog-banner"><img src="<?=$arResult["PICTURE"]["SRC"]?>" alt="<?=$arResult["PICTURE"]["ALT"]?>"></div>
	<?$this->EndViewTarget();?>
<?endif?>

	<?
	if (!empty($arResult['ITEMS']))
	{
		foreach ($arResult['ITEMS'] as $item)
		{
			$this->AddEditAction($item['ID'], $item['EDIT_LINK'], $elementEdit);
			$this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], $elementDelete, $elementDeleteParams);
			$strMainID = $this->GetEditAreaId($item['ID']);

			$imgTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
				? $item['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
				: $item['NAME'];
			$productTitle = isset($item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
				? $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
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
			<div class="product__col" id="<?=$strMainID?>">
				<div class="prod-item">
					<span class="prod-status">
						<?if ($item['LABEL']){
							if (!empty($item['LABEL_ARRAY_VALUE'])){
								foreach ($item['LABEL_ARRAY_VALUE'] as $code => $value){
									?><span class="prod-status__item" title="<?=$value?>" <?=(!isset($item['LABEL_PROP_MOBILE'][$code]) ? ' style="display:none;"' : '')?> ><?=$value?></span><?
								}
							}
						}?>
						<?if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y'):?>
							<span class="prod-status__item" style="display: <?=($price['PERCENT'] > 0 ? '' : 'none')?>;"><?=-$price['PERCENT']?>%</span>
						<?endif;?>
					</span>
					<a class="favorites-lnk" href="#"></a>
					<a class="prod-img" href="<?=$item['DETAIL_PAGE_URL']?>"><span><img src="<?=$item['PREVIEW_PICTURE']['SRC']?>" alt="<?=$imgTitle?>"></span></a>
					<a class="prod-name" href="<?=$item['DETAIL_PAGE_URL']?>" title="<?=$productTitle?>"><?=$productTitle?></a>
					<div class="prod-price">
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
		}

		/*foreach ($arResult['ITEM_ROWS'] as $rowData)
		{
			$rowItems = array_splice($arResult['ITEMS'], 0, $rowData['COUNT']);

			foreach ($rowItems as $item)
			{
				?>
					<?
					$APPLICATION->IncludeComponent(
						'bitrix:catalog.item',
						'main',
						array(
							'RESULT' => array(
								'ITEM' => $item,
								'AREA_ID' => $areaIds[$item['ID']],
								'TYPE' => $rowData['TYPE'],
								'BIG_LABEL' => 'N',
								'BIG_DISCOUNT_PERCENT' => 'N',
								'BIG_BUTTONS' => 'Y',
								'SCALABLE' => 'N'
							),
							'PARAMS' => $generalParams
								+ array('SKU_PROPS' => $arResult['SKU_PROPS'][$item['IBLOCK_ID']])
						),
						$component,
						array('HIDE_ICONS' => 'Y')
					);
					?>
				<?
			}
		}*/
		unset($generalParams, $rowItems);
	}
	else
	{
		// load css for bigData/deferred load
		// $APPLICATION->IncludeComponent(
		// 	'bitrix:catalog.item',
		// 	'',
		// 	array(),
		// 	$component,
		// 	array('HIDE_ICONS' => 'Y')
		// );
		
	}
	?>

	<?


if ($showBottomPager)
{
	?>
	<div data-pagination-num="<?=$navParams['NavNum']?>">
		<!-- pagination-container -->
		<?=$arResult['NAV_STRING']?>
		<!-- pagination-container -->
	</div>
	<?
}
?>
<?$this->SetViewTarget('seo_section_text');?>
	<?=$arResult["DESCRIPTION"]?>
<?$this->EndViewTarget();?>