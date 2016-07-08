<?php
// $Id: convert_encoding.php,v 1.6 2007/08/09 18:05:30 ohwada Exp $

// 2007-08-01 K.OHWADA
// multibyte.php

// 2006-11-18 K.OHWADA
// for happy_search
// add convert_array_to_utf8()

// 2006-09-10 K.OHWADA
// small change

// 2006-07-10 K.OHWADA
// this is new file
// porting from rssc_convert.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/include/multibyte.php';

//=========================================================
// class happy_linux_convert
//=========================================================
class happy_linux_convert_encoding
{
    public $_MAX_DEPTH    = 10;
    public $_REPLACE_CODE = '?';

    public $_flag_replace_contorl_code = false;
    public $_flag_strip_contorl_code   = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_convert_encoding();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // public function
    //---------------------------------------------------------
    // rss_parse_object.php
    public function &convert_array(&$arr, $to, $from)
    {
        $to   = strtolower($to);
        $form = strtolower($from);

        if ($to == $from) {
            return $arr;
        }

        $ret = $this->_convert_array_recursive(0, $arr, $to, $from);
        return $ret;
    }

    public function &convert_array_to_utf8(&$arr, $encoding = null)
    {
        $ret =& $this->_convert_array_recursive(0, $arr, 'UTF-8', $encoding);
        return $ret;
    }

    public function &convert_array_from_utf8(&$arr, $encoding = null)
    {
        $ret =& $this->_convert_array_recursive(0, $arr, $encoding, 'UTF-8');
        return $ret;
    }

    public function convert($str, $to, $from)
    {
        $to   = strtolower($to);
        $form = strtolower($from);

        if ($to == $from) {
            return $str;
        }

        if ($from == 'utf-8') {
            $str = $this->convert_from_utf8($str, $to);
        } elseif ($to == 'utf-8') {
            $str = $this->convert_to_utf8($str, $from);
        } else {
            $str = happy_linux_convert_encoding($str, $to, $from);
        }

        return $str;
    }

    public function convert_to_utf8($str, $encoding = null)
    {
        return happy_linux_convert_to_utf8($str, $encoding);
    }

    public function convert_from_utf8($str, $encoding = null)
    {
        return happy_linux_convert_from_utf8($str, $encoding);
    }

    //---------------------------------------------------------
    // private
    //---------------------------------------------------------
    public function &_convert_array_recursive($num, &$arr_in, $to, $from)
    {
        ++$num;
        if ($num > $this->_MAX_DEPTH) {
            $false = false;
            return $false;
        }

        if (is_array($arr_in)) {
            $arr_out = array();
            reset($arr_in);

            foreach ($arr_in as $k => $v) {
                if (is_array($v)) {
                    $arr_out[$k] = $this->_convert_array_recursive($num, $v, $to, $from);
                } else {
                    $arr_out[$k] = $this->convert($v, $to, $from);
                }
            }

            return $arr_out;
        }

        $ret = $this->convert($arr_in, $to, $from);
        return $ret;
    }

    // --- class end ---
}
