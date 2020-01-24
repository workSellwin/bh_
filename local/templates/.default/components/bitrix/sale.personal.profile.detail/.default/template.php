<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

?>
<div class="bx-auth">
<div class="sale-profile-detail-link-list">
	<a href="<?=$arParams["PATH_TO_LIST"]?>"><?=GetMessage("SPPD_RECORDS_LIST")?></a>
</div>

<?
if(strlen($arResult["ID"])>0)
{
	ShowError($arResult["ERROR_MESSAGE"]);
	?>
	<form method="post"  class="sale-profile-detail-form authorize-form" action="<?=POST_FORM_ACTION_URI?>" enctype="multipart/form-data">
		<?=bitrix_sessid_post()?>
		<input type="hidden" name="ID" value="<?=$arResult["ID"]?>">

		<label for="sale-personal-profile-detail-name"><?=Loc::getMessage('SALE_PNAME')?>:<span class="req">*</span></label>
		<div class="field-wrp">
			<input class="form-control field" type="text" name="NAME" maxlength="50" id="sale-personal-profile-detail-name" value="<?=htmlspecialcharsbx($arResult["NAME"])?>" />
		</div>
		<?
		foreach($arResult["ORDER_PROPS"] as $block)
		{
			if (!empty($block["PROPS"]))
			{
				?>
				<h4>
					<b><?= $block["NAME"]?></b>
				</h4>
				<?
				foreach($block["PROPS"] as $key => $property)
				{
					$name = "ORDER_PROP_".$property["ID"];
					$currentValue = $arResult["ORDER_PROPS_VALUES"][$name];
					$alignTop = ($property["TYPE"] === "LOCATION" && $arParams['USE_AJAX_LOCATIONS'] === 'Y') ? "vertical-align-top" : "";
					?>
					<?if($property["TYPE"] != "CHECKBOX" &&  $name!= "ORDER_PROP_17"):?>
						<label for="sppd-property-<?=$key?>">
							<?= $property["NAME"]?>:
							<?
							if ($property["REQUIED"] == "Y")
							{
								?>
								<span class="req">*</span>
								<?
							}
							?>
						</label>
					<?endif;?>
					<div class="field-wrp">
						<?
						if ($property["TYPE"] == "CHECKBOX")
						{
							?>
							<div class="field-wrp">
								<input
									class=""
									id="sppd-property-<?=$key?>"
									type="checkbox"
									name="<?=$name?>"
									value="Y"
									<?if ($currentValue == "Y" || !isset($currentValue) && $property["DEFAULT_VALUE"] == "Y") echo " checked";?>/>
								<label for="sppd-property-<?=$key?>">
									<?= $property["NAME"]?>:
									<?
									if ($property["REQUIED"] == "Y")
									{
										?>
										<span class="req">*</span>
										<?
									}
									?>
								</label>
							</div>
							<?
						}
						elseif ($property["TYPE"] == "TEXT" && $name!= "ORDER_PROP_17")
						{
							?>
							<input
								class="field"
								type="text" name="<?=$name?>"
								maxlength="50"
								id="sppd-property-<?=$key?>"
								value="<?=$currentValue?>"/>
							<?
						}
						elseif ($property["TYPE"] == "SELECT")
						{
							?>
							<select
								class="field"
								name="<?=$name?>"
								id="sppd-property-<?=$key?>"
								size="<?echo (intval($property["SIZE1"])>0)?$property["SIZE1"]:1; ?>">
									<?
									foreach ($property["VALUES"] as $value)
									{
										?>
										<option value="<?= $value["VALUE"]?>" <?if ($value["VALUE"] == $currentValue || !isset($currentValue) && $value["VALUE"]==$property["DEFAULT_VALUE"]) echo " selected"?>>
											<?= $value["NAME"]?>
										</option>
										<?
									}
									?>
							</select>
							<?
						}
						elseif ($property["TYPE"] == "MULTISELECT")
						{
							?>
							<select
								class="field"
								id="sppd-property-<?=$key?>"
								multiple name="<?=$name?>[]"
								size="<?echo (intval($property["SIZE1"])>0)?$property["SIZE1"]:5; ?>">
									<?
									$arCurVal = array();
									$arCurVal = explode(",", $currentValue);
									for ($i = 0, $cnt = count($arCurVal); $i < $cnt; $i++)
										$arCurVal[$i] = trim($arCurVal[$i]);
									$arDefVal = explode(",", $property["DEFAULT_VALUE"]);
									for ($i = 0, $cnt = count($arDefVal); $i < $cnt; $i++)
										$arDefVal[$i] = trim($arDefVal[$i]);
									foreach($property["VALUES"] as $value)
									{
										?>
										<option value="<?= $value["VALUE"]?>"<?if (in_array($value["VALUE"], $arCurVal) || !isset($currentValue) && in_array($value["VALUE"], $arDefVal)) echo" selected"?>><?echo $value["NAME"]?></option>
										<?
									}
									?>
							</select>
							<?
						}
						elseif ($property["TYPE"] == "TEXTAREA")
						{
							?>
							<textarea
								class="field"
								id="sppd-property-<?=$key?>"
								rows="<?echo ((int)($property["SIZE2"])>0)?$property["SIZE2"]:4; ?>"
								cols="<?echo ((int)($property["SIZE1"])>0)?$property["SIZE1"]:40; ?>"
								name="<?=$name?>"><?= (isset($currentValue)) ? $currentValue : $property["DEFAULT_VALUE"];?>
							</textarea>
							<?
						}
						elseif ($property["TYPE"] == "LOCATION")
						{
							$locationTemplate = ($arParams['USE_AJAX_LOCATIONS'] !== 'Y') ? "popup" : "";

							$locationValue = intval($currentValue) ? $currentValue : $property["DEFAULT_VALUE"];
							CSaleLocation::proxySaleAjaxLocationsComponent(
								array(
									"AJAX_CALL" => "N",
									'CITY_OUT_LOCATION' => 'Y',
									'COUNTRY_INPUT_NAME' => $name.'_COUNTRY',
									'CITY_INPUT_NAME' => $name,
									'LOCATION_VALUE' => $locationValue,
								),
								array(
								),
								$locationTemplate,
								true,
								'location-block-wrapper'
							);

						}
						elseif ($property["TYPE"] == "RADIO")
						{
							foreach($property["VALUES"] as $value)
							{
								?>
								<input
									class=""
									type="radio"
									id="sppd-property-<?=$key?>"
									name="<?=$name?>"
									value="<?echo $value["VALUE"]?>"
									<?if ($value["VALUE"] == $currentValue || !isset($currentValue) && $value["VALUE"] == $property["DEFAULT_VALUE"]) echo " checked"?>>
								<?= $value["NAME"]?><br />
								<?
							}
						}
						?>
					</div>
					<?
				}
			}
		}
		?>
		<input type="submit" class="btn btn_black" name="save" value="<?echo GetMessage("SALE_SAVE") ?>">
		&nbsp;
		<?/*<input type="submit" class="btn btn-themes btn-default btn-md"  name="apply" value="<?=GetMessage("SALE_APPLY")?>">
		&nbsp;*/?>
		<input type="submit" class="btn btn_black"  name="reset" value="<?echo GetMessage("SALE_RESET")?>">
	</form>

	<script>
		BX.message({
			SPPD_FILE_COUNT: '<?=Loc::getMessage('SPPD_FILE_COUNT')?>',
			SPPD_FILE_NOT_SELECTED: '<?=Loc::getMessage('SPPD_FILE_NOT_SELECTED')?>'
		});
		BX.Sale.PersonalProfileComponent.PersonalProfileDetail.init();
	</script>
	<?
}
else
{
	ShowError($arResult["ERROR_MESSAGE"]);
}
?>
</div>