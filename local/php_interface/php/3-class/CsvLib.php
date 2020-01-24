<?php


class CsvLib
{
    /**
     * @param $FileResult
     * @param int $line
     * @return array
     */
    static function GetLineFileCsv($FileResult, $line = 1)
    {
        $file = file($FileResult);
        $Fheader = str_getcsv($file[$line - 1], ',');
        fclose($file);
        return $Fheader;
    }

    /**
     * @param $FileResult
     * @param int $line
     * @return array
     */
    static function GetLineFileCsvNew($fileName, $line = 1, $delimitr = ';')
    {
        $file = file($fileName);
        $Fheader = str_getcsv($file[$line - 1], $delimitr);
        fclose($fileName);
        return $Fheader;
    }

    /** |
     * @param $FileResult
     * @param bool $Fheader2
     * @return bool
     */
    static function CsvToArray($FileResult, $Fheader2 = false)
    {
        if (mb_detect_encoding(file_get_contents($FileResult)) != 'UTF-8') {
            ShowError('Кодировка файла должна быть UTF-8');
            return false;
        }

        $i = -1;
        if ($Fheader2) {
            $Fheader = $Fheader2;
        } else {
            $Fheader = self::GetLineFileCsv($FileResult);
        }
        $file = fopen($FileResult, 'r');
        $arResultCsv = false;
        $i = 0;
        while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
            if (count($data) < count($Fheader)) {
                ShowError('Количиство столбцов должно быть не меньне 6 строка-' . $i);
                continue;
            }
            if (mb_detect_encoding($data[0]) != 'UTF-8') {
                ShowError('Кодировка Названия должна быть UTF-8 строка-' . $i);
                continue;
            }
            foreach ($Fheader as $key => $fhl) {
                $arResultCsv[$i][$fhl] = mb_convert_encoding($data[$key], 'UTF-8');
                //$arResultCsv[$i][$fhl] = $data[$key];
            }
            $i++;
        }
        return $arResultCsv;
    }

    /**
     * @param $FileResult
     * @param bool $Fheader2
     * @return bool
     */
    static function CsvToArray2($FileResult, $header = false)
    {
        $sFile = mb_convert_encoding(file_get_contents($FileResult), 'UTF-8');
        file_put_contents($FileResult, $sFile);
        $i = -1;
        if ($Fheader2) {
            $Fheader = $Fheader2;
        } else {
            $Fheader = self::GetLineFileCsv($FileResult);
        }
        $file = fopen($FileResult, 'r');
        $arResultCsv = false;
        $i = 0;
        while (($data = fgetcsv($file, 0, ";")) !== FALSE) {
            foreach ($Fheader as $key => $fhl) {
                $arResultCsv[$i][$fhl] = $data[$key];
            }
            $i++;
        }
        return $arResultCsv;
    }


    static function CsvToArrayNew($file, $header = false)
    {
        $i = -1;
        if ($header) {
            $Fheader = $header;
        } else {
            $Fheader = self::GetLineFileCsvNew($file);
        }
        $file = fopen($file, 'r');
        $arResultCsv = false;
        $i = 0;
        while (($data = fgetcsv($file, 0, ";")) !== FALSE) {
            if ($i == 0) {
                $i++;
                continue;
            }
            foreach ($Fheader as $key => $fhl) {
                $arResultCsv[$i][$fhl] = $data[$key];
            }
            $i++;
        }
        return $arResultCsv;
    }
}
