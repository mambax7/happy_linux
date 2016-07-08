<?php
// $Id: local.php,v 1.4 2007/08/08 03:19:52 ohwada Exp $

// 2007-05-12 K.OHWADA
// get_ping_servers()

// 2006-10-05 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-10-01 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_locate_us
// United Sates of America (US)
//=========================================================
class happy_linux_locate_us extends happy_linux_locate_base
{

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $arr = array(
            'country_code'   => 'us',
            'country_name'   => 'United Sates of America',
            'yahoo_url'      => 'http://www.yahoo.com/',
            'yahoo_map_url'  => 'http://map.yahoo.com/',
            'google_url'     => 'http://www.google.com/',
            'google_map_url' => 'http://maps.google.com/',
            'gm_server'      => 'http://maps.google.com/',
            'gm_location'    => 'Chetopa Kansas USA',
            'gm_latitude'    => '37.0',
            'gm_longitude'   => '-95.0',
            'gm_zoom'        => '4',
            'ping_servers'   => $this->get_us_ping_servers(),
        );

        $this->array_merge($arr);
    }

    // --- class end ---
}
