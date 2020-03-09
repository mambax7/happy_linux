<?php
// $Id: local.php,v 1.1 2010/11/07 14:59:12 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2006-12-17 K.OHWADA
//=========================================================

//---------------------------------------------------------
// for Iran user
// I dont verficate in Iran mode
// please correct the actual status.
// and post in the support forum
//---------------------------------------------------------

//=========================================================
// class locate_ir
// Iran (ir)
//=========================================================
class locate_ir extends Happy_linux\LocateBase
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $arr = [
            'country_code'   => 'ir',
            'country_name'   => 'Iran',
            'yahoo_url'      => 'https://www.yahoo.com/',
            'yahoo_map_url'  => 'https://map.yahoo.com/',
            'google_url'     => 'https://www.google.com/intl/fa/',
            'google_map_url' => 'https://maps.google.com/',
            'gm_server'      => 'https://maps.google.com/',
            'gm_location'    => 'Tehran Iran',
            'gm_latitude'    => '35.644114',
            'gm_longitude'   => '51.380739',
            'gm_zoom'        => '6',
            'ping_servers'   => $this->get_us_ping_servers(),
        ];

        $this->array_merge($arr);
    }

    // --- class end ---
}
