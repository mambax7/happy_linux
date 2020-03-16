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
   //  require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/xoops_database_php5.php';
} // PHP 4
else {
   //  require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/xoops_database.php';
}

//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/DatabaseMysql.php';
//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/error.php';
//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/file.php';
//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/bin_file.php';
//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/bin_base.php';

// global.php
if (file_exists(XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $xoops_language . '/global.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $xoops_language . '/global.php';
} else {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/english/global.php';
}

xoops_loadLanguage('global');

// charset.php
//if (file_exists(XOOPS_ROOT_PATH . '/modules/happylinux/preload/charset.php')) {
//    require_once XOOPS_ROOT_PATH . '/modules/happylinux/preload/charset.php';
//} elseif (file_exists(XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $xoops_language . '/charset.php')) {
//    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $xoops_language . '/charset.php';
//} else {
//    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/english/charset.php';
//}

if (file_exists(XOOPS_ROOT_PATH . '/modules/happylinux/preload/charset.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/preload/charset.php';
} else{
    xoops_loadLanguage('charset');
}



