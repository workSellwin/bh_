<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<a href="<?= $arResult["URL_TO_LIST"] ?>"><?= GetMessage("SALE_RECORDS_LIST") ?></a>

<div class="bx_my_order_cancel">
    <? if (strlen($arResult["ERROR_MESSAGE"]) <= 0): ?>
        <form method="post" action="<?= POST_FORM_ACTION_URI ?>">

            <input type="hidden" name="CANCEL" value="Y">
            <?= bitrix_sessid_post() ?>
            <input type="hidden" name="ID" value="<?= $arResult["ID"] ?>">

            <?= GetMessage("SALE_CANCEL_ORDER1") ?>

            <a href="<?= $arResult["URL_TO_DETAIL"] ?>"><?= GetMessage("SALE_CANCEL_ORDER2") ?>
                #<?= $arResult["ACCOUNT_NUMBER"] ?></a>?
            <b><?= GetMessage("SALE_CANCEL_ORDER3") ?></b><br/><br/>
            <?= GetMessage("SALE_CANCEL_ORDER4") ?>:<br/>
            <br>
            <label for="REASON_CANCELED_RADIO_1"><input checked  id="REASON_CANCELED_RADIO_1" type="radio" name="REASON_CANCELED_RADIO" value="нашли более выгодное предложение"> нашли более выгодное предложение</label><br><br>
            <label for="REASON_CANCELED_RADIO_2"><input id="REASON_CANCELED_RADIO_2" type="radio" name="REASON_CANCELED_RADIO" value="передумали приобретать товар"> передумали приобретать товар</label><br><br>
            <label for="REASON_CANCELED_RADIO_3"><input id="REASON_CANCELED_RADIO_3" type="radio" name="REASON_CANCELED_RADIO" value="не устраивает доставка"> не устраивает доставка</label><br><br>
            <label for="REASON_CANCELED_RADIO_4"><input id="REASON_CANCELED_RADIO_4" type="radio" name="REASON_CANCELED_RADIO" value="вернетесь к покупке позже"> вернетесь к покупке позже</label><br><br>
            <label for="REASON_CANCELED_RADIO_5"><input id="REASON_CANCELED_RADIO_5" type="radio" name="REASON_CANCELED_RADIO" value="свой вариант"> свой вариант</label><br><br>
            <br>
            <textarea name="REASON_CANCELED" id="REASON_CANCELED"></textarea><br/><br/>
            <input type="submit" name="action" value="<?= GetMessage("SALE_CANCEL_ORDER_BTN") ?>">

        </form>
    <? else: ?>
        <?= ShowError($arResult["ERROR_MESSAGE"]); ?>
    <? endif; ?>

</div>
<script>
    $(document).ready(function () {
        var s1 = $('[name="REASON_CANCELED_RADIO"]');
        var t1 = $('#REASON_CANCELED');
        var s1v = s1.val();
        t1.val(s1v);
        t1.hide();
        $('.bx_my_order_cancel').on('change', '[name="REASON_CANCELED_RADIO"]', function () {
            var v = $(this).val();
            if (v == 'свой вариант') {
                t1.show();
                t1.val('');
            } else {
                t1.hide();
                t1.val(v);
            }
        });
    });
</script>
