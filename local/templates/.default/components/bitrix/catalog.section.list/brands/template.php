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

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
//$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
?>

<?/*if (0 < $arResult["SECTIONS_COUNT"]):?>
	<div class="main__brands product">
		<?foreach ($arResult['SECTIONS'] as &$arSection):?>
			<?
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				//$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
			?>
			<div class="product__col" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
				<div class="prod-item">
					<a class="prod-img" href="<?=$arSection['SECTION_PAGE_URL'];?>"><span><img src="<?=(!empty($arSection['PICTURE']['SRC'])?$arSection['PICTURE']['SRC']:SITE_TEMPLATE_PATH."/images/no-category-image.png");?>" alt=""></span></a>
					<a class="prod-name" href="<?=$arSection['SECTION_PAGE_URL'];?>"><?=$arSection['NAME'];?></a>
				</div>
			</div>
		<?endforeach?>
	</div>
<?endif;*/?>
<?



$countOnPage = 20;
$elements = $arResult['SECTIONS'];
$page = intval($_GET['PAGEN_1']);
//if(empty($page)) $page = 0;
$elementsPage = array_slice($elements, $page * $countOnPage, $countOnPage);
?>
<?if($arParams["AJAX"] != "Y"):?>
	<div class="mg_brands">
	<div class="main__brands">
<?else:?>
	<?$APPLICATION->RestartBuffer();?>
<?endif;?>

		<?foreach ($elementsPage as &$arSection):?>
			<?
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				//$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
				$renderImage = CFile::ResizeImageGet($arSection['PICTURE']["ID"], Array("width" => 300,"height" => 2999), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
			?>
			<div class="brands__col" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
				<a class="brands-img" href="<?=$arSection['SECTION_PAGE_URL'];?>">
                    <?if($_GET['ll'] == 'Y' && intval($arParams['INDEX']) > 6):?>
                        <img src="/local/templates/.default/images/zaglushka.png"
                             data-defer-src="<?=(!empty($arSection['PICTURE']['SRC']))?$renderImage["src"]:SITE_TEMPLATE_PATH."/images/no-category-image.png"?>"
                             alt=""
                        >
                    <?else:?>
                        <img src="<?=(!empty($arSection['PICTURE']['SRC']))?$renderImage["src"]:SITE_TEMPLATE_PATH."/images/no-category-image.png"?>" alt="" >
                    <?endif;?>

				</a>
			</div>
		<?endforeach?>
<?if($arParams["AJAX"] != "Y"):?>
	</div>
	</div>
<?=$arResult[NAV_STRING]?>
<?


$navResult = new CDBResult();
$navResult->NavPageCount = ceil(count($elements) / $countOnPage)-1;
$navResult->NavPageNomer = $page;
$navResult->NavNum = 1;
$navResult->NavPageSize = $countOnPage;
$navResult->NavRecordCount = count($elements);

$curPage = $page;
$totalPages = ceil(count($elements) / $countOnPage)-1;
$navNum = 1;

//$APPLICATION->IncludeComponent('bitrix:system.pagenavigation', 'main', array('NAV_RESULT' => $navResult,));
?>
<?endif;?>

<?if($arParams["AJAX"] != "Y"):?>
	<?if($totalPages > 1):?>
		<div class="more-items" style="text-align:center">
			<a href="#" id="load-items" class="btn btn_border"><span id="ajax-loader"></span>  Показать еще</a>
		</div>
	<?endif;?>
</div>
	<?if($totalPages > 1):?>
		<script>
			$(function(){
				var newsSetLoader = new newsLoader({
					root: '.mg_brands',
					newsBlock: '.main__brands',
					newsLoader: '#load-items',
					//ajaxLoader: '#ajax-loader img',
					loadSett:{
						endPage: <?=$totalPages?>,
						navNum: <?=$navNum?>
					}	
				});
				newsSetLoader.init();
			});
		</script>
	<?endif;?>

<?endif;?>
