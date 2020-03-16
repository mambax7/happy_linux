<?php
// $Id: local.php,v 1.1 2012/04/08 18:22:28 ohwada Exp $

// 2007-05-12 K.OHWADA
// get_ping_servers()

// 2006-10-05 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-10-01 K.OHWADA
//=========================================================
// _LANGCODE: ru
// _CHARSET : utf-8
// Translator: Houston (Contour Design Studio https://www.cdesign.ru/)

//=========================================================
// class locate_ru
// Russian Federation (RU)
//=========================================================

/**
 * Class locate_ru
 */
class locate_ru extends LocateBase
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $arr = [
            'country_code'   => 'ru',
            'country_name'   => 'Российская Федерация',
            'yahoo_url'      => 'https://ru.yahoo.com/',
            'yahoo_map_url'  => 'https://map.yahoo.com/',
            'google_url'     => 'https://www.google.ru/',
            'google_map_url' => 'https://maps.google.ru/',
            'gm_server'      => 'https://maps.google.ru/',
            'gm_location'    => 'Москва, Российская Федерация',
            'gm_latitude'    => '55.755833',
            'gm_longitude'   => '37.617778',
            'gm_zoom'        => '10',
            'ping_servers'   => $this->get_us_ping_servers(),
        ];

        $this->array_merge($arr);
    }

    // --- class end ---
}
