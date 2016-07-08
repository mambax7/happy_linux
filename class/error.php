<?php
// $Id: error.php,v 1.11 2007/09/23 05:07:25 ohwada Exp $

// 2007-09-20 K.OHWADA
// bin mode

// 2007-09-01 K.OHWADA
// clear_errors_logs()
// add flag_sanitize to getErrors()

// 2007-08-01 K.OHWADA
// print_error_in_div()

// 2007-07-16 K.OHWADA
// _sanitize()

// 2007-03-01 K.OHWADA
// happy_linux_time()

// 2006-09-20 K.OHWADA
// use happy_linux_strcut
// add get_db_error()

// 2006-07-10 K.OHWADA
// this is new file
// porting from rssc_error.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/class/time.php';

//=========================================================
// class happy_linux_error
//=========================================================
class happy_linux_error
{
    // class
    public $_time_class;

    // log & error
    public $_logs       = array();
    public $_errors     = array();
    public $_error_code = 0;
    public $_error_flag = false;   // no error

    // debug
    public $_flag_debug_print_log   = 0;
    public $_flag_debug_print_error = 0;
    public $_flag_debug_print_time  = 0;

    // for db handler
    public $_flag_debug_print_db_sql = 0;
    public $_debug_db_max_sql        = 1000;

    // color: red;
    public $_SPAN_STYLE_ERROR = 'color: #ff0000;';

    // color: red;  background-color: lightyellow;  border: gray;
    public $_DIV_STYLE_ERROR = 'color: #ff0000; background-color: #ffffe0; border: #808080 1px dotted; padding: 3px 3px 3px 3px;';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // bin mode
        if (defined('HAPPY_LINUX_BIN_MODE') && HAPPY_LINUX_BIN_MODE) {
            // dummy
        } // normal
        else {
            $this->_time_class = happy_linux_time::getInstance();
        }
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_error();
        }
        return $instance;
    }

    //=========================================================
    // Public
    //=========================================================
    public function clear_errors_logs()
    {
        $this->_clear_errors();
        $this->_clear_logs();
    }

    public function &getLogs($format = 'n', $flag_sanitize = true)
    {
        $ret = '';
        if ($format == 'n') {
            return $this->_logs;
        }

        if (count($this->_logs) == 0) {
            return $ret;
        }

        foreach ($this->_logs as $msg) {
            if ($flag_sanitize) {
                $msg = $this->_sanitize($msg);
            }
            $ret .= $msg . "<br />\n";
        }

        return $ret;
    }

    public function &getErrors($format = 'n', $flag_sanitize = true)
    {
        // Only variable references should be returned by reference

        $ret = '';
        if ($format == 'n') {
            return $this->_errors;
        }

        if (count($this->_errors) == 0) {
            return $ret;
        }

        foreach ($this->_errors as $msg) {
            if ($flag_sanitize) {
                $msg = $this->_sanitize($msg);
            }
            $ret .= $msg . "<br />\n";
        }

        return $ret;
    }

    public function getErrorCode()
    {
        return $this->_error_code;
    }

    public function returnExistError()
    {
        if ($this->_error_flag) {
            return false;
        }

        return true;
    }

    public function set_debug_print_log($val)
    {
        $this->_flag_debug_print_log = (int)$val;
    }

    public function set_debug_print_error($val)
    {
        $this->_flag_debug_print_error = (int)$val;
    }

    public function set_debug_print_time($val)
    {
        $this->_flag_debug_print_time = (int)$val;
    }

    public function get_debug_print_time()
    {
        return $this->_flag_debug_print_time;
    }

    //---------------------------------------------------------
    // print error
    //---------------------------------------------------------
    public function print_error_in_div($msg, $flag_sanitize = true)
    {
        echo $this->build_error_in_div($msg, $flag_sanitize);
        echo "<br />\n";
    }

    public function build_error_in_div($msg, $flag_sanitize = true)
    {
        if ($flag_sanitize) {
            $msg = $this->_sanitize($msg);
        }

        $text = '<div style="' . $this->_DIV_STYLE_ERROR . '">';
        $text .= $msg;
        $text .= "</div>\n";
        return $text;
    }

    public function set_span_style_error($val)
    {
        $this->_SPAN_STYLE_ERROR = $val;
    }

    public function set_div_style_error($val)
    {
        $this->_DIV_STYLE_ERROR = $val;
    }

    //---------------------------------------------------------
    // for db handler
    //---------------------------------------------------------
    public function set_debug_db_error($val)
    {
        $this->set_debug_print_error($val);
    }

    public function set_debug_db_sql($val)
    {
        $this->_flag_debug_print_db_sql = (int)$val;
    }

    public function set_debug_db_max_sql($val)
    {
        $this->_debug_db_max_sql = (int)$val;
    }

    //=========================================================
    // Private
    //=========================================================
    public function _clear_errors()
    {
        $this->_errors     = array();
        $this->_error_code = 0;
        $this->_error_flag = false; // no error
    }

    public function _clear_logs()
    {
        $this->_logs = array();
    }

    public function _set_log_func_name($text)
    {
        $this->_set_log('function: ' . $text);
    }

    public function _set_log($text_arr)
    {
        if (is_array($text_arr)) {
            foreach ($text_arr as $text) {
                $this->_logs[] = $text;

                if ($this->_flag_debug_print_log) {
                    $this->_print_line($text);
                }
            }
        } else {
            $this->_logs[] = $text_arr;

            if ($this->_flag_debug_print_log) {
                $this->_print_line($text_arr);
            }
        }
    }

    public function _set_errors($text_arr)
    {
        if (is_array($text_arr)) {
            foreach ($text_arr as $text) {
                $this->_errors[] = $text;

                if ($this->_flag_debug_print_error) {
                    $this->_print_error($text);
                }
            }
        } else {
            $this->_errors[] = $text_arr;

            if ($this->_flag_debug_print_error) {
                $this->_print_error($text_arr);
            }
        }

        $this->_error_flag = true;  // error
    }

    public function _set_error_flag()
    {
        $this->_error_flag = true;  // error
    }

    public function _set_error_code($code)
    {
        $this->_error_code = $code;
    }

    public function _print_line($text)
    {
        echo $this->_sanitize($text);
        echo "<br />\n";
    }

    public function _print_error($msg, $flag_sanitize = true)
    {
        echo $this->_build_error_in_span($msg, $flag_sanitize = true);
        echo "<br />\n";
    }

    public function _build_error_in_span($msg, $flag_sanitize = true)
    {
        if ($flag_sanitize) {
            $msg = $this->_sanitize($msg);
        }

        $text = '<span style="' . $this->_SPAN_STYLE_ERROR . '">';
        $text .= $msg;
        $text .= "</span>\n";
        return $text;
    }

    public function _sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    //---------------------------------------------------------
    // for db handler
    //---------------------------------------------------------
    public function _set_db_error($sql = '', $limit = 0, $offset = 0)
    {
        $err1 = $this->get_db_error();
        $this->_set_errors($err1);

        if ($sql) {
            $sql = $this->_make_db_sql($sql, $limit, $offset);
            $this->_set_errors($sql);
        }
    }

    public function _print_db_sql($sql, $limit = 0, $offset = 0)
    {
        if (!$this->_flag_debug_print_db_sql) {
            return;
        }
        if (empty($sql)) {
            return;
        }

        $sql = $this->_make_db_sql($sql, $limit, $offset);
        $this->_time_class->print_lap_time("sql: $sql");
    }

    public function _make_db_sql($sql, $limit = 0, $offset = 0)
    {
        $sql = $this->_shorten_text($sql, $this->_debug_db_max_sql);
        $sql .= ' LIMIT ' . $offset . ', ' . $limit;
        return $sql;
    }

    // override this function
    public function get_db_error()
    {
        // dummy
    }

    //---------------------------------------------------------
    // multibyte function
    //---------------------------------------------------------
    public function _shorten_text($text, $max = 100)
    {
        if (strlen($text) > $max) {
            $text = happy_linux_strcut($text, 0, $max) . ' ...';
        }
        return $text;
    }

    // --- class end ---
}
