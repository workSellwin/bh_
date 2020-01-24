<?
define('STOP_STATISTICS', true);
define('NO_AGENT_CHECK', true);

require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

\Kosmos\Export::process();
?>