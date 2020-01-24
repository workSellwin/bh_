<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 04.05.18
 * Time: 12:38
 */

namespace Kosmos;

use \Bitrix\Main\Loader;

class Multisite {

	/*
	 * Получение id текущего сайта
	 */
	public static function getCurrentSite($propertyID)
	{
		$currentSiteID = false;
		$res = \CUserFieldEnum::GetList(Array(), Array("USER_FIELD_ID" => $propertyID));
		while($row = $res->GetNext())
		{
			if($row["VALUE"] == SITE_ID)
			{
				$currentSiteID = $row["ID"];
				break;
			}
		}

		return $currentSiteID;
	}

	/*
	 * Удаление Брендов, не отображаемых для данного сайта
	 */
	public static function filterBrandsOnSite($arResult)
	{
		Loader::includeModule("iblock");

		$currentSiteID = self::getCurrentSite(BRANDS_PROPERTY_SITE_ID);

		foreach($arResult["SECTIONS"] as $key => $arSection)
		{
			if($arSection["UF_SITE"] && !empty($arSection["UF_SITE"]) && !in_array($currentSiteID, $arSection["UF_SITE"]))
				unset($arResult["SECTIONS"][$key]);
		}

		return $arResult;
	}

	/*
	 * Показывать ли раздел Бренда на текущем сайте
	 */

	public static function showBrandSection($arResult)
	{
		Loader::includeModule("iblock");

		$currentSiteID = self::getCurrentSite(BRANDS_PROPERTY_SITE_ID);

		$arFilter = Array("IBLOCK_ID" => BRANDS_IBLOCK_ID, "CODE" => $arResult["VARIABLES"]["SECTION_CODE"], "ID" => $arResult["VARIABLES"]["SECTION_ID"]);
		$arSection = \CIBlockSection::GetList(Array(), $arFilter, false, Array("ID", "IBLOCK_ID", BRANDS_PROPERTY_SITE_CODE))->GetNext();

		$showSection = true;
		if($arSection["UF_SITE"] && !empty($arSection["UF_SITE"]) && !in_array($currentSiteID, $arSection["UF_SITE"]))
			$showSection = false;

		return $showSection;
	}

	/*
	 * Обработка 404 страницы
	 */
	function onEpilog404Handler()
	{
		$page_404 = "/404.php";

		global $APPLICATION;

		if(strpos($APPLICATION->GetCurPage(), $page_404) === false && defined("ERROR_404") && ERROR_404 == "Y"):

			$APPLICATION->RestartBuffer();
			\CHTTP::SetStatus("404 Not Found");
			include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php");
			include($_SERVER["DOCUMENT_ROOT"] . $page_404);
			include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php");
			die();

		endif;
	}

	/*
	 * Показывать ли раздел Каталога на текущем сайте
	 */

	public static function showCatalogSection($arResult)
	{
		Loader::includeModule("iblock");

		$currentSiteID = self::getCurrentSite(CATALOG_PROPERTY_SITE_ID);

		$arFilter = Array("IBLOCK_ID" => CATALOG_IBLOCK_ID, "CODE" => $arResult["VARIABLES"]["SECTION_CODE"], "ID" => $arResult["VARIABLES"]["SECTION_ID"]);
		$arSection = \CIBlockSection::GetList(Array(), $arFilter, false, Array("ID", "IBLOCK_ID", "DEPTH_LEVEL", CATALOG_PROPERTY_SITE_CODE))->GetNext();

		if($arSection["DEPTH_LEVEL"] > 1)
		{
			$res = \CIBlockSection::GetNavChain(CATALOG_IBLOCK_ID, $arSection["ID"], Array("ID", "IBLOCK_ID"))->GetNext();
			$arFilter = Array("IBLOCK_ID" => CATALOG_IBLOCK_ID, "ID" => $res["ID"]);
			$arSection = \CIBlockSection::GetList(Array(), $arFilter, false, Array("ID", "IBLOCK_ID", "DEPTH_LEVEL", CATALOG_PROPERTY_SITE_CODE))->GetNext();
		}

		$showSection = true;
		if($arSection["UF_SITE"] && !empty($arSection["UF_SITE"]) && !in_array($currentSiteID, $arSection["UF_SITE"]))
			$showSection = false;

		return $showSection;
	}

	/*
	 * Все разделы каталога текущего сайта
	 */

	public static function getCatalogSections()
	{
		$cacheID = "catalog_sections_site_" . SITE_ID;
		$cacheDir = "/catalog_sections_site";
		$cacheTtl = 3600000;

		$obCache = new \CPHPCache;
		if($obCache->InitCache($cacheTtl, $cacheID, $cacheDir))
		{
			$arSections = $obCache->GetVars();
		}
		elseif(Loader::includeModule("iblock") && $obCache->StartDataCache())
		{
			$arSections = array();

			global $CACHE_MANAGER;
			$CACHE_MANAGER->StartTagCache($cacheDir);

			$currentSiteID = self::getCurrentSite(CATALOG_PROPERTY_SITE_ID);
			$arFilter = Array("IBLOCK_ID" => CATALOG_IBLOCK_ID, CATALOG_PROPERTY_SITE_CODE => $currentSiteID, "DEPTH_LEVEL" => 1, "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y");
			$res = \CIBlockSection::GetList(Array(), $arFilter, false, Array("ID", "IBLOCK_ID", "LEFT_MARGIN", "RIGHT_MARGIN"));
			while($row = $res->GetNext())
			{
				$CACHE_MANAGER->RegisterTag("iblock_id_" . CATALOG_IBLOCK_ID);

				$arSections[] = $row["ID"];
				$rs = \CIBlockSection::GetList(
					Array('LEFT_MARGIN'=>'ASC'),
					Array(
						'IBLOCK_ID'=>CATALOG_IBLOCK_ID,
						'>LEFT_MARGIN'=>$row['LEFT_MARGIN'],
						'<RIGHT_MARGIN'=>$row['RIGHT_MARGIN'],
					),
					false,
					Array("ID", "IBLOCK_ID")
				);
				while($rw = $rs->GetNext())
					if(!(in_array($rw["ID"], $arSections)))
						$arSections[] = $rw["ID"];

			}

			$CACHE_MANAGER->EndTagCache();

			$obCache->EndDataCache($arSections);
		}
		else
		{
			$arSections = array();
		}

		return $arSections;
	}

	/*
	 * Фильтрация поиска по каталогу
	 */
	public static function filterCatalogSearch($arResult)
	{
		if(!empty($arResult["CATEGORIES"][0]["ITEMS"]))
		{
			Loader::includeModule("iblock");

			$arIDs = Array();
			foreach($arResult["CATEGORIES"][0]["ITEMS"] as $arItem)
			{
				if($arItem["ITEM_ID"] && !(in_array($arItem["ITEM_ID"], $arIDs)))
					$arIDs[] = $arItem["ITEM_ID"];
			}
			if(!empty($arIDs))
			{
				$arElementSections = Array();
				$arFilter = Array("ID" => $arIDs, "IBLOCK_ID" => CATALOG_IBLOCK_ID);
				$res = \CIBlockElement::GetList(Array(), $arFilter, false, false, Array("ID", "IBLOCK_ID"));
				while($row = $res->GetNext())
				{
					$arGroups = \CIBlockElement::GetElementGroups($row["ID"], true);
					while($rw = $arGroups->GetNext())
						$arElementSections[$row["ID"]][] = $rw["ID"];
				}

				$arSiteSections = self::getCatalogSections();

				foreach($arResult["CATEGORIES"][0]["ITEMS"] as $key => $arItem)
				{
					if($arItem["ITEM_ID"])
					{
						$delete = true;

						foreach($arElementSections[$arItem["ITEM_ID"]] as $sectionID)
						{
							if(in_array($sectionID, $arSiteSections))
							{
								$delete = false;
								break;
							}
						}

						if($delete)
							unset($arResult["CATEGORIES"][0]["ITEMS"][$key]);
					}
				}
			}
		}

		return $arResult;
	}

}