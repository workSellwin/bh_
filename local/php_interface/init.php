<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

require_once 'krumo/class.krumo.php';
require_once 'vendor/autoload.php';

use Bitrix\Sale;

\CModule::IncludeModule('highloadblock');
\CModule::IncludeModule('sale');

define('yandex_apikey', '91227d27-4cba-45e6-9179-7f0dc075d31d');

if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/constants.php"))
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/constants.php");

if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/events.php"))
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/events.php");

if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/classes.php"))
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/classes.php");
if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/functions.php"))
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/functions.php");
if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/fields/usertypeelement.php"))
    require_once($_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/fields/usertypeelement.php");


include_once __DIR__ . '/php.php';

$autoload = $_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php';
if (file_exists($autoload))
    include_once $autoload;


AddEventHandler("main", "OnEndBufferContent", "removeType");
function removeType(&$content)
{
    $content = replace_output($content);
}

function replace_output($d)
{
    $d = str_replace(['http%3A//', 'https%3A//'],['http://', 'https://'], $d);
    return str_replace(' type="text/javascript"', "", $d);
}

AddEventHandler('main', 'OnFileSave', ['FileHandler', 'OnFileSaveHandler']);
AddEventHandler('main', 'OnFileDelete', ['FileHandler', 'OnFileDeleteHandler']);
AddEventHandler('main', 'OnGetFileSRC', ['FileHandler', 'OnGetFileSRCHandler']);
AddEventHandler("main", "OnAfterResizeImage", ['FileHandler', "OnAfterResizeImageHandler"]);
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", ["ElementHandler", "OnBeforeIBlockElementUpdateHandler"]);
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", ["ElementHandler", "OnBeforeIBlockElementUpdateHandler"]);
//AddEventHandler("iblock", "OnAfterIBlockElementDelete", Array("ElementHandler", "OnAfterIBlockElementDeleteHandler"));
