<?php

namespace XoopsModules\Happylinux;

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
// Time()

// 2006-09-20 K.OHWADA
// use happylinux_strcut
// add get_db_error()

// 2006-07-10 K.OHWADA
// this is new file
// porting from rssc_error.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//  require_once XOOPS_ROOT_PATH . '/modules/happylinux/class/time.php';

//=========================================================
// class error
//=========================================================

/**
 * Class Error
 * @package XoopsModules\Happylinux
 */
class Error
{
    // class
    public $_time_class;

    // log & error
    public $_logs       = [];
    public $_errors     = [];
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
        if (defined('HAPPYLINUX_BIN_MODE') && HAPPYLINUX_BIN_MODE) {
            // dummy
        } // normal
        else {
            $this->_time_class = Time::getInstance();
        }
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
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

    /**
     * @param string $format
     * @param bool   $flag_sanitize
     * @return array|string
     */
    public function &getLogs($format = 'n', $flag_sanitize = true)
    {
        $ret = '';
        if ('n' == $format) {
            return $this->_logs;
        }

        if (0 == count($this->_logs)) {
            return $ret;
        }

        foreach ($this->_logs as $msg) {
            if ($flag_sanitize) {
                $msg = $this->_sanitize($msg);
            }
            $ret .= $msg . "<br>\n";
        }

        return $ret;
    }

    /**
     * @param string $format
     * @param bool   $flag_sanitize
     * @return array|string
     */
    public function &getErrors($format = 'n', $flag_sanitize = true)
    {
        // Only variable references should be returned by reference

        $ret = '';
        if ('n' == $format) {
            return $this->_errors;
        }

        if (0 == count($this->_errors)) {
            return $ret;
        }

        foreach ($this->_errors as $msg) {
            if ($flag_sanitize) {
                $msg = $this->_sanitize($msg);
            }
            $ret .= $msg . "<br>\n";
        }

        return $ret;
    }

    /**
     * @return int
     */
    public function getErrorCode()
    {
        return $this->_error_code;
    }

    /**
     * @return bool
     */
    public function returnExistError()
    {
        if ($this->_error_flag) {
            return false;
        }

        return true;
    }

    /**
     * @param $val
     */
    public function set_debug_print_log($val)
    {
        $this->_flag_debug_print_log = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_debug_print_error($val)
    {
        $this->_flag_debug_print_error = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_debug_print_time($val)
    {
        $this->_flag_debug_print_time = (int)$val;
    }

    /**
     * @return int
     */
    public function get_debug_print_time()
    {
        return $this->_flag_debug_print_time;
    }

    //---------------------------------------------------------
    // print error
    //---------------------------------------------------------
    /**
     * @param      $msg
     * @param bool $flag_sanitize
     */
    public function print_error_in_div($msg, $flag_sanitize = true)
    {
        echo $this->build_error_in_div($msg, $flag_sanitize);
        echo "<br>\n";
    }

    /**
     * @param      $msg
     * @param bool $flag_sanitize
     * @return string
     */
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

    /**
     * @param $val
     */
    public function set_span_style_error($val)
    {
        $this->_SPAN_STYLE_ERROR = $val;
    }

    /**
     * @param $val
     */
    public function set_div_style_error($val)
    {
        $this->_DIV_STYLE_ERROR = $val;
    }

    //---------------------------------------------------------
    // for db handler
    //---------------------------------------------------------
    /**
     * @param $val
     */
    public function set_debug_db_error($val)
    {
        $this->set_debug_print_error($val);
    }

    /**
     * @param $val
     */
    public function set_debug_db_sql($val)
    {
        $this->_flag_debug_print_db_sql = (int)$val;
    }

    /**
     * @param $val
     */
    public function set_debug_db_max_sql($val)
    {
        $this->_debug_db_max_sql = (int)$val;
    }

    //=========================================================
    // Private
    //=========================================================
    public function _clear_errors()
    {
        $this->_errors     = [];
        $this->_error_code = 0;
        $this->_error_flag = false; // no error
    }

    public function _clear_logs()
    {
        $this->_logs = [];
    }

    /**
     * @param $text
     */
    public function _set_log_func_name($text)
    {
        $this->_set_log('function: ' . $text);
    }

    /**
     * @param $text_arr
     */
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

    /**
     * @param $text_arr
     */
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

    /**
     * @param $code
     */
    public function _set_error_code($code)
    {
        $this->_error_code = $code;
    }

    /**
     * @param $text
     */
    public function _print_line($text)
    {
        echo $this->_sanitize($text);
        echo "<br>\n";
    }

    /**
     * @param      $msg
     * @param bool $flag_sanitize
     */
    public function _print_error($msg, $flag_sanitize = true)
    {
        echo $this->_build_error_in_span($msg, $flag_sanitize = true);
        echo "<br>\n";
    }

    /**
     * @param      $msg
     * @param bool $flag_sanitize
     * @return string
     */
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

    /**
     * @param $str
     * @return string
     */
    public function _sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    //---------------------------------------------------------
    // for db handler
    //---------------------------------------------------------
    /**
     * @param string $sql
     * @param int    $limit
     * @param int    $offset
     */
    public function _set_db_error($sql = '', $limit = 0, $offset = 0)
    {
        $err1 = $this->get_db_error();
        $this->_set_errors($err1);

        if ($sql) {
            $sql = $this->_make_db_sql($sql, $limit, $offset);
            $this->_set_errors($sql);
        }
    }

    /**
     * @param     $sql
     * @param int $limit
     * @param int $offset
     */
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

    /**
     * @param     $sql
     * @param int $limit
     * @param int $offset
     * @return string
     */
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
    /**
     * @param     $text
     * @param int $max
     * @return string
     */
    public function _shorten_text($text, $max = 100)
    {
        if (mb_strlen($text) > $max) {
            $text = happylinux_strcut($text, 0, $max) . ' ...';
        }

        return $text;
    }

    // --- class end ---
}
