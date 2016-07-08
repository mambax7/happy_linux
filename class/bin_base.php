<?php
// $Id: bin_base.php,v 1.2 2011/12/30 00:50:30 ohwada Exp $

// 2011-12-29 K.OHWADA
// $this->_offset in _set_env_param_web()

// 2007-10-10 K.OHWADA
// _set_system_param()
// _print_data()

// 2007-09-20 K.OHWADA
// PHP 5.2: Non-static method happy_linux_bin_file::getInstance() should not be called statically
// PHP 5.2: set timezone

// 2007-08-01 K.OHWADA
// HAPPY_LINUX_MB_LANGUAGE

// 2007-06-10 K.OHWADA
// divid to bin_file
// _print_write_html_header()
// check_pass() set_cmd_option()

// 2007-05-12 K.OHWADA
// change _file_open() _file_close()

// 2006-09-18 K.OHWADA
// add $_argv_1 etc

// 2006-07-10 K.OHWADA
// this is new file
// porting from bin_base_class.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================
// php xxx.php  pass
// php xxx.php -pass=pass [ -limit=0 -offset=0 -abc ]
//---------------------------------------------------------

class happy_linux_bin_base
{
	var $_DIRNAME;
	var $_bin_file;

// constant
	var $_X_MAILER = 'XOOPS';

// test parameter
	var $_mode       = '';
	var $_flag_print = false;
	var $_flag_write = true;
	var $_flag_chmod = false;

// command option
	var $_pass   = null;
	var $_limit  = 10;
	var $_offset = 0;

	var $_FLAG_PRINT_WEB = true;
	var $_FLAG_WRITE_WEB = true;
	var $_FLAG_CHMOD_WEB = true;
	var $_LIMIT_WEB      = 10;

	var $_FLAG_PRINT_COMMAND = false;
	var $_FLAG_WRITE_COMMAND = true;
	var $_FLAG_CHMOD_COMMAND = false;
	var $_LIMIT_COMMAND      = 0;	// unlimited

// xoops parameter
	var $_CHARSET;
	var $_sitename;
	var $_adminmail;

// command parameter
	var $_opt_arr = null;

// result file
	var $_SUB_DIR    = 'cache';
	var $_GOTO_ADMIN = 'goto admin index';

	var $_filename = null;
	var $_file_admin_index;

	var $_mail_to    = null;
	var $_mail_title = null;
	var $_mail_level = 0;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_bin_base( $dirname )
{
	$this->_DIRNAME = $dirname;
	$this->_file_admin_index = 'modules/'.$this->_DIRNAME.'/admin/index.php';

// MUST set before happy_linux_bin_file
	if( !defined('HAPPY_LINUX_BIN_MODE') ) 
	{
		define('HAPPY_LINUX_BIN_MODE', '1');
	}

// Non-static method happy_linux_bin_file::getInstance() should not be called statically
	$this->_bin_file = new happy_linux_bin_file();

// system parameter
	$this->_set_system_param();
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function _set_system_param()
{
	global $xoopsConfig;
	global $xoops_sitename, $xoops_adminmail; 

	$sitename  = null;
	$adminmail = null;

	if ( isset( $xoopsConfig['sitename'] ) )
	{
		$sitename = $xoopsConfig['sitename'];
	}
	elseif ( isset( $xoops_sitename ) )
	{
		$sitename = $xoops_sitename;
	}

	if ( isset( $xoopsConfig['adminmail'] ) )
	{
		$adminmail = $xoopsConfig['adminmail'];
	}
	elseif ( isset( $xoops_adminmail ) )
	{
		$adminmail = $xoops_adminmail;
	}

	if ( $sitename )
	{
		$this->set_sitename( $sitename );
	}
	if ( $adminmail )
	{
		$this->set_adminmail( $adminmail );
	}
	if ( defined('_CHARSET') )
	{
		$this->set_charset( _CHARSET );
	}

// multibyte
	if ( defined('HAPPY_LINUX_MB_LANGUAGE') ) 
	{
		happy_linux_mb_language( HAPPY_LINUX_MB_LANGUAGE );
	}
	if ( defined('HAPPY_LINUX_MB_ENCODING') ) 
	{
		happy_linux_internal_encoding( HAPPY_LINUX_MB_ENCODING );
	}

// PHP 5.2: set timezone
	if ( function_exists('date_default_timezone_set') && 
	     function_exists('date_default_timezone_get') )
	{
		$tz = @date_default_timezone_get();
		date_default_timezone_set( $tz );
	}
}

function set_sitename($val)
{
	$this->_sitename = $val;
}

function set_adminmail($val)
{
	$this->_adminmail = $val;
}

function set_charset($val)
{
	$this->_CHARSET = $val;
}

//=========================================================
// private
//=========================================================
//---------------------------------------------------------
// env_param
//---------------------------------------------------------
function set_env_param()
{
// web
// in whtasnew, set REQUEST_METHOD, because suppress notice
	if ( isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] )
	{
		$this->_set_env_param_web();
	}
// command line
	else
	{
		$this->_set_env_param_cmd();
	}

	$this->_set_flag_write_to_bin_file( $this->_flag_write);
}

function check_pass($pass)
{
	if ( $pass && ( $pass == $this->_pass ) )
	{
		return true;
	}
	return false;
}

function _set_env_param_web()
{
	$this->_mode       = 'web';
	$this->_flag_print = $this->_FLAG_PRINT_WEB;
	$this->_flag_write = $this->_FLAG_WRITE_WEB;
	$this->_flag_chmod = $this->_FLAG_CHMOD_WEB;
	$this->_limit      = $this->_LIMIT_WEB;

	$this->_opt_arr =& $_GET;

	if ( $this->isset_opt('pass') )
	{
		$this->_pass = $this->get_opt('pass');
	}

	if ( $this->isset_opt('limit') )
	{
		$this->_limit = $this->get_opt('limit');
	}

	if ( $this->isset_opt('offset') )
	{
		$this->_offset = $this->get_opt('offset');
	}
}

function _set_env_param_cmd()
{
	$this->_mode       = 'command';
	$this->_flag_print = $this->_FLAG_PRINT_COMMAND;
	$this->_flag_write = $this->_FLAG_WRITE_COMMAND;
	$this->_flag_chmod = $this->_FLAG_CHMOD_COMMAND;
	$this->_limit      = $this->_LIMIT_COMMAND;

	$this->_set_cmd_option();

	if ( $this->isset_opt('pass') )
	{
		$this->_pass = $this->get_opt('pass');
	}
	elseif ( isset($_SERVER['argv'][1]) )
	{
		$this->_pass = $_SERVER['argv'][1];
	}

	if ( $this->isset_opt('limit') )
	{
		$this->_limit = $this->get_opt('limit');
	}

	if ( $this->isset_opt('offset') )
	{
		$this->_offset = $this->get_opt('offset');
	}
}

function _set_cmd_option()
{
	$arr = array();

	if ( $_SERVER['argc'] > 1 )
	{
		for( $i=1; $i<$_SERVER['argc']; $i++ )
		{
			if ( preg_match('/\-(.*)=(.*)/', $_SERVER['argv'][$i], $matches) )
			{
				$arr[ $matches[1] ] = $matches[2];
			}
			elseif ( preg_match('/\-(.*)/', $_SERVER['argv'][$i], $matches) )
			{
				$arr[ $matches[1] ] = true;
			}
		}
	}

	$this->_opt_arr =& $arr;
	return $arr;
}

function isset_opt($key)
{
	if ( isset($this->_opt_arr[$key]) )
	{
		return true;
	}
	return false;
}

function get_opt($key)
{
	if ( isset($this->_opt_arr[$key]) )
	{
		return $this->_opt_arr[$key];
	}
	return false;
}

//---------------------------------------------------------
// html header & footer
//---------------------------------------------------------
function _print_write_html_header()
{
	$this->_print_write_data( $this->_print_write_html_header() );
}

function _print_write_html_footer()
{
	$this->_print_write_data( $this->_print_write_html_footer() );
}

function _get_html_header()
{
	$text = <<<END_OF_TEXT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=$this->_CHARSET">
<title> $this->_TITLE </title>
</head><body>
<h3> $this->_TITLE </h3>
<hr />
END_OF_TEXT;

	return $text;
}

function _get_html_footer()
{
	$url_admin = XOOPS_URL.'/'.$this->_file_admin_index;

	$text = <<<END_OF_TEXT
<br />
<hr />
<a href="$url_admin">$this->_GOTO_ADMIN</a><br />
</head></html>
END_OF_TEXT;

	return $text;
}

function _print_write_data($data)
{
	$this->_print_data($data);
	$this->_write_data($data);
}

function _print_data($data)
{
	if ($this->_flag_print)
	{
		echo $data;
	}
}

//---------------------------------------------------------
// mail
//---------------------------------------------------------
function _send_mail_content_by_level( $content, $level )
{
	if ( $this->_mail_level >= $level )
	{
		return $this->_send_mail_content( $content );
	}
	return true;	// no action
}

function _send_mail_content( $content )
{
	return $this->_send_mail($this->_mail_to, $this->_mail_title, $content);
}

function _send_mail($mailto, $title, $content)
{
	$mailto  = $this->_adminmail;
	$subject = '['. $this->_sitename .'] '. $title;
	$body    = $this->_build_mail_body($title, $content);
	$header  = 'From: '. $this->_adminmail ." \n";
	$header .= 'X-Mailer: '. $this->_X_MAILER ." \n"; 

	$ret = happy_linux_send_mail($mailto, $subject, $body, $header);
	return $ret;
}

function _build_mail_body($title, $body)
{
	$siteurl = XOOPS_URL .'/';

	$msg  = '';
	if ( $this->_flag_write && $this->_filename )
	{
		$msg  = "You can view detail here:\n";
		$msg .= XOOPS_URL .'/'. $this->_filename ."\n";
	}

	$text = <<<END_OF_TEXT
$title

$body

$msg
-----------
$this->_sitename ( $siteurl )
webmaster
$this->_adminmail
-----------
END_OF_TEXT;

	return $text;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_mailer($val)
{
	$this->_X_MAILER = $val;
}

function set_mail_to($val)
{
	$this->_mail_to = $val;
}

function set_mail_title($val)
{
	$this->_mail_title = $val;
}

function set_mail_level($val)
{
	$this->_mail_level = intval($val);
}

// not include XOOPS_URL
function set_filename($file)
{
	$this->_filename = $file;
}

function get_filename()
{
	return $this->_filename;
}

//---------------------------------------------------------
// bin file class
//---------------------------------------------------------
function _open_file( $filename, $mode='w' )
{
	return $this->_bin_file->open_bin( $filename, $mode );
}

function _close_file()
{
	$this->_bin_file->close_bin( $this->_flag_chmod );
}

function _write_file($data)
{
	$this->_bin_file->write_bin($data);
}

function _set_flag_write_to_bin_file($val)
{
	$this->_bin_file->set_flag_write($val);
}

// --- class end ---
}

?>