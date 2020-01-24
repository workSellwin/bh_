<?php

use InitMainTrait;

class creatGoogleFile
{
    public $IBLOCK_ID = 2;

    public function __construct()
    {
        InitMainTrait::includeModules();
    }

    public function GetGoogleFile(){
        $arSectionId = $this->GetSectionID();
        $data['HEAD'] = ['ID', 'Item title', 'Item subtitle', 'Price', 'Final URL', 'Image URL', 'Sale price'];
        $data['BODY'] = $this->GetProductGoogle($arSectionId);
        $this->SetExcel($data);
    }

    protected function GetSectionID(){
        $uf_arresult = CIBlockSection::GetList(Array("SORT" => "­­ASC"), Array("IBLOCK_ID" => $this->IBLOCK_ID, 'UF_SITE' => 3));
        $arSectionId = [];
        while ($section = $uf_arresult->GetNext()) {
            $arSectionId[] = $section['ID'];
        }
        return $arSectionId;
    }

    protected function GetProductGoogle($arSectionId){
        $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "DETAIL_PAGE_URL", "CATALOG_QUANTITY", "DETAIL_PICTURE","SECTION_ID", "PREVIEW_TEXT", "PRICE_15", 'PROPERTY_NAME_GOOGLE', 'PROPERTY_TEXT_GOOGLE');
        $arFilter = Array("IBLOCK_ID" => $this->IBLOCK_ID, ">CATALOG_QUANTITY"=>0, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y", "SECTION_ID" => $arSectionId, '=PRICE_TYPE' => 15, "!ID" => 25714);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        $arData = [];
        while ($arFields = $res->GetNext()) {
            if($arFields['CATALOG_QUANTITY'] > 0){
                $src = CFile::GetPath($arFields['DETAIL_PICTURE']);
                if(!$src){
                    $src = CFile::GetPath($arFields['PREVIEW_PICTURE']);
                }
                $arPrice = CCatalogProduct::GetOptimalPrice($arFields['ID'], 1, array(2), 'N', false, 's1');
                $arData[] = [
                    'id' => $arFields['ID'],
                    'item_title' => strlen($arFields['PROPERTY_NAME_GOOGLE_VALUE']) <= 25 ?  $arFields['PROPERTY_NAME_GOOGLE_VALUE'] : ''   ,
                    'item_subtitle' => strlen($arFields['PROPERTY_TEXT_GOOGLE_VALUE']) <= 25 ? $arFields['PROPERTY_TEXT_GOOGLE_VALUE'] : ''   ,
                    'price' => $arFields['PRICE_15'],
                    'final_url' => 'https://bh.by' . $arFields['DETAIL_PAGE_URL'],
                    'img' => $src ? 'https://bh.by' . $src : '',
                    'sale_price' => $arPrice['DISCOUNT_PRICE'] ? $arPrice['DISCOUNT_PRICE'] : '',
                ];
            }
        }
        return $arData;
    }

    protected function SetExcel($arData)
    {
        global $APPLICATION;
        $type = 'data';
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("LUIWADJOGS")
            ->setLastModifiedBy("Sellwin Group")
            ->setTitle("Document orders report")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("document for Office 2007 XLSX")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("result file");
        $objWorkSheet = $objPHPExcel->getActiveSheet();/* номер листа */
        $objWorkSheet->setTitle('Data');
        $objPHPExcel->setActiveSheetIndex(0);
        $styleArray = [
            'font' => [
                'bold' => true
            ],
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        ];
        $style = array(
            'font' => array(
                'name' => 'Arial',
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );
        $style2 = array(
            'font' => array(
                'name' => 'Arial',
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
            )
        );
        $arHeader = $arData['HEAD'];
        $objWorkSheet->getStyle('A1:Z1')->applyFromArray($styleArray);
        $objWorkSheet->getStyle('A2:Z1000')->applyFromArray($style);
        $objWorkSheet->getStyle('B2:B1000')->applyFromArray($style2);
        foreach ($arHeader as $i => $head) {
            $buk = strtoupper(chr($i + 97));
            $objWorkSheet->getColumnDimension($buk)->setAutoSize(true);
            $buk = $buk . '1';
            $objWorkSheet->setCellValue($buk, $head);
        }

        $arBody = $arData['BODY'];
        foreach ($arBody as $i => $tr) {
            foreach (array_values($tr) as $k => $td) {
                $buk = strtoupper(chr($k + 97));
                $numb = $i + 2;
                $buk = $buk . $numb;
                $objWorkSheet->setCellValue($buk, $td);
            }
        }
        $pathFile = $_SERVER['DOCUMENT_ROOT'] . '/upload/GoogleProduct/GoogleProduct.xlsx';
        $writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save($pathFile);
    }
}
