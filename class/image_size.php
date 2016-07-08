<?php
// $Id: image_size.php,v 1.2 2009/03/01 06:45:12 ohwada Exp $

// 2009-02-20 K.OHWADA
// $flag_zero in adjust_size()

// 2006-07-10 K.OHWADA
// this is new file
// porting from weblinks_image_size.php

//=========================================================
// Happy Linux Framework Module
// for PHP gennerally
// 2006-07-10 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_image_size
//=========================================================
class happy_linux_image_size
{

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_image_size()
{
// dummy
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) 
	{
		$instance = new happy_linux_image_size();
	}

	return $instance;
}

//---------------------------------------------------------
// function
//---------------------------------------------------------
function get_size($file)
{
	$size = GetImageSize( $file );	// PHP function
	if ( !$size ) 
	{
		return array(0,0);
	}

	$width  = intval( $size[0] );
	$height = intval( $size[1] );

	return array($width, $height);
}

function adjust_size($width, $height, $max_width, $max_height, $flag_zero=false)
{
	if ( $flag_zero && (( $width == 0 )||( $height == 0 )) ) {
		return array( $max_width, 0 );
	}

	if ($width > $max_width)
    {
    	$mag    = $max_width / $width;
    	$width  = $max_width;
    	$height = $height * $mag;
    }

	if ($height > $max_height)
    {
    	$mag    = $max_height / $height;
    	$height = $max_height;
    	$width  = $width * $mag;
    }

    $width  = intval($width);
    $height = intval($height);

	return array($width, $height);
}

function minimum_size($width, $height, $min_width=0, $min_height=0)
{
	if ( empty($width) ) 
	{
		$width = $min_width;
	}

	if ( empty($height) ) 
	{
		$height = $min_height;
	}

	return array($width, $height);
}

// --- class end ---
}

?>