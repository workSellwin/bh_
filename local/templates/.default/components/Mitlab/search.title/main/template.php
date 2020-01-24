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
// $this->addExternalCss("/bitrix/css/main/bootstrap.css");
// $this->addExternalCss("/bitrix/css/main/font-awesome.css");

$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

$CONTAINER_RES_ID = trim($arParams["~CONTAINER_RES_ID"]);
if(strlen($CONTAINER_RES_ID) <= 0)
	$CONTAINER_RES_ID = "res-title-search";
$CONTAINER_RES_ID = CUtil::JSEscape($CONTAINER_RES_ID);

if($arParams["SHOW_INPUT"] !== "N"):?>
<div id="<?echo $CONTAINER_ID?>-div" class="bx-searchtitle">
	<form action="<?echo $arResult["FORM_ACTION"]?>" id="<?echo $CONTAINER_ID?>">
		<div class="h__search-inner">
			<input id="<?echo $INPUT_ID?>" type="text" name="q" value="<?=htmlspecialcharsbx($_REQUEST["q"])?>" autocomplete="off" class="h__search-field" placeholder="Что Вы ищете?"/>
			<input class="h__search-btn" name="s" type="submit">
			<div id="<?echo $CONTAINER_RES_ID?>" class="title-search-result"></div>
		</div>
	</form>
</div>
<?endif?>
<script>
	BX.ready(function(){
		new JCTitleSearchGmi({
			'AJAX_PAGE' : '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
			'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
			'INPUT_ID': '<?echo $INPUT_ID?>',
			'RESULT_MAIN' : '<?echo $CONTAINER_RES_ID?>',
			'MIN_QUERY_LEN': 2
		});
	});
</script>
