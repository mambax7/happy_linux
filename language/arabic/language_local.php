<?php
// $Id: language_local.php,v 1.2 2008/02/24 02:14:19 ohwada Exp $

// 2006-10-05 K.OHWADA
// happylinux_language_base

// 2006-09-10 K.OHWADA
// this is new file
// porting form weblinks_language_convert.php

//=========================================================
// Happy Linux Framework Module
// 2006-09-10 K.OHWADA
//=========================================================

//=========================================================
// class language_local
// dummy class for English
//=========================================================

/**
 * Class language_local
 */
class language_local extends LanguageBase
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
        return 'us';    // USA
    }

    // --- class end ---
}
