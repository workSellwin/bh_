<?
if($arResult["DETAIL_PICTURE"])
{
	$arResult["DETAIL_PICTURE"]["CUT"] = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], Array("width" => 1280, "height" => 350), BX_RESIZE_IMAGE_EXACT, false);
}
?>