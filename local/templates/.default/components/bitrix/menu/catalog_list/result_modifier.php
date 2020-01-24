<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arSectionId = [];
foreach ($arResult as $arItems) {
    if ($arItems['PARAMS']['SECTION_ID']) {
        $arSectionId[] = $arItems['PARAMS']['SECTION_ID'];
    }
}


$arFilter = Array('IBLOCK_ID'=>2, 'GLOBAL_ACTIVE'=>'Y', 'ACTIVE'=>'Y', 'DEPTH_LEVEL'=>[1,2]);
$Select = Array('IBLOCK_ID', 'ID', 'NAME', 'CODE', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID', 'SECTION_PAGE_URL', 'UF_BRANDS', 'UF_SERII', 'UF_IMG_1', 'UF_IMG_2', 'UF_IMG_3');

$PR_SER = array();
$db_list = CIBlockSection::GetList(Array('sort'=>'asc'), $arFilter, true, $Select);
while($ar_result = $db_list->GetNext()) {

    foreach ($arResult as &$arItems) {
        if ($arItems['PARAMS']['SECTION_ID'] == $ar_result['IBLOCK_SECTION_ID'] && $ar_result['DEPTH_LEVEL']==2 ) {
            $arItems['CHILD'][]=$ar_result;
        }
        if($arItems['PARAMS']['SECTION_ID'] == $ar_result['ID'] && $ar_result['DEPTH_LEVEL'] == 1){
           //PR($ar_result);
           if(!empty($ar_result['UF_BRANDS'])){
               foreach ($ar_result['UF_BRANDS']as $val){
                   $brend = explode('|', trim($val));
                   $arItems['BRANDS'][trim($brend[0])]=trim($brend[1]);
               }
           }

            if(!empty($ar_result['UF_SERII'])){
                foreach ($ar_result['UF_SERII']as $val){
                    $serii = explode('|', trim($val));
                    $arItems['SERII'][trim($serii[0])]=trim($serii[1]);
                    if($serii[2]){
                        $arItems['BR_SR'][trim($serii[2])][trim($serii[0])]['NAME']=trim($serii[0]);
                        $arItems['BR_SR'][trim($serii[2])][trim($serii[0])]['URL']=trim($serii[1]);
                    }
                }
            }

            if($ar_result['UF_IMG_1'] && $ar_result['UF_IMG_2']){
                $arItems['IMG'][]= CFile::GetPath($ar_result['UF_IMG_1']);
                $arItems['IMG'][]= CFile::GetPath($ar_result['UF_IMG_2']);
            }

            $arItems['IMG']['CATALOG_IMG']= CFile::GetPath($ar_result['UF_IMG_3']);

        }
    }
}

?>