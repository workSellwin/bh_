<?php

/**
 * Created by PhpStorm.
 * User: maspanau
 * Date: 19.04.2019
 * Time: 11:29
 */
class ordersReport
{
    public $arOrder = [];
    public $catalogID = 2;
    public $pathFile = '';
    public $sendEmail = '';
    public $interval = 10;
    public $date_to;
    public $date_from;

    public function __construct($SEND_EMAIL)
    {
        \CModule::IncludeModule("sale");
        \CModule::IncludeModule("iblock");
        $this->sendEmail = $SEND_EMAIL;
    }

    /**
     * получить массив
     * @param $TIME
     * @return array
     */
    public function getArOrdersReport($TIME)
    {
        return $this->formatData($this->getListOrders($TIME));
    }

    //получить массив
    public function getFileOrdersReport($TIME)
    {
        global $DB;
        $this->date_to = date($DB->DateFormatToPHP(\CSite::GetDateFormat("SHORT")), $TIME);
        $this->date_from = date('d.m.Y');
        $data['HEAD'] = ['ID заказа', 'ФИО', 'Тип доставки', 'Тел', 'E-mail', 'Внешний код товара', 'Название товара', 'Бренд', 'Серия', 'Тип продукта'];
        $this->getListOrders();
        $data['BODY'] = $this->formatData();
        $this->SetExcel($data);
    }

    public function getFileOrdersReportInterval($DATE_TO)
    {
        $this->date_to = $DATE_TO;
        $this->date_from = date('d.m.Y', strtotime($DATE_TO . ' + ' . $this->interval . ' days'));
        $data['HEAD'] = ['ID заказа', 'ФИО', 'Тип доставки', 'Тел', 'E-mail', 'Внешний код товара', 'Название товара', 'Бренд', 'Серия', 'Тип продукта'];
        $this->getListOrders();
        $data['BODY'] = $this->formatData();
        $this->SetExcel($data);
    }

    //получить список заказов
    protected function getListOrders()
    {
        //echo date('d.m.Y', strtotime($this->date . ' + ' . $this->interval . ' days'));
        $arFilter = Array(
            '>=DATE_INSERT' => $this->date_to,
            '<=DATE_INSERT' => $this->date_from,
            'PERSON_TYPE_ID' => 1,
        );

        $rsSales = \CSaleOrder::GetList(array("DATE_INSERT" => "desc"), $arFilter);
        while ($arSales = $rsSales->Fetch()) {
            //ID заказа
            $this->arOrder[$arSales['ID']]['ID'] = $arSales['ID'];

            //тип доставки
            $arDeliv = \CSaleDelivery::GetByID($arSales['DELIVERY_ID']);
            if ($arDeliv) {
                $this->arOrder[$arSales['ID']]['DELIVERY_NAME'] = $arDeliv['NAME'];
            }
            //свойства заказа
            $this->setOrderPropsValue($arSales['ID']);
            //товар заказа
            $this->arOrder[$arSales['ID']]['PROD'] = $this->BasketOrder($arSales['ID']);
        }
    }

    protected function dateUpdate()
    {

    }

    protected function setOrderPropsValue($ORDER_ID)
    {
        $db_props = \CSaleOrderPropsValue::GetOrderProps($ORDER_ID);
        $arProps = [];
        while ($arPropOrder = $db_props->Fetch()) {
            $arProps[$arPropOrder['CODE']] = $arPropOrder['VALUE'];
        }
        $arKey = ['FIO', 'PHONE', 'EMAIL'];
        foreach ($arKey as $key) {
            $this->arOrder[$ORDER_ID][$key] = $arProps[$key];
        }
    }

    protected function BasketOrder($ORDER_ID)
    {
        if($ORDER_ID){
            $dbBasket = \CSaleBasket::GetList(Array("ID" => "ASC"), Array("ORDER_ID" => $ORDER_ID));
            $elem_id = [];
            while ($arBasket = $dbBasket->Fetch()) {
                $elem_id[] = $arBasket['PRODUCT_ID'];
            }
            return $this->getListElement($elem_id, $ORDER_ID);
        }
    }

    protected function getListElement($ELEM_ID, $ORDER_ID)
    {

        $arElements = [];
        if(!empty($ELEM_ID)) {
            $arSelect = Array("ID", "NAME", "IBLOCK_ID", "CODE", "PROPERTY_BRANDS", "PROPERTY_SERIES", "PROPERTY_productype", "XML_ID");
            $arFilter = Array("IBLOCK_ID" => $this->catalogID, 'ID' => $ELEM_ID);
            $res = \CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            while ($ob = $res->GetNext()) {
                $arElements[$ORDER_ID][$ob['ID']]['XML_ID'] = $ob['XML_ID'];
                $arElements[$ORDER_ID][$ob['ID']]['NAME'] = $ob['NAME'];
                $arElements[$ORDER_ID][$ob['ID']]['BRANDS'] = $ob['PROPERTY_BRANDS_VALUE'];
                $arElements[$ORDER_ID][$ob['ID']]['SERIES'] = $ob['PROPERTY_SERIES_VALUE'];
                $arElements[$ORDER_ID][$ob['ID']]['PRODUCTYPE'] = $ob['PROPERTY_PRODUCTYPE_VALUE'];
            }
        }
        return $arElements;
    }

    protected function formatData()
    {
        $arData = $this->arOrder;
        $arDataNew = [];
        $i = 0;
        foreach ($arData as $key => $value) {
            if (!empty($value['PROD'])) {
                foreach ($value['PROD'][$value['ID']] as $k => $val) {
                    $arDataNew[$i]['ID'] = $value['ID'];
                    $arDataNew[$i]['FIO'] = $value['FIO'];
                    $arDataNew[$i]['DELIVERY_NAME'] = $value['DELIVERY_NAME'];
                    $arDataNew[$i]['PHONE'] = 'тел: ' . $value['PHONE'];
                    $arDataNew[$i]['EMAIL'] = $value['EMAIL'];
                    $arDataNew[$i]['XML_ID'] = $val['XML_ID'];
                    $arDataNew[$i]['NAME'] = $val['NAME'];
                    $arDataNew[$i]['BRANDS'] = $val['BRANDS'];
                    $arDataNew[$i]['SERIES'] = $val['SERIES'];
                    $arDataNew[$i]['PRODUCTYPE'] = $val['PRODUCTYPE'];
                    $i++;
                }
            }
        }
        return $this->arOrder = $arDataNew;
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
        $this->pathFile = $_SERVER['DOCUMENT_ROOT'] . '/local/OrderReport/' . date('d-m-Y-H-i-s') . '-OrderReport.xlsx';
        //$this->pathFile = $_SERVER['DOCUMENT_ROOT'] . '/local/OrderReport/' . $this->date_to . '-' . $this->date_from . '-OrderReport.xlsx';
        $writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save($this->pathFile);
        $this->eventEmail($this->sendEmail);
        $this->delFile($this->pathFile);
    }

    protected function eventEmail($SEND_EMAIL)
    {
        $arEventFields = array(
            "send_email" => $SEND_EMAIL
        );
        \CEvent::Send("ORDER_REPORT_MAIL", array('s1', 's2'), $arEventFields, 'Y', '', array($this->pathFile));
    }

    protected function delFile($PATH_FILE)
    {
        if (file_exists($PATH_FILE)) {
            unlink($PATH_FILE);
        }
    }
}
