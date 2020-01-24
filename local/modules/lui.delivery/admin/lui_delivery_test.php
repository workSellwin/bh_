<?
/** @global CMain $APPLICATION */

use Bitrix\Main,
    Bitrix\Main\Application,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\SiteTable,
    Bitrix\Main\UserTable,
    Bitrix\Main\Config\Option,
    Bitrix\Sale;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/prolog.php');

CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
CModule::IncludeModule("lui.delivery");
//Обновление скидки
$arResult = [];
// ID	Дата получения заказа	Время получения заказа	Телефон	Фамилия и имя получателя	Населенный пункт	Город	Улица	Дом	Квартира	Сумма	Комментарии покупателя	Оплачен


$APPLICATION->SetTitle('Тест Api Yandex');
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php"); ?>
    <form action="" method="post">
        <label for="">Запрос в Yandex</label>
        <input type="text" required name="q" value="<?= $_REQUEST['q'] ? $_REQUEST['q'] : '' ?>">
        <input name="V" type="submit" value="Показать">
        <input name="D" type="submit" value="Детализация">
    </form>
<?
if ($_REQUEST['V'] or $_REQUEST['D']) {
    define('yandex_apikey', '91227d27-4cba-45e6-9179-7f0dc075d31d');
    if ($_REQUEST['V']) {
        if ($_REQUEST['q']) {
            $ob = new \Lui\Delivery\YandexApi();
            $arYandex = $ob->GetDataYandex($_REQUEST['q']);
            PR($arYandex);
        }
    }

    if ($_REQUEST['D']) {
        if ($_REQUEST['q']) {
            $ob = new \Lui\Delivery\YandexApi();
            $arYandex = $ob->GetDataYandexAll($_REQUEST['q']);
            PR($arYandex);
        }
    }

}

?>
<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
