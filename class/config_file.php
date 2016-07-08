<?php
// $Id: config_file.php,v 1.2 2006/10/05 10:40:36 ohwada Exp $

// 2006-10-01 K.OHWADA
// use _LANGCODE

// 2006-07-10 K.OHWADA
// this is new file
// porting from admin_config_file_class.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

class happy_linux_config_file
{
    public $_fp;

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
            $instance = new happy_linux_config_file();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------
    public function _save_config($file)
    {
        $this->_fopen($file);
        $this->_write_open();
        $this->_write_xoops_constant();
        $this->_write_return();
        $this->_write_xoops_variable();
        $this->_write_close();
        $this->_fclose();
    }

    public function _fopen($file)
    {
        $this->_fp = fopen($file, 'w');
    }

    public function _fclose()
    {
        fclose($this->_fp);
    }

    public function _write_open()
    {
        fwrite($this->_fp, "<?php \n");

        $date = date('Y/m/d H:i:s');
        fwrite($this->_fp, "// $date \n\n");
    }

    public function _write_close()
    {
        fwrite($this->_fp, "\n?>");
    }

    public function _write_return()
    {
        fwrite($this->_fp, "\n");
    }

    public function _write_constant($key, $value = '')
    {
        if (empty($value)) {
            $value = constant($key);
        }

        $data = "define('$key', '$value');";
        fwrite($this->_fp, "$data \n");
    }

    public function _write_variable($key, $value)
    {
        $data = "$key = '$value';";
        fwrite($this->_fp, "$data \n");
    }

    public function _write_xoops_constant()
    {
        $this->_write_constant('XOOPS_MAINFILE_INCLUDED', 1);
        $this->_write_constant('XOOPS_ROOT_PATH');
        $this->_write_constant('XOOPS_URL');
        $this->_write_constant('XOOPS_DB_TYPE');
        $this->_write_constant('XOOPS_DB_PREFIX');
        $this->_write_constant('XOOPS_DB_HOST');
        $this->_write_constant('XOOPS_DB_USER');
        $this->_write_constant('XOOPS_DB_PASS');
        $this->_write_constant('XOOPS_DB_NAME');
        $this->_write_constant('XOOPS_DB_PCONNECT');
        $this->_write_constant('XOOPS_GROUP_ADMIN');
        $this->_write_constant('XOOPS_GROUP_USERS');
        $this->_write_constant('XOOPS_GROUP_ANONYMOUS');
        $this->_write_constant('_CHARSET');
        $this->_write_constant('_LANGCODE');
    }

    public function _write_xoops_variable()
    {
        global $xoopsConfig;

        $this->_write_variable('$xoops_sitename', $xoopsConfig['sitename']);
        $this->_write_variable('$xoops_adminmail', $xoopsConfig['adminmail']);
        $this->_write_variable('$xoops_language', $xoopsConfig['language']);
    }

    // --- class end ---
}
