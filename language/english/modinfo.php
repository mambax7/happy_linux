<?php
// $Id: modinfo.php,v 1.2 2007/11/12 12:28:49 ohwada Exp $

// 2007-11-01 K.OHWADA
// _MI_HAPPYLINUX_ADMENU1

// 2006-07-10 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

// The name of this module
define('_MI_HAPPYLINUX_NAME', 'Happy Linux Framework');
define('_MI_HAPPYLINUX_DESC', 'The library collection for modules distributing in Happy Linux');

// admin menu
define('_MI_HAPPYLINUX_ADMENU1', 'Server Environment');

//Menu
define('_MI_HAPPYLINUX_MENU_HOME', 'Home');
define('_MI_HAPPYLINUX_MENU_01', 'Admin');
define('_MI_HAPPYLINUX_MENU_ABOUT', 'About');


//Config
define('MI_HAPPYLINUX_EDITOR_ADMIN', 'Editor: Admin');
define('MI_HAPPYLINUX_EDITOR_ADMIN_DESC', 'Select the Editor to use by the Admin');
define('MI_HAPPYLINUX_EDITOR_USER', 'Editor: User');
define('MI_HAPPYLINUX_EDITOR_USER_DESC', 'Select the Editor to use by the User');

//Help
define('_MI_HAPPYLINUX_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_HAPPYLINUX_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_HAPPYLINUX_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_HAPPYLINUX_OVERVIEW', 'Overview');

//define('_MI_HAPPYLINUX_HELP_DIR', __DIR__);

//help multi-page
define('_MI_HAPPYLINUX_DISCLAIMER', 'Disclaimer');
define('_MI_HAPPYLINUX_LICENSE', 'License');
define('_MI_HAPPYLINUX_SUPPORT', 'Support');
