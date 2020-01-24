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
<?if(!empty($arResult["ITEMS"])):?>
	<div class="main__category-prod">
		<div class="container">
			<div class="main__ttl">У нас покупают</div>
			<div class="category-prod-wrp">
				<div class="loader show"></div>
				<div class="main__category-prod carousel hide">
					<?foreach($arResult["ITEMS"] as $arItem):?>
						<?
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						?>
						<div class="category-prod__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<a href="<?=$arItem["DISPLAY_PROPERTIES"]["LINK"]["VALUE"]?>" class="category-prod">
								<span class="category-prod__img">
                                    <img src="/local/templates/.default/images/zaglushka.png"
                                         data-defer-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                                         alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                                    >
                                </span>
								<span class="category-prod__name"><?=$arItem["NAME"]?></span>
							</a>
						</div>
					<?endforeach;?>
				</div>
			</div>
		</div>
	</div>
<?endif?>