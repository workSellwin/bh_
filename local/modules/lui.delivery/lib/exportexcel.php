<?php


namespace Lui\Delivery;


class ExportExcel
{
    public function SetExcel($arResult)
    {
        $arResult['ORDERS'] = array_filter($arResult['ORDERS'], function ($e) {
            if (!isset($e['CONFIG']['ERROR'])) {
                return true;
            }
            return $e['CONFIG']['ERROR'] == 'N' ? true : false;
        });
        $count = count($arResult['ORDERS']);
        global $APPLICATION;
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("LUIWADJOGS")
            ->setLastModifiedBy("Sellwin Group")
            ->setTitle("Document orders report")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("document for Office 2007 XLSX")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("result file");
        $objWorkSheet = $objPHPExcel->getActiveSheet();/* номер листа */
        $objWorkSheet->setTitle('sale_order');
        $objPHPExcel->setActiveSheetIndex(0);

        $endSize = 'N';

        $bold = [
            'font' => [
                'bold' => true,
                'name' => 'Arial',
                'size' => '12',
            ]
        ];

        $italic = [
            'font' => [
                'bold' => false,
                'name' => 'Arial',
                'size' => '12',
            ]];

        $italicM = [
            'font' => [
                'bold' => false,
                'name' => 'Arial',
                'size' => '10',
            ]];

        $alignment = [
            'alignment' => [
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
                'wrap' => true,
            ]
        ];

        $fill = [
            'fill' => [
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => ['rgb' => 'f9ff00'],
            ]
        ];


        $styleBold = array_merge($bold, $alignment);
        $styleItalic = array_merge($italic, $alignment);
        $styleItalicM = array_merge($italicM, $alignment);
        $styleBoldY = array_merge($bold, $alignment, $fill);
        $styleItalicY = array_merge($bold, $alignment, $fill);

        $arHeader = $arResult['HEADER'];
        $objWorkSheet->getStyle('A1:' . $endSize . '1')->applyFromArray($styleBold);


        $arConfStyleColumns = [];
        // ID
        $arConfStyleColumns['A'] = [
            'SIZE' => 12,
            'STYLE' => $styleBold,
        ];
        // Дата получения заказа
        $arConfStyleColumns['B'] = [
            'SIZE' => 13,
            'STYLE' => $styleItalic,
        ];
        // Время получения заказа
        $arConfStyleColumns['C'] = [
            'SIZE' => 14,
            'STYLE' => $styleBold,
        ];
        //  Телефон
        $arConfStyleColumns['D'] = [
            'SIZE' => 17,
            'STYLE' => $styleBold,
        ];
        // Фамилия и имя получателя
        $arConfStyleColumns['E'] = [
            'SIZE' => 20,
            'STYLE' => $styleItalic,
        ];
        // Служба доставки
        $arConfStyleColumns['F'] = [
            'SIZE' => 20,
            'STYLE' => $styleItalic,
        ];
        // Населенный пункт
        $arConfStyleColumns['G'] = [
            'SIZE' => 12,
            'STYLE' => $styleItalic,
        ];
        // Сумма
        $arConfStyleColumns['H'] = [
            'SIZE' => 12,
            'STYLE' => $styleBold,
        ];

        // Комментарии покупателя
        $arConfStyleColumns['I'] = [
            'SIZE' => 20,
            'STYLE' => $styleItalic,
        ];

        // Оплачен
        $arConfStyleColumns['J'] = [
            'SIZE' => 7,
            'STYLE' => $styleItalic,
        ];

        // Яндекс Адрес
        $arConfStyleColumns['K'] = [
            'SIZE' => 30,
            'STYLE' => $styleBold,
        ];

        // lon
        $arConfStyleColumns['L'] = [
            'SIZE' => 10,
            'STYLE' => $styleItalic,
        ];

        // lat
        $arConfStyleColumns['M'] = [
            'SIZE' => 10,
            'STYLE' => $styleItalic,
        ];

        // Яндекс Запрос
        $arConfStyleColumns['N'] = [
            'SIZE' => 30,
            'STYLE' => $styleItalic,
        ];

        // Устанавливаем ширину сталбцов
        foreach ($arConfStyleColumns as $b => $v) {
            $objWorkSheet->getColumnDimension($b)->setWidth($v['SIZE']);
        }
        // Устанавливаем стили для столбцов
        foreach ($arConfStyleColumns as $b => $v) {
            $objWorkSheet->getStyle($b . '2:' . $b . $count)->applyFromArray($v['STYLE']);
        }

        // устанавливаем значения заголовков
        $i = 0;
        foreach ($arHeader as $head) {
            $objWorkSheet->setCellValueByColumnAndRow($i, 1, $head);
            $i++;
        }
        // Заполняем данными
        $arBody = $arResult['ORDERS'];
        $i = 0;
        foreach ($arBody as $tr) {
            // заливка жёлтым цветом
            if ($tr['CONFIG']['YELLOW']) {
                $ind = $i + 2;
                $objWorkSheet->getStyle('A' . $ind . ':' . $endSize . $ind)->getFill()->setFillType($fill['fill']['type']);
                $objWorkSheet->getStyle('A' . $ind . ':' . $endSize . $ind)->getFill()->getStartColor()->setRGB($fill['fill']['color']['rgb']);
            }
            foreach (array_values($tr['ROW']) as $k => $td) {
                // условие на ID
                if ($k == 1) {
                    $num = $td;
                    // Добовляем то что заказ выгружен
                    AddOrderProperty(40, date('d.m.Y H:i:s'), $num);
                    // Шлём на FTP Чек
                    FileDoc::SeveCheckFtp($num);
                    $td = '№ ' . $td;
                }
                // условие для bold на Оплачен
                if ($k == 9 and $td == 'Да') {
                    $objWorkSheet->getStyleByColumnAndRow($k, $i + 2)->getFont()->setBold(true);
                }
                $objWorkSheet->setCellValueByColumnAndRow($k, $i + 2, $td);
            }
            $i++;
        }

        $APPLICATION->RestartBuffer();
        $date = date('d.m.Y', strtotime($_REQUEST['FROM']));
        header("Content-Type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=delivery-list-{$date}.xlsx");
        $writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save('php://output');
        die();
    }
}
