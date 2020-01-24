<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if ($USER->IsAdmin()){
    $APPLICATION->IncludeComponent("bitrix:menu", "catalog_list", Array(
        "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
        "CHILD_MENU_TYPE" => "left",    // Тип меню для остальных уровней
        "DELAY" => "N",    // Откладывать выполнение шаблона меню
        "MAX_LEVEL" => "1",    // Уровень вложенности меню
        "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
        "MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
        "MENU_CACHE_TYPE" => "N",    // Тип кеширования
        "MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
        "ROOT_MENU_TYPE" => "topcatalog",    // Тип меню для первого уровня
        "USE_EXT" => "N",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
        "COMPONENT_TEMPLATE" => "top_main_catalog",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO"
    ),
        false
    );
}else{

    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section.list",
        "main_catalog_list",
        array(
            "IBLOCK_TYPE" => "catalog",
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_GROUPS" => "N",
            "COUNT_ELEMENTS" => "N",
            "TOP_DEPTH" => "2",
            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
            "VIEW_MODE" => "LIST",
            "SHOW_PARENT_NAME" => "N",
            "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"])?$arParams["SECTIONS_HIDE_SECTION_NAME"]:"N"),
            "ADD_SECTIONS_CHAIN" => "N",
            "COMPONENT_TEMPLATE" => "main_catalog_list",
            "SECTION_ID" => $_REQUEST["SECTION_ID"],
            "SECTION_CODE" => "",
            "SECTION_FIELDS" => array(
                0 => "",
                1 => "",
            ),
            "SECTION_USER_FIELDS" => array(
                0 => "",
                1 => "UF_SITE",
                2 => "UF_HIDE_CATALOG",
                3 => "UF_SORT_BH",
            ),
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO"
        ),
        $component,
        array("HIDE_ICONS" => "Y")
    );

}






