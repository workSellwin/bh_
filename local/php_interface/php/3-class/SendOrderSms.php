<?php
/**
 * Class SendOrderSms
 */

class SendOrderSms
{
    use InitMainTrait;
    use IsOrder;

    private $ORDER_ID;
    private $arProp = [];
    private $obOrder;
    private $debug;

    public function __construct($ORDER_ID, $debug = false)
    {
        $this->includeModules();
        if (is_numeric($ORDER_ID)) {
            $this->ORDER_ID = $ORDER_ID;
        } else {
            throw new \Exception($ORDER_ID . ' no number');
        }
        $this->obOrder = $this->GetOrder();
        $this->debug = $debug;
        try {
            $this->GetOrderProp();
        } catch (Exception $e) {
            echo $e->GetMessage();
        }
    }

    protected function GetOrder()
    {
        return \Bitrix\Sale\Order::load($this->ORDER_ID);
    }

    public function GetOrderProp()
    {
        $ar = $this->obOrder->getPropertyCollection()->getArray();;
        foreach ($ar['properties'] as $v) {
            $z = $v['VALUE'][0];
            $val = $v['OPTIONS'] ? $v['OPTIONS'][$z] : $z;
            $this->arProp[$v['ID']] = $val;
        }
    }


    public function Send()
    {
        if ($this->debug or !$this->isOrder()) {
            $this->SendOrder();
            $this->setOrder($this->ORDER_ID);
        }
    }

    protected function SendOrder()
    {
        if ($this->arProp['3']) {
            $str = $this->GetTemplate();
            $str = $this->SetTplVar($str);
            if ($this->debug) {
                PR([
                    'PHONE' => $this->arProp['3'],
                    'MESSAGE' => $str,
                    'ORDER_ID' => $this->ORDER_ID
                ]);
            } else {
                $this->SendSms($this->arProp['3'], $str, $this->ORDER_ID);
            }
        }
    }

    public function SendSms($phone, $mes, $ID)
    {
        CModule::IncludeModule('mlife.smsservices');
        $obSmsServ = new CMlifeSmsServices();
        $arSend = $obSmsServ->sendSms($phone, $mes, 0, 'BH.BY');
        if ($arSend->error) {
            AddMessage2Log('\n\n\n Заказ №' . $ID . ' Ошибка отправки смс: ' . $arSend->error . ', код ошибки: ' . $arSend->error_code);
        } else {
            AddMessage2Log('\n\n\n Заказ №' . $ID . ' Сообщение успешно отправлено, Стоимость рассылки:' . $arSend->cost . ' руб.');
        }
    }


    protected function SetTplVar($str)
    {
        $arTpl = [
            '#ID#',
            '#PRICE#',
            '#DATE#',
        ];
        $arVAL = [
            $this->ORDER_ID,
            $this->GetPrice(),
            $this->GetDateDelivery()
        ];
        return str_replace($arTpl, $arVAL, $str);
    }

    protected function GetDateDelivery()
    {
        $date = '';
        if ($this->arProp['13']) {
            $date = $this->arProp['13'];
        }
        //если доставка в субботу
        if($this->arProp['42'] == 'Да'){
            $date = date('d.m.Y', strtotime($date. ' + 1 days'));
        }
        if ($this->arProp['11']) {
            $date .= ' c ' . $this->arProp['11'];
        }
        if ($this->arProp['41']) {
            $date .= ' c ' . $this->arProp['41'];
        }

        return $date;
    }

    protected function GetPrice()
    {
        return $this->obOrder->getPrice();
    }


    protected function GetTemplate()
    {
        $str = '';
        $resTemplate = \CIBlockElement::GetList(
            [],
            [
                "IBLOCK_ID" => 11,
                "PROPERTY_DELIVERY_ID" => $this->obOrder->getField("DELIVERY_ID")
            ],
            false,
            [],
            [
                "NAME",
                "ID",
                "PREVIEW_TEXT"
            ]
        );
        if ($arTemplate = $resTemplate->GetNext()) {
            $str = $arTemplate["PREVIEW_TEXT"];
        }
        return $str;
    }

    public function __toString()
    {
        return (string)$this->ORDER_ID;
    }

}
