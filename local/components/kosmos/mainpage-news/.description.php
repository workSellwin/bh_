<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = array(
  "NAME" => Loc::getMessage("T_MAINPAGE_NEWS_DESC_NAME"),
  "DESCRIPTION" => "",
  "PATH" => array(
    "ID" => "kosmos",
    "NAME" => Loc::getMessage("T_KOSMOS_DESC_GROUP_NAME")
  ),
  "CACHE_PATH" => "N",
  "COMPLEX" => "N"
);