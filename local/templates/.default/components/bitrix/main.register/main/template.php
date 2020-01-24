<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>
<div class="bx-auth">

	<?if($USER->IsAuthorized()):?>
		<p style="margin-bottom: 10px"><?echo GetMessage("MAIN_REGISTER_AUTH")?></p>
	<?else:?>
<?/*
        <p>
            Зарегистрируйся и получи в подарок 5 рублей на первую покупку!</p>
        <p>* Подарок временно начисляется оператором. Вы сможете увидеть его в корзине в течение суток с момента регистрации.
        </p><br>*/?>


		<?$APPLICATION->IncludeComponent(
			"bitrix:system.auth.authorize",
			"main-soc",
		Array()
		);?>
		<?if (count($arResult["ERRORS"]) > 0):
			foreach ($arResult["ERRORS"] as $key => $error)
				if (intval($key) == 0 && $key !== 0)
					$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);
			ShowError(implode("<br />", $arResult["ERRORS"]));
		elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
			<p><?echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT")?></p>
		<?endif?>

		<form  class="authorize-form" method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
			<input type="hidden" name="REGISTER[LOGIN]" value="<?=(!empty($arResult["VALUES"]["LOGIN"]))?$arResult["VALUES"]["LOGIN"]:"U_".time()?>"/>
			<?if($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?endif;?>


			<label for=""> <?=GetMessage("REGISTER_FIELD_NAME")?></label>
			<div class="field-wrp">
				<input size="30" type="text" name="REGISTER[NAME]" value="<?=$arResult["VALUES"]["NAME"]?>" placeholder="Представьтесь" class="field"/>
			</div>

			<label for=""> <?=GetMessage("REGISTER_FIELD_PERSONAL_BIRTHDAY")?></label>
			<div class="field-wrp" id="field-date">
				<input size="30"  type="text" name="REGISTER[PERSONAL_BIRTHDAY]" value="<?=$arResult["VALUES"]["PERSONAL_BIRTHDAY"]?>" class="field field_date" placeholder="дд.мм.гггг"/>
				<?$APPLICATION->IncludeComponent(
					'bitrix:main.calendar',
					'',
					array(
						'SHOW_INPUT' => 'N',
						'FORM_NAME' => 'regform',
						'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
						'SHOW_TIME' => 'N'
					),
					null,
					array("HIDE_ICONS"=>"Y")
				);?>
				<script>
					$(document).on("click","#field-date input",function(){
						BX.calendar({node: this, value: new Date(), field: this, bTime: false});
						//changeCalendar();
					});
				</script>
			</div>

			<label for=""> <?=GetMessage("REGISTER_FIELD_PERSONAL_PHONE")?></label>
			<div class="field-wrp">
				<input size="30" type="tel" name="REGISTER[PERSONAL_PHONE]" value="<?=$arResult["VALUES"]["PERSONAL_PHONE"]?>" class="field field_phone" placeholder="+375 - -  - - -  - -  - -"/>
			</div>

			<label for=""> <?=GetMessage("REGISTER_FIELD_EMAIL")?> <span class="starrequired">*</span></label>
			<div class="field-wrp">
				<input size="30" type="email" name="REGISTER[EMAIL]" value="<?=$arResult["VALUES"]["EMAIL"]?>" class="field" placeholder="Електронная почта"/>
			</div>

			<label for=""> <?=GetMessage("REGISTER_FIELD_PASSWORD")?> <span class="starrequired">*</span></label>
			<div class="field-wrp">
				<input size="30" type="password" name="REGISTER[PASSWORD]" value="<?=$arResult["VALUES"]["PASSWORD"]?>" autocomplete="off" class="field" placeholder="Не менее 6 символов"/>
				<?if($arResult["SECURE_AUTH"]):?>
								<span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
									<div class="bx-auth-secure-icon"></div>
								</span>
								<noscript>
								<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
									<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
								</span>
								</noscript>
					<script >
					document.getElementById('bx_auth_secure').style.display = 'inline-block';
					</script>
				<?endif?>
			</div>

			<label for=""> <?=GetMessage("REGISTER_FIELD_CONFIRM_PASSWORD")?> <span class="starrequired">*</span></label>
			<div class="field-wrp">
				<input size="30" type="password" name="REGISTER[CONFIRM_PASSWORD]" value="<?=$arResult["VALUES"]["CONFIRM_PASSWORD"]?>" autocomplete="off" class="field" placeholder="Не менее 6 символов"/>
			</div>

			<?if ($arResult["USE_CAPTCHA"] == "Y"):?>
				<div class="captcha-wrp">
					<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
					<label for=""><?=GetMessage("REGISTER_CAPTCHA_PROMT")?>:<span class="starrequired">*</span></label>
					<div class="field-wrp">
						<input type="text" name="captcha_word" maxlength="50" value="" class="field"/>
						<img class="captcha-img" src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
					</div>

				</div>
			<?endif?>

			<input class="btn btn_black" type="submit" name="register_submit_button" value="<?=GetMessage("AUTH_REGISTER")?>" />
			<div class="note">* <?=GetMessage("AUTH_REQ")?></div>
			<p><br><a href="<?=$arParams["AUTH_AUTH_URL"]?>"><b>Авторизация</b></a></p>
		</form>

	<?endif?>
</div>
