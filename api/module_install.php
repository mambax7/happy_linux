<?php
// $Id: module_install.php,v 1.2 2007/11/16 15:17:13 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-11-11 K.OHWADA
//=========================================================

global $xoopsConfig;
$XOOPS_LANGUAGE = $xoopsConfig['language'];

include_once XOOPS_ROOT_PATH . '/class/template.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/config_define_base.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/module_install.php';

// happy_linux config.php
if (file_exists(XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $XOOPS_LANGUAGE . '/config.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $XOOPS_LANGUAGE . '/config.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/english/config.php';
}
