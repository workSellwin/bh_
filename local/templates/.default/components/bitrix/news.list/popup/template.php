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
<?if(!empty($arResult["ITEMS"])):?>
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<div id="brands-popup" class="brands-popup" style="display: none;">
			<button class="close"></button>
			<?if(!empty($arItem["PROPERTIES"]["LOGO"]["VALUE"])):?>
				<div class="brands-logo-list">
					<?foreach($arItem["PROPERTIES"]["LOGO"]["VALUE"] as $arValue):?>
						<div class="brands-logo">
							<a href="<?=$arValue["LINK"]?>" title="<?=$arValue["NAME"]?>" target="_blank"><img src="<?=$arValue["IMAGE"]?>" alt="<?=$arValue["NAME"]?>"></a>
						</div>
					<?endforeach?>
				</div>
			<?endif?>
			<?if(!empty($arItem["PROPERTIES"]["LINK"]["VALUE"])):?>
				<div class="brands-links-list">
					<?foreach($arItem["PROPERTIES"]["LINK"]["VALUE"] as $key => $value):?>
						<?$title = ($arItem["PROPERTIES"]["LINK"]["DESCRIPTION"][$key]) ? $arItem["PROPERTIES"]["LINK"]["DESCRIPTION"][$key] : $value?>
						<a class="brands__ttl-lnk" href="<?=$value?>" title="<?=$title?>" target="_blank"><?=strtoupper($title)?></a>
					<?endforeach?>
				</div>
			<?endif?>
			<?if($arItem["PROPERTIES"]["MAIN_LINK"]["VALUE"]):?>
				<?$title = ($arItem["PROPERTIES"]["MAIN_LINK"]["DESCRIPTION"]) ? $arItem["PROPERTIES"]["MAIN_LINK"]["DESCRIPTION"] : Loc::getMessage("T_POPUP_MAIN_LINK")?>
				<a class="btn btn_border" href="<?=$arItem["PROPERTIES"]["MAIN_LINK"]["VALUE"]?>" title="<?=$title?>" target="_blank"><?=$title?></a>
			<?endif?>
		</div>
	<?endforeach;?>
<?endif?>