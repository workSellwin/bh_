<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arParams["TEMPLATE_THEME"] = "blue";


$arParams["FILTER_VIEW_MODE"] = (isset($arParams["FILTER_VIEW_MODE"]) && toUpper($arParams["FILTER_VIEW_MODE"]) == "HORIZONTAL") ? "HORIZONTAL" : "VERTICAL";
$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "left";


$arResult['PROP_FILTER']=[];
foreach ($arResult['ITEMS'] as $k => $arItems){
    if($arItems['PRICE'] == 1){
        foreach ($arItems['VALUES'] as $key => $items){
            if(round($items['VALUE']) != $items['HTML_VALUE'] && $items['HTML_VALUE'] ){
                if($key == 'MIN'){
                    $items['NAME']= $arItems['NAME']." от ";
                }
                if ($key == 'MAX'){
                    $items['NAME']= $arItems['NAME']." до ";
                }
                $items['CODE'] = $arItems['CODE'];
                $items['ENCODED_ID'] = $arItems['ENCODED_ID'];
                $items['TYPE'] = $key;
                $arResult['PROP_FILTER']['PRICE'][$arItems['ID']][]=$items;
            }else{
                $arResult['DEF_PRICE'][$arItems['ENCODED_ID']][$key]=$items['VALUE'];
            }

        }
    }else{
        foreach ($arItems['VALUES'] as $key => $items){
            if($items['CHECKED'] == 1){
                $arResult['PROP_FILTER']['PROP'][$arItems['ID']][]=$items;
            }
        }
    }
}