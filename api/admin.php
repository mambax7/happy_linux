<?php
// $Id: admin.php,v 1.3 2007/06/17 03:19:52 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-06-01 K.OHWADA
//=========================================================

global $xoopsConfig;
$XOOPS_LANGUAGE = $xoopsConfig['language'];

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/admin.php';

// admin
if (file_exists(XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $XOOPS_LANGUAGE . '/admin.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $XOOPS_LANGUAGE . '/admin.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/english/admin.php';
}
