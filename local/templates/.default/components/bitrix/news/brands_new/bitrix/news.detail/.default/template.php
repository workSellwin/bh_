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
<div class="brend-detal">
    <div class="brend-img"><img src="<?=$arResult['PREVIEW_PICTURE']['SRC']?>"></div>
    <?if($arResult['PREVIEW_TEXT']):?>
        <div class="brend-text"><?=$arResult['PREVIEW_TEXT']?></div>
    <?endif;?>
</div>


<?/*PR($arResult)*/?>