<?php
/**
 * Событие "OnPageStart"
 * вызывается в начале выполняемой части пролога сайта,
 * после подключения всех библиотек и отработки Агентов.
 *
 * Нужно для событий востоновления пароля по телефону
 */
AddEventHandler("main", "OnPageStart", "OnPageStart");

/**
 * Событие "OnAfterUserLogin"
 * вызывается в методе CUser::Login после попытки авторизовать пользователя,
 * проверив имя входа arParams['LOGIN'] и пароль arParams['PASSWORD'].
 *
 * Нужно для событий авторизации пользователя по телефону
 */
AddEventHandler("main", "OnAfterUserLogin", "OnAfterUserLogin");




AddEventHandler("main", "OnAdminListDisplay", "MyOnAdminListDisplay");


AddEventHandler("main", "OnPageStart", "OnPageStartCheck");

AddEventHandler("main", "OnBeforeProlog", ['DiscountNeighbour','Action']);

AddEventHandler("main", "OnBuildGlobalMenu", "ModifiAdminMenu");