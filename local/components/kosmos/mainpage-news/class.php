<?php
/**
 * Created by PhpStorm.
 * User: kosmos
 * Date: 12.12.2017
 * Time: 22:20
 */
use Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc;

class HtmlInclude extends CBitrixComponent
{
    protected function checkModules()
    {

    }

    function getResult()
    {
        $arResult = Array();

	    $cacheID = "mainpage_news_" . LANGUAGE_ID . "_" . SITE_ID;
	    $cacheDir = "/mainpage_news";
	    $cacheTtl = 360000;

	    $obCache = new \CPHPCache;
	    if($obCache->InitCache($cacheTtl, $cacheID, $cacheDir))
	    {
		    $result = $obCache->GetVars();
	    }
	    elseif($obCache->StartDataCache())
	    {
		    global $CACHE_MANAGER;
		    $CACHE_MANAGER->StartTagCache($cacheDir);

		    \Bitrix\Main\Loader::includeModule("iblock");

		    $arFilter = Array(
			    "ACTIVE" => "Y",
			    "IBLOCK_TYPE" => "news",
			    "IBLOCK_ID" => Array(19, 20, 21),
			    "!PROPERTY_MAINPAGE_SHOW" => false
		    );

		    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DETAIL_PAGE_URL", "PREVIEW_PICTURE", "PREVIEW_TEXT");

		    $arSort = Array("SORT" => "ASC", "ACTIVE_TO" => "DESC");

		    $rsElem = \CIBlockElement::GetList($arSort, $arFilter, false, Array("nTopCount" => 2), $arSelect);
		    while($obElement = $rsElem->GetNextElement())
		    {
			    $arItem = $obElement->GetFields();

			    $arButtons = \CIBlock::GetPanelButtons(
				    $arItem["IBLOCK_ID"],
				    $arItem["ID"],
				    0,
				    array("SECTION_BUTTONS"=>false, "SESSID"=>false)
			    );
			    $arItem["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
			    $arItem["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

			    $ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arItem["IBLOCK_ID"], $arItem["ID"]);
			    $arItem["IPROPERTY_VALUES"] = $ipropValues->getValues();

			    \Bitrix\Iblock\Component\Tools::getFieldImageData(
				    $arItem,
				    array('PREVIEW_PICTURE', 'DETAIL_PICTURE'),
				    \Bitrix\Iblock\Component\Tools::IPROPERTY_ENTITY_ELEMENT,
				    'IPROPERTY_VALUES'
			    );

			    if($arItem["PREVIEW_PICTURE"])
			    {
				    $arItem['PREVIEW_PICTURE']["CUT"] = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width'=>655, 'height'=>214), BX_RESIZE_IMAGE_EXACT, true);
			    }

			    $result[] = $arItem;
			    $CACHE_MANAGER->RegisterTag("iblock_id_" . $arItem["IBLOCK_ID"]);
		    }

		    $CACHE_MANAGER->EndTagCache();
		    $obCache->EndDataCache($result);
	    }
	    else
	    {
		    $result = Array();
	    }

	    $arResult["ITEMS"] = $result;
	    unset($result);

        return $arResult;
    }

    public function executeComponent()
    {
        try
        {
            $this->includeComponentLang("class.php");

            $this->checkModules();

            $this->arResult = $this->getResult();

            $this->includeComponentTemplate();
        }
        catch (SystemException $exception)
        {
            ShowError($exception->getMessage());
        }

    }
}