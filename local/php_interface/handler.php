<?php
//Одна корзина на два сайта
//AddEventHandler("sale", "OnBeforeBasketAdd", ['SyncBasket', 'HandlerOnBeforeBasketAdd']);

//AddEventHandler("sale", "OnBasketAdd", ['SyncBasket', 'HandlerOnBasketAdd']);
//AddEventHandler("sale", "OnBasketUpdate", ['SyncBasket', 'HandlerOnBasketUpdate']);
//AddEventHandler("sale", "OnBeforeBasketDelete", ['SyncBasket', 'HandlerOnBasketDelete']);


// Формирование купона
AddEventHandler("sale", "OnSaleComponentOrderOneStepComplete", 'HandlerOnSaleComponentOrderOneStepComplete');
// убираем текст в письме для торгового предложения
AddEventHandler("sale", "OnOrderNewSendEmail", "HandlerOnOrderNewSendEmail");
