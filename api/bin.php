<?php
// $Id: bin.php,v 1.4 2007/09/23 08:26:54 ohwada Exp $

// 2007-09-20 K.OHWADA
// xoops_database_php5.php

//=========================================================
// Happy Linux Framework Module
// 2007-08-01 K.OHWADA
//=========================================================

// error_reporting(E_ALL);

if (!isset($xoops_language)) {
    $xoops_language = 'english';
}

// PHP 5
if (preg_match('/^5\.\d+/', PHP_VERSION)) {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/xoops_database_php5.php';
} // PHP 4
else {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/xoops_database.php';
}

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/xoops_mysql_database.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/error.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/file.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/bin_file.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/bin_base.php';

// global.php
if (file_exists(XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $xoops_language . '/global.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $xoops_language . '/global.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/english/global.php';
}

// charset.php
if (file_exists(XOOPS_ROOT_PATH . '/modules/happy_linux/preload/charset.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/preload/charset.php';
} elseif (file_exists(XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $xoops_language . '/charset.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $xoops_language . '/charset.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/english/charset.php';
}
