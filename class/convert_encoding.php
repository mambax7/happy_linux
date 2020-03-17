<?php

// $Id: convert_encoding.php,v 1.1 2010/11/07 14:59:23 ohwada Exp $

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

/**
 * Class happy_linux_convert_encoding
 */
class happy_linux_convert_encoding
{
    public $_MAX_DEPTH = 10;
    public $_REPLACE_CODE = '?';

    public $_flag_replace_contorl_code = false;
    public $_flag_strip_contorl_code = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
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

    //---------------------------------------------------------
    // public function
    //---------------------------------------------------------
    // rss_parse_object.php

    /**
     * @param $arr
     * @param $to
     * @param $from
     * @return array|bool|string
     */
    public function &convert_array(&$arr, $to, $from)
    {
        $to = mb_strtolower($to);
        $form = mb_strtolower($from);

        if ($to == $from) {
            return $arr;
        }

        $ret = $this->_convert_array_recursive(0, $arr, $to, $from);

        return $ret;
    }

    /**
     * @param      $arr
     * @param null $encoding
     * @return array|bool|string
     */
    public function &convert_array_to_utf8(&$arr, $encoding = null)
    {
        $ret = &$this->_convert_array_recursive(0, $arr, 'UTF-8', $encoding);

        return $ret;
    }

    /**
     * @param      $arr
     * @param null $encoding
     * @return array|bool|string
     */
    public function &convert_array_from_utf8(&$arr, $encoding = null)
    {
        $ret = &$this->_convert_array_recursive(0, $arr, $encoding, 'UTF-8');

        return $ret;
    }

    /**
     * @param $str
     * @param $to
     * @param $from
     * @return string
     */
    public function convert($str, $to, $from)
    {
        $to = mb_strtolower($to);
        $form = mb_strtolower($from);

        if ($to == $from) {
            return $str;
        }

        if ('utf-8' == $from) {
            $str = $this->convert_from_utf8($str, $to);
        } elseif ('utf-8' == $to) {
            $str = $this->convert_to_utf8($str, $from);
        } else {
            $str = happy_linux_convert_encoding($str, $to, $from);
        }

        return $str;
    }

    /**
     * @param      $str
     * @param null $encoding
     * @return string
     */
    public function convert_to_utf8($str, $encoding = null)
    {
        return happy_linux_convert_to_utf8($str, $encoding);
    }

    /**
     * @param      $str
     * @param null $encoding
     * @return string
     */
    public function convert_from_utf8($str, $encoding = null)
    {
        return happy_linux_convert_from_utf8($str, $encoding);
    }

    //---------------------------------------------------------
    // private
    //---------------------------------------------------------

    /**
     * @param $num
     * @param $arr_in
     * @param $to
     * @param $from
     * @return array|bool|string
     */
    public function &_convert_array_recursive($num, &$arr_in, $to, $from)
    {
        $num++;
        if ($num > $this->_MAX_DEPTH) {
            $false = false;

            return $false;
        }

        if (is_array($arr_in)) {
            $arr_out = [];
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
