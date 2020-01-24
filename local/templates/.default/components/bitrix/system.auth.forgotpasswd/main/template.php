<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

ShowMessage($arParams["~AUTH_RESULT"]);

?>
<div class="bx-auth">
	<form class="authorize-form" name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
		<?if (strlen($arResult["BACKURL"]) > 0):?> <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><?endif?>
		<input type="hidden" name="AUTH_FORM" value="Y">
		<input type="hidden" name="TYPE" value="SEND_PWD">
		<p><?=GetMessage("AUTH_FORGOT_PASSWORD_1")?></p>
		<label for=""><?//=GetMessage("AUTH_EMAIL")?></label>
		<div class="field-wrp">
			<input type="text" name="USER_EMAIL" maxlength="255" class="field"/>
		</div>

		<?if($arResult["USE_CAPTCHA"]):?>
			<tr>
				<td></td>
				<td>
					<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
					<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
				</td>
			</tr>
			<tr>
				<td><?echo GetMessage("system_auth_captcha")?></td>
				<td><input type="text" name="captcha_word" maxlength="50" value="" /></td>
			</tr>
		<?endif?>

		<input type="submit" name="send_account_info" class="btn btn_black" value="<?=GetMessage("AUTH_SEND")?>" />

		<p><br><a href="<?=$arResult["AUTH_AUTH_URL"]?>"><b><?=GetMessage("AUTH_AUTH")?></b></a></p>
	</form>
	<script >
		document.bform.USER_LOGIN.focus();
	</script>
</div>
