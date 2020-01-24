<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS"  =>  array(
		"PHONE"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PHONE"),
			"TYPE" => "STRING",
			"MULTIPLE" => "Y",
			"DEFAULT" => "",
		),
		"FAX"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("FAX"),
			"TYPE" => "STRING",
			"MULTIPLE" => "Y",
			"DEFAULT" => "",
		),
		"EMAIL"  =>  Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("EMAIL"),
			"TYPE" => "STRING",
			"MULTIPLE" => "Y",
			"DEFAULT" => "",
		),
		"ADDRESS"  =>  Array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("ADDRESS"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
	),
);
?>
