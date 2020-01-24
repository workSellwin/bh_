<?php

/**
 * Created by PhpStorm.
 * User: maspanau
 * Date: 04.04.2019
 * Time: 10:13
 */
class exportOnlinerNew
{
    use InitMainTrait;
    public $EXPORT_FILE_PATH = "/upload/EXPORT_ONLINER/export_file_onliner.csv";
    public $ORIGINAL_FILE_PATH = "/upload/EXPORT_ONLINER/original_file_onliner.csv";
    public $IBLOCK_ID = 2;

    public $POLE_NAME = array(
        'category',                     //Название раздела
        'vendor',                       //Производитель
        'model',                        //Модель
        'article',                      //Заводской артикул
        'id',                           //id предложения присваивает onliner.by
        'price',                        //Цена товара
        'currency',                     //Валюта товара
        'comment',                      //комментарий продавца
        'producer',                     //изготовитель товара
        'importer',                     //Импортёр \ поставщик на территорию РБ
        'serviceCenters',               //Сервисный центр
        'warranty',                     //Гарантия (месяцев)
        'deliveryTownTime',             //Доставка по городу (дней)
        'deliveryTownPrice',            //Доставка по городу (стоимость)
        'deliveryCountryTime',          //Доставка по РБ (дней)
        'deliveryCountryPrice',         //Доставка по РБ (стоимость)
        'productLifeTime',              //Срок службы (месяцев)
        'isCashless',                   //Безналичный
        'isCredit',                     //Кредит
        'stockStatus',                  //Наличие товара(in_stock - есть на складе и доступен для покупки; run_out_of_stock - осталось мало или заканчивается)               //Условие покупки в комплекте с указанной позицией - 1+1
    );

    public function __construct()
    {
        $this->includeModules();
    }

    public function process()
    {

        $original_file_path = $_SERVER['DOCUMENT_ROOT'] . $this->ORIGINAL_FILE_PATH;
        $export_file_onliner = $_SERVER['DOCUMENT_ROOT'] . $this->EXPORT_FILE_PATH;
        if (file_exists($original_file_path)) {
            //открываем файл и изменяем кадеровку на UTF-8
            $data = $this->kaderovkaFile($original_file_path);
            //сохроняем с этой кодеровкой
            $this->saveDataFile($export_file_onliner, $data);
            //получаем массив из файла
            $dataArray['FIELDS'] = \CsvLib::CsvToArrayNew($export_file_onliner, $this->POLE_NAME);
            //получаем массив ID(onliner) товаров
            $arId = $this->getIdElemFile($dataArray['FIELDS']);
            //получаем массив елементов из нашего котолога
            $dataElemCatalog = $this->getListElemCatalog($arId);
            //формируем priceList для onliner
            $priceList = $this->formationPriceList($dataArray['FIELDS'], $dataElemCatalog);
            //преобразуем массив в CSV строки и записываем в файл
            $this->arrayToScv($priceList, $export_file_onliner);
        } else {
            echo 'Нет файла';
        }

    }

    private function kaderovkaFile($path, $kaderovka = 'UTF-8')
    {
        $file = file_get_contents($path);
        if (mb_detect_encoding($file) != $kaderovka) {
            $file = mb_convert_encoding($file, $kaderovka, mb_detect_encoding($file));
        }
        return $file;
    }

    private function saveDataFile($path, $data)
    {
        file_put_contents($path, $data);
    }

    private function getIdElemFile($arData)
    {
        if (!empty($arData)) {
            $arId = array_column($arData, 'id');
            return $arId;
        } else {
            return false;
        }
    }

    private function getListElemCatalog($arPropOnlinsrId)
    {
        $filter = [
            'IBLOCK_ID' => $this->IBLOCK_ID,
            //'CATALOG_TYPE' => \Bitrix\Catalog\ProductTable::TYPE_PRODUCT,

            'PROPERTY_ONLINER' => $arPropOnlinsrId,
        ];
        $select = ['ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_ONLINER', 'CATALOG_QUANTITY'];

        $resID = \CIBlockElement::GetList(array('ID' => 'ASC'), $filter, false, false, $select);
        $allIdForExport = array();
        while ($res1 = $resID->GetNextElement()) {
            $reOnl = $res1->GetFields();
            $reProp = $res1->GetProperties();

                $resPrice = \CCatalogProduct::GetOptimalPrice($reOnl["ID"], 1, [2], 'N', 's1');
                $arPrice["PRICE"] = round($resPrice['DISCOUNT_PRICE'], 2);
                $arPrice["PRICE"] = str_replace('.', ',', $arPrice["PRICE"]);
                $onliner_id = $reProp['ONLINER']['VALUE'];
                $allIdForExport[$onliner_id]['ID'] = $reOnl['ID'];
                $allIdForExport[$onliner_id]['NAME'] = $reOnl['NAME'];
                $allIdForExport[$onliner_id]['CATALOG_QUANTITY'] = $reOnl['CATALOG_QUANTITY'];
                $allIdForExport[$onliner_id]['PRICE'] = $reOnl['CATALOG_QUANTITY'] > 0 ? $arPrice["PRICE"] : 0;
                $allIdForExport[$onliner_id]['ONLINER_ID'] = $onliner_id;
                //доставка по Минску
                $PRICE = (float)(str_replace(',', '.', $arPrice["PRICE"]));
                $allIdForExport[$onliner_id]['TownPrice'] = $PRICE < 30.0 ? 5 : 0;
                //доставка по РБ
                $allIdForExport[$onliner_id]['CountryPrice'] = $PRICE < 50.0 ? 5 : 0;


        }
        return $allIdForExport;
    }

    private function formationPriceList($arDataFile, $arDataElemCatalog)
    {
        if (!empty($arDataFile) && !empty($arDataElemCatalog)) {
            foreach ($arDataFile as &$value) {
                if ($arDataElemCatalog[$value['id']]) {
                    $value['price'] = $arDataElemCatalog[$value['id']]['PRICE'];
                    $value['deliveryTownPrice'] = $arDataElemCatalog[$value['id']]['TownPrice'];
                    $value['deliveryCountryPrice'] = $arDataElemCatalog[$value['id']]['CountryPrice'];
                }
            }
            return $arDataFile;
        }
    }

    private function arrayToScv($arData, $file_path, $delimiter = ';')
    {
        $fp = fopen($file_path, 'w');
        foreach ($arData as $fields) {
            fputcsv($fp, $fields, $delimiter);
        }
    }
}
