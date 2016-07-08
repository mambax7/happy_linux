<?php
// $Id: browser.php,v 1.1 2007/11/15 11:08:43 ohwada Exp $

// 2007-11-11 K.OHWADA
// change file name server.php to browser.php

//=========================================================
// Happy Linux Framework Module
// 2006-09-10 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_browser
//=========================================================
class happy_linux_browser
{
	var $_http_user_agent = null;
	var $_os              = null;
	var $_browser         = null;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_browser()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) 
	{
		$instance = new happy_linux_browser();
	}

	return $instance;
}

//---------------------------------------------------------
// presume os and browser by agent
//---------------------------------------------------------
function presume_agent()
{
	$agent = $_SERVER["HTTP_USER_AGENT"];

	if ( empty($agent) )
	{	return;	}	// undefined

// presume OS
	if     (preg_match("/Win/i",  $agent))  $os = 'win';
	elseif (preg_match("/Mac/i",  $agent))  $os = 'mac';
	elseif (preg_match("/Linux/i",$agent))  $os = 'linux';
	elseif (preg_match("/BSD/i",  $agent))  $os = 'bsd';
	elseif (preg_match("/IRIX/i", $agent))  $os = 'irix';
	elseif (preg_match("/Sun/i",  $agent))  $os = 'sun';
	elseif (preg_match("/HP-UX/i",$agent))  $os = 'hpux';
	elseif (preg_match("/AIX/i",  $agent))  $os = 'aix';
	elseif (preg_match("/X11/i",  $agent))  $os = 'x11';
	else                                    $os = 'unknown';

// presume Browser
	if     (preg_match("/MSIE/i",   $agent)) $browser = 'msie';
	elseif (preg_match("/Mozilla/i",$agent)) $browser = 'mozilla';
	else                                     $browser = 'unknown';

	$this->_http_user_agent = $agent;
	$this->_os              = $os;
	$this->_browser         = $browser;
}

//---------------------------------------------------------
// get param
//---------------------------------------------------------
function get_os()
{
	return $this->_os;
}

function get_browser()
{
	return $this->_browser;
}

// --- class end ---
}

?>