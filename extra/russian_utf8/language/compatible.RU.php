<?php
// $Id: compatible.RU.php,v 1.1 2012/04/08 18:22:28 ohwada Exp $

// 2007-11-24 K.OHWADA
// _HAPPYLINUX_CONF_TABLE_MANAGE

// 2007-11-01 K.OHWADA
// _HAPPYLINUX_FAIL

// 2007-06-01 K.OHWADA
// _HAPPYLINUX_AM_JUDGE

// 2007-05-12 K.OHWADA
// _HAPPYLINUX_FORM_INIT_NOT

// 2007-02-20 K.OHWADA
// _HAPPYLINUX_SKIP_TO_NEXT

//=========================================================
// Happy Linux Framework Module
// 2006-12-17 K.OHWADA
//=========================================================
// _LANGCODE: ru
// _CHARSET : utf-8
// Translator: Houston (Contour Design Studio https://www.cdesign.ru/)

//---------------------------------------------------------
// compatible for v1.21
//---------------------------------------------------------
// config
if (!defined('_HAPPYLINUX_CONF_TABLE_MANAGE')) {
    // table manage
    define('_HAPPYLINUX_CONF_TABLE_MANAGE', 'Управление таблицей базы данных');
    define('_HAPPYLINUX_CONF_TABLE_CHECK', 'Проверить таблицу %s');
    define('_HAPPYLINUX_CONF_TABLE_REINSTALL', 'Рекомендуется переустановить, если обнаружена ошибка');
    define('_HAPPYLINUX_CONF_TABLE_CONFIG_RENEW', 'Обновление конфигурации таблицы');
    define('_HAPPYLINUX_CONF_TABLE_CONFIG_RENEW_DESC', 'Выполнить, если обнаружена ошибка. <br>Существующие значения аннулируются. <br>Установите значения после выполнения. ');
}

//---------------------------------------------------------
// compatible for v1.20
//---------------------------------------------------------
// global
if (!defined('_HAPPYLINUX_FAIL')) {
    define('_HAPPYLINUX_WELCOME', 'Добро пожаловать %s');
    define('_HAPPYLINUX_FAIL', 'Неудача');
    define('_HAPPYLINUX_FAILED', 'Не удалось');
    define('_HAPPYLINUX_REFRESH', 'Обновить');
    define('_HAPPYLINUX_REFRESHED', 'Обновлено');
    define('_HAPPYLINUX_FINISH', 'Закончить');
    define('_HAPPYLINUX_FINISHED', 'Закончено');
    define('_HAPPYLINUX_PRINT', 'Печать');
    define('_HAPPYLINUX_SAMPLE', 'Пример');
}

// admin
if (!defined('_HAPPYLINUX_AM_MODULE')) {
    define('_HAPPYLINUX_AM_MODULE', 'Управление модулем');
    define('_HAPPYLINUX_AM_MODULE_DESC', 'Показать список модулей');
    define('_HAPPYLINUX_AM_MODULE_UPDATE', 'Обновление модуля');

    define('_HAPPYLINUX_AM_SERVER_ENV', 'Переменные серверной среды');
    define('_HAPPYLINUX_AM_DIR_NOT_WRITABLE', 'Эта директория недоступка для записи');
    define('_HAPPYLINUX_AM_MEMORY_LIMIT_TOO_SMALL', 'memory_limit слишком мал');
    define('_HAPPYLINUX_AM_MEMORY_WEBLINKS_REQUIRE', 'Модуль веб-ссылок требует больше памяти, чем %s MB');
    define('_HAPPYLINUX_AM_MEMORY_DESC', 'Это значение является одним стандартом.<br>В зависимости от серверной среды, иногда бывает больше или меньше.');
}

//---------------------------------------------------------
// compatible for v0.90
//---------------------------------------------------------
// admin
if (!defined('_HAPPYLINUX_AM_JUDGE')) {
    define('_HAPPYLINUX_AM_JUDGE', 'Программа judegs <b>%s</b>');
    define('_HAPPYLINUX_AM_JUMP', 'Эта страница перезагрузится автоматически через <b>%s</b> сек');
    define('_HAPPYLINUX_AM_JUMP_IFNO1', 'Пожалуйста, нажмите следующее, если страница автоматически не перезагрузится, или программа mis-judges.');
    define('_HAPPYLINUX_AM_JUMP_IFNO2', 'Пожалуйста, установите <i>modules/happylinux/preload/admin.php</i>, когда %s секунд долго');
}

//---------------------------------------------------------
// compatible for v0.80
//---------------------------------------------------------
// form
if (!defined('_HAPPYLINUX_FORM_INIT_NOT')) {
    define('_HAPPYLINUX_FORM_INIT_NOT', 'Не инициализирована таблица конфигурации');
    define('_HAPPYLINUX_FORM_INIT_EXEC', 'Инициализация таблицы конфигурации');
    define('_HAPPYLINUX_FORM_VERSION_NOT', 'Не версия %s');
    define('_HAPPYLINUX_FORM_UPGRADE_EXEC', 'Обновление таблицы конфигурации');
}

// admin
if (!defined('_HAPPYLINUX_AM_GROUP')) {
    define('_HAPPYLINUX_AM_GROUP', 'Управление группой');
    define('_HAPPYLINUX_AM_GROUP_DESC', 'Управление правами доступа модуля');
    define('_HAPPYLINUX_AM_BLOCK', 'Управление блоком');
    define('_HAPPYLINUX_AM_BLOCK_DESC', 'Управление правами доступа блока');
    define('_HAPPYLINUX_AM_GROUP_BLOCK', 'Группа / Управление блоком');
    define('_HAPPYLINUX_AM_GROUP_BLOCK_DESC', 'Управление правами доступа модуля и блока');
    define('_HAPPYLINUX_AM_TEMPLATE', 'Управление шаблоном');
    define('_HAPPYLINUX_AM_TEMPLATE_DESC', 'Управление шаблоном');
}

// rss_view
if (!defined('_HAPPYLINUX_VIEW_SITE_TITLE')) {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/english/rss_view.php';
}

//---------------------------------------------------------
// compatible for v0.70
//---------------------------------------------------------
// global
if (!defined('_HAPPYLINUX_SKIP_TO_NEXT')) {
    define('_HAPPYLINUX_SKIP_TO_NEXT', 'Перейти к следующему');
}

//---------------------------------------------------------
// compatible for v0.40
//---------------------------------------------------------
// global
if (!defined('_HAPPYLINUX_GOTO_MAIN')) {
    define('_HAPPYLINUX_GOTO_MAIN', 'Перейти на главную страницу');
    define('_HAPPYLINUX_GOTO_TOP', 'Перейти на главную страницу');
    define('_HAPPYLINUX_GOTO_ADMIN', 'Перейти на страницу администрирования');
    define('_HAPPYLINUX_GOTO_MODULE', 'Перейти к модулю');
}

// form
if (!defined('_HAPPYLINUX_FORM_ITEM')) {
    define('_HAPPYLINUX_FORM_ITEM', 'Пункт');
}
