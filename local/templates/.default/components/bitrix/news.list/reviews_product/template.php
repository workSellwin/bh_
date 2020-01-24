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
?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="review" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<div class="review__author"><?=$arItem["NAME"]?> | <?=date("d.m.Y", MakeTimeStamp($arItem["TIMESTAMP_X"]))?></div>
		<div class="review__stars">
			<?for($i=1;$i<6;$i++):?>
				<img src="<?=($arItem["PROPERTIES"]["RATING"]["VALUE"] >= $i)? SITE_TEMPLATE_PATH."/images/ico-star_active.png":SITE_TEMPLATE_PATH."/images/ico-star.png"?>" alt="">
			<?endfor?>
		</div>
		<div class="review__txt">
			<?=$arItem["PREVIEW_TEXT"];?>
		</div>
	</div>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
