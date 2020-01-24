<?php
namespace Kosmos;


class Export {

	const ALLOW_GROUPS = [1, 11];

	public static function process()
	{
		try
		{
			$request = \Bitrix\Main\Context::getCurrent()->getRequest();
			$type = $request->get('type');

			switch($type)
			{
				case 'quantity_products':
				case 'quantity_offers':
					self::checkAccess();
					self::getCatalogFile($type);
					break;
				default:
					throw new \Exception('Неизвестный запрос');
					break;
			}

		}
		catch(\Exception $ex)
		{
			echo $ex->getMessage();
		}
	}

	private static function checkAccess()
	{
		$arGroups = \Bitrix\Main\UserTable::getUserGroupIds($GLOBALS['USER']->GetID());
		$intersect = array_intersect($arGroups, self::ALLOW_GROUPS);
		if(empty($intersect))
			throw new \Exception('Доступ запрещен');
	}

	private static function getCatalogFile($type)
	{
		$file = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/catalog_export/' . $type . '.csv';
		if(!file_exists($file))
			throw new \Exception('Файл не найден');

		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		$spreadsheet = $reader->load($file);

		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'XML_ID');
		$sheet->setCellValue('B1', 'Штрих-код');
		$sheet->setCellValue('C1', 'Название');
		$sheet->setCellValue('D1', 'Стоп продаж');
		$sheet->setCellValue('E1', 'Остаток');
		$sheet->setCellValue('F1', 'Цена c НДС');

		$styleArray = [
			'font' => [
				'bold' => true
			]
		];
		$sheet->getStyle('A1:G1')->applyFromArray($styleArray);

		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setAutoSize(true);
		$sheet->getColumnDimension('C')->setWidth(100);
		$sheet->getColumnDimension('D')->setAutoSize(true);
		$sheet->getColumnDimension('E')->setAutoSize(true);
		$sheet->getColumnDimension('F')->setAutoSize(true);
		$sheet->getColumnDimension('G')->setAutoSize(true);

		$lastLine = $sheet->getHighestRow('B');
		for($i = 2; $i <= $lastLine; $i++)
		{
			$spreadsheet->getActiveSheet()->getStyle('B' . $i)
				->getNumberFormat()
				->setFormatCode(
					\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
				);
		}

		/*$writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);
		$writer->setDelimiter(';');
		$writer->setEnclosure('');
		$writer->setLineEnding("\r\n");
		$writer->setSheetIndex(0);
		$writer->save('php://output');

		header('Content-Type: application/csv');
		header('Content-Disposition: attachment;filename="'.$type.'.csv"');
		header('Cache-Control: max-age=0');*/

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$type.'.xlsx"');
		header('Cache-Control: max-age=0');
	}
}