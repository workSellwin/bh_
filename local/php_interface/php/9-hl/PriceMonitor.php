<?php

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;


class PriceMonitor
{
    const MY_HL_BLOCK_ID = 6;
    private static $instances = [];
    protected $Obj;


    protected function __construct()
    {
        \CModule::IncludeModule('highloadblock');
        $this->SetObj();
    }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance()
    {
        $cls = static::class;
        if (!isset(static::$instances[$cls])) {
            static::$instances[$cls] = new static;
        }

        return static::$instances[$cls];
    }

    protected function SetObj()
    {
        $hlblock = HL\HighloadBlockTable::getById(self::MY_HL_BLOCK_ID)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entityClass = $entity->getDataClass();
        $this->Obj = $entityClass;
    }

    public function GetObj()
    {
        return $this->Obj;
    }

    public function add($data)
    {
        $obj = $this->Obj;
        if (!$obj::getList(['filter' => $data])->fetchAll()) {
            $obj::add($data);
        }
    }

    public function delete($data)
    {
        $obj = $this->Obj;
        if ($arData = $obj::getList(['filter' => $data])->fetchAll()) {
            $idDel = $arData[0]['ID'];
            if ($idDel) {
                $obj::delete($idDel);
            }
        }
    }

    public function view($data)
    {
        $obj = $this->Obj;
        if ($arData = $obj::getList(['filter' => $data])->fetchAll()) {
            $idDel = $arData[0]['ID'];
            if ($idDel) {
                return 'Y';
            }
        }
    }

    public function GetUser()
    {
        global $USER;
        $userId = $USER->GetID();
        $obj = $this->Obj;
        $arData = $obj::getList(['filter' => ['UF_USER_ID' => $userId]])->fetchAll();
        return array_column($arData, 'UF_PRODUCT_ID');
    }

    public function GetAll()
    {
        $obj = $this->Obj;
        return $obj::getList()->fetchAll();
    }


    /**
     * @param $data
     * @param $phone
     */
    public function SendSms($data,$phone){
        $tpl='Цена на #PRODUCT# уменьшилась с #PRICE_OLD#  на #PRICE_NEW# ';
        $text=str_replace(['#PRODUCT#','#PRICE_OLD#','#PRICE_NEW#'],$data,$tpl);
        if (CModule::IncludeModule('mlife.smsservices')) {
            $obSmsServ = new CMlifeSmsServices();
            $arSend = $obSmsServ->sendSms($phone, $text, 0, 'BH.BY');
            if ($arSend->error) {
                AddMessage2Log('PriceMonitor Ошибка отправки смс: ' . $arSend->error . ', код ошибки: ' . $arSend->error_code);
            } else {
                AddMessage2Log('PriceMonitor Сообщение успешно отправлено, Стоимость рассылки:' . $arSend->cost . ' руб.');
            }
        }
    }

}
