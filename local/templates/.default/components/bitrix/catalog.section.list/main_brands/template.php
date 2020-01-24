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

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
//$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

$mainSite = (SITE_ID == "s1") ? true : false;
?>
<? if (0 < $arResult["SECTIONS_COUNT"]): ?>
<div class="brands"<?//= ($mainSite) ? " style='padding-bottom:0;'" : "" ?>>
        <div class="container">
			<? //if (!$mainSite): ?>
                <div class="main__ttl">Бренды</div>
			<? //endif ?>
            <div class="brands__wrp">
                <div class="loader show"></div>
                <div class="brands-carousel carousel hide">
                    <? foreach ($arResult['SECTIONS'] as &$arSection): ?>
                        <?
                        $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
                        //$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
                        ?>
                        <? if (!empty($arSection["UF_FLAG_MAIN"])): ?>
                            <div class="brands-carousel__item" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
								<a class="brandImg" href="<?= $arSection['SECTION_PAGE_URL']; ?>" title="<?= $arSection['NAME']; ?>">
                                    <img src="/local/templates/.default/images/zaglushka.png" data-defer-src="<?= (!empty($arSection['PICTURE']['SRC'])) ? $arSection['PICTURE']['SRC'] : SITE_TEMPLATE_PATH . "/images/no-category-image.png" ?>"
                                         alt="<?= $arSection['PICTURE']['TITLE']; ?>">
                                </a>
								<a href="<?= $arSection['SECTION_PAGE_URL']; ?>" class="brandName"><?=$arSection['NAME']?></a>
                            </div>
                        <? endif ?>
                    <? endforeach ?>
                </div>
            </div>
        </div>
    </div>
<? endif; ?>
