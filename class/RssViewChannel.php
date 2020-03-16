<?php

namespace XoopsModules\Happylinux;

// $Id: rss_view_object.php,v 1.2 2010/11/07 19:26:32 ohwada Exp $

// 2010-11-07 K.OHWADA
// _substitute_link()

// 2008-01-20 K.OHWADA
// divid to rss_view_object.php
// Assigning the return value of new by reference is deprecated

// 2007-10-10 K.OHWADA
// set_is_japanese()
// BUG: preg_match() expects parameter 2 to be string, array given in w3cdtf.php
// get() -> get_rss_var()

// 2007-08-01 K.OHWADA
// BUG: not sanitize site_url

// 2007-06-01 K.OHWADA
// RssViewItemSingle()

// 2007-05-12 K.OHWADA
// this is new file
// porting form rssc_xml_object.php

//=========================================================
// Happy Linux Framework Module
// this file contains 7 classes
//   RssView
//   happylinux_rss_view_basic
//   RssViewChannel
//   RssViewImage
//   RssViewTextinput
//   RssViewItems
//   RssViewItemSingle
// 2007-05-12 K.OHWADA
//=========================================================

//=========================================================
// class RssViewChannel
//=========================================================

/**
 * Class RssViewChannel
 * @package XoopsModules\Happylinux
 */
class RssViewChannel extends RssViewBasic
{
    // RSS
    public $DATE_RFC822_LIST = ['pubdate', 'lastbuilddate'];

    public $DATE_W3C_LIST = [
        // ATOM 1.0
        'published',
        'updated',
        // ATOM 0.3
        'modified',
        'issued',
        'created',
    ];

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // view format
    //---------------------------------------------------------
    public function format()
    {
        $this->set('title', $this->_substitute_title('title'));

        // RFC882
        foreach ($this->DATE_RFC822_LIST as $key) {
            if ($this->get_rss_var($key)) {
                $time_unix = $this->get_unixtime_rfc822($this->get_rss_var($key));
                $this->_set_unixtime($key, $time_unix);
            }
        }

        // W3C format
        foreach ($this->DATE_W3C_LIST as $key) {
            if ($this->get_rss_var($key)) {
                $time_unix = $this->get_unixtime_w3cdtf($this->get_rss_var($key));
                $this->_set_unixtime($key, $time_unix);
            }
        }

        // dc:date
        if ($this->is_set('dc', 'date')) {
            // BUG: preg_match() expects parameter 2 to be string, array given in w3cdtf.php
            $time_unix = $this->get_unixtime_w3cdtf($this->get_rss_var('dc', 'date'));
            $this->_set_unixtime($key, $time_unix);
        }
    }

    //---------------------------------------------------------
    // for rssc_headline xoopsheadline
    // $format_date: l=long, r=rfc822
    //---------------------------------------------------------
    /**
     * @param string $format_date
     */
    public function format_for_rss($format_date = 'l')
    {
        $date_unix = 0;

        // ATOM 1.0
        if ($this->is_set('updated')) {
            $date_unix = $this->get_unixtime_w3cdtf($this->get_rss_var('updated'));
        } // ATOM 0.3
        elseif ($this->is_set('modified')) {
            $date_unix = $this->get_unixtime_w3cdtf($this->get_rss_var('modified'));
        } // DC
        elseif ($this->is_set('dc', 'date')) {
            $date_unix = $this->get_unixtime_w3cdtf($this->get_rss_var('dc', 'date'));
        }

        if ($date_unix) {
            $date_long   = formatTimestamp($date_unix, 'l');
            $date_rfc822 = date('r', $date_unix);
        }

        if ($this->is_set('pubdate')) {
            $pubdate_unix = $this->get_unixtime_rfc822($this->get_rss_var('pubdate'));
            $pubdate_long = formatTimestamp($pubdate_unix, 'l');

            if ('l' == $format_date) {
                $this->set('pubdate', $pubdate_long);
            }
        } elseif ($date_unix) {
            if ('l' == $format_date) {
                $this->set('pubdate', $date_long);
            } else {
                $this->set('pubdate', $date_rfc822);
            }
        }

        if ($this->is_set('lastbuilddate')) {
            $lastbuilddate_unix = $this->get_unixtime_rfc822($this->get_rss_var('lastbuilddate'));
            $lastbuilddate_long = formatTimestamp($lastbuilddate_unix, 'l');

            if ('l' == $format_date) {
                $this->set('lastbuilddate', $lastbuilddate_long);
            }
        } elseif ($date_unix) {
            if ('l' == $format_date) {
                $this->set('lastbuilddate', $date_long);
            } else {
                $this->set('lastbuilddate', $date_rfc822);
            }
        }

        if (!$this->is_set('webmaster')) {
            // ATOM
            if ($this->is_set('author_email')) {
                $this->set('webmaster', $this->get_rss_var('author_email'));
            } elseif ($this->is_set('author_name')) {
                $this->set('webmaster', $this->get_rss_var('author_name'));
            } // DC
            elseif ($this->is_set('dc', 'creator')) {
                $this->set('webmaster', $this->get_rss_var('dc', 'creator'));
            } elseif ($this->is_set('dc', 'publisher')) {
                $this->set('webmaster', $this->get_rss_var('dc', 'publisher'));
            }
        }

        if (!$this->is_set('copyright')) {
            // ATOM
            if ($this->is_set('rights')) {
                $this->set('copyright', $this->get_rss_var('rights'));
            } // DC
            elseif ($this->is_set('dc', 'rights')) {
                $this->set('copyright', $this->get_rss_var('dc', 'rights'));
            }
        }

        if (!$this->is_set('category')) {
            // DC
            if ($this->is_set('dc', 'subject')) {
                $this->set('category', $this->get_rss_var('dc', 'subject'));
            }
        }

        if (!$this->is_set('language')) {
            // DC
            if ($this->is_set('dc', 'language')) {
                $this->set('language', $this->get_rss_var('dc', 'language'));
            }
        }
    }

    //---------------------------------------------------------
    // view sanitize
    //---------------------------------------------------------
    public function sanitize()
    {
        $arr = [];

        foreach ($this->get_vars() as $k => $v) {
            switch ($k) {
                case 'link':
                case 'link_self':
                case 'author_uri':
                case 'author_url':
                case 'contributor_uri':
                case 'contributor_url':
                    $val = $this->_sanitize_html_url($v);
                    break;
                default:
                    $val = $this->_sanitize_block($v);
                    break;
            }

            $arr[$k] = $val;
        }

        $this->set_vars($arr);
    }

    // --- class end ---
}
