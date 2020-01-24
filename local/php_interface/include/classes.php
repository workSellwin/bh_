<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(null, Array(
	"\\Kosmos\\Multisite" => "/local/php_interface/classes/multisite.php",
	"\\Kosmos\\Export" => "/local/php_interface/classes/export.php",
	"\\Kosmos\\Discount" => "/local/php_interface/classes/discount.php",
	"CUserTypeElementIblockReference" => "/local/php_interface/include/fields/iblock_element_reference.php",
));


class Mitlab{
	function getProductNds($ELEMENT_ID){
		Cmodule::IncludeModule('iblock');
		$resQwery = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => array('2', '3'), 'ID' => $ELEMENT_ID), false, false, array('ID', 'XML_ID', 'PROPERTY_NDS'));
		if ($resQweryOut = $resQwery->GetNext()) {
			if ($resQweryOut['PROPERTY_NDS_VALUE']) {
				return $resQweryOut['PROPERTY_NDS_VALUE'];
			}else{
				return 20;
			}
		}else{
			return 20;
		}
	}
	function getOrderWeight($ORDER_ID){
		$order = \Bitrix\Sale\Order::load($ORDER_ID);
		$basket = $order->getBasket();
		return $basket->getWeight();
	}

	function getOrderData($ORDER_ID,$order)
	{
		if(!is_object($order)){
			$order = \Bitrix\Sale\Order::load($ORDER_ID);
		}

		$propertyCollection = $order->getPropertyCollection();
		$dateOrderDeliveryProperty = $propertyCollection->getItemByOrderPropertyId(21);
		if (is_object($dateOrderDeliveryProperty)) {
			$dateOrderDelivery = ($dateOrderDeliveryProperty->getValue()) ? $dateOrderDeliveryProperty->getValue() : date('d.m.Y');
		}

		$outletIdProperty = $propertyCollection->getItemByOrderPropertyId(27);
		if(is_object($outletIdProperty)){
			$outletId = $outletIdProperty->getValue();
		}
		if(!$outletId){
			$outletId = 124563;
		}

		/*$pricetypeIdProperty = $propertyCollection->getItemByOrderPropertyId(26);
		if(is_object($pricetypeIdProperty)){
			$pricetypeIdValue = $pricetypeIdProperty->getValue();
		}

		switch ($pricetypeIdValue){
			case 'late-0':
				$pricetypeId = 21;
				break;
			case 'late-14':
				$pricetypeId = 7;
				break;
			case 'late-30':
				$pricetypeId = 11;
				break;
			default:
				$pricetypeId = 21;
				break;
		}*/
		$paySystenId = $order->GetField('PAY_SYSTEM_ID');
		switch ($paySystenId) {
			case 12:
				$pricetypeId = 21;
				break;
			case 14:
				$pricetypeId = 7;
				break;
			case 15:
				$pricetypeId = 11;
				break;
			default:
				$pricetypeId = '';
				break;
		}

		$data_send = new \Bitrix\Main\Type\DateTime($dateOrderDelivery);

		$basket = $order->getBasket();
		foreach ($basket as $basketItem) {
			$arItems["ID"] = $basketItem->getId();
			$arItems["PRODUCT_ID"] = $basketItem->getProductId();
			$arItems["NAME"] = $basketItem->getField('NAME');
			$arItems["PRODUCT_XML_ID"] = $basketItem->getField('PRODUCT_XML_ID');
			$arItems["QUANTITY"] = $basketItem->getQuantity();

			$resQwery = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => array('2', '3'), 'ID' => $arItems["PRODUCT_ID"]), false, false, array('ID', 'XML_ID', 'PROPERTY_SKLAD'));
			if ($resQweryOut = $resQwery->GetNext()) {
				if ($resQweryOut['ID'] == $arItems["PRODUCT_ID"]) {
					$arItems['STORAGE'] = $resQweryOut['PROPERTY_SKLAD_VALUE'];
				}
				if ($resQweryOut['XML_ID']) {
					$arItems["PRODUCT_XML_ID_NEW"] = $resQweryOut['XML_ID'];
				} else {
					$arItems["PRODUCT_XML_ID_NEW"] = $arItems["PRODUCT_XML_ID"];
				}
			}

			$arResult['ITEMS'][] = $arItems;
			switch ($arItems['STORAGE']) {
				case 89:
					$arResult['ITEMS_TO_STORAGE'][89]['spec'][] = array("goods_id" => $arItems["PRODUCT_XML_ID_NEW"], "count" => $arItems["QUANTITY"]);
					break;
				case 91:
					$arResult['ITEMS_TO_STORAGE'][91]['spec'][] = array("goods_id" => $arItems["PRODUCT_XML_ID_NEW"], "count" => $arItems["QUANTITY"]);
					break;
			}
		}

		$resToJson['agent_id'] = '900FC2CB-2CF7-42D0-8024-E6E0B761523C';
		$resToJson['docdate'] = date('Y-m-d H:i:s');
		$resToJson['created_datetime'] = $resToJson['docdate'];
		$resToJson['posted_datetime'] = $resToJson['docdate'];
		$resToJson['lt'] = '53.825065';
		$resToJson['ln'] = '27.433957';
		$resToJson['deliverydate'] = $data_send->format('Y-m-d H:i:s');
		$resToJson['outlet_id'] = $outletId;
		$resToJson['payform_id'] = 1;
		$resToJson['pricetype_id'] = $pricetypeId;
		$resToJson['order_type_id'] = 1;
		$resToJson['delivery_type_id'] = 2;
		$resToJson['not_join'] = 0;
		$resToJson['time_from'] = '';
		$resToJson['time_to'] = '';
		$resToJson['note'] = '';

		if ($arResult['ITEMS_TO_STORAGE'][89]) {
			$arResult['ITEMS_TO_STORAGE'][89] = array_merge($resToJson, $arResult['ITEMS_TO_STORAGE'][89]);
			$arResult['ITEMS_TO_STORAGE'][89]['warehouse_id'] = 89;
			$arResult['ITEMS_TO_STORAGE'][89]['uuid'] = $ORDER_ID.'-89';
			$arResult['ITEMS_TO_STORAGE'][89]['visit_uuid'] = $ORDER_ID.'-89';
		}
		if ($arResult['ITEMS_TO_STORAGE'][91]) {
			$arResult['ITEMS_TO_STORAGE'][91] = array_merge($resToJson, $arResult['ITEMS_TO_STORAGE'][91]);
			$arResult['ITEMS_TO_STORAGE'][91]['warehouse_id'] = 91;
			$arResult['ITEMS_TO_STORAGE'][91]['uuid'] = $ORDER_ID.'-91';
			$arResult['ITEMS_TO_STORAGE'][91]['visit_uuid'] = $ORDER_ID.'-91';
		}
		return $arResult;
	}
}