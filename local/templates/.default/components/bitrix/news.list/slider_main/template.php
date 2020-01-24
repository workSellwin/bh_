<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

<div class="top-slider-wrp">
    <div class="loader show"></div>
    <div class="top-slider hide">
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            $img_resized = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"]['ID'], array('width' => 1903, 'height' => 644), BX_RESIZE_IMAGE_PROPORTIONAL, true);
            ?>
            <div class="top-slider__item ">
                <a href="<?= $arItem["DISPLAY_PROPERTIES"]["LINK"]["VALUE"] ?>" <? echo $arItem["PROPERTIES"]["TARGET"]["VALUE"] ? 'target="_blank"' : ''; ?>
                   id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <img src="<?= $img_resized["src"] ?>" alt="<?= $arItem["DETAIL_PICTURE"]["ALT"] ?>">
                </a>
            </div>
        <? endforeach; ?>
    </div>
</div>
