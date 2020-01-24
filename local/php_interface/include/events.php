<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Diag\Debug;


$eventManager = \Bitrix\Main\EventManager::getInstance();

$eventManager->addEventHandler("main", "OnEpilog", Array("\\Kosmos\\Multisite", "onEpilog404Handler"));

AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserRegisterHandler");
AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserRegisterHandler");
AddEventHandler("main", "OnBeforeUserAdd", "OnBeforeUserRegisterHandler");

function OnBeforeUserRegisterHandler(&$arFields)
{
	//Debug::writeToFile($arFields);

	if($arFields["ID"]!=1){
		$arFields["LOGIN"] = $arFields["EMAIL"];
		return true;
	}
}

$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementAdd', 'addDefaultImage');
$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', 'addDefaultImage');
function addDefaultImage(&$arFields)
{
	if(
		($arFields['IBLOCK_ID'] == 2) &&
		($arFields['ID'] > 0)
	)
	{
		\Bitrix\Main\Loader::includeModule('iblock');
		$arFilter = [
			'IBLOCK_TYPE' => 'catalog',
			'IBLOCK_ID' => $arFields['IBLOCK_ID'],
			'ID' => $arFields['ID']
		];
		$res = \CIBlockElement::GetList([], $arFilter, false, ['nTopCount' => 1], ['ID', 'DETAIL_PICTURE']);
		if($row = $res->GetNext())
		{
			if(!$row['DETAIL_PICTURE'])
			{
				$file = $_SERVER['DOCUMENT_ROOT'] . '/local/templates/.default/images/no_image.jpg';
				if(file_exists($file))
				{
					$el = new \CIBlockElement;
					$el->Update($arFields['ID'], ['DETAIL_PICTURE' => \CFile::MakeFileArray($file)]);
				}
			}
		}
	}
}

//$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementAdd', 'addExportFields');
//$eventManager->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', 'addExportFields');
function addExportFields($arFields)
{
	if($arFields['ID'] > 0 && ($arFields['IBLOCK_ID'] == 2 || $arFields['IBLOCK_ID'] == 3))
	{
		$productId = $arFields['ID'];
		$iblockId = $arFields['IBLOCK_ID'];

		$price = 0;
		$quantity = '<10';

		\Bitrix\Main\Loader::includeModule('iblock');
		\Bitrix\Main\Loader::includeModule('catalog');

		$result = \Bitrix\Catalog\ProductTable::getList([
			'filter' => ['=ID'=>$productId],
			'select' => ['ID','QUANTITY']
		]);
        if ($row = $result->fetch()) {
            if ($row['QUANTITY'] > 10) {
                $quantity = '>10';
            } else {
                $quantity = $row['QUANTITY'];
            }
        }

		$result = \CPrice::GetList([], ["PRODUCT_ID" => $productId, "CATALOG_GROUP_ID" => 2]);
		if($row = $result->fetch())
		{
			$price = $row['PRICE'];
		}

		\CIBlockElement::SetPropertyValuesEx($productId, $iblockId, [
			'EXPORT_QUANTITY' => $quantity,
			'EXPORT_PRICE' => $price
		]);
	}
}

$eventManager->addEventHandler('catalog', 'OnBeforeProductUpdate', 'addExportFieldsProp');
function addExportFieldsProp($id, $arFields)
{
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "CODE", "PROPERTY_NOT_UPDATE_QUANTITY");
    $arFilter = Array("IBLOCK_ID"=>2, 'ID' => $id);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNext()){
        if($ob['PROPERTY_NOT_UPDATE_QUANTITY_VALUE'] != 'Y'){
            $quantity = '<10';
            if(isset($arFields['QUANTITY'])){
                if ($arFields['QUANTITY'] > 10) {
                    $quantity = '>10';
                } else {
                    $quantity = $arFields['QUANTITY'];
                }
                \Bitrix\Main\Loader::includeModule('iblock');
                \CIBlockElement::SetPropertyValuesEx($id, 2, [
                    'EXPORT_QUANTITY' => $quantity,
                ]);
            }
        }
    }
}



$eventManager->addEventHandler('sale', 'OnSaleOrderSaved', 'addFavorites');
function addFavorites(\Bitrix\Main\Event $event)
{
	$isNew = $event->getParameter("IS_NEW");
	if($isNew)
	{
		$order = $event->getParameter("ENTITY");

		$sid = $order->getSiteId();
		$uid = $order->getUserId();
		$favoritesCode = 'UF_FAVORITES_' . strtoupper($sid);


		$result = \Bitrix\Main\UserTable::getList([
			'select' => [$favoritesCode],
			'filter' => ['ID' => $uid]
		]);

		if($row = $result->fetch())
		{
			$arItems = unserialize($row[$favoritesCode]);

			$basket = $order->getBasket();
			foreach($basket as $item)
			{
				$productId = $item->getProductId();
				if(!in_array($productId, $arItems))
					$arItems[] = $productId;
			}

			global $USER;
			$USER->Update($uid, [$favoritesCode => serialize($arItems)]);
		}
	}
}

$eventManager->addEventHandler('main', 'OnUserTypeBuildList', ['CUserTypeElementIblockReference', 'GetUserTypeDescription'], 5000);

$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', 'importFix');
function importFix(&$arFields)
{
	if ($_REQUEST['mode']=='import')
	{
		unset($arFields['NAME']);
		unset($arFields['IBLOCK_SECTION']);
		unset($arFields['IBLOCK_SECTION_ID']);
	}
}

$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementAdd', 'importActiveFix');
function importActiveFix(&$arFields)
{
	if ($_REQUEST['mode']=='import')
	{
		$arFields['ACTIVE'] = 'N';
	}
}

$eventManager->addEventHandler('sale', 'OnSaleOrderSaved', 'setDateDeliveryRB');

function setDateDeliveryRB(\Bitrix\Main\Event $event)
{
	$isNew = $event->getParameter("IS_NEW");
	if($isNew) {
		$arDeliveryRB = [4, 9, 19];
		$order = $event->getParameter("ENTITY");
		$propertyCollection = $order->getPropertyCollection();
		$deliveryId = $order->getField('DELIVERY_ID');
		$userType = $order->getPersonTypeId();
		if(in_array($deliveryId,$arDeliveryRB)){

			if (date('G') >= 10) {
				$dateDelivery = getWorkingDay(date('d.m.Y'), true);
			} else {
				$dateDelivery = getWorkingDay(date('d.m.Y'));
			}
			if($userType == 1){
				$date = $propertyCollection->getItemByOrderPropertyId(13);
			}else{
				$date = $propertyCollection->getItemByOrderPropertyId(21);
			}
			$date->setValue($dateDelivery);
			$date->save();
		}
	}
}

/*
 * Восстановление оплат заказа
 */
$eventManager->addEventHandler('sale', 'OnBeforeCollectionDeleteItem', 'salePaymentSaveInfo');
$eventManager->addEventHandler('sale', 'OnSaleOrderBeforeSaved', 'salePaymentReverseInfo');

function salePaymentSaveInfo(\Bitrix\Main\Event $event )
{
	if ($_SESSION['BX_CML2_EXPORT'])
	{
		$entity = $event->getParameter('ENTITY');
		if ( $entity instanceof \Bitrix\Sale\Shipment ) {
			if ( !is_array( $_SESSION['BX_CML2_EXPORT']['DELETED_SHIPMENTS'] )  )
				$_SESSION['BX_CML2_EXPORT']['DELETED_SHIPMENTS'] = array();
			if ( !$entity->isSystem() )
				$_SESSION['BX_CML2_EXPORT']['DELETED_SHIPMENTS'][] = salePaymentCheckFields( $entity->getFields()->getValues(), \Bitrix\Sale\Shipment::getAvailableFields() );
		}
		if ($entity instanceof \Bitrix\Sale\Payment)
		{
			if ( !is_array( $_SESSION['BX_CML2_EXPORT']['DELETED_PAYMENTS'] )  )
				$_SESSION['BX_CML2_EXPORT']['DELETED_PAYMENTS'] = [];

			$_SESSION['BX_CML2_EXPORT']['DELETED_PAYMENTS'][] = salePaymentCheckFields( $entity->getFields()->getValues(), \Bitrix\Sale\Payment::getAvailableFields() );
		}
	}
	else
	{
		return;
	}
}

function salePaymentReverseInfo(\Bitrix\Main\Event $event ) {
	if ( $_SESSION['BX_CML2_EXPORT'] )
	{
		$order = $event->getParameter("ENTITY");

		if ( $_SESSION['BX_CML2_EXPORT']['DELETED_SHIPMENTS'] ) {
			//Вернем отгрузки
			$shipmentCollection = $order->getShipmentCollection();
			$systemShipmentItemCollection = $shipmentCollection->getSystemShipment()->getShipmentItemCollection();
			$products = array();
			$basket = $order->getBasket();
			if ($basket)
			{
				/** @var \Bitrix\Sale\BasketItem $product */
				$basketItems = $basket->getBasketItems();
				foreach ($basketItems as $product)
				{
					$systemShipmentItem = $systemShipmentItemCollection->getItemByBasketCode($product->getBasketCode());
					if ($product->isBundleChild() || !$systemShipmentItem || $systemShipmentItem->getQuantity() <= 0)
						continue;
					$products[] = array(
						'AMOUNT' => $product->getQuantity(),
						'BASKET_CODE' => $product->getBasketCode()
					);
				}
			}
			/** @var \Bitrix\Sale\Shipment $obShipment */
			/** @var array $shipmentFields */
			foreach ( $_SESSION['BX_CML2_EXPORT']['DELETED_SHIPMENTS'] as $shipmentFields ) {
				$fg = true;
				foreach( $shipmentCollection as $obShipment ) {
					if ($obShipment->isSystem())
						continue;
					$usedFields = salePaymentCheckFields($obShipment->getFields()->getValues(), \Bitrix\Sale\Shipment::getAvailableFields() );
					if ( count( array_diff_assoc( $shipmentFields, $usedFields) ) == 0 )
						$fg = false;
					//доставка с такими полями уже есть
				}
				if ( $fg ) {
					$shipment = $shipmentCollection->createItem();

					$arDelivery = \Bitrix\Sale\Delivery\Services\Manager::getById($shipmentFields['DELIVERY_ID']);
					if($arDelivery['CONFIG']['MAIN']['PRICE'] > 0)
					{
						$shipmentFields['BASE_PRICE_DELIVERY'] = $shipmentFields['PRICE_DELIVERY'] = $arDelivery['CONFIG']['MAIN']['PRICE'];
					}

					$shipment->setFields( $shipmentFields );

					\Bitrix\Sale\Helpers\Admin\Blocks\OrderBasketShipment::updateData($order, $shipment, $products);
				}
			}
			unset( $_SESSION['BX_CML2_EXPORT']['DELETED_SHIPMENTS'] );
		}

		if ( $_SESSION['BX_CML2_EXPORT']['DELETED_PAYMENTS'] )
		{
			//Вернем оплаты
			$paymentCollection = $order->getPaymentCollection();
			/** @var \Bitrix\Sale\Payment $obPayment */
			/** @var array $paymentFields */
			foreach ( $_SESSION['BX_CML2_EXPORT']['DELETED_PAYMENTS'] as $paymentFields )
			{
				$fg = true;
				foreach( $paymentCollection as $obPayment )
				{
					$usedFields = salePaymentCheckFields( $obPayment->getFields()->getValues(), \Bitrix\Sale\Payment::getAvailableFields() );
					if ( count( array_diff_assoc( $paymentFields, $usedFields) ) == 0 )
					{
						//такая оплата уже есть
						$fg = false;
					}
				}
				if ( $fg )
				{
					$payment = $paymentCollection->createItem();
					$payment->setFields( $paymentFields );
				}
			}
			unset( $_SESSION['BX_CML2_EXPORT']['DELETED_PAYMENTS'] );
		}

		//Проверим сумму заказа
		$paymentCollection = $order->getPaymentCollection();
		if ( ($sumP = $paymentCollection->getSum() ) != ($sumO = $order->getPrice() ) )
		{
			$diff = $sumO - $sumP;
			$innerPayID = \Bitrix\Sale\PaySystem\Manager::getInnerPaySystemId();
			foreach ( $paymentCollection as $payment )
			{
				if ( $payment->getPaymentSystemId() != $innerPayID)
				{
					$newVal = floatval($payment->getField("SUM")) + floatval($diff);
					$payment->setField("SUM", $newVal);
				}
			}
		}
	}
}

function salePaymentCheckFields( $arValues, $allowedFields)
{
	$result = [];
	foreach ( $arValues as $key => $value )
	{
		if ( in_array( $key,$allowedFields ) && !in_array($key, ['ACCOUNT_NUMBER']) )
		{
			$result[$key] = $value;
		}
	}
	return $result;
}

$eventManager->addEventHandler('catalog', 'OnSuccessCatalogImport1C', 'onSuccessCatalogImport1C');
function onSuccessCatalogImport1C($arParams, $arFields)
{
	\Bitrix\Main\Diag\Debug::writeToFile([$arParams, $arFields]);
}

$eventManager->addEventHandler('catalog', 'OnBeforePriceDelete', 'savePrice');
function savePrice($id)
{
	if ($_REQUEST['mode']=='import')
	{
		$arPrice = CPrice::GetByID($id);
		$arBase = CCatalogGroup::GetBaseGroup();

		if($arPrice['CATALOG_GROUP_ID'] != $arBase['ID'])
			return false;
	}
}