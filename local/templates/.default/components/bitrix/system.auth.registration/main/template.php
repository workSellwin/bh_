<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
ShowMessage($arParams["~AUTH_RESULT"]);
$APPLICATION->IncludeComponent(
	"bitrix:main.register",
	"main",
	Array(
		"AUTH" => "Y",
		"COMPONENT_TEMPLATE" => ".default",
		"REQUIRED_FIELDS" => array(0=>"EMAIL",),
		"SEF_MODE" => "N",
		"SET_TITLE" => "N",
		"SHOW_FIELDS" => array(0=>"EMAIL",1=>"NAME",4=>"PERSONAL_PHONE",5=>"PERSONAL_BIRTHDAY"),
		"SUCCESS_PAGE" => $APPLICATION->GetCurPageParam("",array("backurl")),
		"USER_PROPERTY" => array(),
		"USER_PROPERTY_NAME" => "",
		"USE_BACKURL" => "Y",
		"AUTH_AUTH_URL" => $arResult["AUTH_AUTH_URL"]
	)
);
?>