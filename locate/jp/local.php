<?php
// $Id: local.php,v 1.3 2007/05/15 04:53:48 ohwada Exp $

// 2007-05-12 K.OHWADA
// get_ping_servers()

// 2006-10-05 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-10-01 K.OHWADA
//=========================================================

//=========================================================
// class locate_jp
// Japan (JP)
//=========================================================
class locate_jp extends Happy_linux\LocateBase
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        if (defined('_HAPPY_LINUX_GM_DEFAULT_LOCATION')) {
            $gm_location = _HAPPY_LINUX_GM_DEFAULT_LOCATION;
        } else {
            $gm_location = 'Akashi Muncipal Planetaruim: Akashi, Japan';
        }

        $arr = [
            'happy_linux_url' => 'https://linux.ohwada.jp/',
            'country_code'    => 'jp',
            'country_name'    => 'Japan',
            'yahoo_url'       => 'https://www.yahoo.co.jp/',
            'yahoo_map_url'   => 'https://map.yahoo.co.jp/',
            'google_url'      => 'https://www.google.co.jp/',
            'google_map_url'  => 'https://maps.google.co.jp/',
            'gm_server'       => 'https://maps.google.co.jp/',
            'gm_location'     => $gm_location,
            'gm_latitude'     => '34.64933466571561',
            'gm_longitude'    => '135.0',
            'gm_zoom'         => '6',
            'ping_servers'    => $this->get_ping_servers(),
        ];

        $this->array_merge($arr);
    }

    public function get_ping_servers()
    {
        // remove cocolog-nifty, becuase service is over
        // https://info.cocolog-nifty.com/info/2006/10/ping_6da6.html

        $list = '';
        $list .= "https://ping.bloggers.jp/rpc/\n";
        $list .= "https://bulkfeeds.net/rpc\n";
        $list .= "https://ping.myblog.jp/\n";
        $list .= "https://blog.goo.ne.jp/XMLRPC\n";

        return $list;
    }

    // --- class end ---
}
