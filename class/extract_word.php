<?php
// $Id: extract_word.php,v 1.5 2007/10/25 15:28:26 ohwada Exp $

// 2007-10-10 K.OHWADA
// PHP 5.2: Non-static method

//=========================================================
// Happy Linux Framework Module
// 2007-06-01 K.OHWADA
//=========================================================

class happy_linux_extract_word
{
	var $_kakasi;

	var $_extract_mode    = 1;	// use kakasi
	var $_min_char_length = 8;
	var $_flag_join_prev  = false;
	var $_join_glue       = '';	// without spacing

	var $_flag_strip_tags   = true;
	var $_flag_strip_symbol = true;
	var $_flag_strip_figure = true;
	var $_flag_space_zen_to_han = true;

	var $_result_arr = array();

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_extract_word()
{
// PHP 5.2: Non-static method
	$this->_kakasi =& happy_linux_get_singleton( 'kakasi' );
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) 
	{
		$instance = new happy_linux_extract_word();
	}
	return $instance;
}

//---------------------------------------------------------
// public
//---------------------------------------------------------
function &execute(&$str)
{
	$text = $this->_pre_extract($str);

	if ( ($this->_extract_mode == 1) && $this->_kakasi->is_executable_kakasi() )
	{
		$opt = "-w -c ".$this->_kakasi->get_opt();
		$this->_kakasi->execute($text, $opt);
		$result = $this->_kakasi->get_words();
	}
	else
	{
		$result = $text;
	}

	$ret =& $this->_post_extract($result);
	return $ret;
}

function set_extract_mode($val)
{
	$this->_extract_mode = intval($val);
}

function set_kakasi_path($val)
{
	$this->_kakasi->set_kakasi_path($val);
}

function set_kakasi_mode($val)
{
	$this->_kakasi->set_mode_execute($val);
}

function set_kakasi_dir_work($val)
{
	$this->_kakasi->set_dir_work($val);
}

function get_kakasi_dir_work()
{
	return $this->_kakasi->get_dir_work();
}

//---------------------------------------------------------
// private
//---------------------------------------------------------
function _pre_extract(&$str)
{
	if ($this->_flag_strip_tags)
	{
		$text = strip_tags($str);
	}
	else
	{
		$text = $str;
	}

	if ($this->_flag_strip_figure)
	{
		$text = preg_replace("/\d+/", ' ', $text);
	}

	if ($this->_flag_strip_symbol)
	{
		$text = $this->_strip_symbol($text);
	}

	if ($this->_flag_space_zen_to_han)
	{
		$text = happy_linux_convert_kana($text, 's');
	}

// remove continous space
	$text = preg_replace("/ +/", ' ', $text);

	return $text;
}

function &_post_extract(&$str)
{
	$arr  = array();
	$prev = '';

	$temp = split("[\t\r\n ]", $str);
	foreach ($temp as $w1)
	{
		if ( empty($w1) )
		{	continue;	}

		if ( strlen($w1) >= $this->_min_char_length )
		{
			$arr[] = $w1;
		}

// join with prevous word
		if ( $this->_flag_join_prev )
		{
			$w2 = $prev.$this->_join_glue.$w1;
			if ( strlen($w2) >= $this->_min_char_length )
			{
				$arr[] = $w2;
			}
			$prev = $w1;
		}
	}

	$res = array_unique($arr);
	$this->_result_arr = $res;
	return $res;
}

//---------------------------------------------------------
// \x09 TAB \t
// \xOA LF \n
// \xOD CR \r
// \x20 SP \s
// \x30-\x39 0-9 
// \x41-\x5A A-Z 
// \x61-\x7A a-z 
//---------------------------------------------------------
function _strip_symbol(&$str)
{
	$text = $str;
	$text = preg_replace('/[\x00-\x08]/', ' ', $text);
	$text = preg_replace('/[\x0B-\x0C]/', ' ', $text);
	$text = preg_replace('/[\x0E-\x1F]/' ,' ', $text);
	$text = preg_replace('/[\x21-\x2F]/' ,' ', $text);
	$text = preg_replace('/[\x3A-\x40]/' ,' ', $text);
	$text = preg_replace('/[\x5B-\x60]/' ,' ', $text);
	$text = preg_replace('/[\x7B-\x7F]/', ' ', $text);
	return $text;
}

//---------------------------------------------------------
// set and get property
//---------------------------------------------------------
function set_min_char_length($val)
{
	$this->_min_char_length = intval($val);
}

function set_flag_join_prev($val)
{
	$this->_flag_join_prev = (bool)$val;
}

function set_join_glue($val)
{
	$this->_join_glue = $val;
}

// --- class end ---
}

?>