<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */

global $APPLICATION;?>
<?
$arFilter = Array('IBLOCK_ID'=>2,'ID'=>$arResult['ID'], 'GLOBAL_ACTIVE'=>'Y');
$db_list = CIBlockSection::GetList(Array("timestamp_x"=>"DESC"), $arFilter, false, Array("UF_H1"));
 if($uf_value = $db_list->GetNext()):
     $h1=$uf_value["UF_H1"]; //подменяем ссылку и используем её в дальнейшем
   endif;
   if($h1)
	   $GLOBALS['h1'] = $h1;
   ?>
<?//$GLOBALS['h1'] = 'test';?>
<?
if(strpos($_SERVER['REQUEST_URI'],'filter') !== false){
	$currPage = urldecode($_SERVER['REQUEST_URI']);
	if(strpos($currPage,'?') !== false)
		$currPage = stristr($currPage,'?',true);
	$arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_TITLE", "PROPERTY_LINK", "PROPERTY_DESCRIPTION", "PROPERTY_KEYWORDS");
	$arFilter = Array("IBLOCK_ID" => CATALOG_IBLOCK_ID_FITER_SEO_DATA, "ACTIVE" => "Y", "PROPERTY_LINK" => $currPage);
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 1), $arSelect);
    while($arFields = $res->GetNext())
    {
		//if($arFields['PROPERTY_LINK_VALUE'] == $currPage){
			$GLOBALS['keywords'] = $arFields['PROPERTY_KEYWORDS_VALUE'];
			$GLOBALS['description'] = $arFields['PROPERTY_DESCRIPTION_VALUE'];
			$GLOBALS['title'] = $arFields['PROPERTY_TITLE_VALUE'];
			$GLOBALS['h1'] = $arFields['NAME'];

		//}
		//$APPLICATION->SetTitle($arFields['NAME']);
	}
}
?>
<?if (isset($templateData['TEMPLATE_THEME']))
{
	$APPLICATION->SetAdditionalCSS($templateFolder.'/themes/'.$templateData['TEMPLATE_THEME'].'/style.css');
	$APPLICATION->SetAdditionalCSS('/bitrix/css/main/themes/'.$templateData['TEMPLATE_THEME'].'/style.css', true);
}

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = \Bitrix\Main\Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);

	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?
	}
}

//	lazy load and big data json answers
$request = \Bitrix\Main\Context::getCurrent()->getRequest();
if ($request->isAjaxRequest() && ($request->get('action') === 'showMore' || $request->get('action') === 'deferredLoad'))
{
	$content = ob_get_contents();
	ob_end_clean();

	list(, $itemsContainer) = explode('<!-- items-container -->', $content);
	list(, $paginationContainer) = explode('<!-- pagination-container -->', $content);

	if ($arParams['AJAX_MODE'] === 'Y')
	{
		$component->prepareLinks($paginationContainer);
	}

	$component::sendJsonAnswer(array(
		'items' => $itemsContainer,
		'pagination' => $paginationContainer
	));
}
