<?php

namespace XoopsModules\Happylinux;

// $Id: rss_base_object.php,v 1.6 2008/01/31 14:07:05 ohwada Exp $

// 2008-01-30 K.OHWADA
// typo: create_item_singlel
// happylinux_rss_basic -> happylinux_rss_base_basic

// 2007-10-10 K.OHWADA
// set_is_japanese()

// 2007-09-20 K.OHWADA
// PHP5.2
// Assigning the return value of new by reference is deprecated
// Declaration of happylinux_rss_base_basic::get() should be compatible with that of happylinux_basic::get()

// 2007-08-01 K.OHWADA
// w3cdtf.php

// 2007-06-01 K.OHWADA
// divid from rss_object.php
// move get_unixtime_rfc822 from RssUtility.php

// 2007-05-12 K.OHWADA
// this is new file
// porting form rssc_xml_object.php

//=========================================================
// Happy Linux Framework Module
// this file contains 3 classes
//   happylinux_rss_base
//   happylinux_rss_base_basic
//   happylinux_rss_base_items
// 2007-05-12 K.OHWADA
//=========================================================

require_once XOOPS_ROOT_PATH . '/modules/happylinux/include/w3cdtf.php';

//=========================================================
// class rss_base_basic
//=========================================================

/**
 * Class RssBaseBasic
 * @package XoopsModules\Happylinux
 */
class RssBaseBasic extends BasicObject
{
    // class
    public $_strings;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        // class
        $this->_strings = Strings::getInstance();
    }

    //--------------------------------------------------------
    // set parameter
    //--------------------------------------------------------
    /**
     * @param $val
     */
    public function set_is_japanese($val)
    {
        $this->_strings->set_is_japanese($val);
    }

    //---------------------------------------------------------
    // is set & array
    //---------------------------------------------------------
    // Declaration of happylinux_rss_base_basic::get() should be compatible with that of happylinux_basic::get()
    /**
     * @param      $key1
     * @param bool $key2
     * @return bool|mixed
     */
    public function get_rss_var($key1, $key2 = false)
    {
        $ret = false;
        if (isset($this->_vars[$key1])) {
            if ($key2) {
                if (isset($this->_vars[$key1][$key2])) {
                    $ret = $this->_vars[$key1][$key2];
                }
            } else {
                $ret = $this->_vars[$key1];
            }
        }

        return $ret;
    }

    /**
     * @param      $key1
     * @param bool $key2
     * @return bool
     */
    public function is_set($key1, $key2 = false)
    {
        if (isset($this->_vars[$key1])) {
            if ($key2) {
                if (is_array($this->_vars[$key1]) && isset($this->_vars[$key1][$key2])) {
                    return true;
                }
            } else {
                return true;
            }
        }

        return false;
    }

    //-------------------------------------------------
    // non restrict feed_type
    // caller build, format_from_parse, format_from_db
    //-------------------------------------------------
    /**
     * @return string
     */
    public function _build_summary_for_format()
    {
        $val = '';

        // ATOM summary
        if ($this->is_set('summary')) {
            $val = $this->get_rss_var('summary');
        } // RSS, RDF fulltext
        elseif ($this->_use_fulltext && $this->is_set('fulltext')) {
            $val = $this->get_rss_var('fulltext');
        } // RSS, RDF description
        elseif ($this->is_set('description')) {
            $val = $this->get_rss_var('description');
        } elseif ($this->is_set('dc', 'description')) {
            $val = $this->get_rss_var('dc', 'description');
        } // RSS, RDF content
        elseif ($this->is_set('content', 'encoded')) {
            $val = $this->get_rss_var('content', 'encoded');
        } // ATOM content
        elseif ($this->is_set('content') && $this->get_rss_var('content')) {
            $val = $this->get_rss_var('content');
        }

        $val = $this->_strings->strip_tags_for_text($val);

        return $val;
    }

    // some RSS have twe or more enclosure tag
    // set first one
    /**
     * @return array
     */
    public function _get_enclosure_list()
    {
        $url    = null;
        $type   = null;
        $length = null;

        if ($this->is_set('enclosure')) {
            $enc = $this->get_rss_var('enclosure');

            if (isset($enc[0]['url'])) {
                $url = $enc[0]['url'];
            }
            if (isset($enc[0]['type'])) {
                $type = $enc[0]['type'];
            }
            if (isset($enc[0]['length'])) {
                $length = (int)$enc[0]['length'];
            }
        }

        return [$url, $type, $length];
    }

    //--------------------------------------------------------
    // get unixtime from RFC822
    //--------------------------------------------------------
    /**
     * @param $datetime
     * @return int
     */
    public function get_unixtime_rfc822($datetime)
    {
        $unixtime = strtotime($datetime);

        // maybe undefined time zone
        if (-1 == $unixtime) {
            // delete time zone
            $datetime = preg_replace('/ [a-zA-Z]{3,}$/', '', $datetime);
            $unixtime = strtotime($datetime);
        }

        // give up
        $unixtime = (int)$unixtime;
        if ($unixtime < 0) {
            $unixtime = 0;
        }

        return $unixtime;
    }

    // -------------------------------------------------------------------------
    // get unixtime from W3C DTF (dc:date)
    // -------------------------------------------------------------------------
    /**
     * @param $datetime
     * @return int
     */
    public function get_unixtime_w3cdtf($datetime)
    {
        return happylinux_w3cdtf_to_unixtime($datetime);
    }

    // --- class end ---
}
