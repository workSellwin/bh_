<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($_REQUEST['phone'] == 'Y' and $_REQUEST['USER_ID'] > 0) {
    include 'template_phone.php';
} else {
    include 'template_email.php';
}