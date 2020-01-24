<?php

class PriorityOrderNew
{

    use InitMainTrait;

    protected $arProp;
    protected $typeID;
    protected $deliveryId;
    protected $dateInsert;
    public $arDeliveryMinsk = [2, 7, 8, 10, 11, 14, 15, 16, 17, 20, 21, 43];
    public $arDeliveryRB = [4, 9, 19];
    public $arTime = []; //Время получения заказа

    /**
     * PriorityOrderNew constructor.
     * @param $arProp
     * @param $typeID
     * @param $deliveryId
     * @param mixed $dateInsert
     *
     */
    public function __construct($arProp, $typeID, $deliveryId, $dateInsert = false)
    {
        $this->includeModules();
        $this->arProp = $arProp;
        $this->typeID = $typeID;
        $this->deliveryId = $deliveryId;
        $this->dateInsert = $dateInsert;
    }

    public function setPriorityOrder()
    {
        $date_receipt_order = strtotime($this->arProp['DATE']);// Дата получения заказа!
        $date_insert = strtotime(date('d.m.Y')); // дата создания заказ
        $date_insert_h = date('H'); // часы создания заказа
        $date_insert_i = date('i'); // минуты создания заказа
        $insertMinut = $date_insert_h * 60 + $date_insert_i;
        $sTimeEnum = $this->arProp['TIME']; // окно получения заказа

        $priority = null; //24
        $dateOrder = $this->arProp['DATE']; // 13
        $timeOrder = $this->arProp['TIME']; // 11 or 40

        $valueProp40 = array(
            'e12','e13','e14','e15', 'e16', 'e17', 'e18', 'e19', 'e20', 'e21'
        );

        if(in_array($timeOrder, $valueProp40)){
            $dateOrder = getWorkingDay(date('d.m.Y'), false);
            $date_receipt_order = $date_insert;
        }


        if ($this->typeID == 1) {

            //Доставка по минску
            if (in_array($this->deliveryId, $this->arDeliveryMinsk)) {

                //дата доставки совподает с датой создание заказа
                $arEnum = [];


                if ($date_insert == $date_receipt_order) {

                    if ($insertMinut <= 10 * 60 + 0) {
                        $arEnum[] = 'enum-1';
                        $arEnum[] = 'e12';
                        $arEnum[] = 'e13';
                        $arEnum[] = 'e14';
                        $arEnum[] = 'e15';
                        $arEnum[] = 'e16';
                        $arEnum[] = 'e17';
                        $arEnum[] = 'e18';
                        $arEnum[] = 'e19';
                        $arEnum[] = 'e20';
                        $arEnum[] = 'e21';
                    }

                    if ($insertMinut <= 16 * 60 + 0) {
                        $arEnum[] = 'enum-2';
                        $arEnum[] = 'e18';
                        $arEnum[] = 'e19';
                        $arEnum[] = 'e20';
                        $arEnum[] = 'e21';
                    }


                    if (!in_array($sTimeEnum, $arEnum)) {
                        // попытка обмана!
                        if($sTimeEnum == 'enum-1' || $sTimeEnum == 'enum-2'){
                            if ($insertMinut >= 16 * 60 + 0) {
                                $dateOrder = getWorkingDay(date('d.m.Y'), true);
                                if ($timeOrder['VALUE'] == 'enum-1') {
                                    $priority = 'priority-1';
                                } else {
                                    $priority = 'priority-3';
                                    $timeOrder['VALUE'] = 'enum-2';
                                }
                            }elseif ($insertMinut>=10*60+0){
                                $priority = 'priority-3';
                                $timeOrder['VALUE'] = 'enum-2';
                            }
                        }else{

                            if ($insertMinut>=10*60+0 || $insertMinut <= 16 * 60 + 0) {
                                $dateOrder = getWorkingDay(date('d.m.Y'), false);
                                $timeOrder['VALUE'] = 'e21';
                                $priority = 'priority-3';
                            }
                        }


                    } else {
                        /*switch ($sTimeEnum) {
                            case 'enum-1':
                                $priority = 'priority-1';
                                break;
                            case 'enum-2':
                                $priority = 'priority-1';
                                break;
                            case 'enum-3':
                                $priority = 'priority-3';
                                break;
                            case 'enum-4':
                                $priority = 'priority-3';
                                break;
                        }*/
                        switch ($sTimeEnum) {
                            case 'enum-1':
                                $priority = 'priority-1';
                                break;
                            case 'enum-2':
                                $priority = 'priority-3';
                                break;
                            default:
                                if ($insertMinut <= 10 * 60 + 0) {
                                    $priority = 'priority-1';
                                }
                                if($insertMinut >= 10 * 60 + 0 && $insertMinut <= 16 * 60 + 0){
                                    $priority = 'priority-3';
                                }
                                break;
                        }
                    }

                } else {
                    $priority = 'priority-1';
                }
                //Доставка по РБ
            } elseif (in_array($this->deliveryId, $this->arDeliveryRB)) {
                $priority = 'priority-1';
                if ($insertMinut <= 9 * 60 + 30) {
                    $dateOrder = getWorkingDay(date('d.m.Y'), false);
                } else {
                    $dateOrder = getWorkingDay(date('d.m.Y'), true);
                }
                //Самовывоз
            } elseif ($this->deliveryId == 3) {

                //дата доставки совподает с датой создание заказа
                if ($date_insert == $date_receipt_order) {

                    if ($insertMinut <= 15 * 60) {
                        $priority = 'priority-2';
                        $dateOrder = getWorkingDay(date('d.m.Y'), false);
                    }
                    if ($insertMinut >= 15 * 60) {
                        $priority = 'priority-1';
                        $dateOrder = getWorkingDay(date('d.m.Y'), true);
                    }

                } else {
                    $priority = 'priority-1';
                    $dateOrder = getWorkingDay(date('d.m.Y'), true);
                }

                //Экспресс доставка по г. Минску
            }elseif ($this->deliveryId == 43) {

                //дата доставки совподает с датой создание заказа
                if ($date_insert == $date_receipt_order) {

                    if($insertMinut <= 10 * 60 + 0){
                        $priority = 'priority-1';
                    }


                    if ($insertMinut > (10 * 60 + 0) && $insertMinut<=(16 * 60 + 0)) {
                        $priority = 'priority-3';
                    }
                }

            } else {
                $priority = 'priority-1';
                $dateOrder = getWorkingDay(date('d.m.Y'), true);
            }
            //юр лицо
        } elseif ($this->typeID == 2) {
            $priority = 'priority-4';
            $dateOrder = getWorkingDay(date('d.m.Y'), true);
        }
        return [
            'priority' => $priority,
            'dateOrder' => $dateOrder,
            'timeOrder' => $timeOrder,
        ];
    }

}
