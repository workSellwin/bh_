<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach($arResult['ITEMS'] as &$arItem)
{
	if($arItem["PREVIEW_PICTURE"])
	{
		$arItem['PREVIEW_PICTURE']["CUT"] = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE'], array('width'=>600, 'height'=>214), BX_RESIZE_IMAGE_EXACT, true);
	}
}
unset($arItem);