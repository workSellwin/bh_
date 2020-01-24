<?php

namespace Yauheni\Discount;

class visualdiscount
{

    public function GetDebugData($data)
    {
        $arDebug = [];
        foreach ($data as $d) {

            $arDebug[$d['PRIORITY']][] = [
                'NAME' => $d['NAME'],
                'LID' => $d['LID'],
                'ID' => $d['ID'],
                'PRIORITY' => $d['PRIORITY'],
                'SORT' => $d['SORT'],
                'LAST_DISCOUNT' => $d['LAST_DISCOUNT'],
                'LAST_LEVEL_DISCOUNT' => $d['LAST_LEVEL_DISCOUNT'],
                'GROUP_ID' => $d['GROUP_ID'],
                'ACTIVE_FROM' => $d['ACTIVE_FROM'],
                'ACTIVE_TO' => $d['ACTIVE_TO'],
                'ACTIVE' => $d['ACTIVE']
            ];
        }
        return $arDebug;
    }

    function ShowTableDebug($arDebug)
    {
        $arDebug = array_values($arDebug);
        echo "<form id='visual_discounts' action='/bitrix/admin/discount_start.php' method='post'><table class='table'>";
        echo "</tr>";
        foreach ($arDebug as $k => $tr) {
            //PR($k);
            echo "<tr >";
            foreach ($tr as $td) { ?>
                <td id="<?= $td['ID'] ?>">
                    <input type="hidden" name="ELEM_DISCOUNT_ID" value="<?= $td['ID'] ?>">
                    <div class="bx-gadgets">
                        <div class="bx-gadgets-top-wrap">
                            <div class="bx-gadgets-top-title"><?= $td['NAME'] ?></div>
                            <div class="bx-gadgets-top-button">
                                <a target="_blank"
                                   href="/bitrix/admin/sale_discount_edit.php?ID=<?= $td['ID'] ?>&lang=ru"
                                   title="Настроить">
                                    <img src="/bitrix/js/ui/buttons/icons/images/ui-setting-black.svg?v=1.1" width="545"
                                         height="353" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="bx-gadgets-content">
                            <table>
                                <tr>
                                    <td style="width: 90%;">ПРИОРИТЕТ</td>
                                    <? if ($td['LAST_DISCOUNT'] == 'Y'): ?>
                                        <td><input type="checkbox" class="checkbox-change-js"
                                                   data-elem-id="<?= $td['ID'] ?>" name="LAST_DISCOUNT"
                                                   checked="checked"></td>
                                        <?
                                    else: ?>
                                        <td><input type="checkbox" class="checkbox-change-js"
                                                   data-elem-id="<?= $td['ID'] ?>" name="LAST_DISCOUNT"></td>
                                    <? endif; ?>
                                </tr>
                                <tr>
                                    <td style="width: 90%;">СОРТИРОВКА</td>
                                    <? if ($td['LAST_LEVEL_DISCOUNT'] == 'Y'): ?>
                                        <td><input type="checkbox" class="checkbox-change-js"
                                                   data-elem-id="<?= $td['ID'] ?>" name="LAST_LEVEL_DISCOUNT"
                                                   checked="checked"></td>
                                        <?
                                    else: ?>
                                        <td><input type="checkbox" class="checkbox-change-js"
                                                   data-elem-id="<?= $td['ID'] ?>" name="LAST_LEVEL_DISCOUNT"></td>
                                    <? endif; ?>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            <? }
            echo "</tr>";
        }
        echo "</table></form>";
    }

}