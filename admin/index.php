<?php
// $Id: index.php,v 1.1 2007/11/12 13:17:17 ohwada Exp $

//=========================================================
// WebLinks Module
// 2007-11-01 K.OHWADA
//=========================================================

include '../../../include/cp_header.php';

//---------------------------------------------------------
// happy_linux
//---------------------------------------------------------
$XOOPS_LANGUAGE = $xoopsConfig['language'];

if (!defined('HAPPY_LINUX_DIRNAME')) {
    define('HAPPY_LINUX_DIRNAME', $xoopsModule->dirname());
}

if (!defined('HAPPY_LINUX_ROOT_PATH')) {
    define('HAPPY_LINUX_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . HAPPY_LINUX_DIRNAME);
}

if (!defined('HAPPY_LINUX_URL')) {
    define('HAPPY_LINUX_URL', XOOPS_URL . '/modules/' . HAPPY_LINUX_DIRNAME);
}

// start execution time
include_once HAPPY_LINUX_ROOT_PATH . '/class/time.php';
$happy_linux_time = happy_linux_time::getInstance();

include_once HAPPY_LINUX_ROOT_PATH . '/include/memory.php';
include_once HAPPY_LINUX_ROOT_PATH . '/class/error.php';
include_once HAPPY_LINUX_ROOT_PATH . '/class/dir.php';
include_once HAPPY_LINUX_ROOT_PATH . '/class/system.php';
include_once HAPPY_LINUX_ROOT_PATH . '/class/server_info.php';

// for modinfo.php
if (file_exists(HAPPY_LINUX_ROOT_PATH . '/language/' . $XOOPS_LANGUAGE . '/modinfo.php')) {
    include_once HAPPY_LINUX_ROOT_PATH . '/language/' . $XOOPS_LANGUAGE . '/modinfo.php';
} else {
    include_once HAPPY_LINUX_ROOT_PATH . '/language/english/modinfo.php';
}

//=========================================================
// main
//=========================================================
$info = happy_linux_server_info::getInstance();

xoops_cp_header();

echo $info->build_header(HAPPY_LINUX_DIRNAME, _MI_HAPPY_LINUX_DESC);
echo $info->build_server_env();
echo $info->build_check_dir_work();
echo $info->build_check_memory_limit_default();
echo $info->build_footer();
echo $info->build_powerdby();

xoops_cp_footer();
exit();// --- main end ---
;
