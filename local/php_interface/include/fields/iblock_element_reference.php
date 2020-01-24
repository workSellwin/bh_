<?
use Bitrix\Iblock;
IncludeModuleLangFile(__FILE__);

class CUserTypeElementIblockReference extends CUserTypeInteger
{
	public function GetUserTypeDescription()
	{
		return array(
			"USER_TYPE_ID" => 'element_iblock_reference',
			"CLASS_NAME" => __CLASS__,
			"DESCRIPTION" => "Привязка к элементу инфоблока (с добавлением)",
			"BASE_TYPE" => \CUserTypeManager::BASE_TYPE_INT
		);
	}

	function PrepareSettings($arUserField)
	{
		$height = intval($arUserField["SETTINGS"]["LIST_HEIGHT"]);
		$disp = $arUserField["SETTINGS"]["DISPLAY"];
		if($disp!="CHECKBOX" && $disp!="LIST")
			$disp = "LIST";
		$iblock_id = intval($arUserField["SETTINGS"]["IBLOCK_ID"]);
		if($iblock_id <= 0)
			$iblock_id = "";
		$element_id = intval($arUserField["SETTINGS"]["DEFAULT_VALUE"]);
		if($element_id <= 0)
			$element_id = "";

		$active_filter = $arUserField["SETTINGS"]["ACTIVE_FILTER"] === "Y"? "Y": "N";

		return array(
			"DISPLAY" => $disp,
			"LIST_HEIGHT" => ($height < 1? 1: $height),
			"IBLOCK_ID" => $iblock_id,
			"DEFAULT_VALUE" => $element_id,
			"ACTIVE_FILTER" => $active_filter,
		);
	}

	function GetSettingsHTML($arUserField = false, $arHtmlControl, $bVarsFromForm)
	{
		$result = '';

		if($bVarsFromForm)
			$iblock_id = $GLOBALS[$arHtmlControl["NAME"]]["IBLOCK_ID"];
		elseif(is_array($arUserField))
			$iblock_id = $arUserField["SETTINGS"]["IBLOCK_ID"];
		else
			$iblock_id = "";
		if(CModule::IncludeModule('iblock'))
		{
			$result .= '
			<tr>
				<td>'.GetMessage("USER_TYPE_IBEL_DISPLAY").':</td>
				<td>
					'.GetIBlockDropDownList($iblock_id, $arHtmlControl["NAME"].'[IBLOCK_TYPE_ID]', $arHtmlControl["NAME"].'[IBLOCK_ID]', false, 'class="adm-detail-iblock-types"', 'class="adm-detail-iblock-list"').'
				</td>
			</tr>
			';
		}
		else
		{
			$result .= '
			<tr>
				<td>'.GetMessage("USER_TYPE_IBEL_DISPLAY").':</td>
				<td>
					<input type="text" size="6" name="'.$arHtmlControl["NAME"].'[IBLOCK_ID]" value="'.htmlspecialcharsbx($value).'">
				</td>
			</tr>
			';
		}

		if($bVarsFromForm)
			$ACTIVE_FILTER = $GLOBALS[$arHtmlControl["NAME"]]["ACTIVE_FILTER"] === "Y"? "Y": "N";
		elseif(is_array($arUserField))
			$ACTIVE_FILTER = $arUserField["SETTINGS"]["ACTIVE_FILTER"] === "Y"? "Y": "N";
		else
			$ACTIVE_FILTER = "N";

		if($bVarsFromForm)
			$value = $GLOBALS[$arHtmlControl["NAME"]]["DEFAULT_VALUE"];
		elseif(is_array($arUserField))
			$value = $arUserField["SETTINGS"]["DEFAULT_VALUE"];
		else
			$value = "";
		if(($iblock_id > 0) && CModule::IncludeModule('iblock'))
		{
			$result .= '
			<tr>
				<td>'.GetMessage("USER_TYPE_IBEL_DEFAULT_VALUE").':</td>
				<td>
					<select name="'.$arHtmlControl["NAME"].'[DEFAULT_VALUE]" size="5">
						<option value="">'.GetMessage("IBLOCK_VALUE_ANY").'</option>
			';

			$arFilter = Array("IBLOCK_ID"=>$iblock_id);
			if($ACTIVE_FILTER === "Y")
				$arFilter["ACTIVE"] = "Y";

			$rs = CIBlockElement::GetList(
				array("NAME" => "ASC", "ID" => "ASC"),
				$arFilter,
				false,
				false,
				array("ID", "NAME")
			);
			while($ar = $rs->GetNext())
				$result .= '<option value="'.$ar["ID"].'"'.($ar["ID"]==$value? " selected": "").'>'.$ar["NAME"].'</option>';

			$result .= '</select>';
		}
		else
		{
			$result .= '
			<tr>
				<td>'.GetMessage("USER_TYPE_IBEL_DEFAULT_VALUE").':</td>
				<td>
					<input type="text" size="8" name="'.$arHtmlControl["NAME"].'[DEFAULT_VALUE]" value="'.htmlspecialcharsbx($value).'">
				</td>
			</tr>
			';
		}

		if($bVarsFromForm)
			$value = $GLOBALS[$arHtmlControl["NAME"]]["DISPLAY"];
		elseif(is_array($arUserField))
			$value = $arUserField["SETTINGS"]["DISPLAY"];
		else
			$value = "LIST";
		$result .= '
		<tr>
			<td class="adm-detail-valign-top">'.GetMessage("USER_TYPE_ENUM_DISPLAY").':</td>
			<td>
				<label><input type="radio" name="'.$arHtmlControl["NAME"].'[DISPLAY]" value="LIST" '.("LIST"==$value? 'checked="checked"': '').'>'.GetMessage("USER_TYPE_IBEL_LIST").'</label><br>
				<label><input type="radio" name="'.$arHtmlControl["NAME"].'[DISPLAY]" value="CHECKBOX" '.("CHECKBOX"==$value? 'checked="checked"': '').'>'.GetMessage("USER_TYPE_IBEL_CHECKBOX").'</label><br>
			</td>
		</tr>
		';

		if($bVarsFromForm)
			$value = intval($GLOBALS[$arHtmlControl["NAME"]]["LIST_HEIGHT"]);
		elseif(is_array($arUserField))
			$value = intval($arUserField["SETTINGS"]["LIST_HEIGHT"]);
		else
			$value = 5;
		$result .= '
		<tr>
			<td>'.GetMessage("USER_TYPE_IBEL_LIST_HEIGHT").':</td>
			<td>
				<input type="text" name="'.$arHtmlControl["NAME"].'[LIST_HEIGHT]" size="10" value="'.$value.'">
			</td>
		</tr>
		';

		$result .= '
		<tr>
			<td>'.GetMessage("USER_TYPE_IBEL_ACTIVE_FILTER").':</td>
			<td>
				<input type="checkbox" name="'.$arHtmlControl["NAME"].'[ACTIVE_FILTER]" value="Y" '.($ACTIVE_FILTER=="Y"? 'checked="checked"': '').'>
			</td>
		</tr>
		';

		return $result;
	}

	function GetEditFormHTML($arUserField, $arHtmlControl)
	{
		$inputValueString = '';
		if($arHtmlControl['VALUE'])
		{
			$res = \Bitrix\Iblock\ElementTable::getList([
				'select' => ['ID', 'NAME'],
				'filter' => [
					'ID' => $arHtmlControl['VALUE'],
					'IBLOCK_ID' => $arUserField['SETTINGS']['IBLOCK_ID']
				],
				'limit' => 1
			]);
			if($row = $res->fetch())
			{
				$inputValueString = $row['NAME'] . ' [' . $row['ID'] . ']';
			}
		}

		global $APPLICATION;

		ob_start();

		$control_id = $APPLICATION->IncludeComponent(
			'bitrix:main.lookup.input',
			'iblockedit',
			array(
				'CONTROL_ID' => preg_replace(
					"/[^a-zA-Z0-9_]/i",
					"x",
					$arHtmlControl['NAME'].'_'.mt_rand(0, 10000)
				),
				'INPUT_NAME' => $arHtmlControl['NAME'],
				'INPUT_NAME_STRING' => 'inp_' . $arHtmlControl['NAME'],
				'INPUT_VALUE_STRING' => $inputValueString,
				'START_TEXT' => 'Начните вводить название элемента',
				'MULTIPLE' => 'N',
				'IBLOCK_ID' => $arUserField['SETTINGS']['IBLOCK_ID'],
				'WITHOUT_IBLOCK' => 'N',
				'BAN_SYM' => '',
				'REP_SYM' => '',
				'MAX_WIDTH' => '',
				'MIN_HEIGHT' => '',
				'MAX_HEIGHT' => '',
				'FILTER' => 'Y'
			),
			null,
			array('HIDE_ICONS' => 'Y')
		);

		$strButtonCaption = 'Добавить';
		$editLink = \CIBlock::GetAdminElementEditLink(
				$arUserField['SETTINGS']['IBLOCK_ID'],
				null,
				array(
					"menu" => null,
					"IBLOCK_SECTION_ID" => -1,
					"find_section_section" => -1,
					"lookup" => "jsMLI_".$control_id
				));

		echo '<input type="button" style="margin-top: 5px;" value="'.htmlspecialcharsbx($strButtonCaption).'"
            title="'.$strButtonCaption.'"
            onclick="jsUtils.OpenWindow(\'/bitrix/admin/'.$editLink.'\', 900, 600);">';

		$result = ob_get_contents();
		ob_end_clean();

		return $result;
	}

}
?>