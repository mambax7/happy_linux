<?php
// $Id: w3cdtf.php,v 1.1 2007/08/09 16:08:34 ohwada Exp $

// 2007-08-01 K.OHWADA
// Complete date plus hours and minutes without time zone

// 2004-10-24 K.OHWADA
// suppress warning

//=========================================================
// Happy Linux Framework Module
// move from weblinks
// 2004-10-24 K.OHWADA
//=========================================================

function happy_linux_w3cdtf_to_unixtime($datetime)
{
    $unixtime = 0;
    $arr      = happy_linux_parse_w3cdtf($datetime);
    if (isset($arr['timestamp'])) {
        $unixtime = (int)$arr['timestamp'];
    }

    if ($unixtime < 0) {
        $unixtime = 0;
    }

    return $unixtime;
}

// -------------------------------------------------------------------------
// original:
// http://www.arielworks.net/articles/2004/0224c/
// array parse_w3cdtf(string datetime)
// -------------------------------------------------------------------------
// http://www.w3.org/TR/NOTE-datetime
//  Year:
//      YYYY (eg 1997)
//   Year and month:
//      YYYY-MM (eg 1997-07)
//   Complete date:
//      YYYY-MM-DD (eg 1997-07-16)
//   Complete date plus hours and minutes:
//      YYYY-MM-DDThh:mmTZD (eg 1997-07-16T19:20+01:00)
//   Complete date plus hours, minutes and seconds:
//      YYYY-MM-DDThh:mm:ssTZD (eg 1997-07-16T19:20:30+01:00)
//   Complete date plus hours, minutes, seconds and a decimal fraction of a second
//      YYYY-MM-DDThh:mm:ss.sTZD (eg 1997-07-16T19:20:30.45+01:00)
//
// Not W3C-DTF
//   Complete date plus hours and minutes without time zone:
//      YYYY-MM-DDThh:mm (eg 1997-07-16T19:20)
//   Complete date plus hours, minutes and seconds without time zone:
//      YYYY-MM-DDThh:mm:ss (eg 1997-07-16T19:20:30)
// -------------------------------------------------------------------------
function &happy_linux_parse_w3cdtf($datetime)
{

    // 2004-10-24 K.OHWADA
    // suppress warning
    $year          = 0;
    $month         = 0;
    $day           = 0;
    $hour          = 0;
    $minute        = 0;
    $second        = 0;
    $fraction      = 0;
    $timezone      = 0;
    $offset_sign   = 0;
    $offset_hour   = 0;
    $offset_minute = 0;

    // Year
    if (preg_match("/^(\d{4})$/", $datetime, $val)) {
        $year = $val[1];

        // Year and month
    } elseif (preg_match("/^([0-9]{4})-(0[1-9]|1[0-2])$/", $datetime, $val)) {
        $year  = $val[1];
        $month = $val[2];

        // Complete date
    } elseif (preg_match("/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $datetime, $val)) {
        $year  = $val[1];
        $month = $val[2];
        $day   = $val[3];

        // Complete date plus hours and minutes
    } elseif (preg_match("/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])T([0-5][0-9]):([0-5][0-9])(Z|(\+|-)[0-5][0-9]:[0-5][0-9])$/", $datetime, $val)) {
        $year     = $val[1];
        $month    = $val[2];
        $day      = $val[3];
        $hour     = $val[4];
        $minute   = $val[5];
        $timezone = $val[6];

        // Complete date plus hours, minutes and seconds
    } elseif (preg_match("/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])T([0-5][0-9]):([0-5][0-9]):([0-5][0-9])(Z|(\+|-)[0-5][0-9]:[0-5][0-9])$/", $datetime, $val)) {
        $year     = $val[1];
        $month    = $val[2];
        $day      = $val[3];
        $hour     = $val[4];
        $minute   = $val[5];
        $second   = $val[6];
        $timezone = $val[7];

        // Complete date plus hours, minutes, seconds and a decimal fraction of a second
    } elseif (preg_match("/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])T([0-5][0-9]):([0-5][0-9]):([0-5][0-9]).([0-9]+)(Z|(\+|-)[0-5][0-9]:[0-5][0-9])$/", $datetime, $val)) {
        $year     = $val[1];
        $month    = $val[2];
        $day      = $val[3];
        $hour     = $val[4];
        $minute   = $val[5];
        $second   = $val[6];
        $fraction = $val[7];
        $timezone = $val[8];

        // 2007-08-01 K.OHWADA
        // Complete date plus hours and minutes without timezone
    } elseif (preg_match("/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])T([0-5][0-9]):([0-5][0-9])$/", $datetime, $val)) {
        $year   = $val[1];
        $month  = $val[2];
        $day    = $val[3];
        $hour   = $val[4];
        $minute = $val[5];

        // 2007-08-01 K.OHWADA
        // Complete date plus hours, minutes and seconds without timezone
    } elseif (preg_match("/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])T([0-5][0-9]):([0-5][0-9]):([0-5][0-9])$/", $datetime, $val)) {
        $year   = $val[1];
        $month  = $val[2];
        $day    = $val[3];
        $hour   = $val[4];
        $minute = $val[5];
        $second = $val[6];

        // Not W3C-DTF
    } else {
        $false = false;
        return $false;
    }

    // Offset of Timezone for gmmktime()
    if ($timezone != 'Z') {
        $offset_sign   = substr($timezone, 0, 1);
        $offset_hour   = substr($timezone, 1, 2);
        $offset_minute = substr($timezone, 4, 2);
    }

    $timestamp = gmmktime($hour - ($offset_sign . $offset_hour), $minute - ($offset_sign . $offset_minute), $second, $month, $day, $year);

    $result = array(
        'year'      => $year,
        'month'     => $month,
        'day'       => $day,
        'hour'      => $hour,
        'minute'    => $minute,
        'second'    => $second,
        'fraction'  => $fraction,
        'timezone'  => $timezone,
        'timestamp' => $timestamp
    );

    return $result;
}
