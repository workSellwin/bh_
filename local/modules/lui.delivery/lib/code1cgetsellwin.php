<?php


namespace Lui\Delivery;


class Code1cGetSellwin
{

    public static function GetAllActive()
    {
        $ar1CID = [];
        $ctx = stream_context_create(array(
                'http' => array(
                    'timeout' => 5
                )
            )
        );

        $arData = json_decode(file_get_contents('http://api.sellwin.by/BHService/Requests.svc/Numbers', 0, $ctx), true);
        if ($arData) {
            $arTemp = [];
            foreach ($arData as $dt) {
                $arTemp[$dt['BitNo']][] = $dt['DocNo'];
            }
            foreach ($arTemp as $id => $v) {
                $ar1CID[$id] = implode(', ', $v);
            }
        }

        return $ar1CID;
    }

    public static function GetID($ID)
    {
        $sId = '';
        $ctx = stream_context_create(array(
                'http' => array(
                    'timeout' => 5
                )
            )
        );
        $arData = json_decode(file_get_contents('http://api.sellwin.by/BHService/Requests.svc/Numbers/'.$ID, 0, $ctx), true);
        if ($arData) {
            $arTemp = [];
            foreach ($arData as $dt) {
                $arTemp[] = $dt['DocNo'];
            }
            $sId = implode(', ', $arTemp);
        }
        return $sId;
    }

}
