<?php


class cost extends stdClass
{
    public $cost;// сумма, целое, обязательное
    /**
     * тип оплаты, целое
     * 0 - неопределенный тип оплаты,
     * 1 - нал,
     * 2 - безнал,
     * 3 - терминал,
     * 4 - сертификат,
     * 5 - кредит,
     * 6 - ерип,
     * 7 - WebPay,
     * 8 - WebMoney
     * @var int
     */
    public $type;

    public function SetCost($cost, $PAY_SYSTEM_ID)
    {
        $this->cost = $cost;
        $type = $this->getType($PAY_SYSTEM_ID);
        $this->type = $type;
    }

    protected function GetType($PAY_SYSTEM_ID)
    {
        /*$arType = [
            '1' => 1,
            '7' => 3,
            '8' => 3,
            '11' => 0,
            '12' => 0,
            '13' => 0,
            '14' => 0,
            '15' => 0,
            '16' => 0,
            '17' => 0,
            '23' => 0,
            '24' => 0,
            '25' => 3,
            '26' => 0,
        ];
        return isset($arType[$PAY_SYSTEM_ID]) ? $arType[$PAY_SYSTEM_ID] : 0;*/
        return 0;
    }
}
