<?php
// $Id: module_install.php,v 1.2 2007/11/16 15:17:13 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-11-11 K.OHWADA
//=========================================================

global $xoopsConfig;
$XOOPS_LANGUAGE = $xoopsConfig['language'];

require_once XOOPS_ROOT_PATH . '/class/template.php';
//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/config_define_base.php';
//require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/module_install.php';

// happylinux config.php
if (file_exists(XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $XOOPS_LANGUAGE . '/config.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $XOOPS_LANGUAGE . '/config.php';
} else {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/english/config.php';
}
