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

use \Bitrix\Main\Localization\Loc;
?>
<?
if($arResult['DISPLAY_PROPERTIES']['DISCOUNT']['DISPLAY_VALUE'] && !is_array($arResult['DISPLAY_PROPERTIES']['DISCOUNT']['DISPLAY_VALUE']))
{
	$arResult['DISPLAY_PROPERTIES']['DISCOUNT']['DISPLAY_VALUE'] = [$arResult['DISPLAY_PROPERTIES']['DISCOUNT']['DISPLAY_VALUE']];
}
?>
<?if(!empty($arResult['DISPLAY_PROPERTIES']['DISCOUNT']['DISPLAY_VALUE'])):?>
	<div class="stocks-list">
		<?foreach($arResult['DISPLAY_PROPERTIES']['DISCOUNT']['DISPLAY_VALUE'] as $key => $value):?>
			<div class="stocks-list__item">
				<div class="percent__stocks">
					<?if($arResult['DISPLAY_PROPERTIES']['DISCOUNT']['DESCRIPTION'][$key]):?>
						<?=Loc::getMessage('T_DISCOUNT_INFO_TITLE')?>
						<span><?=$arResult['DISPLAY_PROPERTIES']['DISCOUNT']['DESCRIPTION'][$key]?></span>
					<?endif?>
				</div>
				<div class="name__stocks"><?=$value?></div>
			</div>
		<?endforeach;?>
	</div>
<?endif?>
<?if($arResult['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['SRC']):?>
	<a href="<?=$arResult['DISPLAY_PROPERTIES']['FILE']['FILE_VALUE']['SRC']?>" title="<?=Loc::getMessage('T_DISCOUNT_INFO_FILE')?>" target="_blank" download class="btn btn_border"><?=Loc::getMessage('T_DISCOUNT_INFO_FILE')?></a>
<?endif?>