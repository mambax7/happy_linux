<?php

namespace XoopsModules\Happylinux;

// $Id: locate.php,v 1.8 2007/11/15 11:08:43 ohwada Exp $

// 2007-11-11 K.OHWADA
// set_config_country_conty_code()
// get_happylinux_url()
// add format in get_var()

// 2007-09-20 K.OHWADA
// PHP5.2: Assigning the return value of new by reference is deprecated:

// 2007-07-28 K.OHWADA
// get_us_ping_servers()

// 2006-12-17 K.OHWADA
// BUG 4417: singleton done not work correctly
// add get_local_instance() get_language_country_code() etc

// 2006-10-05 K.OHWADA
// this is new file
// porting form weblinks_language_base.php

//=========================================================
// Happy Linux Framework Module
// 2006-10-01 K.OHWADA
//=========================================================

//=========================================================
// class LocateBase
//=========================================================

/**
 * Class LocateBase
 * @package XoopsModules\Happylinux
 */
class LocateBase
{
    public $_DEFAULT_COUNTRY_CODE = 'us';  // USA

    public $_vars = [];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_vars = [
            'happylinux_url' => 'https://linux2.ohwada.net/',
        ];
    }

    //---------------------------------------------------------
    // function
    //---------------------------------------------------------
    /**
     * @param      $query
     * @param null $google_url
     * @param null $lang
     * @return string
     */
    public function build_google_search_url($query, $google_url = null, $lang = null)
    {
        if (empty($google_url)) {
            $google_url = $this->get_var('google_url');
        }

        if (empty($lang)) {
            $lang = _LANGCODE;
        }

        $query = happylinux_convert_to_utf8($query);
        $query = urlencode($query);
        $url   = $google_url . 'search?hl=' . $lang . '&q=' . $query;

        return $url;
    }

    //---------------------------------------------------------
    // get value
    //---------------------------------------------------------
    /**
     * @return string
     */
    public function get_default_contry_code()
    {
        return $this->_DEFAULT_COUNTRY_CODE;
    }

    /**
     * @return array|string[]
     */
    public function &get_vars()
    {
        return $this->_vars;
    }

    /**
     * @param      $name
     * @param null $format
     * @return bool|mixed|string
     */
    public function get_var($name, $format = null)
    {
        $val = false;
        if (isset($this->_vars[$name])) {
            $val = $this->_vars[$name];
            if ($format) {
                $val = htmlspecialchars($val, ENT_QUOTES);
            }
        }

        return $val;
    }

    /**
     * @param $arr
     */
    public function array_merge($arr)
    {
        // overwrite previus value
        $this->_vars = array_merge($this->_vars, $arr);
    }

    //---------------------------------------------------------
    // default value
    //---------------------------------------------------------
    /**
     * @return string
     */
    public function get_us_ping_servers()
    {
        $list = "https://rpc.weblogs.com/RPC2\n";

        return $list;
    }

    // --- class end ---
}
