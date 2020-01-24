<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arSectionId = [];
foreach ($arResult as $arItems) {
    if ($arItems['PARAMS']['SECTION_ID']) {
        $arSectionId[] = $arItems['PARAMS']['SECTION_ID'];
    }
}


$arFilter = Array('IBLOCK_ID'=>2, 'GLOBAL_ACTIVE'=>'Y', 'ACTIVE'=>'Y', 'SECTION_ID' => $arSectionId);
$Select = Array('IBLOCK_ID', 'ID', 'NAME', 'CODE', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID', 'SECTION_PAGE_URL');


$db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true, $Select);
while($ar_result = $db_list->GetNext()) {

    foreach ($arResult as &$arItems) {
        if ($arItems['PARAMS']['SECTION_ID'] == $ar_result['IBLOCK_SECTION_ID']) {
            $arItems['CHILD'][]=$ar_result;
        }
    }
}
?>