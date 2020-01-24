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
<div style="clear: both"></div>


<div class="container">
    <div class="inform-blox-index">
        <?foreach ($arResult["ITEMS"] as $val):?>
            <?
            $this->AddEditAction($val['ID'], $val['EDIT_LINK'], CIBlock::GetArrayByID($val["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($val['ID'], $val['DELETE_LINK'], CIBlock::GetArrayByID($val["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <div class="inform-item" id="<?=$this->GetEditAreaId($val['ID']);?>">
                <div class="item-img">
                    <img src="<?=CFile::GetPath($val['PROPERTIES']['FILE']['VALUE'])?>">
                </div>
                <div class="info-b">
                    <div class="info-name"><?=$val['NAME']?></div>
                    <div class="info-text"><?=$val['PREVIEW_TEXT']?></div>
                </div>
            </div>
        <?endforeach;?>
    </div>
</div>

<div style="clear: both"></div>
