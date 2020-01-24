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

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/prolog.php');

CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
CModule::IncludeModule("yauheni.discount");

$visualDiscount = new Yauheni\Discount\visualdiscount();
$arGroups = [];
//Обновление скидки
if (isset($_REQUEST['action_checked_ajax']) && !empty($_REQUEST['action_checked_ajax']) && $_REQUEST['action_checked_ajax'] == 'Y') {
    $GLOBALS['APPLICATION']->RestartBuffer();
    if ($_REQUEST['status_checked'] == 'change') {
        $arFields = [
            $_REQUEST['input_name'] => 'Y',
        ];
    } else {
        $arFields = [
            $_REQUEST['input_name'] => 'N',
        ];
    }
    $res = CSaleDiscount::Update($_REQUEST['data_elem_id'], $arFields);
    die;
}

//получение всех груп пользователя
if (isset($_REQUEST['USER_ID']) && !empty($_REQUEST['USER_ID'])) {
    $arGroup = CUser::GetUserGroup($_REQUEST['USER_ID']);
    foreach ($arGroup as $val) {
        $arGroups[$val] = $val;
    }
}

//получение всех груп пользователя
if (isset($_REQUEST['USER_GROUPS']) && !empty($_REQUEST['USER_GROUPS'])) {
    $arGroups[$_REQUEST['USER_GROUPS']] = $_REQUEST['USER_GROUPS'];
}

//
if (isset($_REQUEST['ajax_event_discount']) && !empty($_REQUEST['ajax_event_discount']) && $_REQUEST['ajax_event_discount'] == 'Y') {
    $GLOBALS['APPLICATION']->RestartBuffer();
    $i = 10;
    foreach ($_REQUEST['ar_elem_id'] as $val) {
        $arFields = [
            'SORT' => $i,
            'PRIORITY' => $_REQUEST['ol_prioritet']
        ];
        CSaleDiscount::Update($val, $arFields);
        $i += 10;
    }
    die;
}


if (isset($_REQUEST['ajax_select_discount']) && !empty($_REQUEST['ajax_select_discount']) && $_REQUEST['ajax_select_discount'] == 'Y') {
    $GLOBALS['APPLICATION']->RestartBuffer();
    if (isset($_REQUEST['ar_elem_id']) && isset($_REQUEST['ar_option_id'])) {
        $arGroupID = $_REQUEST['ar_option_id'];
        $name = $_REQUEST['name'];
        $lid = $_REQUEST['lid'];
        $arFields = [
            'LID' => $lid,
            'NAME' => $name,
            'ACTIVE' => 'Y',
            'USER_GROUPS' => $arGroupID,
        ];
        CSaleDiscount::Update($_REQUEST['ar_elem_id'], $arFields);
    }
    die;
}


$discountGroupsToShow = Main\GroupTable::getList(array(
    'select' => array('ID', 'NAME'),
    //'order' => array('C_SORT' => 'ASC', 'ID' => 'ASC')
))->fetchAll();

$arOrder = [
    'PRIORITY' => 'DESC',
    'SORT' => 'ASC',
];
$arFilter = [
    'ACTIVE' => 'Y',
    'LID' => isset($_REQUEST['SITES_ID']) && !empty($_REQUEST['SITES_ID']) ? $_REQUEST['SITES_ID'] : 's1',
    'USER_GROUPS' => $arGroups,
];

//Покозать не активные скидки
if (isset($_REQUEST['SHOW_ACTIVE_NOT']) && !empty($_REQUEST['SHOW_ACTIVE_NOT']) && $_REQUEST['SHOW_ACTIVE_NOT'] == 'on') {
    unset($arFilter['ACTIVE']);
}

$rsDiscounts = CSaleDiscount::GetList($arOrder, $arFilter, false, false, []);
$arDiscounts = [];
while ($arDiscount = $rsDiscounts->Fetch()) {
    $arDiscounts[$arDiscount['ID']] = $arDiscount;

}

foreach ($arDiscounts as $val) {
    $rsDiscountGroups = CSaleDiscount::GetDiscountGroupList(array(), array('DISCOUNT_ID' => $val['ID']), false, false, []);
    while ($arDiscountGroup = $rsDiscountGroups->Fetch()) {
        if ($val['ID'] == $arDiscountGroup['DISCOUNT_ID']) {
            $arDiscounts[$val['ID']]['GROUP_ID'][] = $arDiscountGroup['GROUP_ID'];
        }
    }

}

//PR($arDiscounts);
$APPLICATION->SetTitle('Скидки');
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

$arSiteID = array('s1', 's2');
?>

    <img style="margin: 0 0 20px 5px;" src="/bitrix/images/sale/discount/hint_last_discount_ru.png" width="545"
         height="353" alt="">
    <form method="post" action="" class="form-filter" id="form-filter">
        <table>
            <tr><h3>Фильтр</h3></tr>
            <tr>
                <td>Сайт:</td>
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
                <td>Покозать не активные скидки:</td>
                <td>
                    <input type="checkbox" name="SHOW_ACTIVE_NOT"
                        <?= isset($_REQUEST['SHOW_ACTIVE_NOT']) && !empty($_REQUEST['SHOW_ACTIVE_NOT']) ? 'checked="checked"' : '' ?>>
                </td>
            </tr>
            <tr>
                <td>Период активности:</td>
                <td>
                    <input type="checkbox" name="ACTIVE_FROM"
                        <?= isset($_REQUEST['ACTIVE_FROM']) && !empty($_REQUEST['ACTIVE_FROM']) ? 'checked="checked"' : '' ?>>
                </td>
            </tr>
            <tr>
                <td>ID пользователя:</td>
                <td>
                    <input type="text" name="USER_ID"
                           value="<?= isset($_REQUEST['USER_ID']) && !empty($_REQUEST['USER_ID']) ? $_REQUEST['USER_ID'] : '' ?>">
                </td>
            </tr>
            <tr>
                <td>ID группы пользователя:</td>
                <td>
                    <input type="text" name="USER_GROUPS"
                           value="<?= isset($_REQUEST['USER_GROUPS']) && !empty($_REQUEST['USER_GROUPS']) ? $_REQUEST['USER_GROUPS'] : '' ?>">
                </td>
            </tr>
        </table>
        <input type="submit" name="BNT_FILTER_DISCOUNT" value="Фильтрвать">

    </form>


<?
$i = 1;

//Период активности
if (isset($_REQUEST['ACTIVE_FROM']) && !empty($_REQUEST['ACTIVE_FROM']) && $_REQUEST['ACTIVE_FROM'] == 'on') {
    $arDiscountss = [];
    foreach ($arDiscounts as $key => $val) {
        if ($val['ACTIVE_FROM'] != '') {
            $arDiscountss[$key] = $val;
        }
    }
    $arDiscounts = $arDiscountss;
    unset($arDiscountss);
}

$arCountDiscount = $visualDiscount->GetDebugData($arDiscounts);

//$visualDiscount->ShowTableDebug($arCountDiscount);
?>

<? $arDebug = array_values($arCountDiscount);
//PR($arCountDiscount)?>
    <div style="margin: 0 0 20px 5px;">Количество скидок: <?= count($arDiscounts) ?></div>
    <form id='visual_discounts' action='/bitrix/admin/discount_start.php' method='post'>
        <? foreach ($arCountDiscount as $k => $tr): ?>
            <ol id="sort<?= $i ?>" data-prioritet="<?= $k ?>" class="ui-widget connect sort-ol">
                <? foreach ($tr as $td): ?>
                    <li id="<?= $td['ID'] ?>" data-lid="<?= $td['LID'] ?>" data-name="<?= $td['NAME'] ?>"
                        class="bx-gadgets">
                        <input type="hidden" name="ELEM_DISCOUNT_ID" value="<?= $td['ID'] ?>">
                        <div class="bx-gadgets-top-wrap" data-elem-id="<?= $td['ID'] ?>">
                            <? if ($td['ACTIVE'] == 'Y'):
                                $date = time();
                                if($td['ACTIVE_FROM'] != '' && $td['ACTIVE_TO'] != ''):?>

                                    <? if ($date >= strtotime($td['ACTIVE_FROM']) && $date <= strtotime($td['ACTIVE_TO'])): ?>
                                        <div class="bx-gadgets-top-title" style="color: green"><?= $td['NAME'] ?> [<?= $td['ID'] ?>]</div>
                                    <? else: ?>
                                        <div class="bx-gadgets-top-title" style="color: red"><?= $td['NAME'] ?> [<?= $td['ID'] ?>]</div>
                                    <? endif; ?>

                                <? else: ?>



                                    <?if($td['ACTIVE_FROM'] == '' && $td['ACTIVE_TO'] == ''):?>
                                        <div class="bx-gadgets-top-title" style="color: green"><?= $td['NAME'] ?></div>
                                    <? else: ?>
                                        <?if($td['ACTIVE_FROM'] != '' && $td['ACTIVE_TO'] == ''):?>
                                            <? if ($date >= strtotime($td['ACTIVE_FROM'])): ?>
                                                <div class="bx-gadgets-top-title" style="color: green"><?= $td['NAME'] ?> [<?= $td['ID'] ?>]</div>
                                            <? else: ?>
                                                <div class="bx-gadgets-top-title" style="color: red"><?= $td['NAME'] ?> [<?= $td['ID'] ?>]</div>
                                            <? endif; ?>
                                        <?endif?>

                                        <?if($td['ACTIVE_FROM'] == '' && $td['ACTIVE_TO'] != ''):?>
                                            <? if ($date <= strtotime($td['ACTIVE_TO'])): ?>
                                                <div class="bx-gadgets-top-title" style="color: green"><?= $td['NAME'] ?> [<?= $td['ID'] ?>]</div>
                                            <? else: ?>
                                                <div class="bx-gadgets-top-title" style="color: red"><?= $td['NAME'] ?> [<?= $td['ID'] ?>]</div>
                                            <? endif; ?>
                                        <?endif?>
                                    <?endif?>
                                <? endif; ?>
                            <? else:?>
                                <div class="bx-gadgets-top-title" style="color: red"><?= $td['NAME'] ?> [<?= $td['ID'] ?>]</div>
                            <? endif; ?>
                            <div class="bx-gadgets-top-button">
                                <a target="_blank"
                                   href="/bitrix/admin/sale_discount_edit.php?ID=<?= $td['ID'] ?>&lang=ru"
                                   title="Настроить">
                                    <img src="/bitrix/js/ui/buttons/icons/images/ui-setting-black.svg?v=1.1"
                                         width="545"
                                         height="353" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="bx-gadgets-content" data-elem-id="<?= $td['ID'] ?>">
                            <table>
                                <tr>
                                    <td>
                                        Сайт
                                    </td>
                                    <td align="right" colspan="2">
                                        <?= $td['LID'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Активность
                                    </td>
                                    <? if ($td['ACTIVE'] == 'Y'): ?>
                                        <td colspan="2" align="right"><input type="checkbox" class="checkbox-change-js"
                                                                             data-elem-id="<?= $td['ID'] ?>"
                                                                             name="ACTIVE"
                                                                             checked="checked"></td>
                                        <?
                                    else: ?>
                                        <td colspan="2" align="right"><input type="checkbox" class="checkbox-change-js"
                                                                             data-elem-id="<?= $td['ID'] ?>"
                                                                             name="ACTIVE">
                                        </td>
                                    <? endif; ?>
                                </tr>
                                <tr>
                                    <td style="width: 90%;">Приоритет</td>
                                    <td align="right" class="prior-<?= $td['ID'] ?>"><?= $td['PRIORITY'] ?></td>
                                    <? if ($td['LAST_DISCOUNT'] == 'Y'): ?>
                                        <td align="right"><input type="checkbox" class="checkbox-change-js"
                                                                 data-elem-id="<?= $td['ID'] ?>" name="LAST_DISCOUNT"
                                                                 checked="checked"></td>
                                        <?
                                    else: ?>
                                        <td align="right"><input type="checkbox" class="checkbox-change-js"
                                                                 data-elem-id="<?= $td['ID'] ?>" name="LAST_DISCOUNT">
                                        </td>
                                    <? endif; ?>
                                </tr>
                                <tr>
                                    <td style="width: 90%;">Сортировка</td>
                                    <td align="right" class="sort-<?= $td['ID'] ?>"
                                        data-sort="<?= $td['SORT'] ?>"><?= $td['SORT'] ?></td>
                                    <? if ($td['LAST_LEVEL_DISCOUNT'] == 'Y'): ?>
                                        <td align="right"><input type="checkbox" class="checkbox-change-js"
                                                                 data-elem-id="<?= $td['ID'] ?>"
                                                                 name="LAST_LEVEL_DISCOUNT"
                                                                 checked="checked"></td>
                                        <?
                                    else: ?>
                                        <td align="right"><input type="checkbox" class="checkbox-change-js"
                                                                 data-elem-id="<?= $td['ID'] ?>"
                                                                 name="LAST_LEVEL_DISCOUNT">
                                        </td>
                                    <? endif; ?>
                                </tr>
                                <tr>
                                    <? if ($td['ACTIVE_FROM'] && $td['ACTIVE_TO']): ?>
                                        <td>
                                            Период активности
                                        </td>
                                        <td align="right">
                                            <?= $td['ACTIVE_FROM'] ?>
                                        </td>
                                        <td align="right">
                                            <?= $td['ACTIVE_TO'] ?>
                                        </td>
                                    <? else: ?>

                                        <?if($td['ACTIVE_FROM'] == '' && $td['ACTIVE_TO'] == ''):?>
                                            <td>
                                                Период активности
                                            </td>
                                            <td colspan="2" align="right">
                                                без ограничений
                                            </td>

                                        <? else: ?>
                                            <?if($td['ACTIVE_FROM'] != '' && $td['ACTIVE_TO'] == ''):?>
                                                <td>
                                                    Период активности
                                                </td>
                                                <td align="right">
                                                    от
                                                </td>
                                                <td align="right">
                                                   <?=$td['ACTIVE_FROM']?>
                                                </td>
                                            <?endif;?>
                                            <?if($td['ACTIVE_FROM'] == '' && $td['ACTIVE_TO'] != ''):?>
                                                <td>
                                                    Период активности
                                                </td>
                                                <td align="right">
                                                    до
                                                </td>
                                                <td align="right">
                                                    <?=$td['ACTIVE_TO']?>
                                                </td>
                                            <?endif;?>
                                        <?endif;?>
                                    <? endif; ?>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <select data-select-id="<?= $td['ID'] ?>" name="USER_GROUPS[]"
                                                class="SELECT_USER_GROUPS" multiple style="width: 280px">
                                            <?
                                            foreach ($discountGroupsToShow as $group) {
                                                $group['ID'] = (int)$group['ID'];
                                                $selected = (in_array($group['ID'], $td['GROUP_ID']) ? ' selected' : '');
                                                ?>
                                                <option value="<?= $group['ID'] ?>"<?= $selected ?>>
                                                    [<?= $group['ID'] ?>] <?= htmlspecialcharsEx($group['NAME']) ?>
                                                </option>
                                                <?
                                            }
                                            unset($selected, $group);
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </li>
                <? endforeach; ?>
            </ol>
            <? $i++;
        endforeach; ?>
    </form>


<?

CJSCore::RegisterExt('visual_discounts_js', array(
    'js' => array(
        '/bitrix/js/yauheni.discount/jquery.min.js',
        '/bitrix/js/yauheni.discount/jquery-ui.min.js',
        '/bitrix/js/yauheni.discount/scripts.js',
    ),
    'css' => array(
        '/bitrix/css/yauheni.discount/jquery-ui.css',
        '/bitrix/css/yauheni.discount/style.css',
    ),
    'rel' => array('jquery'),
));

CJSCore::Init(array("visual_discounts_js"));

?>
    <script>
        var sort = '';
        for (step = 1; step <= <?=$i?>; step++) {
            if (step == '<?=$i?>') {
                sort += '#sort' + step;
            } else {
                sort += '#sort' + step + ",";
            }

        }

        $(sort).sortable({connectWith: ".connect"});
        $(sort).sortable({
            stop: function (event, ui) {
                var url = $('#visual_discounts').attr('action');
                var elem_id = $(ui.item.context).attr('id');
                var next_ol = $(ui.item.context).parent();
                var ol_id = $(ui.item.context).parent().attr('id');
                var ol_prioritet = $(ui.item.context).parent().attr('data-prioritet');
                var ar_elem_id = [];
                var j = 10;

                $('#' + ol_id + ' li').each(function () {
                    var id_li = $(this).attr('id');
                    $('.sort-' + id_li).text(j);
                    $('.prior-' + id_li).text(ol_prioritet);
                    ar_elem_id.push($(this).attr('id'));
                    j += 10;
                });

                if (ar_elem_id.length != 0) {
                    ajax_event_discount(ar_elem_id, ol_prioritet, url);

                }
            }
        });

        function ajax_event_discount(ar_elem_id, ol_prioritet, url) {
            $.post(
                url,
                {
                    ar_elem_id: ar_elem_id,
                    ol_prioritet: ol_prioritet,
                    ajax_event_discount: 'Y'
                },
                onAjaxSuccess
            );
            function onAjaxSuccess(data) {
                console.log(data);
            }
        }


    </script>


<?


require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');