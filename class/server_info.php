<?php
// $Id: server_info.php,v 1.3 2008/01/30 08:33:13 ohwada Exp $

// 2008-01-20 K.OHWADA
// safe_mode

//=========================================================
// Happy Linux Framework Module
// 2007-11-01 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_server_info
//=========================================================
class happy_linux_server_info
{

    // PHP 5.2 + XC 2.1
    public $_MEMORY_WEBLINKS_REQUIRE = 10; // 10 MB

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
            $instance = new happy_linux_server_info();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------
    public function build_header($dirname, $desc = null)
    {
        $text = '<h3>' . $dirname . ' : ' . $this->get_module_name() . "</h3>\n";
        if ($desc) {
            $text .= $desc . "<br /><br />\n";
        }
        return $text;
    }

    public function build_footer()
    {
        $time = happy_linux_time::getInstance();

        $text = "<br /><hr />\n";
        $text .= $time->build_elapse_time() . "<br />\n";
        $text .= happy_linux_build_memory_usage_mb() . "<br />\n";
        return $text;
    }

    public function build_powerdby()
    {
        $year = date('Y');
        $text = '<div align="right"><a href="http://linux2.ohwada.net/" target="_blank"><font size="-1">Powered by Happy Linux</font></a></div>' . "\n";
        $text .= '<div align="right"><font size="-1">&copy; 2004 - ' . $year . ', Kenichi OHWADA</font></div>' . "\n";
        return $text;
    }

    public function build_server_env()
    {
        $text = '<h4>' . _HAPPY_LINUX_AM_SERVER_ENV . "</h4>\n";
        $text .= 'OS: ' . php_uname() . "<br />\n";
        $text .= 'PHP: ' . PHP_VERSION . "<br />\n";
        $text .= 'MySQL: ' . $GLOBALS['xoopsDB']->getServerVersion() . "<br />\n";
        $text .= 'XOOPS: ' . XOOPS_VERSION . "<br />\n";
        $text .= "<br />\n";
        $text .= 'error_reporting: ' . error_reporting() . "<br />\n";
        $text .= 'display_errors: ' . $this->get_intval_ini_get('display_errors') . "<br />\n";
        $text .= 'memory_limit: ' . $this->get_intval_ini_get('memory_limit') . "<br />\n";
        $text .= 'magic_quotes_gpc: ' . (int)get_magic_quotes_gpc() . "<br />\n";
        $text .= 'allow_url_fopen: ' . $this->get_intval_ini_get('allow_url_fopen') . "<br />\n";
        $text .= 'safe_mode: ' . $this->get_intval_ini_get('safe_mode') . "<br />\n";
        $text .= 'open_basedir: ' . ini_get('open_basedir') . "<br />\n";

        if (function_exists('mb_internal_encoding')) {
            $text .= "<br />\n";
            $text .= 'mbstring.language: ' . mb_language() . "<br />\n";
            $text .= 'mbstring.detect_order: ' . implode(' ', mb_detect_order()) . "<br />\n";
            $text .= 'mbstring.http_input: ' . ini_get('mbstring.http_input') . "<br />\n";
            $text .= 'mbstring.http_output: ' . mb_http_output() . "<br />\n";
            $text .= 'mbstring.internal_encoding: ' . mb_internal_encoding() . "<br />\n";
            $text .= 'mbstring.script_encoding: ' . ini_get('mbstring.script_encoding') . "<br />\n";
            $text .= 'mbstring.substitute_character: ' . ini_get('mbstring.substitute_character') . "<br />\n";
            $text .= 'mbstring.func_overload: ' . ini_get('mbstring.func_overload') . "<br />\n";
            $text .= 'mbstring.encoding_translation: ' . $this->get_intval_ini_get('mbstring.encoding_translation') . "<br />\n";
            $text .= 'mbstring.strict_encoding: ' . $this->get_intval_ini_get('mbstring.strict_encoding') . "<br />\n";
        } else {
            $text .= "mbstring: unloaded <br />\n";
        }

        $text .= "<br />\n";
        return $text;
    }

    public function build_check_dir_work()
    {
        $dir_work = $this->get_dir_work();
        $text     = '';
        if ($dir_work && !is_writable($dir_work)) {
            $text .= $this->build_error(_HAPPY_LINUX_AM_DIR_NOT_WRITABLE);
        }
        $text .= 'work directory: ' . $dir_work . "<br /><br />\n";
        return $text;
    }

    public function build_check_memory_limit_default()
    {
        return $this->build_check_memory_limit($this->_MEMORY_WEBLINKS_REQUIRE);
    }

    public function build_check_memory_limit($require)
    {
        $memory_limit = $this->get_intval_ini_get('memory_limit');
        $memory_usage = (float)happy_linux_get_memory_usage_mb();
        $text         = '';
        if ($memory_limit && $memory_usage && ($memory_limit < ($memory_usage + $require))) {
            $text .= $this->build_error(_HAPPY_LINUX_AM_MEMORY_LIMIT_TOO_SMALL);
            $text .= 'memory_limit : ' . $memory_limit . " MB <br />\n";
            $text .= 'memory usage : ' . $memory_usage . " MB <br /><br />\n";
            $text .= sprintf(_HAPPY_LINUX_AM_MEMORY_WEBLINKS_REQUIRE, $require) . "<br />\n";
            $text .= _HAPPY_LINUX_AM_MEMORY_DESC . "<br /><br />\n";
            $text .= "Exsample <br />\n";
            $text .= "Minimum : 6 MB<br />\n";
            $text .= " - PHP 4.3.9 , XOOPS 2.0.16a JP without RSSC module <br />\n";
            $text .= " - More than 2 MB : with RSSC module <br />\n";
            $text .= "Large : 20 MB<br />\n";
            $text .= " - PHP 5.2.3 , XOOPS Cube 2.1.2 with RSSC module <br />\n";
        }
        return $text;
    }

    public function build_error($msg)
    {
        $text = '<h4 style="color: #ff0000">' . $msg . "</h4>\n";
        return $text;
    }

    public function get_module_name($format = 's')
    {
        global $xoopsModule;
        return $xoopsModule->getVar('name', $format);
    }

    public function get_dir_work()
    {
        $dir = happy_linux_dir::getInstance();
        return $dir->init_dir_work();
    }

    public function get_intval_ini_get($key)
    {
        return (int)ini_get($key);
    }

    // --- class end ---
}
