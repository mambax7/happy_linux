<?php
// $Id: language_local.php,v 1.4 2007/11/15 12:16:51 ohwada Exp $

// 2007-11-11 K.OHWADA
// happy_linux_server -> happy_linux_browser
// EUC-JP -> UTF-8

// 2006-10-05 K.OHWADA
// happy_linux_language_base
// move get_google_url() to locate.php

// 2006-09-10 K.OHWADA
// this is new file
// porting form weblinks_language_convert.php

//=========================================================
// Happy Linux Framework Module
// 2006-09-10 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_language_local
// for japanese UTF-8
//=========================================================
class happy_linux_language_local extends happy_linux_language_base
{

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // convert encoding
    //---------------------------------------------------------
    public function convert_telafriend_subject($text)
    {
        return $this->_convert_sjis_win_mac($text);
    }

    public function convert_telafriend_body($text)
    {
        return $this->_convert_sjis_win_mac($text);
    }

    public function convert_download_filename($text)
    {
        return $this->_convert_sjis_win_mac($text);
    }

    public function _convert_sjis_win_mac($str)
    {
        $browser = happy_linux_browser::getInstance();

        $browser->presume_agent();
        $os = $browser->get_os();
        if (($os == 'win') || ($os == 'mac')) {
            $str = $this->_convert_utf8_to_sjis($str);
        }
        return $str;
    }

    public function _convert_utf8_to_sjis($str)
    {
        if (function_exists('mb_convert_encoding')) {
            $str = mb_convert_encoding($str, 'SJIS', 'UTF-8');
        }
        return $str;
    }

    //---------------------------------------------------------
    // country code
    //---------------------------------------------------------
    public function get_country_code()
    {
        return 'jp';    // Japan
    }

    // --- class end ---
}
