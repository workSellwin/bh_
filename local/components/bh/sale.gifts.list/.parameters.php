<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"PRICE" => Array(
			"PARENT" => "BASE",
			"NAME" => "Цена",
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
        "IBLOCK_GIFTS_ID" => Array(
            "PARENT" => "BASE",
            "NAME" => "ID инфоблок подарков",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
	),
);
?>