<?
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../..");
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];
//mail("gritsyuk.michael@gmail.com", "TEST", "NO_GOOD");
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
use Bitrix\Main\Diag\Debug;

CModule::IncludeModule("sale");
$flag = false;
$hours = date('G');
switch ($hours) {
	case 11:
		$arFilter = Array("STATUS_ID" => "N", "PROPERTY_VAL_BY_CODE_PRIORITY"=>"priority-1");
		$flag = true;
		break;
	case 13:
		$arFilter = Array("STATUS_ID" => "N", "PROPERTY_VAL_BY_CODE_PRIORITY"=>"priority-2");
		$flag = true;
		break;
	case 16:
		$arFilter = Array("STATUS_ID" => "N", "PROPERTY_VAL_BY_CODE_PRIORITY"=>"priority-3");
		$flag = true;
		break;
}
if($arFilter && $flag){

	/*
	 * Добавление заказов с доставкой только за сегодняшний день
	 */
	$objDateTime = new Bitrix\Main\Type\DateTime(date('d.m.Y') . ' 23:59:59');
	$date = $objDateTime->toString();
	$arFilter += ['<=PROPERTY_VAL_BY_CODE_TEST' => $date];

	$rsSales = CSaleOrder::GetList(array("DATE_INSERT" => "DESC"), $arFilter, false,false,array("*"));
	while ($arSales = $rsSales->Fetch())
	{
		CSaleOrder::StatusOrder($arSales["ID"], "YT");//E//YT
	}
}
Debug::writeToFile("запустился крон");
CMain::FinalActions();
//mail("gritsyuk.michael@gmail.com", "TEST", "GOOD");
?>