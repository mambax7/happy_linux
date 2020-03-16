<?php
// $Id: local.php,v 1.2 2007/11/15 11:08:43 ohwada Exp $

// 2007-11-11 K.OHWADA
// browser.php

//=========================================================
// Happy Linux Framework Module
// 2006-09-10 K.OHWADA
//=========================================================

global $xoopsConfig;
$XOOPS_LANGUAGE = $xoopsConfig['language'];

//  require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/language.php';
//  require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/browser.php';

// language_local
if (file_exists(XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $XOOPS_LANGUAGE . '/language_local.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/' . $XOOPS_LANGUAGE . '/language_local.php';
} else {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/english/language_local.php';
}
