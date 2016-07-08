<?php
// $Id: compatible.EN.php,v 1.1 2012/04/08 18:22:28 ohwada Exp $

// 2007-11-24 K.OHWADA
// _HAPPY_LINUX_CONF_TABLE_MANAGE

// 2007-11-01 K.OHWADA
// _HAPPY_LINUX_FAIL

// 2007-06-01 K.OHWADA
// _HAPPY_LINUX_AM_JUDGE

// 2007-05-12 K.OHWADA
// _HAPPY_LINUX_FORM_INIT_NOT

// 2007-02-20 K.OHWADA
// _HAPPY_LINUX_SKIP_TO_NEXT

//=========================================================
// Happy Linux Framework Module
// 2006-12-17 K.OHWADA
//=========================================================

//---------------------------------------------------------
// compatible for v1.21
//---------------------------------------------------------
// config
if (!defined('_HAPPY_LINUX_CONF_TABLE_MANAGE')) {
    // table manage
    define('_HAPPY_LINUX_CONF_TABLE_MANAGE', 'DB Table Management');
    define('_HAPPY_LINUX_CONF_TABLE_CHECK', 'Check %s Table');
    define('_HAPPY_LINUX_CONF_TABLE_REINSTALL', 'Recommend to re-install if detected error');
    define('_HAPPY_LINUX_CONF_TABLE_CONFIG_RENEW', 'Renewal Config Table');
    define('_HAPPY_LINUX_CONF_TABLE_CONFIG_RENEW_DESC', 'Execute if detected error. <br />The present values are repealed. <br />Set values after execution. ');
}

//---------------------------------------------------------
// compatible for v1.20
//---------------------------------------------------------
// global
if (!defined('_HAPPY_LINUX_FAIL')) {
    define('_HAPPY_LINUX_WELCOME', 'Welcome %s');
    define('_HAPPY_LINUX_FAIL', 'Fail');
    define('_HAPPY_LINUX_FAILED', 'Failed');
    define('_HAPPY_LINUX_REFRESH', 'Refresh');
    define('_HAPPY_LINUX_REFRESHED', 'Refreshed');
    define('_HAPPY_LINUX_FINISH', 'Finish');
    define('_HAPPY_LINUX_FINISHED', 'Finished');
    define('_HAPPY_LINUX_PRINT', 'Print');
    define('_HAPPY_LINUX_SAMPLE', 'Sample');
}

// admin
if (!defined('_HAPPY_LINUX_AM_MODULE')) {
    define('_HAPPY_LINUX_AM_MODULE', 'Module Managemnet');
    define('_HAPPY_LINUX_AM_MODULE_DESC', 'Show module list');
    define('_HAPPY_LINUX_AM_MODULE_UPDATE', 'Module Update');

    define('_HAPPY_LINUX_AM_SERVER_ENV', 'Server Environmnet Variables');
    define('_HAPPY_LINUX_AM_DIR_NOT_WRITABLE', 'This directory is not writeable');
    define('_HAPPY_LINUX_AM_MEMORY_LIMIT_TOO_SMALL', 'memory_limit is too small');
    define('_HAPPY_LINUX_AM_MEMORY_WEBLINKS_REQUIRE', 'Weblinks module requires more memory about %s MB');
    define('_HAPPY_LINUX_AM_MEMORY_DESC', 'This value is one standard.<br />Depending on the server environment, it is sometimes more or less.');
}

//---------------------------------------------------------
// compatible for v0.90
//---------------------------------------------------------
// admin
if (!defined('_HAPPY_LINUX_AM_JUDGE')) {
    define('_HAPPY_LINUX_AM_JUDGE', 'This program judegs <b>%s</b>');
    define('_HAPPY_LINUX_AM_JUMP', 'This page reload automatically after <b>%s</b> sec');
    define('_HAPPY_LINUX_AM_JUMP_IFNO1', 'Please click following, if the page does not automatically reload, or the program mis-judges.');
    define('_HAPPY_LINUX_AM_JUMP_IFNO2', 'Please set <i>modules/happy_linux/preload/admin.php</i>, when fell %s seconds is long');
}

//---------------------------------------------------------
// compatible for v0.80
//---------------------------------------------------------
// form
if (!defined('_HAPPY_LINUX_FORM_INIT_NOT')) {
    define('_HAPPY_LINUX_FORM_INIT_NOT', 'Not initialize Config table');
    define('_HAPPY_LINUX_FORM_INIT_EXEC', 'Initialize Config table');
    define('_HAPPY_LINUX_FORM_VERSION_NOT', 'Not Version %s');
    define('_HAPPY_LINUX_FORM_UPGRADE_EXEC', 'Upgrade Config table');
}

// admin
if (!defined('_HAPPY_LINUX_AM_GROUP')) {
    define('_HAPPY_LINUX_AM_GROUP', 'Gruop Manage');
    define('_HAPPY_LINUX_AM_GROUP_DESC', 'The management of the access permition of the module');
    define('_HAPPY_LINUX_AM_BLOCK', 'Block Manage');
    define('_HAPPY_LINUX_AM_BLOCK_DESC', 'The management of the access permition of the block');
    define('_HAPPY_LINUX_AM_GROUP_BLOCK', 'Gruop / Block Manage');
    define('_HAPPY_LINUX_AM_GROUP_BLOCK_DESC', 'The management of the access permition of the module and the block');
    define('_HAPPY_LINUX_AM_TEMPLATE', 'Template Manage');
    define('_HAPPY_LINUX_AM_TEMPLATE_DESC', 'The management of the template');
}

// rss_view
if (!defined('_HAPPY_LINUX_VIEW_SITE_TITLE')) {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/english/rss_view.php';
}

//---------------------------------------------------------
// compatible for v0.70
//---------------------------------------------------------
// global
if (!defined('_HAPPY_LINUX_SKIP_TO_NEXT')) {
    define('_HAPPY_LINUX_SKIP_TO_NEXT', 'Skip to Next');
}

//---------------------------------------------------------
// compatible for v0.40
//---------------------------------------------------------
// global
if (!defined('_HAPPY_LINUX_GOTO_MAIN')) {
    define('_HAPPY_LINUX_GOTO_MAIN', 'Go To Main Page');
    define('_HAPPY_LINUX_GOTO_TOP', 'Go To Top Page');
    define('_HAPPY_LINUX_GOTO_ADMIN', 'Go To Admin Page');
    define('_HAPPY_LINUX_GOTO_MODULE', 'Go To Module');
}

// form
if (!defined('_HAPPY_LINUX_FORM_ITEM')) {
    define('_HAPPY_LINUX_FORM_ITEM', 'Item');
}
