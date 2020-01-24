<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("CD_BST_NAME"),
	"DESCRIPTION" => GetMessage("CD_BST_DESCRIPTION"),
	"ICON" => "/images/search_title.gif",
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "Mitlab",
		"NAME" => "МИТлаб",
		"CHILD" => array(
			"ID" => "search",
			"NAME" => "Поиск по заголовкам"
		)
	),
);

?>