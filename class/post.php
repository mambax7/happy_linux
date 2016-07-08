<?php
// $Id: post.php,v 1.8 2007/07/18 19:40:49 ohwada Exp $

// 2007-07-14 K.OHWADA
// get_urlencode_keywords()
// is_post_email_format()

// 2006-12-10 K.OHWADA
// add get_text_from_post() etc

// 2006-11-20 K.OHWADA
// for happy_search
// add is_get_set() get_get_urlencode()
// add get_post_get_array_int()

// 2006-09-18 K.OHWADA
// add get_get_keywords()
// add is_post_set()

// 2006-07-10 K.OHWADA
// this is new file
// porting from rssc_post.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_post
//=========================================================
class happy_linux_post extends happy_linux_strings
{
    public $_keyword_array = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_post();
        }

        return $instance;
    }

    //=========================================================
    // Public
    //=========================================================
    //---------------------------------------------------------
    // check $_POST
    //---------------------------------------------------------
    public function is_post_set($key)
    {
        if (isset($_POST[$key])) {
            return true;
        }
        return false;
    }

    public function is_post_fill($key)
    {
        if (isset($_POST[$key]) && ($_POST[$key] !== '')) {
            return true;
        }
        return false;
    }

    public function is_post_url_fill($key)
    {
        if (isset($_POST[$key])) {
            return $this->check_http_fill($_POST[$key]);
        }
        return false;
    }

    public function is_post_url_llegal($key)
    {
        if (isset($_POST[$key]) && ($_POST[$key] !== '')) {
            if ($this->check_http_start($_POST[$key]) && !$this->check_javascript($_POST[$key])) {
                return true;
            }
            return false;
        }
        return true;    // no check
    }

    public function is_post_email_format($key)
    {
        if (isset($_POST[$key]) && ($_POST[$key] !== '')) {
            if ($this->check_email_format($_POST[$key])) {
                return true;
            }
            return false;
        }
        return true;    // no check
    }

    //---------------------------------------------------------
    // check $_GET
    //---------------------------------------------------------
    public function is_get_set($key)
    {
        if (isset($_GET[$key])) {
            return true;
        }
        return false;
    }

    public function is_get_fill($key)
    {
        if (isset($_GET[$key]) && ($_GET[$key] !== '')) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // get $_POST
    //---------------------------------------------------------
    public function get_post($key, $default = '')
    {
        if (isset($_POST[$key])) {
            $val = $_POST[$key];
        } else {
            $val = $default;
        }
        return $val;
    }

    public function get_post_int($key, $default = 0)
    {
        return $this->get_int_from_post($_POST, $key, $default);
    }

    public function get_post_float($key, $default = 0)
    {
        if (isset($_POST[$key])) {
            return (float)$_POST[$key];
        } else {
            $val = (float)$default;
        }
        return $val;
    }

    public function get_post_text($key, $default = '')
    {
        return $this->get_text_from_post($_POST, $key, $default);
    }

    public function get_post_url($key, $default = '', $flag_only = true, $flag_deny = true)
    {
        if (isset($_POST[$key])) {
            $text = $this->strip_slashes_gpc($_POST[$key]);
            $text = $this->strip_control($text);
            if ($flag_only) {
                $text = $this->deny_http_only($text);
            }
            if ($flag_deny) {
                $text = $this->allow_http($text);
            }
        } else {
            $text = $default;
        }
        return $text;
    }

    public function get_post_trim($key, $default = '')
    {
        if (isset($_POST[$key])) {
            $val = trim($_POST[$key]);
        } else {
            $val = trim($default);
        }
        return $val;
    }

    public function get_post_urlencode($key, $default = '')
    {
        if (isset($_POST[$key])) {
            $val = urlencode($_POST[$key]);
        } else {
            $val = $default;
        }
        return $val;
    }

    public function &get_post_array_int($key, $default = null)
    {
        $arr = $default;
        if (isset($_POST[$key]) && is_array($_POST[$key])) {
            $arr = array();
            foreach ($_POST[$key] as $k => $v) {
                $arr[$k] = (int)$v;
            }
        }
        return $arr;
    }

    public function &get_post_array_float($key, $default = null)
    {
        $arr = $default;
        if (isset($_POST[$key]) && is_array($_POST[$key])) {
            $arr = array();
            foreach ($_POST[$key] as $k => $v) {
                $arr[$k] = (float)$v;
            }
        }
        return $arr;
    }

    public function &get_post_array_text($key, $default = null)
    {
        $arr = $default;
        if (isset($_POST[$key]) && is_array($_POST[$key])) {
            $arr = array();
            foreach ($_POST[$key] as $k => $v) {
                $text    = $this->strip_slashes_gpc($v);
                $text    = $this->strip_control($text);
                $arr[$k] = $text;
            }
        }
        return $arr;
    }

    public function get_post_text_split($key, $pattern = "\n")
    {
        $arr = array();
        if (isset($_POST[$key])) {
            $val = $this->strip_slashes_gpc($_POST[$key]);
            $arr = preg_split($pattern, $val);
        }
        return $arr;
    }

    //---------------------------------------------------------
    // get $_GET
    //---------------------------------------------------------
    public function get_get($key, $default = '')
    {
        if (isset($_GET[$key])) {
            $val = $_GET[$key];
        } else {
            $val = $default;
        }
        return $val;
    }

    public function get_get_int($key, $default = 0)
    {
        return $this->get_int_from_post($_GET, $key, $default);
    }

    public function get_get_float($key, $default = 0)
    {
        if (isset($_GET[$key])) {
            $val = (float)$_GET[$key];
        } else {
            $val = (float)$default;
        }
        return $val;
    }

    public function get_get_text($key, $default = '')
    {
        return $this->get_text_from_post($_GET, $key, $default);
    }

    public function get_get_trim($key, $default = '')
    {
        if (isset($_GET[$key])) {
            $val = trim($_GET[$key]);
        } else {
            $val = trim($default);
        }
        return $val;
    }

    public function get_get_urlencode($key, $default = '')
    {
        if (isset($_POST[$key])) {
            $val = urlencode($_POST[$key]);
        } else {
            $val = $default;
        }
        return $val;
    }

    public function get_get_keywords($key = 'keywords', $default = null)
    {
        if (isset($_GET[$key])) {
            $val = trim(urldecode($_GET[$key]));
        } else {
            $val = trim($default);
        }
        return $val;
    }

    public function &get_get_keyword_array($key = 'keywords')
    {
        $arr = null;
        $val = $this->get_get_keywords($key);
        if ($val) {
            $arr = explode(' ', $val);
        }
        $this->_keyword_array =& $arr;
        return $arr;
    }

    public function get_urlencode_keywords()
    {
        return $this->urlencode_from_array($this->_keyword_array);
    }

    //---------------------------------------------------------
    // get $_POST & $_GET
    //---------------------------------------------------------
    public function get_post_get($key, $default = '')
    {
        if (isset($_POST[$key])) {
            $val = $_POST[$key];
        } elseif (isset($_GET[$key])) {
            $val = $_GET[$key];
        } else {
            $val = $default;
        }

        return $val;
    }

    public function get_post_get_int($key, $default = 0)
    {
        if (isset($_POST[$key])) {
            $val = (int)$_POST[$key];
        } elseif (isset($_GET[$key])) {
            $val = (int)$_GET[$key];
        } else {
            $val = (int)$default;
        }

        return $val;
    }

    public function get_post_get_float($key, $default = 0)
    {
        if (isset($_POST[$key])) {
            $val = (float)$_POST[$key];
        } elseif (isset($_GET[$key])) {
            $val = (float)$_GET[$key];
        } else {
            $val = (float)$default;
        }

        return $val;
    }

    public function get_post_get_text($key, $default = '')
    {
        if (isset($_POST[$key])) {
            $text = $this->strip_slashes_gpc($_POST[$key]);
            $text = $this->strip_control($text);
        } elseif (isset($_GET[$key])) {
            $text = $this->strip_slashes_gpc($_GET[$key]);
            $text = $this->strip_control($text);
        } else {
            $text = $default;
        }

        return $text;
    }

    public function get_post_get_trim($key, $default = '')
    {
        if (isset($_POST[$key])) {
            $val = trim($_POST[$key]);
        } elseif (isset($_GET[$key])) {
            $val = trim($_GET[$key]);
        } else {
            $val = trim($default);
        }

        return $val;
    }

    public function &get_post_get_array_int($key, $default = null)
    {
        $arr = $default;
        if (isset($_POST[$key]) && is_array($_POST[$key])) {
            $arr = array();
            foreach ($_POST[$key] as $k => $v) {
                $arr[$k] = (int)$v;
            }
        } elseif (isset($_GET[$key]) && is_array($_GET[$key])) {
            $arr = array();
            foreach ($_GET[$key] as $k => $v) {
                $arr[$k] = (int)$v;
            }
        } else {
            $arr = false;
        }
        return $arr;
    }

    public function get_post_get_passwd_old()
    {
        $flag_passwd = false;
        $flag_code   = false;
        $passwd      = '';

        if (isset($_POST['passwd_old'])) {
            $flag_passwd = true;
            $passwd      = $this->get_post_text('passwd_old');
        } elseif (isset($_GET['code'])) {
            $flag_code = true;
            $passwd    = $this->get_get_text('code');
        }

        return array($passwd, $flag_passwd, $flag_code);
    }

    //--------------------------------------------------------
    // get form POST
    //--------------------------------------------------------
    public function get_int_from_post(&$post, $key, $default = 0)
    {
        $val = $default;
        if (isset($post[$key])) {
            $val = $post[$key];
        }
        return (int)$val;
    }

    public function get_text_from_post(&$post, $key, $default = '')
    {
        $text = $default;
        if (isset($post[$key])) {
            // strip GPC slashes
            $text = $this->strip_slashes_gpc($post[$key]);
            $text = $this->strip_control($text);
        }
        return $text;
    }

    public function &get_int_unique_array_without_from_post(&$post, $key, $without = 0)
    {
        $arr = array();
        if (isset($post[$key])) {
            $arr =& $this->build_unique_array_without($post[$key], $without);
        }
        return $arr;
    }

    // --- class end ---
}
