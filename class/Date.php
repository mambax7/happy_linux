<?php

namespace XoopsModules\Happylinux;

// $Id: date.php,v 1.4 2009/01/04 13:20:50 ohwada Exp $

// 2009-01-04 K.OHWADA
// never revise a time difference in date_rfc822_user()

// 2007-11-24 K.OHWADA
// judge_newday()

//=========================================================
// Happy Linux Framework Module
// 2007-10-10 K.OHWADA
//=========================================================

//=========================================================
// class time
//=========================================================

/**
 * Class Date
 * @package XoopsModules\Happylinux
 */
class Date
{
    public $_time_now;
    public $_tz_orignal     = null;
    public $_tz_current     = null;
    public $_offset_orginal = null;
    public $_offset_current = null;
    public $_flag_revision  = false;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_time_now       = time();
        $this->_tz_orignal     = $this->get_default_timezone();
        $this->_offset_orginal = (int)$this->get_timezone_offset();
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        static $instance;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // public
    //---------------------------------------------------------
    /**
     * @param $time
     * @param $days
     * @return bool
     */
    public function judge_newday($time, $days)
    {
        if (($days > 0)
            && ($time > ($this->_time_now - $this->day_to_sec($days)))) {
            return true;
        }

        return false;
    }

    /**
     * @param $time
     * @param $hours
     * @return bool
     */
    public function judge_today($time, $hours)
    {
        if (($hours > 0)
            && ($time > ($this->_time_now - $this->hour_to_sec($hours)))) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function set_default_timezone_by_xoops_default_timezone()
    {
        return false;
        $hour = $this->get_xoops_default_tz_hour();
        $tz   = $this->get_timezone_name_by_hour($hour);
        if ($tz) {
            $ret = $this->set_default_timezone($tz);
            if ($ret) {
                $this->_flag_revision  = true;
                $this->_tz_current     = $tz;
                $this->_offset_current = $this->hour_to_sec($hour);
            }

            return $ret;
        }

        return false;
    }

    /**
     * @param $hour
     * @return bool|false|mixed|string
     */
    public function get_timezone_name_by_hour($hour)
    {
        // first try
        $tz = $this->get_timezone_name_by_hour_list($hour);
        if ($tz) {
            return $tz;
        }

        // second try
        $tz = $this->get_timezone_name_by_abbr($hour);
        if ($tz) {
            return $tz;
        }

        // third try
        $tz = $this->get_timezone_name_by_abbreviations_list($hour);
        if ($tz) {
            return $tz;
        }

        return false;
    }

    /**
     * @param $hour
     * @return bool|string
     */
    public function get_timezone_name_by_hour_list($hour)
    {
        $hour_10   = 10 * $hour;
        $hour_list = &$this->get_timezone_hour_list();
        $iden_list = &$this->get_timezone_identifiers_list();

        if (isset($hour_list[$hour_10]) && is_array($iden_list) && count($iden_list)) {
            // check avilable
            $tz = $hour_list[$hour_10];
            if (in_array($tz, $iden_list)) {
                return $tz;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function &get_timezone_hour_list()
    {
        $arr = [
            '-120' => 'Pacific/Kwajalein',
            '-115' => 'Pacific/Samoa',
            '-110' => 'Pacific/Midway',
            '-105' => 'US/Hawaii',
            '-100' => 'US/Hawaii',
            '-95'  => 'Pacific/Marquesas',
            '-90'  => 'US/Alaska',
            '-80'  => 'US/Pacific',
            '-70'  => 'US/Mountain',
            '-60'  => 'US/Central',
            '-50'  => 'US/Eastern',
            '-45'  => 'America/Curacao',
            '-40'  => 'Brazil/West',
            '-35'  => 'Canada/Newfoundland',
            '-30'  => 'Brazil/East',
            '-20'  => 'America/Noronha',
            '-10'  => 'Atlantic/Azores',
            '00'   => 'UTC',
            '10'   => 'Europe/Paris',
            '20'   => 'Europe/Helsinki',
            '25'   => 'Africa/Mogadishu',
            '30'   => 'Europe/Moscow',
            '35'   => 'Asia/Tehran',
            '40'   => 'Asia/Dubai',
            '45'   => 'Asia/Kabul',
            '50'   => 'Asia/Karachi',
            '55'   => 'Asia/Calcutta',
            '60'   => 'Asia/Dacca',
            '65'   => 'Asia/Calcutta',
            '70'   => 'Asia/Bangkok',
            '75'   => 'Asia/Brunei',
            '80'   => 'Singapore',
            '85'   => 'Asia/Harbin',
            '90'   => 'Asia/Tokyo',
            '95'   => 'Australia/Adelaide',
            '100'  => 'Australia/Melbourne',
            '105'  => 'Australia/Lord_Howe',
            '110'  => 'Pacific/Guadalcanal',
            '115'  => 'Pacific/Norfolk',
            '120'  => 'Pacific/Auckland',
        ];

        return $arr;
    }

    /**
     * @param $hour
     * @return bool|false|string
     */
    public function get_timezone_name_by_abbr($hour)
    {
        return $this->get_timezone_name_from_abbr('', $this->hour_to_sec($hour), 0);
    }

    /**
     * @param $hour
     * @return bool|mixed
     */
    public function get_timezone_name_by_abbreviations_list($hour)
    {
        $sec = $this->hour_to_sec($hour);

        $list = $this->get_timezone_abbreviations_list($hour);
        if (!$list) {
            return false;
        }

        foreach ($list as $a) {
            foreach ($a as $b) {
                if ($b['offset'] == $sec) {
                    return $b['timezone_id'];
                }
            }
        }

        return false;
    }

    /**
     * @param $hour
     * @return int
     */
    public function hour_to_sec($hour)
    {
        $sec = 3600 * $hour;

        return (int)$sec;
    }

    /**
     * @param $day
     * @return int
     */
    public function day_to_sec($day)
    {
        $sec = 86400 * $day;

        return (int)$sec;
    }

    //---------------------------------------------------------
    // https://www.faqs.org/rfcs/rfc2822
    // Thu, 21 Dec 2000 16:01:07 +0200
    //
    // https://www.php.net/manual/ja/function.date.php
    //---------------------------------------------------------
    /**
     * @param $time
     * @return false|string
     */
    public function date_rfc822_user($time)
    {
        return $this->date_rfc822($time);
    }

    /**
     * @param $time
     * @return false|string
     */
    public function date_rfc822($time)
    {
        return date('r', $time);
    }

    //---------------------------------------------------------
    // https://www.w3.org/TR/NOTE-datetime
    // 2003-12-13T18:30:02+09:00
    //
    // https://www.php.net/manual/ja/function.date.php
    // User Contributed Notes
    //
    // PHP 5 support iso8601 format: date('c')
    //---------------------------------------------------------
    /**
     * @param $time
     * @return string
     */
    public function date_iso8601_user($time)
    {
        return $this->date_iso8601($time);
    }

    /**
     * @param $time
     * @return string
     */
    public function date_iso8601($time)
    {
        $tzd  = date('O', $time);
        $tzd  = mb_substr(chunk_split($tzd, 3, ':'), 0, 6);
        $date = date('Y-m-d\TH:i:s', $time) . $tzd;

        return $date;
    }

    /**
     * @param null $time
     * @return false|string
     */
    public function date_year_user($time = null)
    {
        if (empty($time)) {
            $time = time();
        }
        $date = date('Y', $this->get_user_timestamp($time));

        return $date;
    }

    //---------------------------------------------------------
    // PHP 5.1 support this function
    //---------------------------------------------------------
    /**
     * @param $tz
     * @return bool
     */
    public function set_default_timezone($tz)
    {
        if (function_exists('date_default_timezone_set')) {
            return date_default_timezone_set($tz);
        }

        return false;
    }

    /**
     * @return bool|string
     */
    public function get_default_timezone()
    {
        $tz = false;
        if (function_exists('date_default_timezone_get')) {
            $tz = date_default_timezone_get();
        }

        return $tz;
    }

    /**
     * @return array|bool|false
     */
    public function &get_timezone_abbreviations_list()
    {
        $arr = false;
        if (function_exists('timezone_abbreviations_list')) {
            $arr = timezone_abbreviations_list();
        }

        return $arr;
    }

    /**
     * @return array|bool|false
     */
    public function &get_timezone_identifiers_list()
    {
        $arr = false;
        if (function_exists('timezone_identifiers_list')) {
            $arr = timezone_identifiers_list();
        }

        return $arr;
    }

    /**
     * @param      $abbr
     * @param null $offset
     * @param null $isdst
     * @return bool|false|string
     */
    public function get_timezone_name_from_abbr($abbr, $offset = null, $isdst = null)
    {
        $tz = false;
        if (function_exists('timezone_name_from_abbr')) {
            if (null === $offset) {
                $tz = timezone_name_from_abbr($abbr);
            } elseif (null === $isdst) {
                $tz = timezone_name_from_abbr($abbr, $offset);
            } else {
                $tz = timezone_name_from_abbr($abbr, $offset, $isdst);
            }
        }

        return $tz;
    }

    /**
     * @param int $time
     * @return false|string
     */
    public function get_timezone_offset($time = 0)
    {
        if ($time > 0) {
            return date('Z', $time);
        }

        return date('Z');
    }

    //---------------------------------------------------------
    // XOOPS variable
    //---------------------------------------------------------
    /**
     * @return mixed
     */
    public function get_xoops_server_tz_hour()
    {
        global $xoopsConfig;

        return $xoopsConfig['server_TZ'];
    }

    /**
     * @return mixed
     */
    public function get_xoops_default_tz_hour()
    {
        global $xoopsConfig;

        return $xoopsConfig['default_TZ'];
    }

    /**
     * @return array|int|mixed|null
     */
    public function get_xoops_user_timezone_offset()
    {
        global $xoopsUser;
        if (is_object($xoopsUser)) {
            return $xoopsUser->getVar('timezone_offset');
        }

        return 0;
    }

    // user local to unix time

    /**
     * @param        $time
     * @param string $timeoffset
     * @return float|int
     */
    public function get_user_timestamp($time, $timeoffset = '')
    {
        return xoops_getUserTimestamp($time, $timeoffset);
    }

    // same as formatTimestamp

    /**
     * @param        $time
     * @param        $format
     * @param string $timeoffset
     * @return false|string
     */
    public function format_timestamp($time, $format, $timeoffset = '')
    {
        return date($format, $this->get_user_timestamp($time, $timeoffset));
    }

    // --- class end ---
}
