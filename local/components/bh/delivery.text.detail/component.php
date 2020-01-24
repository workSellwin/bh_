<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$ob = new DeliveryDetail($arParams['PRICE']);
$arResult = $ob->GetTextAll();
$this->IncludeComponentTemplate();