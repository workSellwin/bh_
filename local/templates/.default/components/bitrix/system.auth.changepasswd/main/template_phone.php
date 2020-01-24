<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="bx-auth">

    <form method="post" class="authorize-form" action="<?= $arResult["AUTH_FORM"] ?>" name="bform">
        <? ShowMessage($arParams["~AUTH_RESULT"]); ?>
        <? ShowMessage($_SESSION['USER_MESSAGE_STR']) ?>
        <? if ($_SESSION['USER_MESSAGE_STR'] == 'Пароль изменён') {
            unset($_REQUEST["USER_CODE_SMS"]);
        } ?>
        <? if (strlen($arResult["BACKURL"]) > 0): ?><input type="hidden" name="backurl"
                                                           value="<?= $arResult["BACKURL"] ?>" /><? endif ?>
        <input type="hidden" name="AUTH_FORM" value="Y">
        <input type="hidden" name="TYPE" value="CHANGE_PWD_PHONE">
        <input type="hidden" name="USER_ID" value="<?= (int)$_REQUEST['USER_ID'] ?>">
        <label for="">Код из SMS</label>
        <div class="field-wrp">
            <input type="text" name="USER_CODE_SMS" maxlength="50"
                   value="<?= $_REQUEST["USER_CODE_SMS"] ? $_REQUEST["USER_CODE_SMS"] : ''; ?>" class="field"/>
        </div>

        <label for=""><?= GetMessage("AUTH_NEW_PASSWORD_REQ") ?></label>
        <div class="field-wrp">
            <input type="password" name="USER_PASSWORD" maxlength="50" value="<?= $arResult["USER_PASSWORD"] ?>"
                   class="field" autocomplete="off"/>
            <? if ($arResult["SECURE_AUTH"]): ?>
                <span class="bx-auth-secure" id="bx_auth_secure" title="<? echo GetMessage("AUTH_SECURE_NOTE") ?>"
                      style="display:none">
					<div class="bx-auth-secure-icon"></div>
				</span>
                <noscript>
				<span class="bx-auth-secure" title="<? echo GetMessage("AUTH_NONSECURE_NOTE") ?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
                </noscript>
                <script >
                    document.getElementById('bx_auth_secure').style.display = 'inline-block';
                </script>
            <? endif ?>
        </div>

        <label for=""><?= GetMessage("AUTH_NEW_PASSWORD_CONFIRM") ?></label>
        <div class="field-wrp">
            <input type="password" name="USER_CONFIRM_PASSWORD" maxlength="50"
                   value="<?= $arResult["USER_CONFIRM_PASSWORD"] ?>" class="field" autocomplete="off"/>
        </div>

        <? if ($arResult["USE_CAPTCHA"]): ?>
            <div class="captcha-wrp">
                <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" width="180"
                     height="40" alt="CAPTCHA"/>

                <label for=""><? echo GetMessage("system_auth_captcha") ?></label>
                <div class="field-wrp">
                    <input type="text" name="captcha_word" maxlength="50" value="" class="field"/>
                </div>
            </div>
        <? endif ?>

        <input type="submit" class="btn btn_black" name="change_pwd" value="<?= GetMessage("AUTH_CHANGE") ?>"/></td>

        <p><br><? echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?></p>
        <p><br><a href="<?= $arResult["AUTH_AUTH_URL"] ?>"><b><?= GetMessage("AUTH_AUTH") ?></b></a></p>
    </form>

    <script >
        document.bform.USER_LOGIN.focus();
    </script>
</div>
