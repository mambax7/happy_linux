<?php
// $Id: rss_default.php,v 1.1 2007/11/14 11:30:03 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-11-11 K.OHWADA
//=========================================================

//=========================================================
// class builder base
//=========================================================
class happy_linux_rss_default
{

// http://www.rssboard.org/rss-specification#ltimagegtSubelementOfLtchannelgt
	var $_SITE_IMAGE_WIDTH_MAX      = 144;
	var $_SITE_IMAGE_WIDTH_DEFAULT  =  88;
	var $_SITE_IMAGE_HEIGHT_MAX     = 400;
	var $_SITE_IMAGE_HEIGHT_DEFAULT =  31;

	var $_SITE_AUTHOR_NAME_UID     = 1;
	var $_SITE_AUTHOR_NAME_DEFAULT = 'xoops';
	var $_SITE_IMAGE_LOGO          = 'images/logo.gif';

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_rss_default()
{
	// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) 
	{
		$instance = new happy_linux_rss_default();
	}
	return $instance;
}

//---------------------------------------------------------
// site information
//---------------------------------------------------------
function get_default_site_url()
{
	return XOOPS_URL.'/';
}

function get_default_site_name()
{
	return $this->get_xoops_sitename();
}

function get_default_site_desc()
{
	return $this->get_xoops_slogan();
}

function get_default_site_tag()
{
	return $this->parse_site_tag( $this->get_default_site_url() );
}

function get_default_site_link_self()
{
	$val = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	return $val;
}

function get_default_site_author_name()
{
	$name = $this->get_xoops_uname_from_id( $this->_SITE_AUTHOR_NAME_UID );
	if ( empty($name) )
	{
		$name = $this->_SITE_AUTHOR_NAME_DEFAULT;
	}
	return $name;
}

function get_default_site_author_email()
{
	return $this->get_xoops_adminmail();
}

function get_default_site_author_uri()
{
	return '';
}

function parse_site_tag($url)
{
	$parse = parse_url($url);
	if ( isset($parse['host']) )
	{
		return $parse['host'];
	}
	return false;
}

//---------------------------------------------------------
// site image
//---------------------------------------------------------
function get_default_site_image_logo()
{
	return $this->_SITE_IMAGE_LOGO;
}

function get_site_image_width_max()
{
	return $this->_SITE_IMAGE_WIDTH_MAX;
}

function get_site_image_height_max()
{
	return $this->_SITE_IMAGE_HEIGHT_MAX;
}

function get_site_image_size( $logo )
{
	$url    = '';
	$width  = 0;
	$height = 0;

	if ( empty($logo) )
	{	return array($url, $width, $height);	}

	$url  = XOOPS_URL."/".$logo;
	$path = XOOPS_ROOT_PATH."/".$logo;

	$size = GetImageSize( $path );	// PHP function
	if ( $size ) 
	{
		$width  = intval( $size[0] );
		$height = intval( $size[1] );
	}

	return array($url, $width, $height);
}

function check_site_image_width( $width )
{
	$ret  = 0;
	if ( $width > $this->_SITE_IMAGE_WIDTH_MAX )
	{
		$ret = 1;
	}
	return $ret;
}

function check_site_image_height( $height )
{
	$ret  = 0;
	if ( $height > $this->_SITE_IMAGE_HEIGHT_MAX )
	{
		$ret = 1;
	}
	return $ret;
}

//---------------------------------------------------------
// xoops param
//---------------------------------------------------------
function get_xoops_sitename()
{
	global $xoopsConfig;
	return $xoopsConfig['sitename'];
}

function get_xoops_slogan()
{
	global $xoopsConfig;
	return $xoopsConfig['slogan'];
}

function get_xoops_adminmail()
{
	global $xoopsConfig;
	return $xoopsConfig['adminmail'];
}

function get_xoops_uname_from_id( $uid, $usereal=0 )
{
	return XoopsUser::getUnameFromId( $uid, $usereal );
}

// --- class end ---
}

?>