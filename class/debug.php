<?php
// $Id: debug.php,v 1.1 2006/12/22 15:02:34 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2006-12-10 K.OHWADA
//=========================================================
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

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_debug();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // fucntion
    //---------------------------------------------------------
    public function print_constant_by_array($arr, $title = null, $flag_normal = false)
    {
        $msg = $this->get_constant_by_array($arr, $flag_normal);

        if ($this->_flag_set) {
            echo implode("<br />\n", $msg);
        } elseif ($title) {
            echo $title . "<br />\n";
        }
    }

    public function get_constant_by_array($arr, $flag_normal = false)
    {
        $msg_arr = array();
        foreach ($arr as $name) {
            $msg = $this->get_constant($name, $flag_normal);
            if ($msg) {
                $msg_arr[] = $msg;
            }
        }
        return $msg_arr;
    }

    public function get_constant($name, $flag_normal = false)
    {
        $val  = constant($name);
        $text = $name . ': ' . $val;
        if ($val) {
            $text            = '<span style="color: #ff0000">' . $text . '</span>';
            $this->_flag_set = true;
        } elseif (!$flag_normal) {
            $text = '';
        }
        return $text;
    }

    // --- class end ---
}
