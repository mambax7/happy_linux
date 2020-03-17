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
// class happy_linux_locate_ir
// Iran (ir)
//=========================================================

/**
 * Class happy_linux_locate_ir
 */
class happy_linux_locate_ir extends happy_linux_locate_base
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $arr = [
            'country_code' => 'ir',
            'country_name' => 'Iran',
            'yahoo_url' => 'http://www.yahoo.com/',
            'yahoo_map_url' => 'http://map.yahoo.com/',
            'google_url' => 'http://www.google.com/intl/fa/',
            'google_map_url' => 'http://maps.google.com/',
            'gm_server' => 'http://maps.google.com/',
            'gm_location' => 'Tehran Iran',
            'gm_latitude' => '35.644114',
            'gm_longitude' => '51.380739',
            'gm_zoom' => '6',
            'ping_servers' => $this->get_us_ping_servers(),
        ];

        $this->array_merge($arr);
    }

    // --- class end ---
}
