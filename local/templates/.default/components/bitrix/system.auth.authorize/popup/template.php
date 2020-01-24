<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<div class="bx-auth bx-auth_popup">
	<form class="authorize-form" name="form_auth" method="post" action="/ajax/popup_auth.php" data-ajax="/ajax/popup_auth.php">
		<div class="authorize-form__ttl">Авторизация</div>
		<div class="bx-auth_popup__in">
			<div class="hint-error hide">
				Чтобы добавить товар в избранное, авторизуйтесь или зарегистрируйтесь
			</div>
			<label for="USER_LOGIN_AUTH">E-mail / Phone</label>
			<div class="field-wrp">
				<input class="field" type="text" id="USER_LOGIN_AUTH" name="USER_LOGIN" maxlength="255" value=""/>
			</div>

			<label for="USER_PASSWORD_AUTH"><?=GetMessage("AUTH_PASSWORD")?></label>
			<div class="field-wrp">
				<input class="field chk"  id="USER_PASSWORD_AUTH" type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off"/>
			</div>


			<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
				<div class="checkbox">
					<input class="checkbox-field" type="checkbox" id="USER_REMEMBER_2" name="USER_REMEMBER" value="Y" />
					<label class="checkbox__label" for="USER_REMEMBER_2">&nbsp;<?=GetMessage("AUTH_REMEMBER_ME")?></label>
				</div>
			<?endif?>

			<input class="btn btn_black" type="submit" name="Login" value="<?=GetMessage("AUTH_AUTHORIZE")?>" />


				<br><br>
					<a href="/auth/?forgot_password=yes" rel="nofollow">Забыли свой пароль?</a><br>
					<a href="/auth/?register=yes" rel="nofollow">Зарегистрироваться</a>

			<div class="bx-authform-social">
				<?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "popup",
					array(
						"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
						"CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
						"AUTH_URL" => $arResult["AUTH_URL"],
						"POST" => $arResult["POST"],
						"SHOW_TITLES" => $arResult["FOR_INTRANET"]?'N':'Y',
						"FOR_SPLIT" => $arResult["FOR_INTRANET"]?'Y':'N',
						"AUTH_LINE" => $arResult["FOR_INTRANET"]?'N':'Y',
					),
					$component,
					array("HIDE_ICONS"=>"Y")
				);?>
			</div>
		</div>
	</form>
</div>
