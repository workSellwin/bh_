<?php
/**
 *
 */
function OnPageStart()
{
    if ($_REQUEST['TYPE'] == 'SEND_PWD') {
        $_SESSION['USER_MESSAGE_STR'] = '';
        if (strpos($_REQUEST['USER_EMAIL'], '@') === false) {
            $arUser = GetUserIsPhone($_REQUEST['USER_EMAIL']);
            if ($arUser) {
                $code = randString(4, ["ABCDEFGHIJKLNMOPQRSTUVWX­YZ", "0123456789"]);
                $user = new CUser;
                $user->Update($arUser['ID'], ['UF_CODE_SEND_PWD' => $code]);
                FuncSendSms($_REQUEST['USER_EMAIL'], 'CODE: ' . $code);
                $_SESSION['CODE_SEND_PWD'] = $code;
                LocalRedirect('/auth/?change_password=yes&phone=Y&USER_ID=' . $arUser['ID']);
            }
        }
    }

    if ($_REQUEST['TYPE'] == 'CHANGE_PWD_PHONE' and $_REQUEST['USER_ID'] > 0) {
        if ($_SESSION['CODE_SEND_PWD'] == $_REQUEST['USER_CODE_SMS']) {
            $arUser = GetUserIsID($_REQUEST['USER_ID']);
            if ($arUser['UF_CODE_SEND_PWD'] == $_REQUEST['USER_CODE_SMS']) {
                $user = new CUser;
                $fields = Array(
                    "PASSWORD" => $_REQUEST['USER_PASSWORD'],
                    "CONFIRM_PASSWORD" => $_REQUEST['USER_CONFIRM_PASSWORD'],
                );
                $user->Update($arUser['ID'], $fields);
                $strError = $user->LAST_ERROR;
                if ($strError) {
                    $_SESSION['USER_MESSAGE_STR'] = $strError;
                } else {
                    $_SESSION['USER_MESSAGE_STR'] = 'Пароль изменён';
                    //$user->Authorize($arUser['ID']);
                }
            }
        }
    }
}



/**
 * @param $arParams
 */
function OnAfterUserLogin(&$arParams)
{
    if (strpos($arParams['LOGIN'], '@') === false) {
        global $APPLICATION, $USER;
        $arUser = GetUserIsPhone(trim($arParams['LOGIN']));
        if (!is_object($USER)) $USER = new CUser;
        if ($arUser['ID']) {
            // PR($arUser,true);
            $arAuthResult = $USER->Login($arUser['LOGIN'], $arParams['PASSWORD'], $arParams['REMEMBER'], $arParams['PASSWORD_ORIGINAL']);
            $APPLICATION->arAuthResult = $arAuthResult;
            //PR($arAuthResult,true);
            //PR($arParams,true);
            /// // +375 29 606 71 66 e.gisak@sellwin.by 111111
            if ($arAuthResult) {
                $arParams = [
                    'LOGIN' => $arParams['LOGIN'],
                    'PASSWORD' => $arParams['PASSWORD'],
                    'REMEMBER' => $arParams['REMEMBER'],
                    'PASSWORD_ORIGINAL' => $arParams['PASSWORD_ORIGINAL'],
                    'OTP' => $arParams['OTP'],
                    'USER_ID' => $arUser['ID'],
                    'CAPTCHA_WORD' => $arParams['CAPTCHA_WORD'],
                    'CAPTCHA_SID' => $arParams['CAPTCHA_SID'],
                    'RESULT_MESSAGE' => true,
                    'TYPE' => 'OK',
                ];
            }
        }
    }
}


function OnPageStartCheck()
{
    if ($_REQUEST['action'] == 'check_requests') {
        $arID = $_REQUEST['ID'];
        $urlTpl = '/bitrix/admin/sale_order_print_new.php?ORDER_ID=#ID#&doc=order_form&' . bitrix_sessid_get();
        $arUrl=[];
        foreach ($arID as $id) {
            $url= str_replace('#ID#', $id, $urlTpl);
            $arUrl[]="window.open('{$url}');";
        }
        $script=implode(' ',$arUrl);
        echo <<<SCRIPT
        <script>
            $script
        </script>
SCRIPT;
    }
}

function MyOnAdminListDisplay(&$list)
{
    if ('tbl_sale_order' == $list->table_id) {
        $list->arActions['check_requests'] = 'Выгрузка чеков заказов';
        /*   foreach ($list->aRows as $row) {
               $arActions = $row->aActions;
               $arActions[] = array(
                   "ICON" => "Excell",
                   "TEXT" => 'Выгрузка чека',
                   "TITLE" => 'doc',
                   "ACTION" => 'check_request_one'
               );
               $row->AddActions($arActions);
           }*/
    }
}


/**
 * @param $arTime
 */
function ReplaseTimeInterval40(&$arTime)
{
    foreach ($arTime as &$prop) {
        if ($prop['ID'] == 41) {
            if (!empty($prop['OPTIONS'])) {
                $date_insert_h = date('H'); // часы создания заказа
                $date_insert_i = date('i'); // минуты создания заказа
                $insertMinut = $date_insert_h * 60 + $date_insert_i;
                if ($insertMinut >= 10 * 60 + 0 && $insertMinut <= 16 * 60 + 0) {
                    unset($prop['OPTIONS']['e12']);
                    unset($prop['OPTIONS']['e13']);
                    unset($prop['OPTIONS']['e14']);
                    unset($prop['OPTIONS']['e15']);
                    unset($prop['OPTIONS']['e16']);
                    unset($prop['OPTIONS']['e17']);

                    unset($prop['OPTIONS_SORT'][0]);
                    unset($prop['OPTIONS_SORT'][1]);
                    unset($prop['OPTIONS_SORT'][2]);
                    unset($prop['OPTIONS_SORT'][3]);
                    unset($prop['OPTIONS_SORT'][4]);
                    unset($prop['OPTIONS_SORT'][5]);
                    $prop['OPTIONS_SORT'] = array_values($prop['OPTIONS_SORT']);
                }
            }
        }
    }
}

function ModifiAdminMenu(&$adminMenu, &$moduleMenu){

    $moduleMenu[] = array(
        "parent_menu" => "global_menu_services", // в раздел "Сервис"
        "section" => "ваше название",
        "sort"        => 100,
        "url"         => "/bitrix/admin/smsNotification_options.php?lang=" . LANG,
        "text"        => 'Настройка напоминаний по sms',
        "title"       => 'Всплывашка для пункта меню',
        "icon"        => "form_menu_icon", // малая иконка
        "page_icon"   => "form_page_icon", // большая иконка
        "items_id"    => "menu_ваше название",  // идентификатор ветви
        "items"       => array()          // остальные уровни меню

    );
}

//привязываем свойство бренд
function OnBeforeIBlockElementUpdateHandlerBrands(&$arFields)
{
    if($arFields["IBLOCK_ID"]==2)
    {

        CModule::IncludeModule('iblock');
        //привязка бренда
        $id_brend = '';
        $brend_serii = [];
        if($arFields['PROPERTY_VALUES'][197] && !empty($arFields['PROPERTY_VALUES'][197])){
            foreach ($arFields['PROPERTY_VALUES'][197] as &$valProp){
                if(!$valProp['VALUE']){
                    if($arFields['PROPERTY_VALUES'][60] && !empty($arFields['PROPERTY_VALUES'][197])){
                        foreach ($arFields['PROPERTY_VALUES'][60] as $val){
                            if($val['VALUE']){
                                $arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_SERIES");
                                $arFilter = Array("IBLOCK_ID"=>35, 'NAME'=>$val['VALUE']);
                                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                                while($ob = $res->GetNext())
                                {
                                    if($ob['ID']){
                                        $id_brend = $ob['ID'];
                                        $valProp['VALUE'] = $ob['ID'];
                                        $brend_serii[]= $ob['PROPERTY_SERIES_VALUE'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //привязка серии
        if($arFields['PROPERTY_VALUES'][198] && !empty($arFields['PROPERTY_VALUES'][198])){
            foreach ($arFields['PROPERTY_VALUES'][198] as &$valProp){
                if(!$valProp['VALUE']){
                    if($arFields['PROPERTY_VALUES'][64] && !empty($arFields['PROPERTY_VALUES'][198])){
                        foreach ($arFields['PROPERTY_VALUES'][64] as $val){
                            if($val['VALUE']){
                                $arSelect = Array("ID", "NAME", "IBLOCK_ID");
                                $arFilter = Array("IBLOCK_ID"=>36, 'NAME'=>$val['VALUE']);
                                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                                while($ob = $res->GetNext())
                                {
                                    if($ob['ID']){
                                        $valProp['VALUE'] = $ob['ID'];
                                    }
                                }

                                //нет серии создаём и привязываем к товару и бренду
                                if(!$valProp['VALUE']){
                                    $el = new CIBlockElement;
                                    $code = mb_strtolower(trim($val['VALUE']));
                                    $code = str_replace(' ', '-', $code);
                                    $arLoadProductArray = Array(
                                        "IBLOCK_ID"      => 36,
                                        "NAME"           => $val['VALUE'],
                                        "ACTIVE"         => "Y",
                                        "CODE"           => $code,
                                    );
                                    if($PRODUCT_ID = $el->Add($arLoadProductArray)){
                                        $valProp['VALUE'] = $PRODUCT_ID;
                                        if($id_brend == '' && empty($brend_serii)){
                                            if($arFields['PROPERTY_VALUES'][60]){
                                                foreach ($arFields['PROPERTY_VALUES'][60] as $val){
                                                    if($val['VALUE']){
                                                        $arSelect = Array("ID", "NAME", "IBLOCK_ID", "PROPERTY_SERIES");
                                                        $arFilter = Array("IBLOCK_ID"=>35, 'NAME'=>$val['VALUE']);
                                                        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                                                        while($ob = $res->GetNext())
                                                        {
                                                            if($ob['ID']){
                                                                $id_brend = $ob['ID'];
                                                                $brend_serii[]= $ob['PROPERTY_SERIES_VALUE'];
                                                            }
                                                        }
                                                        $brend_serii[]=$PRODUCT_ID;
                                                        CIBlockElement::SetPropertyValuesEx($id_brend, false, array('SERIES' => $brend_serii));
                                                    }
                                                }
                                            }

                                        }else{
                                            $brend_serii[]=$PRODUCT_ID;
                                            CIBlockElement::SetPropertyValuesEx($id_brend, false, array('SERIES' => $brend_serii));
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        //return false;
    }
}