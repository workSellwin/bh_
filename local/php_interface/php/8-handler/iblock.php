<?php
/**
 * Событие "OnAfterIBlockElementUpdate"
 * вызывается после попытки изменения элемента информационного блока
 * методом CIBlockElement::Update. Работает вне зависимости
 * от того были ли созданы/изменены элементы непосредственно,
 * поэтому необходимо дополнительно проверять параметр: RESULT_MESSAGE.
 *
 *
 */
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "DoIBlockAfterSave");
/**
 * Событие "OnAfterIBlockElementAdd"
 * вызывается после попытки добавления нового элемента информационного
 * блока методом CIBlockElement::Add. Работает вне зависимости
 * от того были ли созданы/изменены элементы непосредственно,
 * поэтому необходимо дополнительно проверять параметр: RESULT_MESSAGE.
 *
 *
 */
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "DoIBlockAfterSave");


/**
 * Событие "OnAfterIBlockElementUpdate"
 * вызывается после попытки изменения элемента информационного блока
 * методом CIBlockElement::Update. Работает вне зависимости
 * от того были ли созданы/изменены элементы непосредственно,
 * поэтому необходимо дополнительно проверять параметр: RESULT_MESSAGE.
 *
 *
 */
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("MyClass", "OnBeforeIBlockElementUpdateHandler"));


/**
 * Событие вызывается до изменения элемента информационного блока,
 * и может быть использовано для отмены изменения или для переопределения некоторых полей.
 */

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "OnBeforeIBlockElementUpdateHandlerBrands");