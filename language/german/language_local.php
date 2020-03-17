<?php

// $Id: language_local.php,v 1.1 2010/11/07 14:59:14 ohwada Exp $

// 2006-10-05 K.OHWADA
// happy_linux_language_base

// 2006-09-10 K.OHWADA
// this is new file
// porting form weblinks_language_convert.php

//=========================================================
// Happy Linux Framework Module
// 2006-09-10 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_language_local
// for German
//=========================================================

/**
 * Class happy_linux_language_local
 */
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
    // country code
    //---------------------------------------------------------

    /**
     * @return string
     */
    public function get_country_code()
    {
        return 'de';    // Deutschland
    }

    // --- class end ---
}
