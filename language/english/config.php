<?php
// $Id: config.php,v 1.4 2007/11/26 05:29:53 ohwada Exp $

// 2007-11-24
// table manage

//=========================================================
// Happy Linux Framework Module
// 2007-10-10 K.OHWADA
//=========================================================

// bin command
define('_HAPPY_LINUX_CONF_COMMAND_MANAGE', 'Command Management');
define('_HAPPY_LINUX_CONF_CREATE_CONFIG', 'Create Config File');
define('_HAPPY_LINUX_CONF_TEST_BIN', 'Test execute of bin command');
define('_HAPPY_LINUX_CONF_BIN', 'Command Configuration');
define('_HAPPY_LINUX_CONF_BIN_DESC', 'Use for bin command');
define('_HAPPY_LINUX_CONF_BIN_PASS', 'Password');
define('_HAPPY_LINUX_CONF_BIN_MAILTO', 'Email Address to send');
define('_HAPPY_LINUX_CONF_BIN_SEND', 'Send Mail');
define('_HAPPY_LINUX_CONF_BIN_SEND_NON', 'Not send');
define('_HAPPY_LINUX_CONF_BIN_SEND_EXECUTE', 'Send when execution');
define('_HAPPY_LINUX_CONF_BIN_SEND_ALWAYS', 'Always send');

// rss
define('_HAPPY_LINUX_CONF_RSS_MANAGE', 'RDF/RSS/ATOM Managemant');
define('_HAPPY_LINUX_CONF_RSS_MANAGE_DESC', 'Genarete and show RDF/RSS/ATOM');
define('_HAPPY_LINUX_CONF_SHOW_RDF', 'Show RDF');
define('_HAPPY_LINUX_CONF_SHOW_RSS', 'Show RSS');
define('_HAPPY_LINUX_CONF_SHOW_ATOM', 'Show ATOM');
define('_HAPPY_LINUX_CONF_DEBUG_RDF', 'Show debug RDF');
define('_HAPPY_LINUX_CONF_DEBUG_RSS', 'Show debug RSS');
define('_HAPPY_LINUX_CONF_DEBUG_ATOM', 'Show debug ATOM');

// template
define('_HAPPY_LINUX_CONF_TPL_COMPILED_CLEAR', 'Clear compiled cache of template');
define('_HAPPY_LINUX_CONF_TPL_COMPILED_CLEAR_DIR', 'MUST execute, when changing template files in %s directory');
define('_HAPPY_LINUX_CONF_RSS_CACHE_CLEAR', 'Clear cache of RSS');
define('_HAPPY_LINUX_CONF_RSS_CACHE_CLEAR_DESC', 'in anoymous user, cache RSS for one hour');
define('_HAPPY_LINUX_CONF_RSS_CACHE_CLEAR_TIME', 'in anoymous user, cache RSS for %s hour');
define('_HAPPY_LINUX_CONF_RSS_CACHE_TIME', 'Cache time (sec)');

define('_HAPPY_LINUX_CONF_NOT_WRITABLE', 'This directory is not writeable');

// 2007-11-24
// table manage
define('_HAPPY_LINUX_CONF_TABLE_MANAGE', 'DB Table Management');
define('_HAPPY_LINUX_CONF_TABLE_CHECK', 'Check %s Table');
define('_HAPPY_LINUX_CONF_TABLE_REINSTALL', 'Recommend to re-install if detected error');
define('_HAPPY_LINUX_CONF_TABLE_CONFIG_RENEW', 'Renewal Config Table');
define('_HAPPY_LINUX_CONF_TABLE_CONFIG_RENEW_DESC', 'Execute if detected error. <br />The present values are repealed. <br />Set values after execution. ');
