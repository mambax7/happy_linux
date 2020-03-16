<?php
// $Id: config.php,v 1.1 2012/04/08 18:22:28 ohwada Exp $

// 2007-11-24
// table manage

//=========================================================
// Happy Linux Framework Module
// 2007-10-10 K.OHWADA
//=========================================================
// _LANGCODE: ru
// _CHARSET : utf-8
// Translator: Houston (Contour Design Studio https://www.cdesign.ru/)

// bin command
define('_HAPPYLINUX_CONF_COMMAND_MANAGE', 'Управление командами');
define('_HAPPYLINUX_CONF_CREATE_CONFIG', 'Создать файл конфигурации');
define('_HAPPYLINUX_CONF_TEST_BIN', 'Выполнить тест бинарной команды');
define('_HAPPYLINUX_CONF_BIN', 'Конфигурация команды');
define('_HAPPYLINUX_CONF_BIN_DESC', 'Используется для бинарной команды');
define('_HAPPYLINUX_CONF_BIN_PASS', 'Пароль');
define('_HAPPYLINUX_CONF_BIN_MAILTO', 'Адрес электронной почты для отправки');
define('_HAPPYLINUX_CONF_BIN_SEND', 'Отправить почту');
define('_HAPPYLINUX_CONF_BIN_SEND_NON', 'Не отправлять');
define('_HAPPYLINUX_CONF_BIN_SEND_EXECUTE', 'Отправлять при исполнении');
define('_HAPPYLINUX_CONF_BIN_SEND_ALWAYS', 'Всегда отправлять');

// rss
define('_HAPPYLINUX_CONF_RSS_MANAGE', 'Управление RDF/RSS/ATOM');
define('_HAPPYLINUX_CONF_RSS_MANAGE_DESC', 'Создавать и показывать RDF/RSS/ATOM');
define('_HAPPYLINUX_CONF_SHOW_RDF', 'Показывать RDF');
define('_HAPPYLINUX_CONF_SHOW_RSS', 'Показывать RSS');
define('_HAPPYLINUX_CONF_SHOW_ATOM', 'Показывать ATOM');
define('_HAPPYLINUX_CONF_DEBUG_RDF', 'Показать отладку RDF');
define('_HAPPYLINUX_CONF_DEBUG_RSS', 'Показать отладку RSS');
define('_HAPPYLINUX_CONF_DEBUG_ATOM', 'Показать отладку ATOM');

// template
define('_HAPPYLINUX_CONF_TPL_COMPILED_CLEAR', 'Очистить кэш скомпилированных шаблонов');
define('_HAPPYLINUX_CONF_TPL_COMPILED_CLEAR_DIR', 'НЕОБХОДИМО выполнить, при изменении файлов шаблона в директории %s');
define('_HAPPYLINUX_CONF_RSS_CACHE_CLEAR', 'Очистить кэш RSS');
define('_HAPPYLINUX_CONF_RSS_CACHE_CLEAR_DESC', 'в анонимном пользователе, кэш RSS на один час');
define('_HAPPYLINUX_CONF_RSS_CACHE_CLEAR_TIME', 'в анонимном пользователе, кэш RSS на %s час(ов)');
define('_HAPPYLINUX_CONF_RSS_CACHE_TIME', 'Время кэша (сек)');

define('_HAPPYLINUX_CONF_NOT_WRITABLE', 'Эта директория недоступка для записи');

// 2007-11-24
// table manage
define('_HAPPYLINUX_CONF_TABLE_MANAGE', 'Управление таблицей базы данных');
define('_HAPPYLINUX_CONF_TABLE_CHECK', 'Проверить таблицу %s');
define('_HAPPYLINUX_CONF_TABLE_REINSTALL', 'Рекомендуется переустановить, если обнаружена ошибка');
define('_HAPPYLINUX_CONF_TABLE_CONFIG_RENEW', 'Обновление конфигурации таблицы');
define('_HAPPYLINUX_CONF_TABLE_CONFIG_RENEW_DESC', 'Выполнить, если обнаружена ошибка. <br>Существующие значения аннулируются. <br>Установите значения после выполнения. ');
