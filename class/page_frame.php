<?php
// $Id: page_frame.php,v 1.2 2012/04/08 18:22:28 ohwada Exp $

// 2012-04-02 K.OHWADA
// get_page_last()

// 2007-11-01 K.OHWADA
// include/memory.php
// REQUEST_URI

// 2007-09-01 K.OHWADA
// _get_post_sortid()

// 2007-06-01 K.OHWADA
// _get_col_class()
// _FLAG_PRINT_SOTRID

// 2006-09-10 K.OHWADA
// use XoopsGTicket
// change _get_handler_objs()

// 2006-07-10 K.OHWADA
// this is new file
// porting from weblinks_page_frame.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

include_once XOOPS_ROOT_PATH.'/modules/happy_linux/include/memory.php';

//=========================================================
// class page_frame
//=========================================================
class happy_linux_page_frame extends happy_linux_form
{
// class instance
	var $_handler;	// MUST set by children class
	var $_pagenavi;

// language
	var $_LANG_TITLE        = _HAPPY_LINUX_TITLE;
	var $_LANG_THERE_ARE    = _HAPPY_LINUX_THERE_ARE;
	var $_LANG_NO_RECORD    = _HAPPY_LINUX_NO_RECORD;
	var $_LANG_ID_ASC       = _HAPPY_LINUX_ID_ASC;
	var $_LANG_ID_DESC      = _HAPPY_LINUX_ID_DESC;
	var $_LANG_SUBMIT_VALUE = _EDIT;

// constant
	var $_FLAG_GET_SORTID        = true;
	var $_FLAG_PRINT_TOP         = true;
	var $_FLAG_PRINT_SOTRID      = false;
	var $_FLAG_PRINT_REQUEST_URI = false;
	var $_FLAG_PRINT_NAVI_PRE    = false;
	var $_FLAG_PRINT_NAVI_POST   = true;
	var $_FLAG_EXECUTE_TIME      = false;

	var $_PERPAGE         = 50;
	var $_MAX_SORTID      = 1;
	var $_SCRIPT          = '';

	var $_HEAD_CLASS      = '';
	var $_HEAD_ALIGN      = 'center';
	var $_HEAD_VALIGN     = 'top';
	var $_HEAD_COLSPAN    = '';
	var $_HEAD_ROWSPAN    = '';

	var $_ITEM_CLASS      = '';
	var $_ITEM_ALIGN      = '';
	var $_ITEM_VALIGN     = 'top';
	var $_ITEM_COLSPAN    = '';
	var $_ITEM_ROWSPAN    = '';

	var $_SUBMIT_CLASS    = 'foot';
	var $_SUBMIT_ALIGN    = 'center';
	var $_SUBMIT_VALIGN   = 'top';
	var $_SUBMIT_COLSPAN  = '';
	var $_SUBMIT_ROWSPAN  = '';
	var $_SUBMIT_NAME     = 'submit';
	var $_SUBMIT_COLSPAN1 = 0;
	var $_SUBMIT_COLSPAN2 = 2;
	var $_SUBMIT_COLSPAN3 = 0;

	var $_NO_ITEM_COLOR   = '#0000ff';	// blue
	var $_NO_ITEM_WEIGHT  = 'bold';

// input param
	var $_flag_sortid    = true;
	var $_flag_alternate = false;
	var $_flag_form      = false;
	var $_id_name        = '';

// variable
	var $_item_count = 0; 
	var $_sortid;
	var $_total_all;
	var $_total;
	var $_start;

//---------------------------------------------------------
// constructor
//---------------------------------------------------------
function happy_linux_page_frame()
{
	$this->happy_linux_form();

// class instance
	$this->_pagenavi =& happy_linux_pagenavi::getInstance();
}

function &getInstance()
{
	static $instance;
	if (!isset($instance)) 
	{
		$instance = new happy_linux_page_frame();
	}
	return $instance;
}

//---------------------------------------------------------
// main
//---------------------------------------------------------
function _show()
{
	$total = $this->_pre_proc();

	if ($total > 0)
	{
		$this->_main_proc();
	}
	else
	{
		$this->_no_item_proc();
	}

	$this->_post_proc();
}

function _show_by_sortid($sortid)
{
	$this->_pagenavi->set_sortid( $sortid );
	$this->_set_sortid( $sortid );
	$this->_show();
}

//---------------------------------------------------------
// Pre processing
//---------------------------------------------------------
function _pre_proc()
{
	$this->_init();
	$this->_init_pagenavi();

	if ($this->_FLAG_GET_SORTID)
	{
		$sortid = $this->_get_post_sortid();
		$this->_pagenavi->set_sortid( $sortid );
		$this->_set_sortid( $sortid );
	}

	$total = $this->_get_total();
	$this->_set_total( $total );	
	$this->_pagenavi->setTotal($total);
	$this->_pagenavi->getGetPage();

	if ($this->_FLAG_PRINT_TOP)
	{
		$this->_print_top();
	}

	return $total;
}

function _init()
{
	// dummy
}

function _print_top()
{
	$this->_print_top_title();
	$this->_print_top_total();
	$this->_print_top_list();
	$this->_print_top_extra();
}

function _print_top_title()
{
	echo "<h4>".$this->_LANG_TITLE."</h4>\n";
}

function _print_top_total()
{
	$total_all = $this->_get_total_all();

	printf( $this->_LANG_THERE_ARE, $total_all);
	echo "<br /><br />\n";
}

function _print_top_list()
{
	$script_asc  = $this->_get_script_asc();
	$script_desc = $this->_get_script_desc();

	echo "<ul>\n";
	echo '<li><a href="'.$script_asc. '">'.$this->_LANG_ID_ASC. "</a><br /><br /></li>\n";
	echo '<li><a href="'.$script_desc.'">'.$this->_LANG_ID_DESC."</a><br /><br /></li>\n";
	echo "</ul>\n";
	echo "<br />\n";
}

function _print_top_extra()
{
	// dummy
}

function _get_total_all()
{
	$total = $this->_get_handler_total();
	$this->_total_all = $total;
	return $total;
}

function _get_total()
{
	$total = $this->_get_total_all();
	$this->_total = $total;
	return $total;
}

//---------------------------------------------------------
// get POST GET sortid
//---------------------------------------------------------
function _get_post_sortid()
{
	$sortid = 0;
	if ( isset($_POST['sortid']) ) {
		$sortid = intval($_POST['sortid']);
	} elseif ( isset($_GET['sortid']) ) {
		$sortid = intval($_GET['sortid']);
	} else {
		$sortid = $this->_convert_op_to_sortid();
	}

	$sortid = $this->_pagenavi->_check_sortid($sortid);
	return $sortid;
}

function _convert_op_to_sortid()
{
	$arr =& $this->_get_op_sortid_array();
	$op  =  $this->_get_post_op();

	$sortid = 0;
	if ( isset($arr[$op]) ) {
		$sortid = $arr[$op];
	} else {
		$sortid = $this->_pagenavi->_sortid_default;
	}

	return intval($sortid);
}

function _get_post_op()
{
	$op = null;
	if     ( isset($_POST['op']) )  $op = $_POST['op'];
	elseif ( isset($_GET['op']) )   $op = $_GET['op'];
	return $op;
}

// override this
function &_get_op_sortid_array()
{
	$arr = array(
		'list_asc'  => 0,
		'list_desc' => 1,
	);
	return $arr;
}

//---------------------------------------------------------
// No item processing
//---------------------------------------------------------
function _no_item_proc()
{
	echo "<br />\n";
	echo $this->_build_page_no_item();
	echo "<br />\n";
}

//---------------------------------------------------------
// Post processing
//---------------------------------------------------------
function _post_proc( $flag=false )
{
	$this->_print_execute_time( $flag );
}

function _print_execute_time( $flag=false )
{
	if ( $flag || $this->_FLAG_EXECUTE_TIME )
	{
		$time =& happy_linux_time::getInstance();
		echo "<br /><hr />\n";
		echo $time->build_elapse_time()."<br />\n";
		echo happy_linux_build_memory_usage_mb()."<br />\n";
	}
}

//---------------------------------------------------------
// Main processing
//---------------------------------------------------------
function _main_proc()
{
	$item_arr =& $this->_pre_main_proc();

	foreach ($item_arr as $item) 
	{
		$this->_item_proc($item);
	}

	$this->_post_main_proc();
}

function &_pre_main_proc()
{
	$start = $this->_calc_pagenavi();
	$this->_pre_main_extra();

	if ($this->_FLAG_PRINT_NAVI_PRE)
	{
		$this->_print_pagenavi();
		echo "<br />\n";
	}

// --- form begin ---
	$this->_pre_main_form_begin();
	$item_arr =& $this->_get_items($this->_PERPAGE, $start);
	return $item_arr;
}

function _pre_main_extra()
{
	// dummy
}

function _pre_main_form_begin()
{
	$this->_print_form_begin();
	$this->_print_table_begin();
	$this->_print_table_header();
}

function _item_proc( &$item )
{
	$this->_print_table_item($item);
}

function _post_main_proc()
{
	$this->_post_main_form_end();
// --- form end ---

	if ($this->_FLAG_PRINT_NAVI_POST)
	{
		$this->_print_pagenavi();
	}
}

function _post_main_form_end()
{
	$this->_print_table_submit();
	$this->_print_table_end();
	$this->_print_form_end();
}

function _print_pagenavi()
{
	$navi = $this->_build_pagenavi();
	echo '<div align="center">'.$navi.'</div>'."\n";
}

//---------------------------------------------------------
// form
//---------------------------------------------------------
function _print_form_begin()
{
	if ($this->_flag_form)
	{
		echo $this->_build_page_form_begin();
	}
}

function _print_form_end()
{
	if ($this->_flag_form)
	{
		echo $this->_build_page_form_end();
	}
}

//---------------------------------------------------------
// table
//---------------------------------------------------------
function _print_table_begin()
{
	echo '<table border="1">'."\n";
}

function _print_table_end()
{
	echo "</table><br />\n";
}

function _print_table_header()
{
	$head_arr =& $this->_get_table_header();

	echo "<tr>";

	foreach ($head_arr as $head)
	{
		echo $this->_build_page_col_head($head);
	}

	echo "</tr>\n";
}

function _print_table_item( &$item )
{
	$col_arr =& $this->_get_cols($item);
	$class   =  $this->_get_col_class($item);

	if ($this->_flag_alternate)
	{
		$class = $this->_build_page_class_alternate();
		echo '<tr class="'.$class.'">'."\n";
	}
	elseif ($class)
	{
		echo '<tr class="'.$class.'">'."\n";
	}
	else
	{
		echo "<tr>\n";
	}

	foreach ($col_arr as $col)
	{
		echo $this->_build_page_col_item($col);
	}

	echo "</tr>\n";
}

function _build_page_class_alternate()
{
	if ($this->_item_count % 2 == 0) 
	{
		$class = 'even';
	}
	else 
	{
		$class = 'odd';
	}

	$this->_item_count ++;

	return $class;
}

function _print_table_submit()
{
	if ($this->_flag_form)
	{
		echo $this->_build_page_submit($this->_SUBMIT_COLSPAN1, $this->_SUBMIT_COLSPAN2, $this->_SUBMIT_COLSPAN3);
	}
}

function _build_page_submit($colspan1=0, $colspan2=2, $colspan3=0)
{
	$text  = "<tr>";
	
	if ( $colspan1 )
	{
		$text .= $this->_build_page_col_submit_null( $colspan1 );
	}

	$text .= $this->_build_page_col_submit( $colspan2 );

	if ( $colspan3 )
	{
		$text .= $this->_build_page_col_submit_null( $colspan3 );
	}

	$text .= "</tr>\n";
	return $text;
}

//---------------------------------------------------------
// get_script
//---------------------------------------------------------
function _get_script_asc()
{
	$script = '?sortid=0';
	return $script;
}

function _get_script_desc()
{
	$script = '?sortid=1';
	return $script;
}

function _get_script()
{
	return $this->_SCRIPT;	// null
}

//---------------------------------------------------------
// sample of items
//---------------------------------------------------------
function &_get_table_header()
{
	$arr = array(
		'id',
		'title',
	);

	return $arr;
}

function &_get_items($limit=0, $start=0)
{
	$objs =& $this->_get_handler_objs($limit, $start);
	return $objs;
}

function &_get_cols( &$obj )
{
	$id    = $this->_build_page_label_by_obj($obj, $this->_id_name);
	$id    = $this->_build_formated_id($id);
	$title = $this->_build_page_label_by_obj($obj, 'title');

	$arr = array(
		$id,
		$title,
	);

	return $arr;
}

function _get_col_class( &$obj )
{
	return '';
}

//---------------------------------------------------------
// utility
//---------------------------------------------------------
function _build_page_id_link_by_obj(&$obj, $key, $jump, $title='', $target='')
{
	if ( !is_object($obj) )  { return false; }

	$id = $obj->getVar($key);
	$jump_id = $jump.$id;

	if ( empty($title) )
	{
		$title = $this->_build_formated_id($id);
	}

	$text = $this->build_html_a_href_name($jump_id, $title, $target);	// class build_html
	return $text;
}

function _build_page_name_link_by_obj(&$obj, $key1, $key2='', $target='')
{
	if ( !is_object($obj) )  { return false; }

	$url = $obj->getVar($key1, 's');

	if ($key2)
	{
		$name = $obj->getVar($key2, 's');
	}
	else
	{
		$name = $url;
	}

	if ($url)
	{
		$text = $this->build_html_a_href_name($url, $name, $target);	// class build_html
		return $text;
	}

	return '&nbsp;';
}

function _build_page_label_by_obj(&$obj, $key)
{
	if ( !is_object($obj) )  { return false; }

	$text = $obj->getVar($key, 's');

	if ($text)
	{
		return $text;
	}

	return '&nbsp;';
}

function _build_formated_id($id, $format="%03d")
{
	$text = sprintf($format, $id);
	return $text;
}

//---------------------------------------------------------
// pagenavi class
//---------------------------------------------------------
function _init_pagenavi()
{
	$this->_pagenavi->setPerpage(      $this->_PERPAGE );
	$this->_pagenavi->set_max_sortid(  $this->_MAX_SORTID );
	$this->_pagenavi->set_flag_sortid( $this->_flag_sortid );
}

function _calc_pagenavi()
{
	$start = $this->_pagenavi->calcStart();
	$end   = $this->_pagenavi->calcEnd();
	$this->_set_start( $start );
	$this->_set_end(   $end );

	return $start;
}

function _build_pagenavi()
{
	$script = $this->_get_script();	// null
	$navi   = $this->_pagenavi->build( $script );
	return $navi;
}

function _set_sortid($val)
{
	$this->_sortid = intval($val);
}

function _set_total($val)
{
	$this->_total = intval($val);
}

function _set_start($val)
{
	$this->_start = intval($val);
}

function _set_end($val)
{
	$this->_end = intval($val);
}

//---------------------------------------------------------
// handler class
//---------------------------------------------------------
function _get_handler_total()
{
	$count = 0;

	if ( is_object($this->_handler) )
	{
		$count = $this->_handler->getCount();
	}

	return $count;
}

function &_get_handler_objs($limit=0, $start=0)
{
	$objs = false;
	if ( is_object($this->_handler) )
	{
		if ($this->_sortid == 1)
		{
			$objs =& $this->_handler->get_objects_desc($limit, $start);
		}
		else
		{
			$objs =& $this->_handler->get_objects_asc($limit, $start);
		}
	}
	return $objs;
}

//---------------------------------------------------------
// wrapper for build_form class
//---------------------------------------------------------
function _build_page_form_begin()
{
	$form  = $this->build_form_begin($this->_FORM_NAME, $this->_ACTION);
	$form .= $this->build_token();
	$form .= $this->build_html_input_hidden($this->_OP_NAME, $this->_op_value);
	if ( $this->_FLAG_PRINT_SOTRID )
	{
		$form .= $this->build_html_input_hidden('sortid', $this->_sortid);
	}
	if ( $this->_FLAG_PRINT_REQUEST_URI && isset($_SERVER['REQUEST_URI']) )
	{
		$uri   = $this->sanitize_url( $_SERVER['REQUEST_URI'] );
		$form .= $this->build_html_input_hidden( 'request_uri', $uri );
	}
	$form .= $this->_build_page_form_begin_extra();
	return $form;
}

function _build_page_form_begin_extra()
{
	return '';
}

function _build_page_form_end()
{
	$text = $this->build_form_end();
	return $text;
}

function _build_page_col_head($value, $colspan=1)
{
	$text  = $this->build_html_th_tag_begin($this->_HEAD_ALIGN, $this->_HEAD_VALIGN, $colspan, $this->_HEAD_ROWSPAN, $this->_HEAD_CLASS);
	$text .= $this->substute_blank($value);
	$text .= $this->build_html_th_tag_end();
	return $text;
}

function _build_page_col_item($value, $colspan=1)
{
	$text  = $this->build_html_td_tag_begin($this->_ITEM_ALIGN, $this->_ITEM_VALIGN, $colspan, $this->_ITEM_ROWSPAN, $this->_ITEM_CLASS);
	$text .= $this->substute_blank($value);
	$text .= $this->build_html_td_tag_end();
	return $text;
}

function _build_page_col_null($colspan=1)
{
	$text  = $this->build_html_td_tag_begin($this->_ITEM_ALIGN, $this->_ITEM_VALIGN, $colspan, $this->_ITEM_ROWSPAN, $this->_ITEM_CLASS);
	$text .= '&nbsp';
	$text .= $this->build_html_td_tag_end();
	return $text;
}

function _build_page_col_submit($colspan=1)
{
	$text  = $this->build_html_td_tag_begin($this->_SUBMIT_ALIGN, $this->_SUBMIT_VALIGN, $colspan, $this->_SUBMIT_ROWSPAN, $this->_SUBMIT_CLASS);
	$text .= $this->build_html_input_submit($this->_SUBMIT_NAME, $this->_LANG_SUBMIT_VALUE);
	$text .= $this->build_html_td_tag_end();
	return $text;
}

function _build_page_col_submit_null($colspan=1)
{
	$text  = $this->build_html_td_tag_begin($this->_SUBMIT_ALIGN, $this->_SUBMIT_VALIGN, $colspan, $this->_SUBMIT_ROWSPAN, $this->_SUBMIT_CLASS);
	$text .= '&nbsp';
	$text .= $this->build_html_td_tag_end();
	return $text;
}

function _build_page_no_item()
{
	$text = $this->build_html_highlight($this->_LANG_NO_RECORD, $this->_NO_ITEM_COLOR, $this->_NO_ITEM_WEIGHT);
	return $text;
}

//---------------------------------------------------------
// set param
//---------------------------------------------------------
function set_handler($table_name, $dirname, $prefix='happy_linux')
{
	$this->_handler =& happy_linux_get_handler($table_name, $dirname, $prefix);
}

function set_id_name($value)
{
	$this->_id_name = $value;
}

function set_flag_sortid($value)
{
	$this->_flag_sortid = (bool)$value;
}

function set_flag_alternate($value)
{
	$this->_flag_alternate = (bool)$value;
}

function set_flag_form($value)
{
	$this->_flag_form = (bool)$value;
}

function set_perpage($value)
{
	$this->_PERPAGE = intval($value);
}

function set_max_sortid($value)
{
	$this->_MAX_SORTID = intval($value);
}

function set_script($value)
{
	$this->_SCRIPT = $value;
}

function set_head_align($value)
{
	$this->_HEAD_ALIGN = $value;
}

function set_head_class($value)
{
	$this->_HEAD_CLASS = $value;
}

function set_item_align($value)
{
	$this->_ITEM_ALIGN = $value;
}

function set_item_class($value)
{
	$this->_ITEM_CLASS = $value;
}

function set_submit_name($value)
{
	$this->_SUBMIT_NAME = $value;
}

function set_submit_align($value)
{
	$this->_SUBMIT_ALIGN = $value;
}

function set_submit_class($value)
{
	$this->_SUBMIT_CLASS = $value;
}

function set_submit_colspan($col1=0, $col2=2, $col3=0)
{
	$this->_SUBMIT_COLSPAN1 = intval($col1);
	$this->_SUBMIT_COLSPAN2 = intval($col2);
	$this->_SUBMIT_COLSPAN3 = intval($col3);
}

function set_no_item_color($value)
{
	$this->_NO_ITEM_COLOR = $value;
}

function set_no_item_weight($value)
{
	$this->_NO_ITEM_WEIGHT = $value;
}

function set_flag_get_sortid($value)
{
	$this->_FLAG_GET_SORTID = (bool)$value;
}

function set_flag_print_top($value)
{
	$this->_FLAG_PRINT_TOP = (bool)$value;
}

function set_flag_print_navi($value)
{
	$this->set_flag_print_navi_post($value);
}

function set_flag_print_navi_pre($value)
{
	$this->_FLAG_PRINT_NAVI_PRE = (bool)$value;
}

function set_flag_print_navi_post($value)
{
	$this->_FLAG_PRINT_NAVI_POST = (bool)$value;
}

function set_flag_print_sortid($value)
{
	$this->_FLAG_PRINT_SOTRID = (bool)$value;
}

function set_flag_print_request_uri($value)
{
	$this->_FLAG_PRINT_REQUEST_URI = (bool)$value;
}

function set_flag_execute_time($val)
{
	$this->_FLAG_EXECUTE_TIME = (bool)$val;
}

function set_lang_title($value)
{
	$this->_LANG_TITLE = $value;
}

function set_lang_there_are($value)
{
	$this->_LANG_THERE_ARE = $value;
}

function set_lang_no_item($value)
{
	$this->_LANG_NO_RECORD = $value;
}

function set_lang_id_asc($value)
{
	$this->_LANG_ID_ASC = $value;
}

function set_lang_id_desc($value)
{
	$this->_LANG_ID_DESC = $value;
}

function set_lang_submit_value($value)
{
	$this->_LANG_SUBMIT_VALUE = $value;
}

function get_page_current()
{
	return $this->_pagenavi->get_page_current();
}

function get_page_last()
{
	return $this->_pagenavi->calc_page_last();
}

//---------------------------------------------------------
// overwrit to form class
//---------------------------------------------------------
function set_operation($value)
{
	$this->set_op_value($value);
}


// --- class end ---
}

?>