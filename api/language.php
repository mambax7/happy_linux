<?php
// $Id: language.php,v 1.9 2008/02/26 15:30:05 ohwada Exp $

// 2008-02-17 K.OHWADA
// plugin.php

// 2007-11-11 K.OHWADA
// happy_linux_include_lang()
// config.php charcode.php xoops_block_check.php

// 2007-09-01 K.OHWADA
// mail.php

// 2007-05-12 K.OHWADA
// rss_view.php

// 2006-12-17 K.OHWADA
// use compatible.php

// 2006-10-01 K.OHWADA
// use locate.php

// 2006-09-10 K.OHWADA
// divided lang files

// 2006-07-08 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-07-02 K.OHWADA
//=========================================================

//---------------------------------------------------------
// NOTE
// NOT include language_local.php locate.php
//---------------------------------------------------------

//---------------------------------------------------------
// function happy_linux_include_lang
//---------------------------------------------------------
function happy_linux_include_lang($file)
{
    global $xoopsConfig;
    $LANGUAGE = $xoopsConfig['language'];
    $DIR_LANG = XOOPS_ROOT_PATH . '/modules/happy_linux/language';

    if (file_exists($DIR_LANG . '/' . $LANGUAGE . '/' . $file)) {
        include_once $DIR_LANG . '/' . $LANGUAGE . '/' . $file;
    } else {
        include_once $DIR_LANG . '/english/' . $file;
    }
}

//---------------------------------------------------------
// execute
//---------------------------------------------------------

happy_linux_include_lang('global.php');
happy_linux_include_lang('search.php');
happy_linux_include_lang('form.php');
happy_linux_include_lang('page_frame.php');
happy_linux_include_lang('manage.php');
happy_linux_include_lang('rss_view.php');
happy_linux_include_lang('modinfo.php');
happy_linux_include_lang('admin.php');
happy_linux_include_lang('mail.php');
happy_linux_include_lang('config.php');
happy_linux_include_lang('charcode.php');
happy_linux_include_lang('xoops_block_check.php');
happy_linux_include_lang('plugin.php');

// compatible
include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/compatible.php';
