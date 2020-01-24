<?php
namespace Kosmos;


class Discount {

	public static function getFull($arrFilter = [], $arSelect = ['ID', 'IBLOCK_ID'])
	{
		if(!\Bitrix\Main\Loader::includeModule('sale'))
			throw new SystemException('Не подключен модуль Sale');

		global $USER;
		$arUserGroups = $USER->GetUserGroupArray();

		$cacheID = 'promo_' . implode('_', array_merge([SITE_ID], $arUserGroups));
		$cacheDir = '/promo';
		$cacheTtl = 3600000;

		$obCache = new \CPHPCache;
		if($obCache->InitCache($cacheTtl, $cacheID, $cacheDir))
		{
			$productsArray = $obCache->GetVars();
		}
		elseif($obCache->StartDataCache())
		{
			global $CACHE_MANAGER;
			$CACHE_MANAGER->StartTagCache($cacheDir);

			if (!is_array($arUserGroups))
				$arUserGroups = [$arUserGroups];
			$actionsNotTemp = \CSaleDiscount::GetList(["ID" => "ASC"],["USER_GROUPS" => $arUserGroups],false,false,["ID"]);

			while($actionNot = $actionsNotTemp->fetch())
			{
				$actionIds[] = $actionNot['ID'];
			}

			$actionIds = array_unique($actionIds);
			sort($actionIds);

			$objDate = new \Bitrix\Main\Type\DateTime();
			$date = $objDate->toString();

			$conditionLogic = array('Equal'=>'=','Not'=>'!','Great'=>'>','Less'=>'<','EqGr'=>'>=','EqLs'=>'<=');
			$arSelect = array_merge(["ID","IBLOCK_ID","XML_ID"],$arSelect);

			$actions = \Bitrix\Sale\Internals\DiscountTable::getList(array(
				'select' => ["ID","ACTIONS_LIST"],
				'filter' => [
					"ACTIVE"=>"Y",
					"USE_COUPONS"=>"N",
					"DISCOUNT_TYPE"=>"P",
					"LID"=>SITE_ID,
					"ID"=>$actionIds,
					[
						"LOGIC" => "OR",
						[
							"<=ACTIVE_FROM"=>$date,
							">=ACTIVE_TO"=>$date
						],
						[
							"=ACTIVE_FROM"=>false,
							">=ACTIVE_TO"=>$date
						],
						[
							"<=ACTIVE_FROM"=>$date,
							"=ACTIVE_TO"=>false
						],
						[
							"=ACTIVE_FROM"=>false,
							"=ACTIVE_TO"=>false
						]
					]
				]
			));

			while($arrAction = $actions->fetch())
			{
				$arrActions[$arrAction['ID']] = $arrAction;
			}

			foreach($arrActions as $actionId => $action)
			{
				$arPredFilter = array_merge(["ACTIVE_DATE"=>"Y", "CAN_BUY"=>"Y"],$arrFilter);
				$arFilter = $arPredFilter;
				$dopArFilter = $arPredFilter;
				$dopArFilter["=XML_ID"] = [];

				foreach($action['ACTIONS_LIST']['CHILDREN'] as $condition)
				{
					foreach($condition['CHILDREN'] as $keyConditionSub=>$conditionSub)
					{
						$cs=$conditionSub['DATA']['value'];
						$cls=$conditionLogic[$conditionSub['DATA']['logic']];
						$CLASS_ID = explode(':',$conditionSub['CLASS_ID']);

						if($CLASS_ID[0]=='ActSaleSubGrp')
						{
							foreach($conditionSub['CHILDREN'] as $keyConditionSubElem=>$conditionSubElem)
							{
								$cse=$conditionSubElem['DATA']['value'];
								$clse=$conditionLogic[$conditionSubElem['DATA']['logic']];
								$CLASS_ID_EL = explode(':',$conditionSubElem['CLASS_ID']);

								if($CLASS_ID_EL[0]=='CondIBProp')
								{
									$arFilter["IBLOCK_ID"]=$CLASS_ID_EL[1];
									$arFilter[$clse."PROPERTY_".$CLASS_ID_EL[2]]=array_merge((array)$arFilter[$clse."PROPERTY_".$CLASS_ID_EL[2]],(array)$cse);
									$arFilter[$clse."PROPERTY_".$CLASS_ID_EL[2]]=array_unique($arFilter[$clse."PROPERTY_".$CLASS_ID_EL[2]]);
								}
								elseif($CLASS_ID_EL[0]=='CondIBName')
								{
									$arFilter[$clse."NAME"]=array_merge((array)$arFilter[$clse."NAME"],(array)$cse);
									$arFilter[$clse."NAME"]=array_unique($arFilter[$clse."NAME"]);
								}
								elseif($CLASS_ID_EL[0]=='CondIBElement')
								{
									$arFilter[$clse."ID"]=array_merge((array)$arFilter[$clse."ID"],(array)$cse);
									$arFilter[$clse."ID"]=array_unique($arFilter[$clse."ID"]);
								}
								elseif($CLASS_ID_EL[0]=='CondIBTags')
								{
									$arFilter[$clse."TAGS"]=array_merge((array)$arFilter[$clse."TAGS"],(array)$cse);
									$arFilter[$clse."TAGS"]=array_unique($arFilter[$clse."TAGS"]);
								}
								elseif($CLASS_ID_EL[0]=='CondIBSection')
								{
									$arFilter[$clse."SECTION_ID"]=array_merge((array)$arFilter[$clse."SECTION_ID"],(array)$cse);
									$arFilter[$clse."SECTION_ID"]=array_unique($arFilter[$clse."SECTION_ID"]);
								}
								elseif($CLASS_ID_EL[0]=='CondIBXmlID')
								{
									$arFilter[$clse."XML_ID"]=array_merge((array)$arFilter[$clse."XML_ID"],(array)$cse);
									$arFilter[$clse."XML_ID"]=array_unique($arFilter[$clse."XML_ID"]);
								}
								elseif($CLASS_ID_EL[0]=='CondBsktAppliedDiscount')
								{
									foreach($arrActions as $tempAction)
									{
										if(
											($tempAction['SORT']<$action['SORT']&&$tempAction['PRIORITY']>$action['PRIORITY']&&$cse=='N')||
											($tempAction['SORT']>$action['SORT']&&$tempAction['PRIORITY']<$action['PRIORITY']&&$cse=='Y'))
										{
											$arFilter=false;
											break 4;
										}
									}
								}
							}
						}
						elseif($CLASS_ID[0]=='CondIBProp')
						{
							$arFilter["IBLOCK_ID"]=$CLASS_ID[1];
							$arFilter[$cls."PROPERTY_".$CLASS_ID[2]]=array_merge((array)$arFilter[$cls."PROPERTY_".$CLASS_ID[2]],(array)$cs);
							$arFilter[$cls."PROPERTY_".$CLASS_ID[2]]=array_unique($arFilter[$cls."PROPERTY_".$CLASS_ID[2]]);
						}
						elseif($CLASS_ID[0]=='CondIBName')
						{
							$arFilter[$cls."NAME"]=array_merge((array)$arFilter[$cls."NAME"],(array)$cs);
							$arFilter[$cls."NAME"]=array_unique($arFilter[$cls."NAME"]);
						}
						elseif($CLASS_ID[0]=='CondIBElement')
						{
							$arFilter[$cls."ID"]=array_merge((array)$arFilter[$cls."ID"],(array)$cs);
							$arFilter[$cls."ID"]=array_unique($arFilter[$cls."ID"]);
						}
						elseif($CLASS_ID[0]=='CondIBTags')
						{
							$arFilter[$cls."TAGS"]=array_merge((array)$arFilter[$cls."TAGS"],(array)$cs);
							$arFilter[$cls."TAGS"]=array_unique($arFilter[$cls."TAGS"]);
						}
						elseif($CLASS_ID[0]=='CondIBSection')
						{
							$arFilter[$cls."SECTION_ID"]=array_merge((array)$arFilter[$cls."SECTION_ID"],(array)$cs);
							$arFilter[$cls."SECTION_ID"]=array_unique($arFilter[$cls."SECTION_ID"]);
						}
						elseif($CLASS_ID[0]=='CondIBXmlID')
						{
							$arFilter[$cls."XML_ID"]=array_merge((array)$arFilter[$cls."XML_ID"],(array)$cs);
							$arFilter[$cls."XML_ID"]=array_unique($arFilter[$cls."XML_ID"]);
						}
						elseif($CLASS_ID[0]=='CondBsktAppliedDiscount')
						{
							foreach($arrActions as $tempAction)
							{
								if(
									($tempAction['SORT']<$action['SORT']&&$tempAction['PRIORITY']>$action['PRIORITY']&&$cs=='N')||
									($tempAction['SORT']>$action['SORT']&&$tempAction['PRIORITY']<$action['PRIORITY']&&$cs=='Y'))
								{
									$arFilter=false;
									break 3;
								}
							}
						}
					}
				}

				if($arFilter!==false&&$arFilter!=$arPredFilter)
				{
					if(!isset($arFilter['=XML_ID']))
					{
						$res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
						while($ob = $res->GetNextElement())
						{
							$arFields = $ob->GetFields();

							/*$arPrice = \CCatalogProduct::GetOptimalPrice($arFields['ID'], 1, $USER->GetUserGroupArray());
							if($arPrice['RESULT_PRICE']['DISCOUNT'])*/
								$productsArray[] = $arFields["ID"];

							$CACHE_MANAGER->RegisterTag("iblock_id_" . $arFields['IBLOCK_ID']);
						}
					}
					elseif(!empty($arFilter['=XML_ID']))
					{
						$dopArFilter['=XML_ID'] = array_unique(array_merge($arFilter['=XML_ID'],$dopArFilter['=XML_ID']));
					}
				}
			}

			if(isset($dopArFilter)&&!empty($dopArFilter['=XML_ID']))
			{
				$res = \CIBlockElement::GetList([], $dopArFilter, false, ["nTopCount"=>count($dopArFilter['=XML_ID'])], $arSelect);
				while($ob = $res->GetNextElement())
				{
					$arFields = $ob->GetFields();
					$productsArray[] = $arFields["ID"];
				}
			}
			$productsArray=array_unique($productsArray);

			$CACHE_MANAGER->EndTagCache();
			$obCache->EndDataCache($productsArray);
		}
		else
		{
			$productsArray = [];
		}

		return $productsArray;
	}

}