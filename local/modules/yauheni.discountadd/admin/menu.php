<?

IncludeModuleLangFile(__FILE__);
$APPLICATION->SetAdditionalCSS("/bitrix/panel/main/export_onliner_menu.css");

if ($APPLICATION->GetGroupRight("Yauheni.discountadd") != "D") {
    $aMenu = array(
        "parent_menu" => "global_menu_marketing",
        //"section" => "phpdevorg.exportonliner",
        "sort" => 500,
        "icon" => "export_onliner_menu_icon",
        "text" => GetMessage("YAUHENI_DISCOUNTADD"),
        "title" => GetMessage("YAUHENI_DISCOUNTADD"),
        "items_id" => "yauheni.discountadd",
        "items" => array()

    );
    $aMenu["items"][] = array(

        "text" => GetMessage("YAUHENI_DISCOUNT_DISCOUNTADD"),
        "title" => GetMessage("FORM_RESULTS_ALT"),
        "url" => "discountadd_start.php?lang=" . LANGUAGE_ID,
        "icon" => "export_onliner_menu_icon",
        "page_icon" => "export_onliner_menu_icon",
        "items" => array()
    );


    return $aMenu;

}
return false;
?>