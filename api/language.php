<?php
// $Id: language.php,v 1.9 2008/02/26 15:30:05 ohwada Exp $

// 2008-02-17 K.OHWADA
// plugin.php

// 2007-11-11 K.OHWADA
// happylinux_include_lang()
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
// function happylinux_include_lang
//---------------------------------------------------------
/**
 * @param $file
 */
function happylinux_include_lang($file)
{
    global $xoopsConfig;
    $LANGUAGE = $xoopsConfig['language'];
    $DIR_LANG = XOOPS_ROOT_PATH . '/modules/happylinux/language';

    if (file_exists($DIR_LANG . '/' . $LANGUAGE . '/' . $file)) {
        require_once $DIR_LANG . '/' . $LANGUAGE . '/' . $file;
    } else {
        require_once $DIR_LANG . '/english/' . $file;
    }
}

//---------------------------------------------------------
// execute
//---------------------------------------------------------

happylinux_include_lang('global.php');
happylinux_include_lang('search.php');
happylinux_include_lang('form.php');
happylinux_include_lang('page_frame.php');
happylinux_include_lang('manage.php');
happylinux_include_lang('rss_view.php');
happylinux_include_lang('modinfo.php');
happylinux_include_lang('admin.php');
happylinux_include_lang('mail.php');
happylinux_include_lang('config.php');
happylinux_include_lang('charcode.php');
happylinux_include_lang('xoops_block_check.php');
happylinux_include_lang('plugin.php');

// compatible
require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/compatible.php';
