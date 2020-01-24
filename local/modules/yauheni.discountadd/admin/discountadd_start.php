<?
/** @global CMain $APPLICATION */
use Bitrix\Main,
    Bitrix\Main\Application,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\SiteTable,
    Bitrix\Main\UserTable,
    Bitrix\Main\Config\Option,
    Bitrix\Sale;


if (isset($_REQUEST['work_start']))
{
    define("NO_AGENT_STATISTIC", true);
    define("NO_KEEP_STATISTIC", true);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/prolog.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/local/vendor/phpoffice/phpexcel/Classes/PHPExcel.php');

CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
CModule::IncludeModule("yauheni.discountadd");

$POST_RIGHT = $APPLICATION->GetGroupRight("main");
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm("Доступ запрещен");

$clean_test_table = '<table id="result_table" cellpadding="0" cellspacing="0" border="0" width="100%" class="internal">'.
    '<tr class="heading">'.
    '<td>Текущее действие</td>'.
    '<td width="1%">&nbsp;</td>'.
    '</tr>'.
    '</table>';

$aTabs = array(array("DIV" => "edit1", "TAB" => "Обработка"));
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$APPLICATION->SetTitle("Создание скидок из файла");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");




$PriceType = array();
$dbPriceType = CCatalogGroup::GetList(
    array("ID" => "ASC"),
    array(),
    false,
    false,
    $arSelectFields = array()
);
while ($arPriceType = $dbPriceType->Fetch())
{
    $PriceType[$arPriceType['ID']] = $arPriceType;
}

//PR($PriceType);

?>



    <form method="post" action="<?echo $APPLICATION->GetCurPage()?>" enctype="multipart/form-data" name="post_form" id="post_form">
        <?
        echo bitrix_sessid_post();

        $tabControl->Begin();
        $tabControl->BeginNextTab();
        ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <?if($_POST["sendfile"] == "Send File"){
            if(is_uploaded_file($_FILES["userfile"]["tmp_name"]))
            {
                if(!@copy($_FILES['userfile']['tmp_name'],$_SERVER["DOCUMENT_ROOT"]."/upload/".$_FILES['userfile']['name'])){
                    echo 'Что-то пошло не так';
                }
                else {
                    //echo 'Ссылка на файл: <a href="/bitrix/admin/fileman_file_view.php?path=%2Fupload%2F'.$_FILES['userfile']['name'].'&site=s1&lang=ru" target="_blank">'.$_FILES['userfile']['name'].'</a>';
                    $file_path = $_SERVER["DOCUMENT_ROOT"]."/upload/".$_FILES['userfile']['name'];
                }
            }else{
                echo("Ошибка загрузки файла");
            }

            //-------------------------------------------------------------------


            $SiteID = isset($_REQUEST['SITES_ID']) ? $_REQUEST['SITES_ID'] : 's1';
            $UserID = isset($_REQUEST['USER_ID']) ? $_REQUEST['USER_ID'] : '';
            $USER_GROUPS = isset($_REQUEST['USER_GROUPS']) ? $_REQUEST['USER_GROUPS'] : '';
            $pref = isset($_REQUEST['pref']) ? $_REQUEST['pref'] : '';
            $Discount = new Yauheni\DiscountAdd\discountaddfile();
            $priority = isset($_REQUEST['priority']) ? $_REQUEST['priority'] : 17788;
            $type_price = isset($_REQUEST['type_price']) ? $_REQUEST['type_price'] : '';
            $Discount->process($file_path, $SiteID, $UserID, $USER_GROUPS, $pref, $priority, $type_price);
            //-------------------------------------------------------------------

            echo '<p style="color: green; font-size: 20px">Скидки успешно созданны. <a href="/bitrix/admin/discount_start.php?lang=ru">Перейти на скидки</a></p>';
        }
        $arSiteID = array('s1', 's2');
        ?>
        <form enctype="multipart/form-data" action="" method="POST">
            <table>
                <tr>
                    <td>Отправить CSV файл:</td>
                    <td><input name="userfile" type="file" required/></td>
                </tr>
                <tr>
                    <td>Сайт ID:</td>
                    <td>
                        <select name="SITES_ID">
                            <? foreach ($arSiteID as $val): ?>
                                <? if (isset($_REQUEST['SITES_ID']) && !empty($_REQUEST['SITES_ID']) && $val == $_REQUEST['SITES_ID']): ?>
                                    <option selected value="<?= $val ?>"><?= $val ?></option>
                                <? else: ?>
                                    <option value="<?= $val ?>"><?= $val ?></option>
                                <? endif; ?>
                            <? endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Пользователь ID:</td>
                    <td>
                        <input name="USER_ID" type="text"/>
                    </td>
                </tr>
                <tr>
                    <td>Группа пользователя:</td>
                    <td>
                        <input name="USER_GROUPS" type="text" required />
                    </td>
                </tr>
                <tr>
                    <td>Префикс к названию скидки:</td>
                    <td>
                        <input name="pref" type="text" required/>
                    </td>
                </tr>
                <tr>
                    <td>Приоритет скидки:</td>
                    <td>
                        <input name="priority" type="text" required/>
                    </td>
                </tr>
                <tr>
                    <td>Тип цены:</td>
                    <td>
                        <select name="type_price" style="width: 180px;">
                            <option value="N">Не выбран</option>
                            <?foreach ($PriceType as $k => $v):?>
                                <option value="<?=$k?>"><?=$v['NAME']?> <?=$v['NAME_LANG'] ? '('.$v['NAME_LANG'].')' : '' ?> [<?=$v['ID']?>]</option>
                            <?endforeach;?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><button type="submit" name="sendfile" value="Send File">Создать скидки</button></td>
                </tr>
            </table>
        </form>
        <script>
            $(document).on("click",".show_calendar",function(){
                BX.calendar({node: this, value: new Date(), field: this, bTime: false});
            });
        </script>
        <?
        $tabControl->End();
        ?>
    </form>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>