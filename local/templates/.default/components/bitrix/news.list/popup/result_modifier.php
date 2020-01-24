<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arSize = (SITE_ID == 's2') ? Array("width" => 100, "height" => 100) : Array("width" => 60, "height" => 60);

foreach($arResult["ITEMS"] as $key => $arItem)
{
	if(!empty($arItem["PROPERTIES"]["LOGO"]["VALUE"]))
	{
		$arFilter = Array("IBLOCK_TYPE" => "news", "IBLOCK_ID" => 18, "ID" => $arItem["PROPERTIES"]["LOGO"]["VALUE"], "ACTIVE" => "Y");
		$arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "PROPERTY_LINK");

		$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		while($row = $res->GetNext())
		{
			$img = ($row["PREVIEW_PICTURE"]) ? CFile::ResizeImageGet($row["PREVIEW_PICTURE"], $arSize, BX_RESIZE_IMAGE_PROPORTIONAL_ALT, false) : false;

			foreach($arResult["ITEMS"][$key]["PROPERTIES"]["LOGO"]["VALUE"] as $i => $value)
			{
				if($value == $row["ID"])
				{
					$arResult["ITEMS"][$key]["PROPERTIES"]["LOGO"]["VALUE"][$i] = Array(
						"IMAGE" => ($img) ? $img["src"] : false,
						"NAME" => $row["NAME"],
						"LINK" => $row["PROPERTY_LINK_VALUE"]
					);
				}
			}
		}
	}
}