<?
use Bitrix\Iblock;
IncludeModuleLangFile(__FILE__);

class CUserTypeElementIblock extends CUserTypeInteger
{
	const USER_TYPE_ID = 'elementiblock';

	function GetUserTypeDescription()
	{
		return array(
			"USER_TYPE_ID" => static::USER_TYPE_ID,
			"CLASS_NAME" => __CLASS__,
			"DESCRIPTION" => "элемент инфоблока новый",
			"BASE_TYPE" => \CUserTypeManager::BASE_TYPE_INT,
			"EDIT_CALLBACK" => array(__CLASS__, 'GetPublicEdit'),
			"VIEW_CALLBACK" => array(__CLASS__, 'GetPublicView'),
		);
	}

	//********************************************************************************************************
	function GetEditFormHTML($arUserField, $arHtmlControl)
	{
		if(CModule::IncludeModule("iblock"));
		if($arUserField["ENTITY_VALUE_ID"]<1 && strlen($arUserField["SETTINGS"]["DEFAULT_VALUE"])>0)
			$arHtmlControl["VALUE"] = htmlspecialcharsbx($arUserField["SETTINGS"]["DEFAULT_VALUE"]);

		$arHtmlControl["VALIGN"] = "middle";

		$res = CIBlockElement::GetByID($arHtmlControl["VALUE"]);
		if($ar_res = $res->GetNext())
			$name = $ar_res['NAME'];

		$windowTableId = 'table_UF_OLD_ELEMENTS';
		$str = '<div>';
		$str .= '<input type="text" '.
			'name="'.$arHtmlControl["NAME"].'" '.
			'id="'.$arHtmlControl["NAME"].'" '.
			'size="'.$arUserField["SETTINGS"]["SIZE"].'" '.
			'value="'.$arHtmlControl["VALUE"].'" '.
			($arUserField["EDIT_IN_LIST"]!="Y"? 'disabled="disabled" ': '').
			'>';
		$str .= '<input type="button" value="..." onClick="jsUtils.OpenWindow(\'/bitrix/admin/iblock_element_search.php?n='.urlencode($arHtmlControl["NAME"]).'&amp;tableId='.$windowTableId.'\', 900, 700);">';
		$str .= '<div style="color:green;" id="sp_'.htmlspecialcharsbx($arHtmlControl["NAME"]).'" >'.$name.'</div>';
		$str .= '</div><br>';

		return $str;
	}
	//********************************************************************************************************




	function GetFilterHTML($arUserField, $arHtmlControl)
	{
		return '<input type="text" '.
			'name="'.$arHtmlControl["NAME"].'" '.
			'size="'.$arUserField["SETTINGS"]["SIZE"].'" '.
			'value="'.$arHtmlControl["VALUE"].'"'.
			'>';
	}

	function GetAdminListViewHTML($arUserField, $arHtmlControl)
	{
		if(strlen($arHtmlControl["VALUE"])>0)
			return $arHtmlControl["VALUE"];
		else
			return '&nbsp;';
	}

	function GetAdminListEditHTML($arUserField, $arHtmlControl)
	{
		return '<input type="text" '.
			'name="'.$arHtmlControl["NAME"].'" '.
			'size="'.$arUserField["SETTINGS"]["SIZE"].'" '.
			'value="'.$arHtmlControl["VALUE"].'" '.
			'>';
	}

	function CheckFields($arUserField, $value)
	{
		$aMsg = array();
		if(strlen($value)>0 && $arUserField["SETTINGS"]["MIN_VALUE"]!=0 && intval($value)<$arUserField["SETTINGS"]["MIN_VALUE"])
		{
			$aMsg[] = array(
				"id" => $arUserField["FIELD_NAME"],
				"text" => GetMessage("USER_TYPE_INTEGER_MIN_VALUE_ERROR",
					array(
						"#FIELD_NAME#"=>$arUserField["EDIT_FORM_LABEL"],
						"#MIN_VALUE#"=>$arUserField["SETTINGS"]["MIN_VALUE"]
					)
				),
			);
		}
		if(strlen($value)>0 && $arUserField["SETTINGS"]["MAX_VALUE"]<>0 && intval($value)>$arUserField["SETTINGS"]["MAX_VALUE"])
		{
			$aMsg[] = array(
				"id" => $arUserField["FIELD_NAME"],
				"text" => GetMessage("USER_TYPE_INTEGER_MAX_VALUE_ERROR",
					array(
						"#FIELD_NAME#"=>$arUserField["EDIT_FORM_LABEL"],
						"#MAX_VALUE#"=>$arUserField["SETTINGS"]["MAX_VALUE"]
					)
				),
			);
		}
		return $aMsg;
	}

	public static function GetPublicView($arUserField, $arAdditionalParameters = array())
	{
		$value = static::normalizeFieldValue($arUserField["VALUE"]);

		$html = '';
		$first = true;
		foreach($value as $res)
		{
			if(!$first)
			{
				$html .= static::getHelper()->getMultipleValuesSeparator();
			}
			$first = false;

			if(strlen($arUserField['PROPERTY_VALUE_LINK']) > 0)
			{
				$res = '<a href="'.htmlspecialcharsbx(str_replace('#VALUE#', intval($res), $arUserField['PROPERTY_VALUE_LINK'])).'">'.$res.'</a>';
			}
			else
			{
				$res = intval($res);
			}

			$html .= static::getHelper()->wrapSingleField($res);
		}

		static::initDisplay();

		return static::getHelper()->wrapDisplayResult($html);
	}


	public function getPublicEdit($arUserField, $arAdditionalParameters = array())
	{
		$fieldName = static::getFieldName($arUserField, $arAdditionalParameters);
		$value = static::getFieldValue($arUserField, $arAdditionalParameters);

		$html = '';

		foreach($value as $res)
		{
			$attrList = array();

			if($arUserField["EDIT_IN_LIST"] != "Y")
			{
				$attrList['disabled'] = 'disabled';
			}

			if($arUserField["SETTINGS"]["SIZE"] > 0)
			{
				$attrList['size'] = intval($arUserField["SETTINGS"]["SIZE"]);
			}

			if(array_key_exists('attribute', $arAdditionalParameters))
			{
				$attrList = array_merge($attrList, $arAdditionalParameters['attribute']);
			}

			if(isset($attrList['class']) && is_array($attrList['class']))
			{
				$attrList['class'] = implode(' ', $attrList['class']);
			}

			$attrList['class'] = static::getHelper()->getCssClassName().(isset($attrList['class']) ? ' '.$attrList['class'] : '');

			$attrList['name'] = $fieldName;

			$attrList['type'] = 'text';
			$attrList['value'] = $res;
			$attrList['tabindex'] = '0';

			$html .= static::getHelper()->wrapSingleField('<input '.static::buildTagAttributes($attrList).'/>');
		}

		if($arUserField["MULTIPLE"] == "Y" && $arAdditionalParameters["SHOW_BUTTON"] != "N")
		{
			$html .= static::getHelper()->getCloneButton($fieldName);
		}

		static::initDisplay();

		return static::getHelper()->wrapDisplayResult($html);
	}


}

AddEventHandler('main', 'OnUserTypeBuildList', array('CUserTypeElementIblock', 'GetUserTypeDescription'), 5000);
?>