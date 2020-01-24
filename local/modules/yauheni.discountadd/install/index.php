<?

IncludeModuleLangFile(__FILE__);

Class Yauheni_discountadd extends CModule
{
    var $MODULE_ID = "yauheni.discountadd";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    function Yauheni_discountadd()
    {
        $arModuleVersion = array();
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME = GetMessage("VISUAL_WORK_DISCOUNTSADD_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("VISUAL_WORK_DISCOUNTSADD_MODULE_DESCRIPTION");
        $this->PARTNER_NAME = GetMessage("VISUAL_WORK_DISCOUNTSADD_PARTNER_NAME");
        $this->PARTNER_URI = "";
    }

    function DoInstall()
    {
        global $APPLICATION;
        $this->InstallEvents();
        $this->InstallFiles();
        return true;
    }

    function DoUninstall()
    {
        global $APPLICATION;
        echo $this->GetPath();
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        return true;
    }

    public function GetPath($notDocumentRoot = false){
        if($notDocumentRoot){
            return str_replace(\Composer\Console\Application::getDocumentRoot(), '', dirname(__DIR__));
        }else{
            return dirname(__DIR__);
        }
    }

    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }


    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }

    function InstallFiles()
    {
        //CopyDirFiles($this->GetPath() . "/install/css/yauheni.discount", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/css/yauheni.discount", true);
        //CopyDirFiles($this->GetPath() . "/install/js/yauheni.discount", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/js/yauheni.discount", true);
        CopyDirFiles($this->GetPath() . "/install/admin/discountadd_start.php", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/discountadd_start.php", true);
        RegisterModule($this->MODULE_ID);
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles($this->GetPath() . "/install/admin/", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin");
        //DeleteDirFilesEx("/bitrix/css/yauheni.discount");
        //DeleteDirFilesEx("/bitrix/js/yauheni.discount");
        UnRegisterModule($this->MODULE_ID);
        return true;
    }
}?>