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

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/include/memory.php';

//=========================================================
// class page_frame
//=========================================================
class happy_linux_page_frame extends happy_linux_form
{
    // class instance
    public $_handler;  // MUST set by children class
    public $_pagenavi;

    // language
    public $_LANG_TITLE        = _HAPPY_LINUX_TITLE;
    public $_LANG_THERE_ARE    = _HAPPY_LINUX_THERE_ARE;
    public $_LANG_NO_RECORD    = _HAPPY_LINUX_NO_RECORD;
    public $_LANG_ID_ASC       = _HAPPY_LINUX_ID_ASC;
    public $_LANG_ID_DESC      = _HAPPY_LINUX_ID_DESC;
    public $_LANG_SUBMIT_VALUE = _EDIT;

    // constant
    public $_FLAG_GET_SORTID        = true;
    public $_FLAG_PRINT_TOP         = true;
    public $_FLAG_PRINT_SOTRID      = false;
    public $_FLAG_PRINT_REQUEST_URI = false;
    public $_FLAG_PRINT_NAVI_PRE    = false;
    public $_FLAG_PRINT_NAVI_POST   = true;
    public $_FLAG_EXECUTE_TIME      = false;

    public $_PERPAGE    = 50;
    public $_MAX_SORTID = 1;
    public $_SCRIPT     = '';

    public $_HEAD_CLASS   = '';
    public $_HEAD_ALIGN   = 'center';
    public $_HEAD_VALIGN  = 'top';
    public $_HEAD_COLSPAN = '';
    public $_HEAD_ROWSPAN = '';

    public $_ITEM_CLASS   = '';
    public $_ITEM_ALIGN   = '';
    public $_ITEM_VALIGN  = 'top';
    public $_ITEM_COLSPAN = '';
    public $_ITEM_ROWSPAN = '';

    public $_SUBMIT_CLASS    = 'foot';
    public $_SUBMIT_ALIGN    = 'center';
    public $_SUBMIT_VALIGN   = 'top';
    public $_SUBMIT_COLSPAN  = '';
    public $_SUBMIT_ROWSPAN  = '';
    public $_SUBMIT_NAME     = 'submit';
    public $_SUBMIT_COLSPAN1 = 0;
    public $_SUBMIT_COLSPAN2 = 2;
    public $_SUBMIT_COLSPAN3 = 0;

    public $_NO_ITEM_COLOR  = '#0000ff';  // blue
    public $_NO_ITEM_WEIGHT = 'bold';

    // input param
    public $_flag_sortid    = true;
    public $_flag_alternate = false;
    public $_flag_form      = false;
    public $_id_name        = '';

    // variable
    public $_item_count = 0;
    public $_sortid;
    public $_total_all;
    public $_total;
    public $_start;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        // class instance
        $this->_pagenavi = happy_linux_pagenavi::getInstance();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_page_frame();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // main
    //---------------------------------------------------------
    public function _show($obj = null, $extra = null, $mode = 0)
    {
        $total = $this->_pre_proc();

        if ($total > 0) {
            $this->_main_proc();
        } else {
            $this->_no_item_proc();
        }

        $this->_post_proc();
    }

    public function _show_by_sortid($sortid)
    {
        $this->_pagenavi->set_sortid($sortid);
        $this->_set_sortid($sortid);
        $this->_show();
    }

    //---------------------------------------------------------
    // Pre processing
    //---------------------------------------------------------
    public function _pre_proc()
    {
        $this->_init();
        $this->_init_pagenavi();

        if ($this->_FLAG_GET_SORTID) {
            $sortid = $this->_get_post_sortid();
            $this->_pagenavi->set_sortid($sortid);
            $this->_set_sortid($sortid);
        }

        $total = $this->_get_total();
        $this->_set_total($total);
        $this->_pagenavi->setTotal($total);
        $this->_pagenavi->getGetPage();

        if ($this->_FLAG_PRINT_TOP) {
            $this->_print_top();
        }

        return $total;
    }

    public function _init()
    {
        // dummy
    }

    public function _print_top()
    {
        $this->_print_top_title();
        $this->_print_top_total();
        $this->_print_top_list();
        $this->_print_top_extra();
    }

    public function _print_top_title()
    {
        echo '<h4>' . $this->_LANG_TITLE . "</h4>\n";
    }

    public function _print_top_total()
    {
        $total_all = $this->_get_total_all();

        printf($this->_LANG_THERE_ARE, $total_all);
        echo "<br /><br />\n";
    }

    public function _print_top_list()
    {
        $script_asc  = $this->_get_script_asc();
        $script_desc = $this->_get_script_desc();

        echo "<ul>\n";
        echo '<li><a href="' . $script_asc . '">' . $this->_LANG_ID_ASC . "</a><br /><br /></li>\n";
        echo '<li><a href="' . $script_desc . '">' . $this->_LANG_ID_DESC . "</a><br /><br /></li>\n";
        echo "</ul>\n";
        echo "<br />\n";
    }

    public function _print_top_extra()
    {
        // dummy
    }

    public function _get_total_all()
    {
        $total            = $this->_get_handler_total();
        $this->_total_all = $total;
        return $total;
    }

    public function _get_total()
    {
        $total        = $this->_get_total_all();
        $this->_total = $total;
        return $total;
    }

    //---------------------------------------------------------
    // get POST GET sortid
    //---------------------------------------------------------
    public function _get_post_sortid()
    {
        $sortid = 0;
        if (isset($_POST['sortid'])) {
            $sortid = (int)$_POST['sortid'];
        } elseif (isset($_GET['sortid'])) {
            $sortid = (int)$_GET['sortid'];
        } else {
            $sortid = $this->_convert_op_to_sortid();
        }

        $sortid = $this->_pagenavi->_check_sortid($sortid);
        return $sortid;
    }

    public function _convert_op_to_sortid()
    {
        $arr =& $this->_get_op_sortid_array();
        $op  = $this->_get_post_op();

        $sortid = 0;
        if (isset($arr[$op])) {
            $sortid = $arr[$op];
        } else {
            $sortid = $this->_pagenavi->_sortid_default;
        }

        return (int)$sortid;
    }

    public function _get_post_op()
    {
        $op = null;
        if (isset($_POST['op'])) {
            $op = $_POST['op'];
        } elseif (isset($_GET['op'])) {
            $op = $_GET['op'];
        }
        return $op;
    }

    // override this
    public function &_get_op_sortid_array()
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
    public function _no_item_proc()
    {
        echo "<br />\n";
        echo $this->_build_page_no_item();
        echo "<br />\n";
    }

    //---------------------------------------------------------
    // Post processing
    //---------------------------------------------------------
    public function _post_proc($flag = false)
    {
        $this->_print_execute_time($flag);
    }

    public function _print_execute_time($flag = false)
    {
        if ($flag || $this->_FLAG_EXECUTE_TIME) {
            $time = happy_linux_time::getInstance();
            echo "<br /><hr />\n";
            echo $time->build_elapse_time() . "<br />\n";
            echo happy_linux_build_memory_usage_mb() . "<br />\n";
        }
    }

    //---------------------------------------------------------
    // Main processing
    //---------------------------------------------------------
    public function _main_proc()
    {
        $item_arr =& $this->_pre_main_proc();

        foreach ($item_arr as $item) {
            $this->_item_proc($item);
        }

        $this->_post_main_proc();
    }

    public function &_pre_main_proc()
    {
        $start = $this->_calc_pagenavi();
        $this->_pre_main_extra();

        if ($this->_FLAG_PRINT_NAVI_PRE) {
            $this->_print_pagenavi();
            echo "<br />\n";
        }

        // --- form begin ---
        $this->_pre_main_form_begin();
        $item_arr =& $this->_get_items($this->_PERPAGE, $start);
        return $item_arr;
    }

    public function _pre_main_extra()
    {
        // dummy
    }

    public function _pre_main_form_begin()
    {
        $this->_print_form_begin();
        $this->_print_table_begin();
        $this->_print_table_header();
    }

    public function _item_proc(&$item)
    {
        $this->_print_table_item($item);
    }

    public function _post_main_proc()
    {
        $this->_post_main_form_end();
        // --- form end ---

        if ($this->_FLAG_PRINT_NAVI_POST) {
            $this->_print_pagenavi();
        }
    }

    public function _post_main_form_end()
    {
        $this->_print_table_submit();
        $this->_print_table_end();
        $this->_print_form_end();
    }

    public function _print_pagenavi()
    {
        $navi = $this->_build_pagenavi();
        echo '<div align="center">' . $navi . '</div>' . "\n";
    }

    //---------------------------------------------------------
    // form
    //---------------------------------------------------------
    public function _print_form_begin()
    {
        if ($this->_flag_form) {
            echo $this->_build_page_form_begin();
        }
    }

    public function _print_form_end()
    {
        if ($this->_flag_form) {
            echo $this->_build_page_form_end();
        }
    }

    //---------------------------------------------------------
    // table
    //---------------------------------------------------------
    public function _print_table_begin()
    {
        echo '<table border="1">' . "\n";
    }

    public function _print_table_end()
    {
        echo "</table><br />\n";
    }

    public function _print_table_header()
    {
        $head_arr =& $this->_get_table_header();

        echo '<tr>';

        foreach ($head_arr as $head) {
            echo $this->_build_page_col_head($head);
        }

        echo "</tr>\n";
    }

    public function _print_table_item(&$item)
    {
        $col_arr =& $this->_get_cols($item);
        $class   = $this->_get_col_class($item);

        if ($this->_flag_alternate) {
            $class = $this->_build_page_class_alternate();
            echo '<tr class="' . $class . '">' . "\n";
        } elseif ($class) {
            echo '<tr class="' . $class . '">' . "\n";
        } else {
            echo "<tr>\n";
        }

        foreach ($col_arr as $col) {
            echo $this->_build_page_col_item($col);
        }

        echo "</tr>\n";
    }

    public function _build_page_class_alternate()
    {
        if ($this->_item_count % 2 == 0) {
            $class = 'even';
        } else {
            $class = 'odd';
        }

        $this->_item_count++;

        return $class;
    }

    public function _print_table_submit()
    {
        if ($this->_flag_form) {
            echo $this->_build_page_submit($this->_SUBMIT_COLSPAN1, $this->_SUBMIT_COLSPAN2, $this->_SUBMIT_COLSPAN3);
        }
    }

    public function _build_page_submit($colspan1 = 0, $colspan2 = 2, $colspan3 = 0)
    {
        $text = '<tr>';

        if ($colspan1) {
            $text .= $this->_build_page_col_submit_null($colspan1);
        }

        $text .= $this->_build_page_col_submit($colspan2);

        if ($colspan3) {
            $text .= $this->_build_page_col_submit_null($colspan3);
        }

        $text .= "</tr>\n";
        return $text;
    }

    //---------------------------------------------------------
    // get_script
    //---------------------------------------------------------
    public function _get_script_asc()
    {
        $script = '?sortid=0';
        return $script;
    }

    public function _get_script_desc()
    {
        $script = '?sortid=1';
        return $script;
    }

    public function _get_script()
    {
        return $this->_SCRIPT;  // null
    }

    //---------------------------------------------------------
    // sample of items
    //---------------------------------------------------------
    public function &_get_table_header()
    {
        $arr = array(
            'id',
            'title',
        );

        return $arr;
    }

    public function &_get_items($limit = 0, $start = 0)
    {
        $objs =& $this->_get_handler_objs($limit, $start);
        return $objs;
    }

    public function &_get_cols(&$obj)
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

    public function _get_col_class(&$obj)
    {
        return '';
    }

    //---------------------------------------------------------
    // utility
    //---------------------------------------------------------
    public function _build_page_id_link_by_obj(&$obj, $key, $jump, $title = '', $target = '')
    {
        if (!is_object($obj)) {
            return false;
        }

        $id      = $obj->getVar($key);
        $jump_id = $jump . $id;

        if (empty($title)) {
            $title = $this->_build_formated_id($id);
        }

        $text = $this->build_html_a_href_name($jump_id, $title, $target);   // class build_html
        return $text;
    }

    public function _build_page_name_link_by_obj(&$obj, $key1, $key2 = '', $target = '')
    {
        if (!is_object($obj)) {
            return false;
        }

        $url = $obj->getVar($key1, 's');

        if ($key2) {
            $name = $obj->getVar($key2, 's');
        } else {
            $name = $url;
        }

        if ($url) {
            $text = $this->build_html_a_href_name($url, $name, $target);    // class build_html
            return $text;
        }

        return '&nbsp;';
    }

    public function _build_page_label_by_obj(&$obj, $key)
    {
        if (!is_object($obj)) {
            return false;
        }

        $text = $obj->getVar($key, 's');

        if ($text) {
            return $text;
        }

        return '&nbsp;';
    }

    public function _build_formated_id($id, $format = '%03d')
    {
        $text = sprintf($format, $id);
        return $text;
    }

    //---------------------------------------------------------
    // pagenavi class
    //---------------------------------------------------------
    public function _init_pagenavi()
    {
        $this->_pagenavi->setPerpage($this->_PERPAGE);
        $this->_pagenavi->set_max_sortid($this->_MAX_SORTID);
        $this->_pagenavi->set_flag_sortid($this->_flag_sortid);
    }

    public function _calc_pagenavi()
    {
        $start = $this->_pagenavi->calcStart();
        $end   = $this->_pagenavi->calcEnd();
        $this->_set_start($start);
        $this->_set_end($end);

        return $start;
    }

    public function _build_pagenavi()
    {
        $script = $this->_get_script(); // null
        $navi   = $this->_pagenavi->build($script);
        return $navi;
    }

    public function _set_sortid($val)
    {
        $this->_sortid = (int)$val;
    }

    public function _set_total($val)
    {
        $this->_total = (int)$val;
    }

    public function _set_start($val)
    {
        $this->_start = (int)$val;
    }

    public function _set_end($val)
    {
        $this->_end = (int)$val;
    }

    //---------------------------------------------------------
    // handler class
    //---------------------------------------------------------
    public function _get_handler_total()
    {
        $count = 0;

        if (is_object($this->_handler)) {
            $count = $this->_handler->getCount();
        }

        return $count;
    }

    public function &_get_handler_objs($limit = 0, $start = 0)
    {
        $objs = false;
        if (is_object($this->_handler)) {
            if ($this->_sortid == 1) {
                $objs =& $this->_handler->get_objects_desc($limit, $start);
            } else {
                $objs =& $this->_handler->get_objects_asc($limit, $start);
            }
        }
        return $objs;
    }

    //---------------------------------------------------------
    // wrapper for build_form class
    //---------------------------------------------------------
    public function _build_page_form_begin()
    {
        $form = $this->build_form_begin($this->_FORM_NAME, $this->_ACTION);
        $form .= $this->build_token();
        $form .= $this->build_html_input_hidden($this->_OP_NAME, $this->_op_value);
        if ($this->_FLAG_PRINT_SOTRID) {
            $form .= $this->build_html_input_hidden('sortid', $this->_sortid);
        }
        if ($this->_FLAG_PRINT_REQUEST_URI && isset($_SERVER['REQUEST_URI'])) {
            $uri = $this->sanitize_url($_SERVER['REQUEST_URI']);
            $form .= $this->build_html_input_hidden('request_uri', $uri);
        }
        $form .= $this->_build_page_form_begin_extra();
        return $form;
    }

    public function _build_page_form_begin_extra()
    {
        return '';
    }

    public function _build_page_form_end()
    {
        $text = $this->build_form_end();
        return $text;
    }

    public function _build_page_col_head($value, $colspan = 1)
    {
        $text = $this->build_html_th_tag_begin($this->_HEAD_ALIGN, $this->_HEAD_VALIGN, $colspan, $this->_HEAD_ROWSPAN, $this->_HEAD_CLASS);
        $text .= $this->substute_blank($value);
        $text .= $this->build_html_th_tag_end();
        return $text;
    }

    public function _build_page_col_item($value, $colspan = 1)
    {
        $text = $this->build_html_td_tag_begin($this->_ITEM_ALIGN, $this->_ITEM_VALIGN, $colspan, $this->_ITEM_ROWSPAN, $this->_ITEM_CLASS);
        $text .= $this->substute_blank($value);
        $text .= $this->build_html_td_tag_end();
        return $text;
    }

    public function _build_page_col_null($colspan = 1)
    {
        $text = $this->build_html_td_tag_begin($this->_ITEM_ALIGN, $this->_ITEM_VALIGN, $colspan, $this->_ITEM_ROWSPAN, $this->_ITEM_CLASS);
        $text .= '&nbsp';
        $text .= $this->build_html_td_tag_end();
        return $text;
    }

    public function _build_page_col_submit($colspan = 1)
    {
        $text = $this->build_html_td_tag_begin($this->_SUBMIT_ALIGN, $this->_SUBMIT_VALIGN, $colspan, $this->_SUBMIT_ROWSPAN, $this->_SUBMIT_CLASS);
        $text .= $this->build_html_input_submit($this->_SUBMIT_NAME, $this->_LANG_SUBMIT_VALUE);
        $text .= $this->build_html_td_tag_end();
        return $text;
    }

    public function _build_page_col_submit_null($colspan = 1)
    {
        $text = $this->build_html_td_tag_begin($this->_SUBMIT_ALIGN, $this->_SUBMIT_VALIGN, $colspan, $this->_SUBMIT_ROWSPAN, $this->_SUBMIT_CLASS);
        $text .= '&nbsp';
        $text .= $this->build_html_td_tag_end();
        return $text;
    }

    public function _build_page_no_item()
    {
        $text = $this->build_html_highlight($this->_LANG_NO_RECORD, $this->_NO_ITEM_COLOR, $this->_NO_ITEM_WEIGHT);
        return $text;
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------
    public function set_handler($table_name, $dirname, $prefix = 'happy_linux')
    {
        $this->_handler = happy_linux_get_handler($table_name, $dirname, $prefix);
    }

    public function set_id_name($value)
    {
        $this->_id_name = $value;
    }

    public function set_flag_sortid($value)
    {
        $this->_flag_sortid = (bool)$value;
    }

    public function set_flag_alternate($value)
    {
        $this->_flag_alternate = (bool)$value;
    }

    public function set_flag_form($value)
    {
        $this->_flag_form = (bool)$value;
    }

    public function set_perpage($value)
    {
        $this->_PERPAGE = (int)$value;
    }

    public function set_max_sortid($value)
    {
        $this->_MAX_SORTID = (int)$value;
    }

    public function set_script($value)
    {
        $this->_SCRIPT = $value;
    }

    public function set_head_align($value)
    {
        $this->_HEAD_ALIGN = $value;
    }

    public function set_head_class($value)
    {
        $this->_HEAD_CLASS = $value;
    }

    public function set_item_align($value)
    {
        $this->_ITEM_ALIGN = $value;
    }

    public function set_item_class($value)
    {
        $this->_ITEM_CLASS = $value;
    }

    public function set_submit_name($value)
    {
        $this->_SUBMIT_NAME = $value;
    }

    public function set_submit_align($value)
    {
        $this->_SUBMIT_ALIGN = $value;
    }

    public function set_submit_class($value)
    {
        $this->_SUBMIT_CLASS = $value;
    }

    public function set_submit_colspan($col1 = 0, $col2 = 2, $col3 = 0)
    {
        $this->_SUBMIT_COLSPAN1 = (int)$col1;
        $this->_SUBMIT_COLSPAN2 = (int)$col2;
        $this->_SUBMIT_COLSPAN3 = (int)$col3;
    }

    public function set_no_item_color($value)
    {
        $this->_NO_ITEM_COLOR = $value;
    }

    public function set_no_item_weight($value)
    {
        $this->_NO_ITEM_WEIGHT = $value;
    }

    public function set_flag_get_sortid($value)
    {
        $this->_FLAG_GET_SORTID = (bool)$value;
    }

    public function set_flag_print_top($value)
    {
        $this->_FLAG_PRINT_TOP = (bool)$value;
    }

    public function set_flag_print_navi($value)
    {
        $this->set_flag_print_navi_post($value);
    }

    public function set_flag_print_navi_pre($value)
    {
        $this->_FLAG_PRINT_NAVI_PRE = (bool)$value;
    }

    public function set_flag_print_navi_post($value)
    {
        $this->_FLAG_PRINT_NAVI_POST = (bool)$value;
    }

    public function set_flag_print_sortid($value)
    {
        $this->_FLAG_PRINT_SOTRID = (bool)$value;
    }

    public function set_flag_print_request_uri($value)
    {
        $this->_FLAG_PRINT_REQUEST_URI = (bool)$value;
    }

    public function set_flag_execute_time($val)
    {
        $this->_FLAG_EXECUTE_TIME = (bool)$val;
    }

    public function set_lang_title($value)
    {
        $this->_LANG_TITLE = $value;
    }

    public function set_lang_there_are($value)
    {
        $this->_LANG_THERE_ARE = $value;
    }

    public function set_lang_no_item($value)
    {
        $this->_LANG_NO_RECORD = $value;
    }

    public function set_lang_id_asc($value)
    {
        $this->_LANG_ID_ASC = $value;
    }

    public function set_lang_id_desc($value)
    {
        $this->_LANG_ID_DESC = $value;
    }

    public function set_lang_submit_value($value)
    {
        $this->_LANG_SUBMIT_VALUE = $value;
    }

    public function get_page_current()
    {
        return $this->_pagenavi->get_page_current();
    }

    public function get_page_last()
    {
        return $this->_pagenavi->calc_page_last();
    }

    //---------------------------------------------------------
    // overwrit to form class
    //---------------------------------------------------------
    public function set_operation($value)
    {
        $this->set_op_value($value);
    }

    // --- class end ---
}
