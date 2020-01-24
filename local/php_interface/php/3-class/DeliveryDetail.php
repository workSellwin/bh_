<?php

class DeliveryDetail
{

    protected $price;

    public function __construct($price)
    {
        $this->price = $price;
    }

    protected function GetTextRB()
    {
        $result = '5 руб';
        if ($this->price >= 50) {
            $result = 'бесплатно';
        }
        return $result;
    }

    protected function GetTextMinsk()
    {
        $result = '5 руб';
        if ($this->price >= 30) {
            $result = 'бесплатно';
        }
        return $result;
    }

    protected function GetTextDate()
    {
        if (date('H') >= 16) {
            $result = FormatDate('d F', strtotime(getWorkingDay(date('d.m.Y'), true)));
        }else{
            $result = FormatDate('d F', strtotime(getWorkingDay(date('d.m.Y'), false)));
        }
        return $result;
    }

    /**
     * @return array
     */
    public function GetTextAll()
    {
        return [
            'DATE' => $this->GetTextDate(),
            'RB' => $this->GetTextRB(),
            'MINSK' => $this->GetTextMinsk(),
        ];
    }

}