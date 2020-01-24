<?php

class updateDuplicateCode
{

    private $arDuplicateCode = [];
    public $IBLOCK_ID;

    public function __construct($IBLOCK_ID)
    {
        \CModule::IncludeModule('iblock');
        $this->IBLOCK_ID = $IBLOCK_ID;
    }

    public function process(){
        $this->getListElementDuplicateCode();
        if(!empty($this->arDuplicateCode)){
            $this->updateCodeElement();
            return 'Дубли символьных кодов изменены';
        }else{
            return 'Дубли символьных кодов нету';
        }
    }

    private function getListElementDuplicateCode(){
        $code = [];
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "CODE");
        $arFilter = Array("IBLOCK_ID"=>$this->IBLOCK_ID,);
        $res = \CIBlockElement::GetList(Array(''), $arFilter, false, false, $arSelect);
        while($ob = $res->GetNext()){
            if(isset($code[trim($ob['CODE'])])){
                $this->arDuplicateCode[$ob['ID']]['CODE'] = trim($ob['CODE']);
                $this->arDuplicateCode[$ob['ID']]['NAME'] = trim($ob['NAME']);
            }else{
                $code[trim($ob['CODE'])] = $ob['ID'];
            }
        }
    }

    private function updateCodeElement(){
        $arTransParams = array(
            "max_len" => 100,
            "change_case" => 'L', // 'L' - toLower, 'U' - toUpper, false - do not change
            "replace_space" => '-',
            "replace_other" => '-',
            "delete_repeat_replace" => true
        );
        foreach ($this->arDuplicateCode as $id => $val){
            if($val['CODE'] != ''){
                $arLoadProductArray = Array(
                    'CODE' => $val['CODE'].'-'.$id,
                );
            }else{
                $arLoadProductArray = Array(
                    'CODE' => \CUtil::translit($val["NAME"], "ru", $arTransParams).'-'.$id,
                );
            }
            $el = new \CIBlockElement;
            $PRODUCT_ID = $id;  // изменяем элемент с кодом (ID) 2
            $el->Update($PRODUCT_ID, $arLoadProductArray);
        }
    }
}