<?php
if (!function_exists('GetUserIsID')) {
    /**
     * @param $id
     * @return array
     */
    function GetUserIsID($id)
    {
        $arResult = [];
        if ($id) {
            $filter = ['ID' => $id];
            $rsUsers = \CUser::GetList(($by = "ID"), ($order = "ASC"), $filter, ['SELECT' => ['UF_*']]);
            $arResult = $rsUsers->Fetch();
        }
        return $arResult;
    }
}

if (!function_exists("getChilds")) {
    function getChilds($input, &$start = 0, $level = 0)
    {
        if (!$level) {
            $lastDepthLevel = 1;
            if (is_array($input)) {
                foreach ($input as $i => $arItem) {
                    if ($arItem["DEPTH_LEVEL"] > $lastDepthLevel) {
                        if ($i > 0) {
                            $input[$i - 1]["IS_PARENT"] = 1;
                        }
                    }
                    $lastDepthLevel = $arItem["DEPTH_LEVEL"];
                }
            }
        }
        $childs = array();
        $count = count($input);
        for ($i = $start; $i < $count; $i++) {
            $item = $input[$i];
            if ($level > $item['DEPTH_LEVEL'] - 1) {
                break;
            } elseif (!empty($item['IS_PARENT'])) {
                $i++;
                $item['CHILD'] = getChilds($input, $i, $level + 1);
                $i--;
            }
            $childs[] = $item;
        }
        $start = $i;
        return $childs;
    }
}

if (!function_exists('GetUserIsPhone')) {
    /**
     * @param $phone
     * @return array
     */
    function GetUserIsPhone($phone)
    {
        $arResult = [];
        if ($phone) {
            $phone = str_replace(' ', '', $phone);
            $p1 = substr($phone, 0, 4);
            $p2 = substr($phone, 4, 2);
            $p3 = substr($phone, 6, 3);
            $p4 = substr($phone, 9, 2);
            $p5 = substr($phone, 11, 2);
            $phoneSearch = $p1 . ' ' . $p2 . ' ' . $p3 . ' ' . $p4 . ' ' . $p5;
            $phoneSearch = trim($phoneSearch);
            $filter = ['PERSONAL_PHONE' => $phoneSearch];
            $rsUsers = \CUser::GetList(($by = "ID"), ($order = "ASC"), $filter, ['SELECT' => ['UF_*']]);
            $arResult = $rsUsers->Fetch();
        }
        return $arResult;
    }
}

if (!function_exists('GetEntityDataClass')) {
    /**
     * @param $HlBlockId
     * @return \Bitrix\Main\Entity\DataManager|bool
     * @throws \Bitrix\Main\SystemException
     */
    function GetEntityDataClass($HlBlockId)
    {
        if (empty($HlBlockId) || $HlBlockId < 1) {
            return false;
        }
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($HlBlockId)->fetch();
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }
}

/**
 * @param $o
 * @param bool $show
 * @param bool $die
 * @return bool
 */
function PR($o, $show = false, $die = false)
{
    global $USER, $APPLICATION;

    if (isset($_REQUEST['DEBUG']) and $_REQUEST['DEBUG'] == 'Y') {
        $show = true;
    }

    if ($die) {
        $APPLICATION->RestartBuffer();
    }

    if ((is_object($USER) and $USER->isAdmin()) || $show) {
        $bt = debug_backtrace();
        $bt = $bt[0];
        $dRoot = $_SERVER["DOCUMENT_ROOT"];
        $dRoot = str_replace("/", "\\", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
        $dRoot = str_replace("\\", "/", $dRoot);
        $bt["file"] = str_replace($dRoot, "", $bt["file"]);
        ?>
        <div style='font-size: 12px;font-family: monospace;width: 100%;color: #181819;background: #EDEEF8;border: 1px solid #006AC5;'>
            <div style='padding: 5px 10px;font-size: 10px;font-family: monospace;background: #006AC5;font-weight:bold;color: #fff;'>
                File: <?= $bt["file"] ?> [<?= $bt["line"] ?>]
            </div>
            <pre style='padding:10px;text-align: left'><? print_r($o) ?></pre>
        </div>
        <?
    } else {
        return false;
    }
    if ($die) {
        die();
    }
}

if (!function_exists('inSalon')) {
    /**
     * @param string $section_name
     * @return bool
     */
    function inSalon($section_name = "")
    {
        CModule::IncludeModule("iblock");
        $res = CIBlockSection::GetList(['SORT' => 'ID'], array("IBLOCK_ID" => 10, "NAME" => $section_name), false, array("*"));
        if ($arSect = $res->Fetch()) {
            if ($arSect["ID"] && $section_name == $arSect["NAME"]) {
                return $arSect;
            }
        }
        return false;
    }
}

if (!function_exists('getWorkingDay')) {
    /**
     * ближайший рабочий день
     *
     * @param string $date
     * @param bool $plusDay
     * @return string
     * @throws Exception
     */
    function getWorkingDay($date = "", $plusDay = false)
    {
        $working = array("04.05.2019", "11.05.2019", "16.11.2019");
        $weekend = array("08.03.2019", '01.05.2019', '06.05.2019', '07.05.2019', '08.05.2019', '09.05.2019', '03.07.2019', '07.11.2019', '08.11.2019','09.11.2019', '25.12.2019');

        $flag = true;
        $data_send = new DateTime($date);
        if ($plusDay) {
            $data_send->modify('+1 days');
        }
        do {
            $date_res_week = $data_send->format('w');
            $date_res_if = $data_send->format('d.m.Y');

            if (in_array($date_res_if, $working)) {
                $date_res = $data_send->format('d.m.Y');
                $flag = false;
            } else if ($date_res_week != "6" && $date_res_week != "0" && !in_array($date_res_if, $weekend)) {
                $date_res = $data_send->format('d.m.Y');
                $flag = false;
            }
            $data_send->modify('+1 days');
        } while ($flag);
        return $date_res;
    }
}

if (!function_exists('FuncSendSms')) {
    /**
     * @param $userPhone
     * @param $mes
     * @return array
     */
    function FuncSendSms($userPhone, $mes)
    {
        $arSend = [];
        if (CModule::IncludeModule("mlife.smsservices")) {
            $obSmsServ = new \Mlife\Smsservices\Sender();
            $phoneCheck = $obSmsServ->checkPhoneNumber($userPhone);
            $userPhone = $phoneCheck['phone'];
            if ($phoneCheck['check']) {
                $obSmsServ->sendSms($userPhone, $mes);
            }
        }
        return $arSend;
    }
}

if (!function_exists('downcounter')) {
    /**
     * @param $date
     * @return bool
     */
    function downcounter($date)
    {
        if (!empty($date)) {
            $check_time = strtotime($date) - time();
            if ($check_time <= 0) {
                return false;
            }

            $res["DAYS"] = floor($check_time / 86400);
            $res["HOURS"] = floor(($check_time % 86400) / 3600);
            $res["MINUTES"] = floor(($check_time % 3600) / 60);
            $res["SECONDS"] = $check_time % 60;

            return $res;
        } else {
            return false;
        }
    }
}

if (!function_exists('getProductSale')) {
    /**
     * @param $ID_PRODUCT
     * @return bool
     */
    function getProductSale($ID_PRODUCT)
    {
        global $USER;
        CModule::IncludeModule("sale");
        CModule::IncludeModule("catalog");
        $arDiscounts = CCatalogDiscount::GetDiscountByProduct(
            $ID_PRODUCT,
            $USER->GetUserGroupArray()
        );
        $i = 0;
        $idSale = false;
        foreach ($arDiscounts as $keySale => $itemSale) {
            if (time() >= strtotime($itemSale['ACTIVE_FROM']) && $itemSale['ACTIVE_TO']) {
                $date_to = $itemSale['ACTIVE_TO'];
                if ($i == 0) {
                    $idSale = $keySale;
                } else {
                    if (strtotime($date_to) > strtotime($itemSale['ACTIVE_TO'])) {
                        $idSale = $keySale;
                    }
                }
                $i++;
            }
        }
        if ($idSale) {
            return $arDiscounts[$idSale];
        } else {
            return false;
        }

    }
}

if (!function_exists('GmiPrint')) {
    /**
     * @param $res
     */
    function GmiPrint($res)
    {
        global $USER;
        if ($USER->IsAdmin()) {
            echo '<pre style="text-align:left; font-size:14px">';
            print_r($res);
            echo "</pre>";
        }
    }
}

if (!function_exists('returnQuantityProduct')) {
    /**
     * @param $PRODUCT_ID
     * @return int
     */
    function returnQuantityProduct($PRODUCT_ID)
    {
        $res = CCatalogProduct::GetList(array(), array("ID" => $PRODUCT_ID), false, array(), array());
        if ($ob = $res->GetNext()) {
            return $ob["QUANTITY"];
        } else {
            return 0;
        }
    }
}


if (!function_exists('getBarCode')) {
    /**
     * @param $ID
     * @return bool
     */
    function getBarCode($ID)
    {
        CModule::IncludeModule("iblock");
        $res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => array(2, 3), 'ID' => $ID), false, Array(), array("NAME", "ID", 'PROPERTY_strish_kod'));
        if ($ob = $res->GetNext()) {
            return $ob['PROPERTY_STRISH_KOD_VALUE'];
        } else {
            return false;
        }
    }
}

if (!function_exists('searchByBarCodeArray')) {
    /**
     * @param $barCode
     * @return array
     */
    function searchByBarCodeArray($barCode)
    {
        $arID = [];
        CModule::IncludeModule("iblock");
        CModule::IncludeModule("sale");
        CModule::IncludeModule("catalog");
        $res = CIBlockElement::GetList(Array(), array("IBLOCK_ID" => array(2, 3), 'PROPERTY_strish_kod' => $barCode), false, Array(), array("NAME", "ID"));
        while ($ob = $res->GetNext()) {
            $arID[] = $ob['ID'];
        }
        return $arID;
    }
}

if (!function_exists('getBrowser')) {

    function getBrowser()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE/i', $agent) && !preg_match('/Opera/i', $agent)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/Firefox/i', $agent)) {
            $browser = 'Mozilla Firefox';
        } elseif (preg_match('/Chrome/i', $agent)) {
            $browser = 'Google Chrome';
        } elseif (preg_match('/Safari/i', $agent)) {
            $browser = 'Apple Safari';
        } elseif (preg_match('/Opera/i', $agent)) {
            $browser = 'Opera';
        } elseif (preg_match('/Opera Mini/i', $agent)) {
            $browser = 'Opera Mini';
        } elseif (preg_match('/Netscape/i', $agent)) {
            $browser = 'Netscape';
        } else {
            $browser = 'Неизвестно';
        }
        return $browser;
    }
}

if (!function_exists('searchByBarCode')) {
    /**
     * @param $barCode
     * @return array
     */
    function searchByBarCode($barCode)
    {
        CModule::IncludeModule("iblock");
        CModule::IncludeModule("sale");
        CModule::IncludeModule("catalog");
        $res = CIBlockElement::GetList(Array('ID' => 'ASC'), array("IBLOCK_ID" => array(2, 3), 'PROPERTY_strish_kod' => $barCode), false, Array(), array("NAME", "ID"));
        $arCode = [];
        while ($ob = $res->GetNext()) {
            $arCode[] = $ob['ID'];
        }
        return $arCode;
    }
}

if (!function_exists('AddOrderProperty')) {
    /**
     * @param $prop_id
     * @param $value
     * @param $order
     * @return bool
     */
    function AddOrderProperty($prop_id, $value, $order)
    {
        if (!strlen($prop_id)) {
            return false;
        }
        if (CModule::IncludeModule('sale')) {
            if ($arOrderProps = CSaleOrderProps::GetByID($prop_id)) {
                $db_vals = CSaleOrderPropsValue::GetList(array(), array('ORDER_ID' => $order, 'ORDER_PROPS_ID' => $arOrderProps['ID']));
                if ($arVals = $db_vals->Fetch()) {
                    return CSaleOrderPropsValue::Update($arVals['ID'], array(
                        'NAME' => $arVals['NAME'],
                        'CODE' => $arVals['CODE'],
                        'ORDER_PROPS_ID' => $arVals['ORDER_PROPS_ID'],
                        'ORDER_ID' => $arVals['ORDER_ID'],
                        'VALUE' => $value,
                    ));
                } else {
                    return CSaleOrderPropsValue::Add(array(
                        'NAME' => $arOrderProps['NAME'],
                        'CODE' => $arOrderProps['CODE'],
                        'ORDER_PROPS_ID' => $arOrderProps['ID'],
                        'ORDER_ID' => $order,
                        'VALUE' => $value,
                    ));
                }
            }
        }
    }
}
if (!function_exists('resizeText')) {
    function resizeText($text, $length){
        $string = strip_tags($text);
        $string = substr($string, 0, $length);
        $string = rtrim($string, "!,.-");
        $string = substr($string, 0, strrpos($string, ' '));
        return $string."… ";
    }
}
