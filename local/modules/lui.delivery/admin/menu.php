<?

IncludeModuleLangFile(__FILE__);
$APPLICATION->SetAdditionalCSS("/bitrix/panel/main/export_onliner_menu.css");

if ($APPLICATION->GetGroupRight("lui.delivery") != "D") {
    $aMenu = array(
        "parent_menu" => "global_menu_store",
        "section" => "menu_order",
        "sort" => 500,
        "icon" => "export_onliner_menu_icon",
        "text" => GetMessage("LUI_DISCOUNT"),
        "title" => GetMessage("LUI_DISCOUNT"),
        "url" => "lui_delivery.php?lang=" . LANGUAGE_ID,
        "items_id" => "lui.delivery",
        "items" => array(
            array(
                "text" => 'TEST YANDEX',
                "url" => "lui_delivery_test.php?lang=".LANGUAGE_ID,
                "more_url" => array(),
                "title" => 'Test Yandex',
            ),
        )

    );

    return $aMenu;

}
return false;
