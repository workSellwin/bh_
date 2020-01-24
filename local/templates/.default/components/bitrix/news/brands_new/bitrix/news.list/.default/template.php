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
<div class="mg_brands">
    <div class="main__brands">
        <?foreach($arResult["ITEMS"] as $arItem):
            //PR($arItem)?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            //$renderImage = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']["ID"], Array("width" => 300,"height" => 2999), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
            ?>
            <div class="brands__col" id="<? echo $this->GetEditAreaId($arItem['ID']); ?>">
                <a class="brands-img" href="<?=$arItem['DETAIL_PAGE_URL'];?>">
                    <img src="<?=($arItem['PREVIEW_PICTURE']['SRC']) ? $arItem['PREVIEW_PICTURE']['SRC'] : SITE_TEMPLATE_PATH."/images/no-category-image.png"?>" alt="" >
                </a>
            </div>
        <?endforeach;?>
    </div>
</div>