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
// Translator: Houston (Contour Design Studio http://www.cdesign.ru/)

//=========================================================
// class happy_linux_locate_ru
// Russian Federation (RU)
//=========================================================
class happy_linux_locate_ru extends happy_linux_locate_base
{

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $arr = array(
            'country_code'   => 'ru',
            'country_name'   => 'Российская Федерация',
            'yahoo_url'      => 'http://ru.yahoo.com/',
            'yahoo_map_url'  => 'http://map.yahoo.com/',
            'google_url'     => 'http://www.google.ru/',
            'google_map_url' => 'http://maps.google.ru/',
            'gm_server'      => 'http://maps.google.ru/',
            'gm_location'    => 'Москва, Российская Федерация',
            'gm_latitude'    => '55.755833',
            'gm_longitude'   => '37.617778',
            'gm_zoom'        => '10',
            'ping_servers'   => $this->get_us_ping_servers(),
        );

        $this->array_merge($arr);
    }

    // --- class end ---
}
