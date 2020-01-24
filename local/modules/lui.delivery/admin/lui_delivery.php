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
use Lui\Delivery\Code1cGetSellwin;
use Lui\Delivery\OrdersData;
use Lui\Delivery\Config;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/prolog.php');

CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
CModule::IncludeModule("lui.delivery");

$arResult = [];

if ($_REQUEST['export'] or $_REQUEST['view']) {

    $arResult = [];
    $arOrders = [];
    $obOrdersData = new OrdersData();
    $obConfig = new Config();
    $arResult['HEADER'] = $obConfig->GetConfig();
    if (!empty($_REQUEST['ID']) and !isset($_REQUEST['view'])) {
        $arOrdersID = $_REQUEST['ID'];
    } else {
        $arOrdersID = $obOrdersData->GetOrders($_REQUEST['FROM']);
    }

    $arOrders = $obOrdersData->GetData($arOrdersID, $arResult['HEADER']);

    if ($arOrders) {
        $arOrders = $obOrdersData->validation($arOrders);
        $arOrders = $obOrdersData->deliveryRB($arOrders);
    }


    if ($_REQUEST['IS_UNLOADED_ORDER']) {
        $arOrders = array_filter($arOrders, function ($v) {
            return !(bool)$v['ORDER']['PROPS']['UNLOADED_ORDER_L'];
        });
    }


    switch ($_REQUEST['TIME']) {
        case '1':
            $arOrders = array_filter($arOrders, function ($v) {
                $bull = false;
                $delivery = $v['ORDER']['DELIVERY_ID'];
                $timeFrom = (int)$v['ROW']['TIME_FROM'];
                $timeTo = (int)$v['ROW']['TIME_TO'];
                if (OrdersData::isRB($delivery)) {
                    $bull = true;
                } elseif (OrdersData::isMinsk($delivery)) {
                    if (($timeFrom >= 12 and $timeFrom <= 18) and ($timeTo >= 12 and $timeTo <= 18)) {
                        $bull = true;
                    }
                }
                return $bull;
            });
            break;
        case '2':
            $arOrders = array_filter($arOrders, function ($v) {
                $bull = false;
                $delivery = $v['ORDER']['DELIVERY_ID'];
                $timeFrom = (int)$v['ROW']['TIME_FROM'];
                $timeTo = (int)$v['ROW']['TIME_TO'];
                if ($v['ORDER']['PROPS']['DELIVERY_SATURDAY']=='Да') {
                    $bull = true;
                } elseif (OrdersData::isMinsk($delivery)) {
                    if (($timeFrom >= 18 and $timeFrom <= 22) and ($timeTo >= 18 and $timeTo <= 22)) {
                        $bull = true;
                    }
                }
                return $bull;
            });
            break;
        case 'enum-1':
            $arOrders = array_filter($arOrders, function ($v) {
                $bull = false;
                $timeFrom = (int)$v['ROW']['TIME_FROM'];
                $timeTo = (int)$v['ROW']['TIME_TO'];
                if (($timeFrom >= 12 and $timeFrom <= 18) and ($timeTo >= 12 and $timeTo <= 18)) {
                    $bull = true;
                }
                return $bull;
            });
            break;
        case 'enum-2':
            $arOrders = array_filter($arOrders, function ($v) {
                $bull = false;
                $timeFrom = (int)$v['ROW']['TIME_FROM'];
                $timeTo = (int)$v['ROW']['TIME_TO'];
                if (($timeFrom >= 18 and $timeFrom <= 22) and ($timeTo >= 18 and $timeTo <= 22)) {
                    $bull = true;
                }
                return $bull;
            });
            break;
    }


    $arResult['ORDERS'] = $arOrders;
}

$APPLICATION->SetTitle('Выгрузка маршрутного листа');
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); ?>
    <form action="" method="post">
        <div>
            <label for="">Дата доставки</label>
            <input type="date" required name="FROM" value="<?= $_REQUEST['FROM'] ? $_REQUEST['FROM'] : '' ?>">
            <?
            $arTime = [
                'enum-1' => '12.00-18.00',
                'enum-2' => '18.00-22.00',
                '1' => 'Минск, РБ (12-18)',
                '2' => 'Минск (18-22)',
            ]; ?>
            <label for="">Время доставки <select name="TIME">
                    <option value="">Все</option>
                    <?
                    foreach ($arTime as $k => $v) { ?>
                        <option <?= $_REQUEST['TIME'] == $k ? 'selected' : '' ?> value="<?= $k ?>"><?= $v ?></option>
                        <?
                    } ?>
                </select>
            </label>
            <hr>
        </div>
        <div>
            <? /*  <label for="is_yandex_input">Запрос в яндекс
                <input type="checkbox" id="is_yandex_input"
                       value="Y" <?= $_REQUEST['IS_YANDEX'] ? 'checked' : '' ?>
                       name="IS_YANDEX"></label><br>*/ ?>
            <label for="is_uploader_input">не выгруженные ранее
                <input type="checkbox" id="is_uploader_input"
                       value="Y" <?= $_REQUEST['IS_UNLOADED_ORDER'] ? 'checked' : '' ?>
                       name="IS_UNLOADED_ORDER"> </label><br>
            <?
            if ($USER->GetID() == '4043') { ?>
                <label for="is_bts_input">DEBUG
                    <input type="checkbox" id="is_bts_input"
                           value="Y" <?= $_REQUEST['DEBUG'] ? 'checked' : '' ?>
                           name="DEBUG"> </label><br>
                <?
            } ?>
            <hr>
        </div>
        <? /* <label for="">до </label>
        <input type="date" required name="TO" value="<?= $_REQUEST['TO'] ? $_REQUEST['TO'] : '' ?>">*/ ?>
        <input name="view" type="submit" value="Показать">
        <input name="export" type="submit" value="Выгрузить">

        <?

        if ($_REQUEST['view'] and $arResult['ORDERS']) {

            CJSCore::Init(array("jquery"));

            echo "<br>";
            echo <<<SCRIPT
<script>
$(document).ready(function(){
    $('body').on('click','#checked_all',function(){
        var c=$(this);
        if(c.prop('checked')){
            $('.all_checked').each(function(i,elem) {
              $(this).prop('checked',true);
            });
        }else{
             $('.all_checked').each(function(i,elem) {
              $(this).prop('checked',false);
            });
        }
    });
});
</script>
SCRIPT;

            echo "<table class='adm-list-table'>";
            $arHead = $arResult['HEADER'];
            echo "<thead>";
            echo "<tr class='adm-list-table-header'>";
            echo "<th class='adm-list-table-cell'><div class=\"adm-list-table-cell-inner\"><input type='checkbox'    id='checked_all' value='Y'></div></th>";
            echo "<th class='adm-list-table-cell'><div class=\"adm-list-table-cell-inner\">№</div></th>";
            foreach ($arHead as $td) {
                echo "<th class='adm-list-table-cell'><div class=\"adm-list-table-cell-inner\">{$td}</div></th>";
            }
            echo "</tr>";
            echo "<thead>";
            echo "<tbody>";
            $k = 1;
            $sum = 0;
            foreach ($arResult['ORDERS'] as $i => $tr) {
                $checked = $tr['ORDER']['PROPS']['UNLOADED_ORDER_L'] ? '' : 'checked';
                if ($tr['CONFIG']['ERROR'] == 'Y') {
                    $checked = '';
                }
                $STATUS_ID = $tr['ORDER']['STATUS_ID'];
                echo "<tr class='adm-list-table-row '>";
                echo "<td class='adm-list-table-cell' {$tr['CONFIG']['STYLE']}><input type='checkbox'  class='all_checked' {$checked}  name='ID[]' value='{$tr['ROW']['ID']}'></td>";
                echo "<td class='adm-list-table-cell' {$tr['CONFIG']['STYLE']}>{$k}</td>";
                $k++;
                foreach ($tr['ROW'] as $code => $td) {
                    switch ($code) {
                        case 'ID' :
                            echo "<td class='adm-list-table-cell' {$tr['CONFIG']['STYLE']}><a target='_blank' href='/bitrix/admin/sale_order_view.php?ID={$td}&filter=Y&set_filter=Y&lang=ru'><b>№ {$td}</b></a></td>";
                            break;
                        case 'PRICE':
                            $sum += $td;
                            echo "<td class='adm-list-table-cell' {$tr['CONFIG']['STYLE']}><b>{$td}</b></td>";
                            break;
                        case 'TIME' :
                        case 'PHONE':
                        case 'YANDEX_ADRESS':
                            echo "<td class='adm-list-table-cell' {$tr['CONFIG']['STYLE']}><b>{$td}</b></td>";
                            break;
                        case 'PAID':
                            if ($td == 'Да') {
                                echo "<td class='adm-list-table-cell' {$tr['CONFIG']['STYLE']}><b>{$td}</b></td>";
                            } else {
                                echo "<td class='adm-list-table-cell' {$tr['CONFIG']['STYLE']}>{$td}</td>";
                            }
                            break;
                        case 'YANDEX_LON':
                        case 'YANDEX_LAT':
                            if (!$_REQUEST['BTS']) {
                                echo "<td class='adm-list-table-cell' {$tr['CONFIG']['STYLE']}>{$td}</td>";
                            }
                            break;
                        default:
                            echo "<td class='adm-list-table-cell' {$tr['CONFIG']['STYLE']}>{$td}</td>";
                            break;
                    }
                }
                echo "</tr>";
            }
            echo "<tbody>";
            echo "</table>";
            echo "<h3>Итого сумма {$sum}</h3>";
        }

        if ($_REQUEST['export'] and $arResult['ORDERS']) {
            $obd = new \Lui\Delivery\NavBy($arResult);
            $obd->setSoapConfig('sellwin', 'oafege', 'http://gps.beltranssat.by/vrp-rs/ws/vrp?wsdl');
            $obd->sends();
            if (isset($_REQUEST['DEBUG']) and $_REQUEST['DEBUG'] == 'Y') {
                PR($obd->GetResponce(), false, true);
            }
            $ob = new \Lui\Delivery\ExportExcel();
            $ob->SetExcel($arResult);
        }
        ?>
    </form>
<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
