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

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/language.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/browser.php';

// language_local
if (file_exists(XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $XOOPS_LANGUAGE . '/language_local.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $XOOPS_LANGUAGE . '/language_local.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/english/language_local.php';
}
