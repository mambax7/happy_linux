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
// class RssViewItemSingle
//=========================================================

/**
 * Class RssViewItemSingle
 * @package XoopsModules\Happylinux
 */
class RssViewItemSingle extends RssViewBasic
{
    // RSS
    public $DATE_RFC822_LIST = ['pubdate'];

    public $DATE_W3C_LIST = [
        // ATOM 1.0
        'published',
        'updated',
        // ATOM 0.3
        'modified',
        'issued',
        'created',
    ];

    public $_view_item = null;

    // nonstandard fulltext tag
    public $_use_fulltext = true;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->_view_item = RssViewItem::getInstance();
    }

    //-------------------------------------------------
    // non restrict feed_type
    // caller build, format_from_parse, format_from_db
    //-------------------------------------------------
    /**
     * @return bool|mixed
     */
    public function _build_content_for_format()
    {
        $val = false;

        // ATOM content
        if ($this->is_set('atom_content') && $this->get_rss_var('atom_content')) {
            $val = $this->get_rss_var('atom_content');
        } // RSS, RDF content
        elseif ($this->is_set('content', 'encoded')) {
            $val = $this->get_rss_var('content', 'encoded');
        } // ATOM content
        elseif ($this->is_set('content') && $this->get_rss_var('content')) {
            $val = $this->get_rss_var('content');
        } // RSS, RDF fulltext
        elseif ($this->_use_fulltext && $this->is_set('fulltext')) {
            $val = $this->get_rss_var('fulltext');
        } // RSS, RDF description
        elseif ($this->is_set('description')) {
            $val = $this->get_rss_var('description');
        } elseif ($this->is_set('dc', 'description')) {
            $val = $this->get_rss_var('dc', 'description');
        } // ATOM summary
        elseif ($this->is_set('summary')) {
            $val = $this->get_rss_var('summary');
        }

        return $val;
    }

    /**
     * @return bool|mixed|string
     */
    public function _build_id_for_format()
    {
        $val = '';

        if ($this->is_set('id')) {
            $val = $this->get_rss_var('id');
        } elseif ($this->is_set('entry_id')) {
            $val = $this->get_rss_var('entry_id');
        }

        return $val;
    }

    //---------------------------------------------------------
    // view format
    //---------------------------------------------------------
    public function format_from_parse()
    {
        $this->set('site_title', $this->_substitute_title('site_title'));
        $this->set('title', $this->_substitute_title('title'));
        $this->set('content', $this->_build_content_for_format());
        $this->set('summary', $this->_build_summary_for_format());
        $this->set('id', $this->_build_id_for_format());
        $this->_format_enclosure();

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
            $time_unix = $this->get_unixtime_w3cdtf($this->get_rss_var('dc', 'date'));
            $this->_set_unixtime($key, $time_unix);
        }

        // updated_long
        if (!$this->is_set('updated_long') && $this->is_set('updated_unix')) {
            $time_unix = $this->get_rss_var('updated_unix');
            $this->_set_unixtime('updated', $time_unix);
        }
    }

    public function format_from_db()
    {
        $this->set('site_title', $this->_substitute_title('site_title'));
        $this->set('title', $this->_substitute_title('title'));
        $this->set('site_link', $this->_substitute_link('site_link'));
        $this->set('link', $this->_substitute_link('link'));
        $this->set('content', $this->_build_content_for_format());
        $this->set('summary', $this->_build_summary_for_format());
        $this->_format_guid_url();
        $this->_set_unixtime('updated', $this->get_rss_var('updated_unix'));
        $this->_set_unixtime('published', $this->get_rss_var('published_unix'));
    }

    // some feed have non URL formated guid
    // https://news.google.com/news?ned=us&output=rss
    // tag:news.google.com,2005:cluster=421c2ca3
    public function _format_guid_url()
    {
        $val = $this->get_rss_var('guid');
        $val = $this->_strings->allow_http($val);
        $val = $this->_strings->deny_http_only($val);
        $this->set('guid_url', $val);
    }

    public function _format_enclosure()
    {
        list($enc_url, $enc_type, $enc_length) = $this->_get_enclosure_list();
        $this->set('enclosure_url', $enc_url);
        $this->set('enclosure_type', $enc_type);
        $this->set('enclosure_length', $enc_length);
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
        if ($this->is_set('pubdate')) {
            if ('l' == $format_date) {
                $pubdate_unix = $this->get_unixtime_rfc822($this->get_rss_var('pubdate'));
                $pubdate_long = formatTimestamp($pubdate, 'l');
                $this->set('pubdate', $pubdate_long);
            }
        } elseif ($this->is_set('published_unix')) {
            $published_unix = $this->get_rss_var('published_unix');

            if ('l' == $format_date) {
                $published_long = formatTimestamp($published_unix, 'l');
                $this->set('pubdate', $published_long);
            } else {
                $published_rfc822 = date('r', $published_unix);
                $this->set('pubdate', $published_rfc822);
            }
        }

        if (!$this->is_set('description') && $this->is_set('content')) {
            $this->set('description', $this->get_rss_var('content'));
        }
    }

    //---------------------------------------------------------
    // view sanitize
    //---------------------------------------------------------
    /**
     * @param null $param
     */
    public function sanitize($param=null)
    {
        $this->_view_item->set_param($param);

        $this->set_is_japanese($param['is_japanese']);

        $arr = [];

        foreach ($this->get_vars() as $k => $v) {
            switch ($k) {
                case 'link':
                case 'author_uri':
                case 'author_url':
                case 'contributor_uri':
                case 'contributor_url':
                case 'enclosure_url':
                case 'guid_url':
                    // BUG: not sanitize site_url
                case 'site_url':
                    $val = $this->_sanitize_html_url($v);
                    break;
                case 'title':
                    $val = $this->_view_item->sanitize_title($v);
                    break;
                case 'content':
                    $v1 = $v;
                    if (is_array($v) && isset($v['encoded'])) {
                        $v1 = $v['encoded'];
                    }
                    $val = $this->_view_item->sanitize_content($v1);
                    break;
                case 'summary':
                    $val = $this->_view_item->sanitize_summary($v);
                    break;
                case 'raws':
                case 'item_orig':
                    $val = '';
                    break;
                default:
                    //          echo "$k |$v| <br>\n";
                    $val = $this->_sanitize_block($v);
                    break;
            }

            $arr[$k] = $val;
        }

        $this->set_vars($arr);
    }

    // --- class end ---
}
