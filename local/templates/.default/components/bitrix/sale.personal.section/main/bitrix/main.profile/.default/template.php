<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
$this->setFrameMode(true);
use Bitrix\Main\Localization\Loc;

?>

<div class="bx_profile">
	<?
	ShowError($arResult["strProfileError"]);

	if ($arResult['DATA_SAVED'] == 'Y')
	{
		ShowNote(Loc::getMessage('PROFILE_DATA_SAVED'));
	}

	?>
	<form method="post" name="form1" class="authorize-form" action="<?=$APPLICATION->GetCurUri()?>" enctype="multipart/form-data" role="form">
		<?=$arResult["BX_SESSION_CHECK"]?>
		<input type="hidden" name="lang" value="<?=LANG?>" />
		<input type="hidden" name="ID" value="<?=$arResult["ID"]?>" />
		<input type="hidden" name="LOGIN" value="<?=$arResult["arUser"]["LOGIN"]?>"/>
		<div class="main-profile-block-shown" id="user_div_reg">
			<div class="main-profile-block-date-info">
				<?
				if($arResult["ID"]>0)
				{
					if (strlen($arResult["arUser"]["TIMESTAMP_X"])>0)
					{
						?>
							<label>
								<?=Loc::getMessage('LAST_UPDATE')?>
								<?=$arResult["arUser"]["TIMESTAMP_X"]?>
							</label>
						<?
					}

					if (strlen($arResult["arUser"]["LAST_LOGIN"])>0)
					{
						?>
						<label>
							<?=Loc::getMessage('LAST_LOGIN')?>
							<?=$arResult["arUser"]["LAST_LOGIN"]?>
						</label>
						<?
					}
				}
				?>
			</div>
			<?
			if (!in_array(LANGUAGE_ID,array('ru', 'ua')))
			{
				?>
				<div class="form-group">
					<label class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="main-profile-title"><?=Loc::getMessage('main_profile_title')?></label>
					<div class="col-sm-12">
						<input class="form-control" type="text" name="TITLE" maxlength="50" id="main-profile-title" value="<?=$arResult["arUser"]["TITLE"]?>" />
					</div>
				</div>
				<?
			}
			?>
			<label for="main-profile-name"><?=Loc::getMessage('NAME')?></label>
			<div class="field-wrp">
				<input class="form-control field" type="text" name="NAME" maxlength="50" id="main-profile-name" value="<?=$arResult["arUser"]["NAME"]?>" />
			</div>
			
			<label for="main-profile-name">Дата рождения</label>
			<div class="field-wrp">
				<input class="form-control field field_date" type="text" name="PERSONAL_BIRTHDAY" size="13" value="<?=$arResult["arUser"]["PERSONAL_BIRTHDAY"]?>" onclick="BX.calendar({node:this, field:'PERSONAL_BIRTHDAY', form: '', bTime: false, bHideTime: false});">
			</div>
			
			<label for="main-profile-name">Телефон</label>
			<div class="field-wrp">
				<input size="30" type="tel" name="PERSONAL_PHONE" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" class="field field_phone" placeholder="+375 - -  - - -  - -  - -">
			</div>

			<label for="main-profile-email"><?=Loc::getMessage('EMAIL')?></label>
			<div class="field-wrp">
				<input class="form-control field" type="text" name="EMAIL" maxlength="50" id="main-profile-email" value="<?=$arResult["arUser"]["EMAIL"]?>" />
			</div>

			<?
			if($arResult["arUser"]["EXTERNAL_AUTH_ID"] == '')
			{
				?>
				<label for="main-profile-password"><?=Loc::getMessage('NEW_PASSWORD_REQ')?></label>
				<div class="field-wrp">
					<input class=" form-control field" type="password" name="NEW_PASSWORD" maxlength="50" id="main-profile-password" value="" autocomplete="off"/>
				</div>

				<label for="main-profile-password-confirm"><?=Loc::getMessage('NEW_PASSWORD_CONFIRM')?></label>
				<div class="field-wrp">
					<input class="form-control field" type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" id="main-profile-password-confirm" autocomplete="off" />
				</div>
				<label>*<?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></label>
				<?
			}
			?>
		</div>
		<p class="main-profile-form-buttons-block col-sm-9 col-md-offset-3">
			<input type="submit" name="save" class="btn btn_black" value="<?=(($arResult["ID"]>0) ? Loc::getMessage("MAIN_SAVE") : Loc::getMessage("MAIN_ADD"))?>">
			&nbsp;
			<input type="submit" class="btn btn_black"  name="reset" value="<?echo GetMessage("MAIN_RESET")?>">
		</p>
	</form>
		<?
		if ($arResult["SOCSERV_ENABLED"])
		{
			$APPLICATION->IncludeComponent(
				"bitrix:socserv.auth.split", 
				"main", 
				array(
					"SHOW_PROFILES" => "Y",
					"ALLOW_DELETE" => "Y",
					"COMPONENT_TEMPLATE" => "main"
				),
				false
			);
		}
		?>
</div>