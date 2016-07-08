<?php
// $Id: bin_base.php,v 1.2 2011/12/30 00:50:30 ohwada Exp $

// 2011-12-29 K.OHWADA
// $this->_offset in _set_env_param_web()

// 2007-10-10 K.OHWADA
// _set_system_param()
// _print_data()

// 2007-09-20 K.OHWADA
// PHP 5.2: Non-static method happy_linux_bin_file::getInstance() should not be called statically
// PHP 5.2: set timezone

// 2007-08-01 K.OHWADA
// HAPPY_LINUX_MB_LANGUAGE

// 2007-06-10 K.OHWADA
// divid to bin_file
// _print_write_html_header()
// check_pass() set_cmd_option()

// 2007-05-12 K.OHWADA
// change _file_open() _file_close()

// 2006-09-18 K.OHWADA
// add $_argv_1 etc

// 2006-07-10 K.OHWADA
// this is new file
// porting from bin_base_class.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================
// php xxx.php  pass
// php xxx.php -pass=pass [ -limit=0 -offset=0 -abc ]
//---------------------------------------------------------

class happy_linux_bin_base
{
    public $_DIRNAME;
    public $_bin_file;

    // constant
    public $_X_MAILER = 'XOOPS';

    // test parameter
    public $_mode       = '';
    public $_flag_print = false;
    public $_flag_write = true;
    public $_flag_chmod = false;

    // command option
    public $_pass   = null;
    public $_limit  = 10;
    public $_offset = 0;

    public $_FLAG_PRINT_WEB = true;
    public $_FLAG_WRITE_WEB = true;
    public $_FLAG_CHMOD_WEB = true;
    public $_LIMIT_WEB      = 10;

    public $_FLAG_PRINT_COMMAND = false;
    public $_FLAG_WRITE_COMMAND = true;
    public $_FLAG_CHMOD_COMMAND = false;
    public $_LIMIT_COMMAND      = 0;   // unlimited

    // xoops parameter
    public $_CHARSET;
    public $_sitename;
    public $_adminmail;

    // command parameter
    public $_opt_arr = null;

    // result file
    public $_SUB_DIR    = 'cache';
    public $_GOTO_ADMIN = 'goto admin index';

    public $_filename = null;
    public $_file_admin_index;

    public $_mail_to    = null;
    public $_mail_title = null;
    public $_mail_level = 0;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct($dirname)
    {
        $this->_DIRNAME          = $dirname;
        $this->_file_admin_index = 'modules/' . $this->_DIRNAME . '/admin/index.php';

        // MUST set before happy_linux_bin_file
        if (!defined('HAPPY_LINUX_BIN_MODE')) {
            define('HAPPY_LINUX_BIN_MODE', '1');
        }

        // Non-static method happy_linux_bin_file::getInstance() should not be called statically
        $this->_bin_file = new happy_linux_bin_file();

        // system parameter
        $this->_set_system_param();
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------
    public function _set_system_param()
    {
        global $xoopsConfig;
        global $xoops_sitename, $xoops_adminmail;

        $sitename  = null;
        $adminmail = null;

        if (isset($xoopsConfig['sitename'])) {
            $sitename = $xoopsConfig['sitename'];
        } elseif (isset($xoops_sitename)) {
            $sitename = $xoops_sitename;
        }

        if (isset($xoopsConfig['adminmail'])) {
            $adminmail = $xoopsConfig['adminmail'];
        } elseif (isset($xoops_adminmail)) {
            $adminmail = $xoops_adminmail;
        }

        if ($sitename) {
            $this->set_sitename($sitename);
        }
        if ($adminmail) {
            $this->set_adminmail($adminmail);
        }
        if (defined('_CHARSET')) {
            $this->set_charset(_CHARSET);
        }

        // multibyte
        if (defined('HAPPY_LINUX_MB_LANGUAGE')) {
            happy_linux_mb_language(HAPPY_LINUX_MB_LANGUAGE);
        }
        if (defined('HAPPY_LINUX_MB_ENCODING')) {
            happy_linux_internal_encoding(HAPPY_LINUX_MB_ENCODING);
        }

        // PHP 5.2: set timezone
        if (function_exists('date_default_timezone_set')
            && function_exists('date_default_timezone_get')
        ) {
            $tz = @date_default_timezone_get();
            date_default_timezone_set($tz);
        }
    }

    public function set_sitename($val)
    {
        $this->_sitename = $val;
    }

    public function set_adminmail($val)
    {
        $this->_adminmail = $val;
    }

    public function set_charset($val)
    {
        $this->_CHARSET = $val;
    }

    //=========================================================
    // private
    //=========================================================
    //---------------------------------------------------------
    // env_param
    //---------------------------------------------------------
    public function set_env_param()
    {
        // web
        // in whtasnew, set REQUEST_METHOD, because suppress notice
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']) {
            $this->_set_env_param_web();
        } // command line
        else {
            $this->_set_env_param_cmd();
        }

        $this->_set_flag_write_to_bin_file($this->_flag_write);
    }

    public function check_pass($pass)
    {
        if ($pass && ($pass == $this->_pass)) {
            return true;
        }
        return false;
    }

    public function _set_env_param_web()
    {
        $this->_mode       = 'web';
        $this->_flag_print = $this->_FLAG_PRINT_WEB;
        $this->_flag_write = $this->_FLAG_WRITE_WEB;
        $this->_flag_chmod = $this->_FLAG_CHMOD_WEB;
        $this->_limit      = $this->_LIMIT_WEB;

        $this->_opt_arr =& $_GET;

        if ($this->isset_opt('pass')) {
            $this->_pass = $this->get_opt('pass');
        }

        if ($this->isset_opt('limit')) {
            $this->_limit = $this->get_opt('limit');
        }

        if ($this->isset_opt('offset')) {
            $this->_offset = $this->get_opt('offset');
        }
    }

    public function _set_env_param_cmd()
    {
        $this->_mode       = 'command';
        $this->_flag_print = $this->_FLAG_PRINT_COMMAND;
        $this->_flag_write = $this->_FLAG_WRITE_COMMAND;
        $this->_flag_chmod = $this->_FLAG_CHMOD_COMMAND;
        $this->_limit      = $this->_LIMIT_COMMAND;

        $this->_set_cmd_option();

        if ($this->isset_opt('pass')) {
            $this->_pass = $this->get_opt('pass');
        } elseif (isset($_SERVER['argv'][1])) {
            $this->_pass = $_SERVER['argv'][1];
        }

        if ($this->isset_opt('limit')) {
            $this->_limit = $this->get_opt('limit');
        }

        if ($this->isset_opt('offset')) {
            $this->_offset = $this->get_opt('offset');
        }
    }

    public function _set_cmd_option()
    {
        $arr = array();

        if ($_SERVER['argc'] > 1) {
            for ($i = 1; $i < $_SERVER['argc']; ++$i) {
                if (preg_match('/\-(.*)=(.*)/', $_SERVER['argv'][$i], $matches)) {
                    $arr[$matches[1]] = $matches[2];
                } elseif (preg_match('/\-(.*)/', $_SERVER['argv'][$i], $matches)) {
                    $arr[$matches[1]] = true;
                }
            }
        }

        $this->_opt_arr =& $arr;
        return $arr;
    }

    public function isset_opt($key)
    {
        if (isset($this->_opt_arr[$key])) {
            return true;
        }
        return false;
    }

    public function get_opt($key)
    {
        if (isset($this->_opt_arr[$key])) {
            return $this->_opt_arr[$key];
        }
        return false;
    }

    //---------------------------------------------------------
    // html header & footer
    //---------------------------------------------------------
    public function _print_write_html_header()
    {
        $this->_print_write_data($this->_print_write_html_header());
    }

    public function _print_write_html_footer()
    {
        $this->_print_write_data($this->_print_write_html_footer());
    }

    public function _get_html_header()
    {
        $text = <<<END_OF_TEXT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=$this->_CHARSET">
<title> $this->_TITLE </title>
</head><body>
<h3> $this->_TITLE </h3>
<hr />
END_OF_TEXT;

        return $text;
    }

    public function _get_html_footer()
    {
        $url_admin = XOOPS_URL . '/' . $this->_file_admin_index;

        $text = <<<END_OF_TEXT
<br />
<hr />
<a href="$url_admin">$this->_GOTO_ADMIN</a><br />
</head></html>
END_OF_TEXT;

        return $text;
    }

    public function _print_write_data($data)
    {
        $this->_print_data($data);
        $this->_write_data($data);
    }

    public function _print_data($data)
    {
        if ($this->_flag_print) {
            echo $data;
        }
    }

    //---------------------------------------------------------
    // mail
    //---------------------------------------------------------
    public function _send_mail_content_by_level($content, $level)
    {
        if ($this->_mail_level >= $level) {
            return $this->_send_mail_content($content);
        }
        return true;    // no action
    }

    public function _send_mail_content($content)
    {
        return $this->_send_mail($this->_mail_to, $this->_mail_title, $content);
    }

    public function _send_mail($mailto, $title, $content)
    {
        $mailto  = $this->_adminmail;
        $subject = '[' . $this->_sitename . '] ' . $title;
        $body    = $this->_build_mail_body($title, $content);
        $header  = 'From: ' . $this->_adminmail . " \n";
        $header .= 'X-Mailer: ' . $this->_X_MAILER . " \n";

        $ret = happy_linux_send_mail($mailto, $subject, $body, $header);
        return $ret;
    }

    public function _build_mail_body($title, $body)
    {
        $siteurl = XOOPS_URL . '/';

        $msg = '';
        if ($this->_flag_write && $this->_filename) {
            $msg = "You can view detail here:\n";
            $msg .= XOOPS_URL . '/' . $this->_filename . "\n";
        }

        $text = <<<END_OF_TEXT
$title

$body

$msg
-----------
$this->_sitename ( $siteurl )
webmaster
$this->_adminmail
-----------
END_OF_TEXT;

        return $text;
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------
    public function set_mailer($val)
    {
        $this->_X_MAILER = $val;
    }

    public function set_mail_to($val)
    {
        $this->_mail_to = $val;
    }

    public function set_mail_title($val)
    {
        $this->_mail_title = $val;
    }

    public function set_mail_level($val)
    {
        $this->_mail_level = (int)$val;
    }

    // not include XOOPS_URL
    public function set_filename($file)
    {
        $this->_filename = $file;
    }

    public function get_filename()
    {
        return $this->_filename;
    }

    //---------------------------------------------------------
    // bin file class
    //---------------------------------------------------------
    public function _open_file($filename, $mode = 'w')
    {
        return $this->_bin_file->open_bin($filename, $mode);
    }

    public function _close_file()
    {
        $this->_bin_file->close_bin($this->_flag_chmod);
    }

    public function _write_file($data)
    {
        $this->_bin_file->write_bin($data);
    }

    public function _set_flag_write_to_bin_file($val)
    {
        $this->_bin_file->set_flag_write($val);
    }

    // --- class end ---
}
