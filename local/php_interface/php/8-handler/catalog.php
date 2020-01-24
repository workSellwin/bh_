<?php
/**
 * OnPriceAdd - событие, вызываемое в случае успешного
 * создания нового ценового предложения (новой цены) для товара.
 */
AddEventHandler("catalog", "OnPriceAdd", "DoIBlockAfterSave");
/**
 *OnPriceUpdate - событие, вызываемое в случае успешного
 * изменения ценового предложения (цены) товара.
 */
AddEventHandler("catalog", "OnPriceUpdate", "DoIBlockAfterSave");