<?php
// $Id: rss_parser.php,v 1.2 2012/04/08 18:22:28 ohwada Exp $

// 2012-04-02 K.OHWADA
// include/functions.php

//=========================================================
// Happy Linux Framework Module
// 2007-06-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// system
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH . '/class/snoopy.php';

//---------------------------------------------------------
// happy_linux
//---------------------------------------------------------
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/include/functions.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/include/multibyte.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/include/rss_constant.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/magpie/magpie_parse.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/magpie/magpie_cache.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/time.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/error.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/strings.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/remote_file.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/convert_encoding.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/basic_object.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/rss_base_object.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/rss_parse_object.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/rss_utility.php';
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/rss_parser.php';
