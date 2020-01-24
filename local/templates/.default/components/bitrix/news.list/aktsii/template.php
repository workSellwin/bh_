<? use Bitrix\Main\Localization\Loc;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
<article class="news-list-wrp">
    <div class="news-list" id="ts-pager-content">
        <?foreach($arResult["ITEMS"] as $arItem):?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            $file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array('width'=>408, 'height'=>214), BX_RESIZE_IMAGE_PROPORTIONAL, true);
            ?>
            <div class="news-list__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <?if($arItem["PREVIEW_PICTURE"]):?>
                    <a class="news-list__img" href="<?=$arItem["PROPERTIES"]['LINK']['VALUE'] ? $arItem["PROPERTIES"]['LINK']['VALUE'] : $arItem['DETAIL_PAGE_URL'] ?>" title="<?=$arItem["NAME"]?>">
                        <img src="<?=$file["src"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>">
                    </a>
                <?endif?>
                <div class="news-list__info">
                    <div class="news__ttl"><?=$arItem["NAME"]?></div>
                    <?if($arItem["PREVIEW_TEXT"]):?>
                        <p><?=$arItem["PREVIEW_TEXT"]?></p>
                    <?endif?>
                    <?if($arItem["PROPERTIES"]['LINK']['VALUE']):?>
                        <a class="btn btn_border" href="<?=$arItem["PROPERTIES"]['LINK']['VALUE']?>" title="<?=$arItem["NAME"]?>"><?=Loc::getMessage("T_SERVICES_MORE")?></a>
                    <?endif;?>
                </div>
			
                <div class="a-btn-date">До <?=FormatDate("d F Y", strtotime($arItem['DATE_ACTIVE_TO']))?> года</div>

            </div>
        <?endforeach;?>
    </div>
    <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
        <?=$arResult["NAV_STRING"]?>
    <?endif;?>
</article>