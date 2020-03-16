<?php

namespace XoopsModules\Happylinux;

// $Id: language.php,v 1.6 2007/09/23 05:07:25 ohwada Exp $

// 2007-09-20 K.OHWADA
// PHP5.2: Assigning the return value of new by reference is deprecated

// 2006-12-17 K.OHWADA
// BUG 4417: singleton done not work correctly
// change get_local_instance() get_instance_by_language() etc

// 2006-10-05 K.OHWADA
// add happylinux_language_factory
// move get_google_url() to locate.php

// 2006-09-10 K.OHWADA
// this is new file
// porting from weblinks_language.php

//=========================================================
// Happy Linux Framework Module
// 2006-09-10 K.OHWADA
//=========================================================

//=========================================================
// class LanguageBase
//=========================================================

/**
 * Class LanguageBase
 * @package XoopsModules\Happylinux
 */
class LanguageBase
{
    public $_DEFAULT_LANGAGE = 'english';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    //---------------------------------------------------------
    // get value
    //---------------------------------------------------------
    /**
     * @return string
     */
    public function get_default_language()
    {
        return $this->_DEFAULT_LANGAGE;
    }

    //---------------------------------------------------------
    // convert encoding
    //---------------------------------------------------------
    /**
     * @param $text
     * @return mixed
     */
    public function convert_telafriend_subject($text)
    {
        return $text;
    }

    /**
     * @param $text
     * @return mixed
     */
    public function convert_telafriend_body($text)
    {
        return $text;
    }

    /**
     * @param $text
     * @return mixed
     */
    public function convert_download_filename($text)
    {
        return $text;
    }

    //---------------------------------------------------------
    // system param
    //---------------------------------------------------------
    /**
     * @return mixed
     */
    public function get_xoops_language()
    {
        global $xoopsConfig;

        return $xoopsConfig['language'];
    }

    /**
     * @return string
     */
    public function get_xoops_langcode()
    {
        return _LANGCODE;
    }

    //---------------------------------------------------------
    // country code
    //---------------------------------------------------------
    /**
     * @return string
     */
    public function get_country_code()
    {
        return 'us';    // USA
    }

    // --- class end ---
}
