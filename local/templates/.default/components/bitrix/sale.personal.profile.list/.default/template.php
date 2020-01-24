<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if(strlen($arResult["ERROR_MESSAGE"])>0)
{
	ShowError($arResult["ERROR_MESSAGE"]);
}
if(strlen($arResult["NAV_STRING"]) > 0)
{
	?>
	<p><?=$arResult["NAV_STRING"]?></p>
	<?
}
if (count($arResult["PROFILES"]))
{
	?>
	<div class="cnt">
		<div class="table-wrp">
		<table class="table sale-personal-profile-list-container">
			<tr>
				<th>Название</th>
				<th>Ф.И.О.</th>
				<th>Телефон</th>
				<th>Адрес</th>
				<th><?=Loc::getMessage("SALE_ACTION")?></th>
			</tr>
			<?foreach($arResult["PROFILES"] as $val)
			{
				unset($arAddress);
				$resultUserProperties = CSaleOrderUserPropsValue::GetList(
					array("SORT" => "ASC"),
					array("USER_PROPS_ID" => $val["ID"]),
					false,
					false,
					array("ID", "ORDER_PROPS_ID", "VALUE", "SORT", "USER_PROPS_ID")
				);
				while ($userProperty = $resultUserProperties->GetNext())
				{
					//GmiPrint($userProperty);
					$arAddress[$userProperty["ORDER_PROPS_ID"]] = $userProperty;
				}

				if($arAddress[18])
				{
					$resAddress = $arAddress[18]['VALUE'];
				}
				else
				{
					$arLocs = CSaleLocation::GetByID($arAddress[6]["VALUE"], LANGUAGE_ID);
					$resAddress = '';
					if(!empty($arAddress[6]["VALUE"])){
						$arLocs = CSaleLocation::GetByID($arAddress[6]["VALUE"], LANGUAGE_ID);
						if(!empty($arLocs["CITY_NAME"])){
							$resAddress = $arLocs["CITY_NAME"];
						}
					}
					if(!empty($arAddress[16]["VALUE"])){
						$resAddress .= " ".$arAddress[16]["VALUE"];
					}
					if(!empty($arAddress[16]["VALUE"])){
						$resAddress .= " д.".$arAddress[14]["VALUE"];
					}
					if(!empty($arAddress[16]["VALUE"])){
						$resAddress .= " кв.".$arAddress[15]["VALUE"];
					}
				}

				$name = ($arAddress[19]['VALUE']) ? $arAddress[19]['VALUE'] : $arAddress[1]["VALUE"];
				$phone = ($arAddress[20]['VALUE']) ? $arAddress[20]['VALUE'] : $arAddress[3]["VALUE"];


				?>
				<tr>
					<td><?= $val["NAME"] ?></td>
					<td><?=$name?></td>
					<td><?=$phone?></td>
					<td><?= $resAddress;?></td>
					<td class="sale-personal-profile-list-actions">
						<a class="sale-personal-profile-list-change-button" title="<?= Loc::getMessage("SALE_DETAIL_DESCR") ?>"
							href="<?= $val["URL_TO_DETAIL"] ?>"><?= GetMessage("SALE_DETAIL") ?>
						</a>
						<a class="del-cart" title="<?= Loc::getMessage("SALE_DELETE_DESCR") ?>"
							href="javascript:if(confirm('<?= Loc::getMessage("STPPL_DELETE_CONFIRM") ?>')) window.location='<?= $val["URL_TO_DETELE"] ?>'">
						</a>
					</td>
				</tr>
				<?
			}?>
		</table>
		</div>
	</div>	
	<?
	if(strlen($arResult["NAV_STRING"]) > 0)
	{
		?>
		<p><?=$arResult["NAV_STRING"]?></p>
		<?
	}
}
else
{
	?>
	<h3><?=Loc::getMessage("STPPL_EMPTY_PROFILE_LIST") ?></h3>
	<?
}
?>
<?/*<a data-fancybox="" data-src="#add_profiles" href="javascript:;" class="btn btn_black">Добавить профиль</a>*/?>