<?

foreach ($arResult['ITEMS'] as $key => &$arItem)
{
	if($arItem["PREVIEW_PICTURE"])
	{
		$arItem["PREVIEW_PICTURE"]["CUT"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array("width" => 408, "height" => 214), BX_RESIZE_IMAGE_EXACT, false);
	}
}
?>