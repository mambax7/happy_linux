<?php

// $Id: debug.php,v 1.1 2010/11/07 14:59:20 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2006-12-10 K.OHWADA
//=========================================================

/**
 * Class happy_linux_debug
 */
class happy_linux_debug
{
    public $_flag_set = false;

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
    // fucntion
    //---------------------------------------------------------

    /**
     * @param      $arr
     * @param null $title
     * @param bool $flag_normal
     */
    public function print_constant_by_array($arr, $title = null, $flag_normal = false)
    {
        $msg = $this->get_constant_by_array($arr, $flag_normal);

        if ($this->_flag_set) {
            echo implode("<br>\n", $msg);
        } elseif ($title) {
            echo $title . "<br>\n";
        }
    }

    /**
     * @param      $arr
     * @param bool $flag_normal
     * @return array
     */
    public function get_constant_by_array($arr, $flag_normal = false)
    {
        $msg_arr = [];
        foreach ($arr as $name) {
            $msg = $this->get_constant($name, $flag_normal);
            if ($msg) {
                $msg_arr[] = $msg;
            }
        }

        return $msg_arr;
    }

    /**
     * @param      $name
     * @param bool $flag_normal
     * @return string
     */
    public function get_constant($name, $flag_normal = false)
    {
        $val = constant($name);
        $text = $name . ': ' . $val;
        if ($val) {
            $text = '<span style="color: #ff0000">' . $text . '</span>';
            $this->_flag_set = true;
        } elseif (!$flag_normal) {
            $text = '';
        }

        return $text;
    }

    // --- class end ---
}
