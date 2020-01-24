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
// $curPage = $arResult["NAV_STRING"]->NavPageNomer;
// $totalPages = $arResult["NAV_STRING"]->NavPageCount;
// $navNum = $arResult["NAV_STRING"]->NavNum;

// if(empty($navNum)) $navNum = 1;

// if(empty($curPage) && empty($_GET["PAGEN_1"]))
// 	$curPage = 1;
// else if(!empty($_GET["PAGEN_1"]))
// 	$curPage = $_GET["PAGEN_1"];

// if(empty($totalPages)) $totalPages = ceil($arResult["NAV_STRING_COUNT"] / $arResult["SECTIONS_COUNT"]);
?>

<?//if($arParams["AJAX"] != "Y"):?>
	<div class="mg_brands">
	<div class="main__brands">
<?/*else:?>
	<?$APPLICATION->RestartBuffer();?>
<?endif;*/?>
		<?foreach ($arResult['SECTIONS'] as &$arSection):?>
			<?
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$renderImage = CFile::ResizeImageGet($arSection['PICTURE']["ID"], Array("width" => 300,"height" => 2999), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
			?>
			<div class="brands__col" id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
				<a class="brands-img" href="<?=$arSection['SECTION_PAGE_URL'];?>">
					<img src="<?=(!empty($arSection['PICTURE']['SRC']))?$renderImage["src"]:SITE_TEMPLATE_PATH."/images/no-category-image.png"?>" alt="" >
				</a>
			</div>
		<?endforeach?>
<?//if($arParams["AJAX"] != "Y"):?>
	</div>
	</div>
	<?/*if($totalPages > 1):?>
		<div class="more-items" style="text-align:center">
			<a href="#" id="load-items" class="btn btn_border"><span id="ajax-loader"></span>  Показать еще</a>
		</div>

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

<?endif;*/?>
<?//=$arResult["NAV_STRING"]?>