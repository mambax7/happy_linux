<?php
// $Id: locate.php,v 1.1 2007/11/15 11:08:43 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-11-11 K.OHWADA
//=========================================================

global $xoopsConfig;
$XOOPS_LANGUAGE = $xoopsConfig['language'];


/*
//  require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/language.php';
//  require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/locate.php';

// language_local.php
if (file_exists(XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $XOOPS_LANGUAGE . '/language_local.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $XOOPS_LANGUAGE . '/language_local.php';
} else {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/english/language_local.php';
}

// locate.php
if (file_exists(XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $XOOPS_LANGUAGE . '/locate.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $XOOPS_LANGUAGE . '/locate.php';
} else {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/english/locate.php';
}
*/

xoops_loadLanguage('language_local');
xoops_loadLanguage('locate');
