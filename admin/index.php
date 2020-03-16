<?php

//use  XoopsModules\Happylinux;

// $Id: index.php,v 1.1 2007/11/12 13:17:17 ohwada Exp $

//=========================================================
// WebLinks Module
// 2007-11-01 K.OHWADA
//=========================================================

use XoopsModules\Happylinux\Common;

require __DIR__ . '/admin_header.php';
// Display Admin header
xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();

//---------------------------------------------------------
// happylinux
//---------------------------------------------------------
$XOOPS_LANGUAGE = $xoopsConfig['language'];

if (!defined('HAPPYLINUX_DIRNAME')) {
    define('HAPPYLINUX_DIRNAME', $xoopsModule->dirname());
}

if (!defined('HAPPYLINUX_ROOT_PATH')) {
    define('HAPPYLINUX_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . HAPPYLINUX_DIRNAME);
}

if (!defined('HAPPYLINUX_URL')) {
    define('HAPPYLINUX_URL', XOOPS_URL . '/modules/' . HAPPYLINUX_DIRNAME);
}

// start execution time
//require_once HAPPYLINUX_ROOT_PATH . '/class/time.php';
$happylinux_time = XoopsModules\Happylinux\Time::getInstance();

require_once HAPPYLINUX_ROOT_PATH . '/include/memory.php';
//require_once HAPPYLINUX_ROOT_PATH . '/class/error.php';
//require_once HAPPYLINUX_ROOT_PATH . '/class/dir.php';
//require_once HAPPYLINUX_ROOT_PATH . '/class/system.php';
//require_once HAPPYLINUX_ROOT_PATH . '/class/server_info.php';

// for modinfo.php
//if (file_exists(HAPPYLINUX_ROOT_PATH . '/language/' . $XOOPS_LANGUAGE . '/modinfo.php')) {
//    require_once HAPPYLINUX_ROOT_PATH . '/language/' . $XOOPS_LANGUAGE . '/modinfo.php';
//} else {
//    require_once HAPPYLINUX_ROOT_PATH . '/language/english/modinfo.php';
//}

//=========================================================
// main
//=========================================================
$info = XoopsModules\Happylinux\ServerInfo::getInstance();

//xoops_cp_header();

echo $info->build_header(HAPPYLINUX_DIRNAME, _MI_HAPPYLINUX_DESC);
echo $info->build_server_env();
echo $info->build_check_dir_work();
echo $info->build_check_memory_limit_default();
echo $info->build_footer();
echo $info->build_powerdby();

xoops_cp_footer();
exit(); // --- main end ---
