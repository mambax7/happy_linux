<?php

// $Id: remote_file.php,v 1.1 2010/11/07 14:59:17 ohwada Exp $

// 2008-02-03 K.OHWADA
// set_snoopy_timeout_connect()

// 2006-11-19 K.OHWADA
// change $_mode to $_remote_mode

// 2006-11-08 K.OHWADA
// proxy server
// add set_proxy()
// change $_flag_allow_url_fopen to $_mode

// 2006-09-01 K.OHWADA
// change constant value

// 2006-07-10 K.OHWADA
// this is new file
// porting from rssc_remote_file.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//---------------------------------------------------------
// define constant
//---------------------------------------------------------
define('HAPPY_LINUX_REMOTE_CODE_EMPTY_URL', 11);
define('HAPPY_LINUX_REMOTE_CODE_NOT_FOPEN', 12);
define('HAPPY_LINUX_REMOTE_CODE_NOT_FCLOSE', 13);
define('HAPPY_LINUX_REMOTE_CODE_NOT_FWRITE', 14);
define('HAPPY_LINUX_REMOTE_CODE_NO_RESULT', 15);

define('HAPPY_LINUX_SNOPPY_CODE_NOT_FETCH', 17);
define('HAPPY_LINUX_SNOPPY_CODE_NO_RESULT', 18);

//=========================================================
// class happy_linux_remote_file
// use class snoopy
//=========================================================

/**
 * Class happy_linux_remote_file
 */
class happy_linux_remote_file extends happy_linux_error
{
    // class instance
    public $_snoopy;

    // file pointer
    public $_fp;

    // variable
    public $_remote_mode = 0;    // use fopen
    public $_error_code = 0;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        // class instance
        $this->_snoopy = new Snoopy();

        // allow_url_fopen
        if (ini_get('allow_url_fopen')) {
            $this->set_remote_mode(0);    // use fopen
        } else {
            $this->set_remote_mode(1);    // use snoopy
        }
    }

    /**
     * @return \happy_linux_remote_file|static
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
    // init
    //---------------------------------------------------------

    /**
     * @param $mode
     */
    public function set_remote_mode($mode)
    {
        $this->_remote_mode = (int)$mode;
    }

    //---------------------------------------------------------
    // check_url
    //---------------------------------------------------------

    /**
     * @param $url
     * @return bool|string
     */
    public function check_url($url)
    {
        $this->_clear_errors();

        if (empty($url)) {
            $this->_set_error_code(HAPPY_LINUX_REMOTE_CODE_EMPTY_URL);
            $this->_set_errors('happy_linux_remote_file: remote url is empty');

            return false;
        }

        switch ($this->_remote_mode) {
            case 1:
                $ret = $this->_check_url_remote($url);
                break;
            case 0:
            default:
                $ret = $this->_check_url_local($url);
                break;
        }

        return $ret;
    }

    /**
     * @param $url
     * @return bool
     */
    public function _check_url_local($url)
    {
        if ($this->_fopen($url, 'r')) {
            $this->_fclose();

            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // read_file
    //---------------------------------------------------------

    /**
     * @param $url
     * @return bool|string
     */
    public function read_file($url)
    {
        $this->_clear_errors();

        if (empty($url)) {
            $this->_set_error_code(HAPPY_LINUX_REMOTE_CODE_EMPTY_URL);
            $this->_set_errors('happy_linux_remote_file: remote url is empty');

            return false;
        }

        switch ($this->_remote_mode) {
            case 1:
                $ret = $this->_read_file_remote($url);
                break;
            case 0:
            default:
                $ret = $this->read_file_local($url);
                break;
        }

        return $ret;
    }

    //---------------------------------------------------------
    // read & write file
    //---------------------------------------------------------

    /**
     * @param $url
     * @return bool|string
     */
    public function read_file_local($url)
    {
        $fp = $this->_fopen($url, 'r');
        if (!$fp) {
            return false;
        }

        $content = $this->_fread();
        $this->_fclose();

        if (!$content) {
            $this->_set_error_code(HAPPY_LINUX_REMOTE_CODE_NO_RESULT);
            $this->_set_errors('happy_linux_remote_file: remote data is empty:');
            $this->_set_errors("url = $url");

            return false;
        }

        return $content;
    }

    /**
     * @param $filename
     * @param $data
     * @return bool
     */
    public function write_file_local($filename, $data)
    {
        $fp = $this->_fopen($filename, 'w');
        if (!$fp) {
            return false;
        }

        $ret = $this->_fwrite($data);
        $this->_fclose();

        return $ret;
    }

    //=========================================================
    // private function
    //=========================================================
    //---------------------------------------------------------
    // file handler
    //---------------------------------------------------------

    /**
     * @param $url
     * @param $mode
     * @return bool|false|resource
     */
    public function _fopen($url, $mode)
    {
        $this->_fp = false;
        $fp = fopen($url, $mode);

        if (!$fp) {
            $this->_set_error_code(HAPPY_LINUX_REMOTE_CODE_NOT_FOPEN);
            $this->_set_errors('happy_linux_remote_file: cannot open url:');
            $this->_set_errors("url = $url");

            return false;
        }

        $this->_fp = $fp;

        return $fp;
    }

    /**
     * @return bool
     */
    public function _fclose()
    {
        if (!$this->_fp) {
            return false;
        }

        if (fclose($this->_fp)) {
            return true;
        }
        $this->_set_error_code(HAPPY_LINUX_REMOTE_CODE_NOT_FCLOSE);
        $this->_set_errors('happy_linux_remote_file: cannot close url');

        return false;
    }

    /**
     * @return bool|string
     */
    public function _fread()
    {
        if (!$this->_fp) {
            return false;
        }

        $content = '';

        do {
            $data = fread($this->_fp, 8192);
            if (0 == mb_strlen($data)) {
                break;
            }
            $content .= $data;
        } while (true);

        return $content;
    }

    /**
     * @param $data
     * @return bool
     */
    public function _fwrite($data)
    {
        if (fwrite($this->_fp, $data)) {
            return true;
        }
        $this->_set_error_code(HAPPY_LINUX_REMOTE_CODE_NOT_FWRITE);
        $this->_set_errors('happy_linux_remote_file: cannot write to remote file');

        return false;
    }

    //=========================================================
    // use class spoopy
    //=========================================================

    /**
     * @param        $host
     * @param string $port
     * @param string $user
     * @param string $pass
     */
    public function set_snoopy_proxy($host, $port = '8080', $user = '', $pass = '')
    {
        $this->set_remote_mode(1);    // use snoopy

        $this->_snoopy->proxy_host = $host;
        $this->_snoopy->proxy_port = $port;

        if ($user) {
            $this->_snoopy->proxy_user = $user;
        }
        if ($pass) {
            $this->_snoopy->proxy_pass = $pass;
        }
    }

    /**
     * @param $time
     */
    public function set_snoopy_timeout_connect($time)
    {
        if ((int)$time > 0) {
            $this->_snoopy->_fp_timeout = (float)$time;
        }
    }

    /**
     * @param $time
     */
    public function set_snoopy_timeout_read($time)
    {
        if ((int)$time > 0) {
            $this->_snoopy->read_timeout = (float)$time;
        }
    }

    /**
     * @param $url
     * @return bool|string
     */
    public function _check_url_remote($url)
    {
        $ret = $this->_snoppy_fetch($url);

        return $ret;
    }

    /**
     * @param $url
     * @return bool|string
     */
    public function _read_file_remote($url)
    {
        $ret = $this->_snoppy_fetch($url);

        return $ret;
    }

    /**
     * @param $url
     * @return bool|string
     */
    public function _snoppy_fetch($url)
    {
        if ($this->_snoopy->fetch($url)) {
            $res = $this->_snoopy->results;

            if ($res) {
                return $res;
            }
            $this->_set_error_code(HAPPY_LINUX_SNOPPY_CODE_NO_RESULT);
            $this->_set_errors('happy_linux_remote_file: remote data is empty:');
            $this->_set_errors("url = $url");
            if ($this->_snoopy->error) {
                $this->_set_errors('snoopy: ' . $this->_snoopy->error);
            }

            return false;
        }
        $this->_set_error_code(HAPPY_LINUX_SNOPPY_CODE_NOT_FETCH);
        $this->_set_errors('happy_linux_remote_file: cannot fetch remote data:');
        $this->_set_errors("url = $url");
        if ($this->_snoopy->error) {
            $this->_set_errors('snoopy: ' . $this->_snoopy->error);
        }

        return false;
    }

    //----- class end -----
}
