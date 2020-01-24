<?php

namespace Lui\Delivery;

class FileDoc
{
    protected $order;
    protected $property;
    protected $basket;

    public function __construct($orderId)
    {

    }

    public function SevaFile()
    {
        $r = false;
        if ($this->order) {
           // $fileName = date('d-m-Y_h-i') . '-' . $this->order->GetField('ID') . '.doc';
            $fileName = date('d-m-Y_h-i') . '-' . $this->order->GetField('ID') . '.doc';
            $obsPath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/lui.delivery/file/' . $fileName;
            $fileStr = $this->RenderHtml();
            file_put_contents($obsPath, $fileStr);
            FtpSellwin::UploadFile($obsPath, true);
        }
        return $r;
    }


    public static function SeveCheckFtp($orderId)
    {
        $ob = new self();
        $ob->SetOrder($orderId);
        $ob->SevaFile();
    }


    public function SetOrder($orderId)
    {
        $this->orderID = $orderId;
        if ($orderId) {
            $this->order = \Bitrix\Sale\Order::load($orderId);
            if ($this->order) {
                $propertyCollection = $this->order->getPropertyCollection();
                $propsData = [];
                foreach ($propertyCollection as $propertyItem) {
                    $codeF = $propertyItem->getField("CODE");
                    switch ($codeF) {
                        case 'EMAIL':
                            $propsData[$codeF] = trim($propertyItem->getValue());
                            break;
                        default:
                            $propsData[$codeF] = trim($propertyItem->getViewHtml());
                            break;
                    }
                }
                $this->property = $propsData;
                $this->property['ID'] = $this->order->getId();
                $this->property['USER_DESCRIPTION'] = $this->order->GetField('USER_DESCRIPTION');
                $this->basket = $this->order->getBasket();
            }
        }
    }

    public function RenderHtml()
    {
        $arRender = [
            '#ORDER_PROPERTY#' => $this->RenderProperty(),
            '#ORDER_BASKET#' => $this->RenderBasket(),
            '#ORDER_COMMENT#' => $this->RenderComment(),
        ];
        return str_replace(array_keys($arRender), array_values($arRender), $this->tplHtml());
    }

    public function RenderProperty()
    {
        $s = '';
        ob_start();
        ?>
        <table>
            <tbody>
            <tr>
                <td>Номер заказа:</td>
                <td><?= $this->property['ID'] ?></td>
            </tr>

            <? if ($this->property['DATE']): ?>
                <tr>
                    <td>Дата доставки:</td>
                    <td><?= $this->property['DATE'] ?></td>
                </tr>
            <? endif; ?>
            <? if ($this->property['TIME']): ?>
                <tr>
                    <td>Время доставки:</td>
                    <td>
                        <?= $this->property['TIME'] ?>
                    </td>
                </tr>
            <? endif; ?>
            <?
            $delivery_name = '';
            $DELIVERY_ID = $this->order->GetField('DELIVERY_ID');
            $PAY_SYSTEM_ID = $this->order->GetField('PAY_SYSTEM_ID');
            if ($DELIVERY_ID) {
                $delivery = \Bitrix\Sale\Delivery\Services\Manager::getById($DELIVERY_ID);
                if (isset($delivery['PARENT_ID']) && $delivery['PARENT_ID'] > 0) {
                    $delivery_parent = \Bitrix\Sale\Delivery\Services\Manager::getById($delivery['PARENT_ID']);
                    $delivery_name = $delivery_parent['NAME'] . ' - ';
                }
                $delivery_name = $delivery_name . $delivery['NAME'];
            }
            ?>
            <? if ($delivery_name) { ?>
                <tr>
                    <td>Служба доставки:</td>
                    <td>
                        <?= $delivery_name ?>
                    </td>
                </tr>
            <? } ?>
            <? if ($this->property['STREET']): ?>
                <tr>
                    <td>Адрес доставки:</td>
                    <td>
                        <?
                        $db_vars = \CSaleLocation::GetList(array(), array("CITY_ID" => $this->property['LOCATION'], "LID" => "ru"), false, false, array());
                        if ($vars = $db_vars->Fetch()) {
                            if ($vars["CITY_NAME"]) {
                                echo $vars["CITY_NAME"] . ", ";
                            }
                        }
                        ?>
                        <?= $this->property['STREET'] . ", дом " . $this->property['HOME'] . ", кв." . $this->property['ROOM'] ?>
                    </td>
                </tr>
            <? endif; ?>
            <? if ($arPaySys = \CSalePaySystem::GetByID($PAY_SYSTEM_ID)): ?>
                <tr>
                    <td>Способ оплаты:</td>
                    <td><?= $arPaySys["PSA_NAME"] ?></td>
                </tr>
            <? endif; ?>
            <? if ($this->property['FIO']): ?>
                <tr>
                    <td>Имя получателя:</td>
                    <td><?= $this->property['FIO'] ?></td>
                </tr>
            <? endif ?>
            <? if ($this->property['PHONE']): ?>
                <tr>
                    <td>Телефон:</td>
                    <td><?= $this->property['PHONE'] ?></td>
                </tr>
            <? endif; ?>
            <? if ($this->property['DATE']): ?>
                <tr>
                    <td>Дата доставки:</td>
                    <td><?= $this->property['DATE'] ?></td>
                </tr>
            <? endif; ?>
            <? if ($this->property['ADRES'] || $this->property['TOWN']): ?>
                <tr>
                    <td>Адрес доставки:</td>
                    <td>
                        <?
                        if ($this->property['TOWN'] && $this->property['ADRES']) {
                            echo $this->property['TOWN'] . ", ";
                        } else {
                            echo $this->property['TOWN'];
                        }
                        ?>
                        <?= $this->property['ADRES'] ?>
                    </td>
                </tr>
            <? endif; ?>
            <? if ($this->property['CONTACT']): ?>
                <tr>
                    <td>Имя получателя:</td>
                    <td><?= $this->property['CONTACT'] ?></td>
                </tr>
            <? endif ?>
            <? if ($this->property['TEL']): ?>
                <tr>
                    <td>Телефон:</td>
                    <td><?= $this->property['TEL'] ?></td>
                </tr>
            <? endif; ?>
            </tbody>
        </table>
        <?
        $s = ob_get_contents();
        ob_end_clean();
        return $s;
    }

    public function RenderBasket()
    {
        $orderBasket = $this->basket->getBasketItems();

        $arBasketIDs = [];
        foreach ($orderBasket as $item) {
            $arBasketIDs[] = $item->getField('ID');
        }
        $s = '';
        ob_start();
        if (count($arBasketIDs) > 0) {
            $arCurFormat = \CCurrencyLang::GetCurrencyFormat($this->order->get ["CURRENCY"]);
            $currency = preg_replace('/(^|[^&])#/', '${1}', $arCurFormat['FORMAT_STRING']);
            ?>
            <table class="blank" style="width: 100%">
                <tr>
                    <td align="center">№</td>
                    <td align="center">Наименование</td>
                    <td align="center">Штрих-код</td>
                    <td align="center">Количество</td>
                    <td align="center">Цена,<?= $currency; ?></td>
                    <td align="center">Скидка</td>
                    <td align="center">Cумма,<?= $currency; ?></td>
                </tr>
                <?
                $priceTotal = 0;
                $countPos = 0;
                $bUseVat = false;
                $arBasketOrder = array();
                for ($i = 0, $countBasketIds = count($arBasketIDs); $i < $countBasketIds; $i++) {
                    $arBasketTmp = \CSaleBasket::GetByID($arBasketIDs[$i]);
                    //GmiPrint($arBasketTmp);

                    if (floatval($arBasketTmp["VAT_RATE"]) > 0)
                        $bUseVat = true;

                    $priceTotal += $arBasketTmp["PRICE"] * $arBasketTmp["QUANTITY"];

                    $arBasketTmp["PROPS"] = array();
                    if (isset($_GET["PROPS_ENABLE"]) && $_GET["PROPS_ENABLE"] == "Y") {
                        $dbBasketProps = CSaleBasket::GetPropsList(
                            array("SORT" => "ASC", "NAME" => "ASC"),
                            array("BASKET_ID" => $arBasketTmp["ID"]),
                            false,
                            false,
                            array("ID", "BASKET_ID", "NAME", "VALUE", "CODE", "SORT")
                        );
                        while ($arBasketProps = $dbBasketProps->GetNext())
                            $arBasketTmp["PROPS"][$arBasketProps["ID"]] = $arBasketProps;
                    }

                    $arBasketOrder[] = $arBasketTmp;
                }

                //разбрасываем скидку на заказ по товарам
                if (floatval($this->order->GetField('DISCOUNT_VALUE')) > 0) {
                    $arBasketOrder = GetUniformDestribution($arBasketOrder, $this->order->GetField('DISCOUNT_VALUE'), $priceTotal);
                }

                //налоги
                $arTaxList = array();
                $db_tax_list = \CSaleOrderTax::GetList(array("APPLY_ORDER" => "ASC"), Array("ORDER_ID" => $this->order->GetField('ID')));
                $iNds = -1;
                $i = 0;
                while ($ar_tax_list = $db_tax_list->Fetch()) {
                    $arTaxList[$i] = $ar_tax_list;
                    // определяем, какой из налогов - НДС
                    // НДС должен иметь код NDS, либо необходимо перенести этот шаблон
                    // в каталог пользовательских шаблонов и исправить
                    if ($arTaxList[$i]["CODE"] == "NDS")
                        $iNds = $i;
                    $i++;
                }


                $i = 0;
                $total_sum = 0;
                foreach ($arBasketOrder as $arBasket):
                    $nds_val = 0;
                    $taxRate = 0;

                    if (floatval($arQuantities[$i]) <= 0)
                        $arQuantities[$i] = DoubleVal($arBasket["QUANTITY"]);

                    $b_AMOUNT = DoubleVal($arBasket["PRICE"]);

                    //определяем начальную цену
                    $item_price = $b_AMOUNT;

                    if (DoubleVal($arBasket["VAT_RATE"]) > 0) {
                        $nds_val = ($b_AMOUNT - DoubleVal($b_AMOUNT / (1 + $arBasket["VAT_RATE"])));
                        $item_price = $b_AMOUNT - $nds_val;
                        $taxRate = $arBasket["VAT_RATE"] * 100;
                    } elseif (!$bUseVat) {
                        $basket_tax = \CSaleOrderTax::CountTaxes($b_AMOUNT * $arQuantities[$i], $arTaxList, $this->order->GetField('CURRENCY'));
                        for ($mi = 0, $countTaxList = count($arTaxList); $mi < $countTaxList; $mi++) {
                            if ($arTaxList[$mi]["IS_IN_PRICE"] == "Y") {
                                $item_price -= $arTaxList[$mi]["TAX_VAL"];
                            }
                            $nds_val += DoubleVal($arTaxList[$mi]["TAX_VAL"]);
                            $taxRate += ($arTaxList[$mi]["VALUE"]);
                        }
                    }
                    ?>
                    <tr>
                        <td><?
                            echo $i + 1; ?></td>
                        <td>
                            <?
                            echo htmlspecialcharsbx($arBasket["NAME"]); ?>
                            <?
                            /*if (is_array($arBasket["PROPS"]) && $_GET["PROPS_ENABLE"] == "Y")
                                    {
                                        foreach($arBasket["PROPS"] as $vv)
                                        {
                                            if(strlen($vv["VALUE"]) > 0 && $vv["CODE"] != "CATALOG.XML_ID" && $vv["CODE"] != "PRODUCT.XML_ID")
                                                echo "<div style=\"font-size:8pt\">".$vv["NAME"].": ".$vv["VALUE"]."</div>";
                                        }
                                    }
                            */
                            ?>
                        </td>
                        <? $countPos += $arQuantities[$i]; ?>
                        <td align="center"><?= getBarCode($arBasket["PRODUCT_ID"]) ?></td>
                        <td align="center"><?
                            echo \Bitrix\Sale\BasketItem::formatQuantity($arQuantities[$i]) ?></td>
                        <td align="right" nowrap><?
                            echo number_format($arBasket["PRICE"], 2, ',', ' '); ?></td>
                        <td align="right" nowrap><?//=$arBasket["DISCOUNT_VALUE"]
                            ?><?= number_format(($arBasket["BASE_PRICE"] - $arBasket["PRICE"]) * $arQuantities[$i], 2, ',', ' '); ?></td>
                        <td align="right" nowrap><?
                            echo number_format($arBasket["PRICE"] * $arQuantities[$i], 2, ',', ' '); ?></td>
                    </tr>
                    <?

                    $total_sum += $arBasket["PRICE"] * $arQuantities[$i];
                    $total_nds += $nds_val * $arQuantities[$i];

                    $i++;
                endforeach;
                ?>
                <tr>
                    <td colspan="3">Итого позиций:</td>
                    <td align="center"><?= $countPos; ?></td>
                    <td colspan="3"></td>
                </tr>
            </table>
            <?
            if ($this->order->GetField('PRICE_DELIVERY')):?>
                <p style="text-align: right;">
                    Итого: <?
                    echo number_format($total_sum, 2, ',', ' '); ?> руб.<br>
                    Стоимость доставки: <?
                    echo number_format($this->order->GetField('PRICE_DELIVERY'), 2, ',', ' '); ?> руб.<br>
                    Итого по чеку: <?
                    echo number_format($this->order->GetField('PRICE'), 2, ',', ' '); ?> руб.
                </p>

            <? else: ?>
                <p style="text-align: right;">Итого по чеку: <?
                    echo number_format($total_sum, 2, ',', ' '); ?> руб.</p>
            <? endif; ?>
            <table style="width: 100%;" class="t1">
                <tbody>
                <tr>
                    <td class="bb"><span>Дата продажи:</span></td>
                    <td></td>
                    <td class="bb"><span>Сумма коррекции:</span></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="bb"><span>Итого с коррекцией:</span></td>
                </tr>
                <tr>
                    <td><br></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="bb"><span>Подпись покупателя**:</span></td>
                    <td></td>
                    <td class="bb"><span>Подпись продавца:</span></td>
                </tr>
                </tbody>
            </table>
            <?
        }
        $s = ob_get_contents();
        ob_end_clean();
        return $s;
    }

    public function RenderComment()
    {
        return <<<HTML
    <table style="width: 100%;">
        <thead>
        <tr>
            <td>Комментарий к заказу</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="border: 1px solid #000;padding: 10px;min-height: 60px;display: block;">
                {$this->property['USER_DESCRIPTION']}             
             </td>
        </tr>
        </tbody>
    </table>
HTML;
    }

    public function tplHtml()
    {
        return <<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:w="urn:schemas-microsoft-com:office:word"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv=Content-Type content="text/html; charset=UTF-8">
    <title langs="ru">Товарный чек</title>
    <style>
        html {
            background-color: #fff;
        }

        .header {
            font-size: 17px;
            font-family: Tahoma;
            padding-left: 8px;
        }

        .sub_header {
            font-size: 13px;
            font-family: Tahoma;
            padding-left: 8px;
        }

        .date {
            font-style: italic;
            font-family: Tahoma;
            padding-left: 8px;
        }

        .number {
            font-size: 24px;
            font-family: Tahoma;
            font-style: italic;
            padding-left: 8px;
        }

        .user {
            font-size: 12px;
            font-family: Tahoma;
            font-weight: bold;
            padding-left: 8px;
        }

        .summa {
            font-size: 12px;
            font-family: Tahoma;
            font-weight: bold;
            padding-left: 15px;
        }

        .container {
            width: 840px;
            margin: 0 auto;
        }

        .logo img {
            vertical-align: middle;
            margin-right: 10px;
        }

        table.t1 td.bb {
            border-bottom: 1px solid;
        }

        table.t1 td.bb span {
            height: 25px;
            display: inline-block;
            margin-bottom: -5px;
            background-color: #fff;
            margin-left: -5px;
            padding-left: 5px;
        }

        table.t1 td {
            min-width: 10px;
        }

        table.blank {
            border-collapse: collapse;
            width: 585px;
        }

        table.blank td {
            border: 0.5pt solid windowtext;
        }
    </style>
</head>

<body bgcolor=white lang=RU style='tab-interval:35.4pt;'>
<div class="container">
    <br><br>
    <div class="logo">
        <img src="https://bh.by/local/templates/main/images/logo_print_new.jpg" width="300" height="45" alt="">
    </div>
    <br>
    <p style="text-align: center;">Товарный чек*</p>
    <p style="width: 100%; border-top: 1px solid #000;"></p>
    <p style="text-align: center;"><b>Интернет-магазин BH.by</b></p>

        #ORDER_PROPERTY#
    <br>
        #ORDER_BASKET#
           
    </p> 
    #ORDER_COMMENT#
</div>
</body>
</html>
HTML;
    }
}
