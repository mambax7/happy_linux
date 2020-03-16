<?php
// $Id: admin.php,v 1.3 2007/06/17 03:19:52 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-06-01 K.OHWADA
//=========================================================

global $xoopsConfig;
$XOOPS_LANGUAGE = $xoopsConfig['language'];

//  require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/admin.php';

// admin
if (file_exists(XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $XOOPS_LANGUAGE . '/admin.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $XOOPS_LANGUAGE . '/admin.php';
} else {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/english/admin.php';
}

//xoops_loadLanguage('admin');
