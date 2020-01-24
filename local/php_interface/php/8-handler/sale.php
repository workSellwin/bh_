<?php

use Bitrix\Main;
use Bitrix\Main\EventManager;

/**
 * Вызывается в компоненте bitrix:sale.order.ajax после создания заказа
 * и всех его параметров, после отправки письма, перед выводом страницы
 * об успешно созданном заказе и оплате заказа.
 */
//AddEventHandler("sale", "OnSaleComponentOrderOneStepFinal", "PriorityOrder");

AddEventHandler("sale", "OnSaleComponentOrderOneStepFinal", "SenderSms");

AddEventHandler("main", "OnBeforeUserAdd", ['DiscountNeighbour','ActionUser']);

//AddEventHandler("sale", "OnSaleCalculateOrderProps", "PriorityOrder");

Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderBeforeSaved',
    'PriorityOrderFunction'
);

/*
Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleOrderBeforeSaved',
    'addGiftsFunction'
);
*/

/**
 * Вызывается после изменения статуса заказа.
 */
AddEventHandler("sale", "OnSaleStatusOrder", "OnSaleStatusOrderHandler");

/**
 * Вызывается перед добавлением заказа, может быть использовано для отмены или модификации данных.
 */
AddEventHandler("sale", "OnBeforeOrderAdd", "OnBeforeOrderAddHandler");

/**
 * Вызывается после добавления заказа.
 */
AddEventHandler("sale", "OnOrderAdd", "OnOrderAddHandler");


/**
 * Одна корзина на два сайта
 */
AddEventHandler("sale", "OnBasketAdd", ['SyncBasket', 'HandlerOnBasketAdd']);
AddEventHandler("sale", "OnBasketUpdate", ['SyncBasket', 'HandlerOnBasketUpdate']);
AddEventHandler("sale", "OnBeforeBasketDelete", ['SyncBasket', 'HandlerOnBasketDelete']);


/**
 * Формирование купона
 */
AddEventHandler("sale", "OnSaleComponentOrderOneStepComplete", 'HandlerOnSaleComponentOrderOneStepComplete');

/**
 * убираем текст в письме для торгового предложения
 */
AddEventHandler("sale", "OnOrderNewSendEmail", "HandlerOnOrderNewSendEmail");
/**
 * добовляем ограничение для доставки
 */
addEventHandler('sale', 'onSaleDeliveryRestrictionsClassNamesBuildList', 'myDeliveryFunction');// службы доставки
/**
 * Деактивировать купон (одноразовый)
 */
//AddEventHandler("sale", "OnSaleComponentOrderOneStepComplete", 'deactivateUsedCoupon');

/**
 * Обработка безноминального купона
 */
EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleBasketBeforeSaved',
    'OnSaleBasketBeforeSavedHandler'
);
/**
 * Отмена заказа
 */
AddEventHandler("sale", "OnSaleBeforeCancelOrder", "OnSaleBeforeCancelOrderHandler");