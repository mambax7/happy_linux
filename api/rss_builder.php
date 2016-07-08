<?php
// $Id: rss_builder.php,v 1.6 2008/01/18 13:10:01 ohwada Exp $

// 2007-11-11 K.OHWADA
// build_cache.php date.php

//=========================================================
// Happy Linux Framework Module
// 2007-06-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// system
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH . '/class/template.php';

//---------------------------------------------------------
// happy_linux
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/include/rss_constant.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/include/multibyte.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/system.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/strings.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/date.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/image_size.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/convert_encoding.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/rss_default.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/build_cache.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/build_rss.php';
