<?php
// $Id: local.php,v 1.1 2010/11/07 14:59:12 ohwada Exp $

// 2006-10-05 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-10-01 K.OHWADA
//=========================================================

//=========================================================
// class locate_de
// Germany (DE)
//=========================================================
class locate_de extends Happy_linux\LocateBase
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $arr = [
            'country_code'   => 'de',
            'country_name'   => 'Germany',
            'yahoo_url'      => 'https://de.yahoo.com/',
            'yahoo_map_url'  => 'https://map.yahoo.com/',
            'google_url'     => 'https://www.google.de/',
            'google_map_url' => 'https://maps.google.de/',
            'gm_server'      => 'https://maps.google.de/',
            'gm_location'    => 'Germany',
            'gm_latitude'    => '51.50874245880332',
            'gm_longitude'   => '10.0634765625',
            'gm_zoom'        => '5',
            'ping_servers'   => $this->get_us_ping_servers(),
        ];

        $this->array_merge($arr);
    }

    // --- class end ---
}
