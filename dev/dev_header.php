<?php
// $Id: dev_header.php,v 1.1 2006/11/21 03:04:56 ohwada Exp $

//================================================================
// Happy Linux Framework Module
// 2006-11-18 K.OHWADA
//================================================================

// set true, if you want to use
$HAPPYLINUX_DEV_PERMIT = false;

require dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
require_once __DIR__ . '/dev_functions.php';
//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/strings.php';
//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/error.php';
//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/object.php';
//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/objectHandler.php';
//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/config_baseHandler.php';

if (!$HAPPYLINUX_DEV_PERMIT) {
    dev_header();
    echo '<h1 style="color: #ff0000;">not permit</h1>' . "\n";
    dev_footer();
}
