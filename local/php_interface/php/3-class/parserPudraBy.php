<?php


class ParserPudraBy
{

    public $LINK = [
        'https://pudra.by/brand/wella-professionals',
        //'https://pudra.by/brand/londa-professional',
        //'https://pudra.by/brand/pharmaceris',
    ];
    public $NAME_FILE='PARSER-PudraBy';
    public $SITE_NAME = 'https://pudra.by';
    public $PATH = '/parser/';
    public $pathFile='';
    public $ATTRIBUTES = [
        'SHTRIX_KOD' => ".//div[@class='card-product-content-head']/ul[@class='card-product-list']/li/div[@class='cart-product-apticul']/span",
        'NAME' => ".//div[@class='card-product-content-head']/h1",
        'BRANDS' => ".//div[@class='card-product-content-head']/ul[@class='card-product-list']/li/div[@class='card-product-brands']",
        //'VOLUME' => ".//tr[@class='card-product-data-volume']/td/strong[@class='value']",
        'DESCRIPTION' => ".//div[@class='card-product-more']",
        'IMG' => ".//img[@class='card-product-img']",
        'MANUFACTURER' => ".//div[@class='card-product-manufacturer']",
    ];
    public $HEAD_EXCEL = [
        'Штрих-код', 'Название', 'Бренд', 'Описание', 'Картинка', 'Производитель', 'URL страницы'
    ];
    public $SIZE_LETTER_EXCEL = [];

    public function startPasser($step_start = 0, $step)
    {
        $arData = [];
        foreach ($this->LINK as $page) {
            $html = file_get_contents($page);
            $sectionDom = new DOMDocument();
            $sectionDom->loadHTML($html);
            $xpath = new DOMXpath($sectionDom);

            $productsHref = $this->getProductHref($xpath);
            $i = 0;
            foreach ($productsHref as $key => $productHref) {
                if ($key >= $step_start && $key <= ($step_start + $step) && $key <= (count($productsHref) - 1)) {
                    $elementDom = new DOMDocument();
                    $html = file_get_contents($this->SITE_NAME . $productHref);
                    $elementDom->loadHTML($html);
                    $elementXpath = new DOMXpath($elementDom);
                    $data = $this->getValueAttribute($elementXpath, $this->ATTRIBUTES);
                    $arData[$i] = $data;
                    $arData[$i]['SITE_URL'] = $this->SITE_NAME . $productHref;
                    $i++;
                }
            }
        }
        return ['DATA' => $arData, 'STEP_START' => $step_start + $step + 1];
    }

    public function getProductHref($xpath)
    {
        $hrefs = [];
        $elements = $xpath->query(".//div[@class='goods-item-body']/a[@class='goods-item-links']");
        if (!is_null($elements)) {
            foreach ($elements as $element) {
                $hrefs[] = $element->getAttribute('href');
            }
        }
        return $hrefs;
    }

    public function getValueAttribute($elementXpath, $arAtributs)
    {
        $date = [];
        foreach ($arAtributs as $key => $atribut) {
            $elements = $elementXpath->query($atribut);
            if (!is_null($elements)) {
                if ($key == 'IMG') {
                    if($elements->item(0)){
                        $date[$key] = $this->SITE_NAME . $elements->item(0)->getAttribute('src');
                    }
                } elseif ($key == 'MANUFACTURER') {
                    $date[$key] = " ".utf8_decode($elements->item(0)->nodeValue)." ";
                } else {
                    $date[$key] = " ".utf8_decode($elements->item(0)->nodeValue)." ";
                }
            }
        }
        return $date;
    }

    public function SetExcel($arData)
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

        $arHeader = $this->HEAD_EXCEL;
        $objWorkSheet->getStyle('A1:Z1')->applyFromArray($styleArray);
        $objWorkSheet->getStyle('A2:Z1000')->applyFromArray($style);
        $objWorkSheet->getStyle('B2:B1000')->applyFromArray($style2);
        foreach ($arHeader as $i => $head) {
            $buk = strtoupper(chr($i + 97));
            if ($this->SIZE_LETTER_EXCEL[$buk]) {
                $objWorkSheet->getColumnDimension($buk)->setWidth($this->SIZE_LETTER_EXCEL[$buk]);
            } else {
                $objWorkSheet->getColumnDimension($buk)->setAutoSize(true);
            }
            $buk = $buk . '1';
            $objWorkSheet->setCellValue($buk, $head);
        }

        $arBody = $arData;
        foreach ($arBody as $i => $tr) {
            foreach (array_values($tr) as $k => $td) {
                $buk = strtoupper(chr($k + 97));
                $numb = $i + 2;
                $buk = $buk . $numb;
                $objWorkSheet->setCellValue($buk, $td);
            }
        }
        $this->pathFile = $_SERVER['DOCUMENT_ROOT'] . $this->PATH.$this->NAME_FILE.'.xlsx';
        $writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $writer->save($this->pathFile);
        return $this->PATH.$this->NAME_FILE.'.xlsx';
    }

}

/*
 * $ParserPudraBy = new ParserPudraBy();
$LIMIT_PARSER = 200;

if(isset($_REQUEST['LINK_PARSER']) && !empty($_REQUEST['LINK_PARSER'])):
    $ParserPudraBy->LINK[0]=$_REQUEST['LINK_PARSER'];
    if(isset($_REQUEST['NAME_FILE']) && !empty($_REQUEST['NAME_FILE'])){
        $ParserPudraBy->NAME_FILE=$_REQUEST['NAME_FILE'];
    }
    if(isset($_REQUEST['PATH']) && !empty($_REQUEST['PATH'])){
        $ParserPudraBy->PATH=$_REQUEST['PATH'];
    }
    $LIMIT_PARSER = isset($_REQUEST['LIMIT_PARSER']) && !empty($_REQUEST['LIMIT_PARSER']) ?  $_REQUEST['LIMIT_PARSER'] : 200 ;
    $arData = $ParserPudraBy->startPasser(0, 200);
    $ParserPudraBy->SIZE_LETTER_EXCEL = [
        'D' => 30,
        'E' => 30,
        'F' => 30,
    ];
    $file_path = $ParserPudraBy->SetExcel($arData['DATA']);
endif;
?>

<form action="#" method="post">

    <span>Ссылка</span><br>
    <input type="text" name="LINK_PARSER" style="width: 400px;" value="<?=$ParserPudraBy->LINK[0]?>"><br><br>
    <span>Имя файла</span><br>
    <input type="text" name="NAME_FILE" style="width: 400px" value="<?=$ParserPudraBy->NAME_FILE?>"><br><br>
    <span>Путь к файлу</span><br>
    <input type="text" name="PATH" style="width: 400px" value="<?=$ParserPudraBy->PATH?>"><br><br>
    <span>Лимит элементов</span><br>
    <input type="text" name="LIMIT_PARSER" style="width: 400px" value="<?=$LIMIT_PARSER?>"><br><br><br>

    <?if($file_path != ''):?>
        <a href="<?=$file_path?>" download>Скачать файл</a><br><br>
    <?endif?>

    <input type="submit" name="parser" value="Старт парсера">
</form>
 */