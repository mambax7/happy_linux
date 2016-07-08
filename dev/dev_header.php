<?php
// $Id: dev_header.php,v 1.1 2006/11/21 03:04:56 ohwada Exp $

//================================================================
// Happy Linux Framework Module
// 2006-11-18 K.OHWADA
//================================================================

// set true, if you want to use
$HAPPY_LINUX_DEV_PERMIT = false;

include '../../../mainfile.php';
include_once 'dev_functions.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/strings.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/error.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/object.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/object_handler.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/config_base_handler.php';

if (!$HAPPY_LINUX_DEV_PERMIT) {
    dev_header();
    echo '<h1 style="color: #ff0000;">not permit</h1>' . "\n";
    dev_footer();
}
