<?php

/**
 * Правка ядра для NDS для юр лиц
 * файл bitrix/components/bitrix/sale.export.1c/component.php \OrderInPriceFields::CopyFile($ABS_FILE_NAME);  561
 * файл bitrix/modules/sale/lib/exchange/importonecbase.php  $fields = \OrderInPriceFields::GetFields($fields); 167
 *
 * Class OrderInPriceFields
 */
class OrderInPriceFields
{
    public static function GetFields($fields)
    {
        if ($fields['AGENT']['ITEM_NAME'] != 'Розничный покупатель') {


            if (isset($fields['ITEMS_FIELDS'])) {
                foreach ($fields['ITEMS_FIELDS'] as &$arItem) {
                    if ($arItem['TAXES']['IN_PRICE'] == 'N') {
                        $arItem['TAXES']['IN_PRICE'] = 'Y';

                        $ndsP = $arItem['TAXES']['TAX_VALUE'] / 100;
                        $ndsCount = round($arItem['PRICE_ONE'] * $arItem['QUANTITY'] * $ndsP, 3);
                        $arItem['PRICE_ONE'] = round(($arItem['PRICE_ONE']*$arItem['QUANTITY']+$ndsCount)/$arItem['QUANTITY'],3);
                        $arItem['SUMM'] = $arItem['PRICE_ONE'] * $arItem['QUANTITY'];
                    }
                }
            }

            if (isset($fields['ITEMS'])) {
                foreach ($fields['ITEMS'] as &$arMas) {
                    foreach ($arMas as &$arItem) {
                        if ($arItem['TAX']['VAT_INCLUDED'] == 'N') {
                            $arItem['TAX']['VAT_INCLUDED'] = 'Y';

                            $ndsP =  $arItem['TAX']['VAT_RATE'];
                            $ndsCount = round($arItem['PRICE_ONE'] * $arItem['QUANTITY'] * $ndsP, 3);
                            $arItem['PRICE_ONE'] = round(($arItem['PRICE_ONE']*$arItem['QUANTITY']+$ndsCount)/$arItem['QUANTITY'],3);

                            $arItem['PRICE'] = $arItem['PRICE_ONE'];
                        }
                    }
                }
            }
            /*
              if (isset($fields['TAXES'])) {
                  //PR($fields['TAXES']);
                  if ($fields['TAXES']['IN_PRICE'] == 'N') {
                      $fields['TAXES']['IN_PRICE'] = 'Y';
                      $fields['AMOUNT'] += $fields['TAXES']['SUMM'];
                  }
              }
      */


        }

        return $fields;
    }

    public static function CopyFile($ABS_FILE_NAME)
    {
        $errors = '';
        if (false and file_exists($ABS_FILE_NAME) && filesize($ABS_FILE_NAME) > 0) {
            $arName = explode('/', $ABS_FILE_NAME);
            $name = end($arName);
            $orderDir = $_SERVER['DOCUMENT_ROOT'] . "/upload/1c_exchange_copy/";
            $ABS_FILE_NAME_NEW = $orderDir . $name;
            if (!@copy($ABS_FILE_NAME, $ABS_FILE_NAME_NEW)) {
                $errors = error_get_last();
            }
        }
        return $errors;
    }
}
