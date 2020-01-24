<style>
    body .row .coupon-apply-btn{
        margin-top:15px;
        width: 250px;
    }
</style>
<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

CJSCore::Init(array("popup"));
$this->addExternalCss("/bitrix/css/main/bootstrap.css");

if (!empty($arResult["errorMessage"])) {
    if (!is_array($arResult["errorMessage"])) {
        ShowError($arResult["errorMessage"]);
    } else {
        foreach ($arResult["errorMessage"] as $errorMessage) {
            ShowError($errorMessage);
        }
    }
} else {

    $wrapperId = str_shuffle(substr($arResult['SIGNED_PARAMS'], 0, 10));
    ?>
    <div class="bx-sap" id="bx-sap<?= $wrapperId ?>">
        <div class="container-fluid">
            <?
            if ($arParams['SELL_VALUES_FROM_VAR'] != 'Y') {

                ?>
                <div class="row">
                    <div class="bx_ordercart_coupon">
                        <span>Введите код для пополнения счета:</span>
                        <input type="text" id="coupon" name="COUPON" value="">
                        &nbsp;<a id="coupon-ok-btn" class="bx_bt_button bx_big" href="javascript:void(0)"
                                 title="Нажмите для применения нового купона">Ок</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 sale-acountpay-block form-horizontal">
                        <h3 class="sale-acountpay-title"><?= Loc::getMessage("SAP_SUM") ?></h3>
                        <div class="" style="max-width: 200px;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <?
                                $inputElement = "
										<div class='col-sm-9'>
											<input type='text'	placeholder='0.00' 
											class='form-control input-lg sale-acountpay-input' value='0.00' "
                                    . "name=" . CUtil::JSEscape(htmlspecialcharsbx($arParams["VAR"])) . " "
                                    . ($arParams['SELL_USER_INPUT'] === 'N' ? "disabled" : "") .
                                    ">
										</div>";
                                $tempCurrencyRow = trim(str_replace("#", "", $arResult['FORMATED_CURRENCY']));
                                $labelWrapper = "<label class='control-label input-lg input-lg col-sm-3'>" . $tempCurrencyRow . "</label>";
                                $currencyRow = str_replace($tempCurrencyRow, $labelWrapper, $arResult['FORMATED_CURRENCY']);
                                $currencyRow = str_replace("#", $inputElement, $currencyRow);
                                echo $currencyRow;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="bx_ordercart_coupon">
                        &nbsp;<a id="coupon-apply-btn" class="bx_bt_button bx_big coupon-apply-btn" href="javascript:void(0)"
                                 title="Нажмите для зачисления">Зачислить</a>
                    </div>
                </div>
                <?
            }
            ?>
        </div>
    </div>
    <?
}
?>

