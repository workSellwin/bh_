<?php


function updateProductCountAgent()
{
	ini_set('max_execution_time', '600');
	
    Cmodule::IncludeModule('catalog');
    //$json = file_get_contents('http://evesell.sellwin.by/eve-adapter-sellwin/stockforstore/json');
    $json = file_get_contents('http://evesell.sellwin.by/eve-adapter-sellwin/stockforstorefull/json');
    $jsonRes = json_decode($json, true);

    // ob_start();
    // print_r($jsonRes);
    // $res99 = ob_get_clean();
    // AddMessage2Log("\n\n".$res99."\n");


    $today = date("H:i:s d-m-Y");
    $count = 0;
    $count2 = 0;
    if (count($jsonRes["dataStore"]) > 0) {
        //ob_start();
        foreach ($jsonRes["dataStore"] as $key => $value) {
            $res = CCatalogProduct::GetList(array(), array("ELEMENT_XML_ID" => $value["good"]), false, array(), array());
            if ($ob = $res->GetNext()) {
                $arIds[] = $ob["ID"];
                if ($ob["QUANTITY"] != $value["count"]) {
                    if (CCatalogProduct::Update($ob["ID"], array('QUANTITY' => $value["count"]))) {
                        $count++;
                    }
                }
            }
            // print_r($value);
            // print_r($ob);
            // echo "\n\n------------------------";
        }
        //$res99 = ob_get_clean();

        $res2 = CCatalogProduct::GetList(array(), array("!ID" => $arIds, "!QUANTITY" => 0), false, false);
        while ($ob = $res2->GetNext()) {
            if (CCatalogProduct::Update($ob["ID"], array('QUANTITY' => 0))) {
                $count2++;
            }
        }
        //AddMessage2Log($res99);
        AddMessage2Log("\n\n" . $today . " Обновленно " . $count . " товаров, и для " . $count2 . " проставлен остаток 0 шт.\n");
    }
    return "updateProductCountAgent();";
}


function CreatGoogleFile(){
    $obGoogleFile = new creatGoogleFile();
    $obGoogleFile->GetGoogleFile();
    return "CreatGoogleFile();";
}

function UpdataExportFileOnliner (){
    $onliner = new exportOnlinerNew();
    $onliner -> process();
    return "UpdataExportFileOnliner();";
}