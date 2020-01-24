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
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
?>
<article class="news-detail news-detail-page cnt">
	<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
		<div class="detail-picture">
			<img
					class="detail_picture"
					border="0"
					src="<?=$arResult["DETAIL_PICTURE"]["CUT"]["src"]?>"
					width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
					height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
					alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
					title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
			/>
		</div>
	<?endif?>
	<div class="detail-text"><?=$arResult["DETAIL_TEXT"]?></div>
	<?if(!empty($arResult["PROPERTIES"]["LOCATION"]["VALUE"])):?>
		<a href="javascript:;" class="btn btn-default product-item-detail-buy-button btn-location" title="<?=Loc::getMessage("T_SERVICE_DETAIL_AVAIBLE")?>" data-fancybox data-type="iframe" data-src="/ajax/service-avaible-map.php?service=<?=urlencode($arResult["ID"])?>">
			<span><?=Loc::getMessage("T_SERVICE_DETAIL_AVAIBLE")?></span>
		</a>
	<?endif?>
</article>