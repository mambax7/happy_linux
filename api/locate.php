<?php

// $Id: locate.php,v 1.1 2010/11/07 14:59:13 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-11-11 K.OHWADA
//=========================================================

global $xoopsConfig;
$XOOPS_LANGUAGE = $xoopsConfig['language'];

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/language.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/locate.php';

// language_local.php
if (file_exists(XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $XOOPS_LANGUAGE . '/language_local.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $XOOPS_LANGUAGE . '/language_local.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/english/language_local.php';
}

// locate.php
if (file_exists(XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $XOOPS_LANGUAGE . '/locate.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/' . $XOOPS_LANGUAGE . '/locate.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/english/locate.php';
}
