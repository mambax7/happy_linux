<?php
// $Id: rss_utility.php,v 1.5 2012/04/10 18:55:18 ohwada Exp $

// 2012-03-01 K.OHWADA
// join_xml_url()

// 2011-12-29 K.OHWADA
// PHP 5.3 : ereg

// 2007-10-10 K.OHWADA
// 2007-09-20 K.OHWADA
// PHP 5.2: Non-static method

// 2007-06-01 K.OHWADA
// move get_unixtime_rfc822 to rss_base_object.php
// find_html_encoding()

// 2007-05-12 K.OHWADA
// this is new file
// porting form rssc_parse_handler.php

//=========================================================
// Happy Linux Framework Module
// 2007-05-12 K.OHWADA
//=========================================================

//---------------------------------------------------------
// define constant
//---------------------------------------------------------
define('HAPPY_LINUX_RSS_CODE_XML_ENCODINGS_DEFAULT', 41);
define('HAPPY_LINUX_RSS_CODE_DISCOVER_SUCCEEDED', 42);
define('HAPPY_LINUX_RSS_CODE_DISCOVER_FAILED', 43);

//=========================================================
// class happy_linux_rss_utility
// caller: rss_parser
//=========================================================
class happy_linux_rss_utility extends happy_linux_error
{
    // class instance
    public $_remote_file;
    public $_convert_encoding;
    public $_strings;

    // basic config
    public $_sel_priority = HAPPY_LINUX_RSS_SEL_ATOM;

    // result
    public $_html_text;
    public $_xml_data;
    public $_xml_mode;
    public $_rdf_url;
    public $_rss_url;
    public $_atom_url;
    public $_xml_kind;
    public $_xml_encoding_detected;
    public $_result_code = 0;

    public $_KNOWN_ENCODINGS   = array('utf-8', 'us-ascii', 'iso-8859-1');
    public $_DEFAULT_ENCODINGS = 'utf-8';
    public $_SOURCE_ENCODINGS  = 'utf-8';

    // select mode
    public $_SEL_MODE = HAPPY_LINUX_RSS_SEL_RSS;
    public $_TEMPLATE_RDF;
    public $_TEMPLATE_RSS;
    public $_TEMPLATE_ATOM;
    public $_TEMPLATE_OTHER;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        // PHP 5.2: Non-static method
        // class instance
        $this->_remote_file      = happy_linux_get_singleton('remote_file');
        $this->_convert_encoding = happy_linux_get_singleton('convert_encoding');
        $this->_strings          = happy_linux_get_singleton('strings');
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_rss_utility();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // init
    //---------------------------------------------------------
    public function set_proxy($host, $port = '8080', $user = '', $pass = '')
    {
        $this->_remote_file->set_snoopy_proxy($host, $port, $user, $pass);
    }

    //=========================================================
    // public
    //=========================================================
    //---------------------------------------------------------
    // discover XML link
    //---------------------------------------------------------
    public function discover($html_url, $sel = '')
    {
        $this->_set_log_func_name('discover');

        $html_text = $this->read_html($html_url);
        if (empty($html_text)) {
            return false;
        }

        if (empty($sel)) {
            $sel = $this->_sel_priority;
        }

        list($rdf_url, $rss_url, $atom_url) = $this->find_link($html_text, $html_url);

        if (($sel == HAPPY_LINUX_RSS_SEL_ATOM) && $atom_url) {
            $xml_mode = HAPPY_LINUX_RSS_MODE_ATOM;
        } elseif (($sel == HAPPY_LINUX_RSS_SEL_RSS) && $rss_url) {
            $xml_mode = HAPPY_LINUX_RSS_MODE_RSS;
        } elseif (($sel == HAPPY_LINUX_RSS_SEL_RDF) && $rdf_url) {
            $xml_mode = HAPPY_LINUX_RSS_MODE_RDF;
        } elseif ($atom_url) {
            $xml_mode = HAPPY_LINUX_RSS_MODE_ATOM;
        } elseif ($rss_url) {
            $xml_mode = HAPPY_LINUX_RSS_MODE_RSS;
        } elseif ($rdf_url) {
            $xml_mode = HAPPY_LINUX_RSS_MODE_RDF;
        } else {
            return false;
        }

        $this->_xml_mode = $xml_mode;
        $this->_rdf_url  = $rdf_url;
        $this->_rss_url  = $rss_url;
        $this->_atom_url = $atom_url;

        return true;
    }

    //---------------------------------------------------------
    // check_exist_rssurl
    // for admin/link_manage.php
    //---------------------------------------------------------
    public function discover_for_manage($mode, $url, $rdf_url, $rss_url, $atom_url, $sel)
    {
        $ret_code = 0;

        // RSS auto discovery
        if ($mode == HAPPY_LINUX_RSS_MODE_AUTO) {
            $ret = $this->discover($url, $sel);
            if ($ret) {
                $ret_code      = HAPPY_LINUX_RSS_CODE_DISCOVER_SUCCEEDED;
                $mode          = $this->get_xml_mode();
                $auto_rdf_url  = $this->get_rdf_url();
                $auto_rss_url  = $this->get_rss_url();
                $auto_atom_url = $this->get_atom_url();

                if ($auto_rdf_url) {
                    $rdf_url = $auto_rdf_url;
                }

                if ($auto_rss_url) {
                    $rss_url = $auto_rss_url;
                }

                if ($auto_atom_url) {
                    $atom_url = $auto_atom_url;
                }
            } else {
                // cannot discover xml link
                $ret_code = HAPPY_LINUX_RSS_CODE_DISCOVER_FAILED;
                $this->_set_errors('cannot discover xml link');
                $this->_set_errors($this->getErrors());
            }
        }

        $this->_xml_mode = $mode;
        $this->_rdf_url  = $rdf_url;
        $this->_rss_url  = $rss_url;
        $this->_atom_url = $atom_url;

        return $ret_code;
    }

    //---------------------------------------------------------
    // read remote HTML
    //---------------------------------------------------------
    public function read_html($url)
    {
        $this->_set_log_func_name('read_html');

        $this->_html_text = null;

        // read remote XML
        $data = $this->_remote_file->read_file($url);

        if (!$data) {
            $this->_set_error_code($this->_remote_file->getErrorCode());
            $this->_set_errors($this->_remote_file->getErrors());
            return false;
        }

        $this->_html_text = $data;
        return $data;
    }

    //---------------------------------------------------------
    // read remote XML
    //
    // head spaces
    // http://www.iwate-svc.jp/feed
    //---------------------------------------------------------
    public function read_xml($url)
    {
        $this->_set_log_func_name('read_xml');

        // read remote XML
        $data = $this->_remote_file->read_file($url);

        if (!$data) {
            $this->_set_error_code($this->_remote_file->getErrorCode());
            $this->_set_errors($this->_remote_file->getErrors());
            return false;
        }

        // remove head spaces
        $data = preg_replace('/^\s+<\?xml/', '<?xml', $data);

        $this->_xml_data = $data;
        return $data;
    }

    //---------------------------------------------------------
    // find XML link: auto discovery
    //---------------------------------------------------------
    public function find_link($html_text, $html_url = '')
    {
        $this->_set_log_func_name('find_link');

        list($rdf_url, $rss_url, $atom_url) = $this->_find_xml_link($html_text, $html_url);

        if (empty($rdf_url) && empty($rss_url) && empty($atom_url)) {
            $this->_set_errors("cannot find xml link: url = $html_url");
        }

        return array($rdf_url, $rss_url, $atom_url);
    }

    //---------------------------------------------------------
    // find XML encoding
    //---------------------------------------------------------
    public function find_encoding($xml)
    {
        $this->_set_log_func_name('find_encoding');

        $encoding = $this->find_xml_encoding($xml);
        if ($encoding) {
            $this->_xml_encoding_detected = $encoding;
        } else {
            if ($this->_DEFAULT_ENCODINGS) {
                $encoding           = $this->_DEFAULT_ENCODINGS;
                $this->_result_code = HAPPY_LINUX_RSS_CODE_XML_ENCODINGS_DEFAULT;
            } else {
                $this->_set_errors('cannot find xml encoding');
                return false;
            }
        }

        $encoding_orig = strtolower($encoding);
        return $encoding_orig;
    }


    //---------------------------------------------------------
    // find XML mode
    //---------------------------------------------------------
    public function find_kind($xml)
    {
        $this->_set_log_func_name('find_kind');

        $kind = $this->_find_xml_kind($xml);
        if (!$kind) {
            $this->_set_errors('cannot find xml kind');
            return false;
        }

        switch ($kind) {
            case 'rdf':
                $mode = HAPPY_LINUX_RSS_MODE_RDF;
                break;

            case 'rss':
                $mode = HAPPY_LINUX_RSS_MODE_RSS;
                break;

            case 'atom':
                $mode = HAPPY_LINUX_RSS_MODE_ATOM;
                break;

            default:
                $this->_set_errors('cannot find xml kind');
                return false;
                break;
        }

        $this->_xml_kind = $kind;
        $this->_xml_mode = $mode;
        return $mode;
    }

    //---------------------------------------------------------
    // convert XML to parse
    //---------------------------------------------------------
    public function convert_to_parse($xml_data, $xml_encoding)
    {
        $this->_set_log_func_name('convert_to_parse');
        $ret = $this->_convert_xml_to_parse($xml_data, $xml_encoding);
        return $ret;
    }

    //---------------------------------------------------------
    // set param
    //---------------------------------------------------------
    public function set_priority($value)
    {
        $this->_sel_priority = $value;
    }

    public function set_encoding_local($value)
    {
        $this->_xml_encoding_local = $value;
    }

    //---------------------------------------------------------
    // get result of auto discovery
    //---------------------------------------------------------
    public function get_html_text()
    {
        return $this->_html_text;
    }

    public function get_xml_mode()
    {
        return $this->_xml_mode;
    }

    public function get_rdf_url()
    {
        return $this->_rdf_url;
    }

    public function get_rss_url()
    {
        return $this->_rss_url;
    }

    public function get_atom_url()
    {
        return $this->_atom_url;
    }

    public function get_xmlurl_by_mode()
    {
        switch ($this->_xml_mode) {
            case HAPPY_LINUX_RSS_MODE_RDF:
                return $this->_rdf_url;
                break;

            case HAPPY_LINUX_RSS_MODE_RSS:
                return $this->_rss_url;
                break;

            case HAPPY_LINUX_RSS_MODE_ATOM:
                return $this->_atom_url;
                break;
        }

        return false;
    }

    //---------------------------------------------------------
    // get result of parse
    //---------------------------------------------------------
    public function get_xml()
    {
        return $this->_xml_data;
    }

    public function get_xml_kind()
    {
        return $this->_xml_kind;
    }

    public function get_result_code()
    {
        return $this->_result_code;
    }


    //=========================================================
    // private
    //=========================================================

    //---------------------------------------------------------
    // find RDF/RSS/ATOM link in HTML
    //---------------------------------------------------------
    // <base href="xxx">
    // <link rel="alternate" type="application/rdf+xml"  title="RDF" href="xxx" />
    // <link rel="alternate" type="application/rss+xml"  title="RSS" href="xxx" />
    // <link rel="alternate" type="application/atom+xml" title="ATOM" href="xxx" />
    //---------------------------------------------------------
    public function _find_xml_link($html, $url = '')
    {
        $href_base = '';
        $href_rdf  = '';
        $href_rss  = '';
        $href_atom = '';

        // save all <link> tags
        if (preg_match("/<base\s+href=(['\"]?)([^\"'<>]*)\\1(.*?)>/si", $html, $match)) {
            $href_base = $match[2];
        }

        // save all <link> tags
        preg_match_all('/<link\s+(.*?)\s*\/?>/si', $html, $match);
        $link_tag_arr = $match[1];

        $link_arr       = array();
        $link_tag_count = count($link_tag_arr);

        // store each <link> tags's attributes
        for ($i = 0; $i < $link_tag_count; ++$i) {
            $attr_wk_arr   = array();
            $link_attr_arr = preg_split('/\s+/s', $link_tag_arr[$i]);

            foreach ($link_attr_arr as $link_attr) {
                $link_attr_pair = preg_split('/\s*=\s*/s', $link_attr, 2);

                if (isset($link_attr_pair[0]) && isset($link_attr_pair[1])) {
                    $key               = $link_attr_pair[0];
                    $value             = $link_attr_pair[1];
                    $key               = strtolower($key);
                    $value             = preg_replace('/([\'"]?)(.*)\1/', '$2', $value);
                    $attr_wk_arr[$key] = $value;
                }
            }

            $link_arr[$i] = $attr_wk_arr;
        }

        // find the link file
        for ($i = 0; $i < $link_tag_count; ++$i) {
            if (!isset($link_arr[$i]['rel'])) {
                continue;
            }
            if (!isset($link_arr[$i]['type'])) {
                continue;
            }
            if (!isset($link_arr[$i]['href'])) {
                continue;
            }

            $rel  = strtolower($link_arr[$i]['rel']);
            $type = strtolower($link_arr[$i]['type']);
            $href = $link_arr[$i]['href'];

            if ($rel != 'alternate') {
                continue;
            }

            if (empty($href_rdf) && ($type == 'application/rdf+xml')) {
                // BUG 4389: cannot auto discovery RDF url
                $href_rdf = $href;
            } elseif (empty($href_rss) && ($type == 'application/rss+xml')) {
                $href_rss = $href;
            } elseif (empty($href_atom) && ($type == 'application/atom+xml')) {
                $href_atom = $href;
            }
        }

        if ($url) {
            $href_rdf  = $this->_relative_to_full_url($href_rdf, $url, $href_base);
            $href_rss  = $this->_relative_to_full_url($href_rss, $url, $href_base);
            $href_atom = $this->_relative_to_full_url($href_atom, $url, $href_base);
        }

        return array($href_rdf, $href_rss, $href_atom);
    }

    //---------------------------------------------------------
    // relative_to_full_url
    //---------------------------------------------------------
    public function _relative_to_full_url($url_xml, $url_html, $url_base)
    {
        if (empty($url_xml)) {
            return '';
        }

        // start from "http"
        if (preg_match("/^(https?:\/\/.)/", $url_xml)) {
            return $url_xml;
        }

        // if base
        if ($url_base) {
            $full = $this->join_xml_url($url_base, $url_xml);
            return $full;
        }

        // start from "/"
        if (preg_match("/^\//", $url_xml)) {
            $param = parse_url($url_html);
            if (isset($param['scheme']) && isset($param['host'])) {
                $url  = $param['scheme'] . '://' . $param['host'];
                $full = $this->join_xml_url($url, $url_xml);
                return $full;
            }
        }

        // others
        $url = $url_html;

        // http://abc/efg.html -> http://abc/
        if (preg_match("/^(.*)\/(.*)$/", $url_html, $match)) {
            $url = $match[1];
        }

        $full = $this->join_xml_url($url, $url_xml);
        return $full;
    }

    public function join_xml_url($url_html, $url_xml)
    {
        $html = $this->strip_slash_from_tail($url_html);
        $xml  = $this->strip_slash_from_head($url_xml);
        $full = $html . '/' . $xml;
        return $full;
    }

    public function strip_slash_from_head($str)
    {
        // ord : the ASCII value of the first character of string
        // 0x2f slash

        if (ord($str) == 0x2f) {
            $str = substr($str, 1);
        }
        return $str;
    }

    public function strip_slash_from_tail($str)
    {
        if (substr($str, -1, 1) == '/') {
            $str = substr($str, 0, -1);
        }
        return $str;
    }

    //---------------------------------------------------------
    // find HTML encoding
    // < meta http-equiv="Content-Type" content="text/html;charset=UTF-8" >
    //---------------------------------------------------------
    public function find_html_encoding($text, $flag_auto = false)
    {
        $encoding = null;
        if (preg_match('/<(meta.*Content-Type.*)>/is', $text, $match1)) {
            if (preg_match('/charset=([a-zA-Z0-9\-\_]+)/is', $match1[1], $match2)) {
                $encoding = trim($match2[1]);
            }
        }
        if (empty($encoding) && $flag_auto) {
            $encoding = happy_linux_detect_encoding($text);
        }
        return $encoding;
    }

    //---------------------------------------------------------
    // find XML encoding
    // < ? xml version="1.0" encoding="UTF-8" ? >
    //---------------------------------------------------------
    public function find_xml_encoding($text, $flag_auto = false)
    {
        $encoding = false;
        if (preg_match('/<\?xml(.*?)\?>/si', $text, $match1)) {
            if (preg_match('/encoding=[\"|\']([a-zA-Z0-9\-\_]+)/si', $match1[1], $match2)) {
                $encoding = trim($match2[1]);
            }
        }
        if (empty($encoding) && $flag_auto) {
            $encoding = happy_linux_detect_encoding($text);
        }
        return $encoding;
    }

    //---------------------------------------------------------
    // find XML mode
    // < rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" >
    // < rss version="2.0" >
    // < feed version="0.3" xmlns="http://purl.org/atom/ns#" >
    // < feed xmlns="http://www.w3.org/2005/Atom" >
    //---------------------------------------------------------
    public function _find_xml_kind($text)
    {
        if (preg_match('/<rdf:RDF(.*?)>/si', $text)) {
            return 'rdf';
        }

        if (preg_match('/<rss(.*?)>/si', $text)) {
            return 'rss';
        }

        if (preg_match('/<feed(.*?)>/si', $text, $match1)) {
            $line = $match1[1];

            if (preg_match('/atom/si', $line)) {
                return 'atom';
            }
        }

        return false;
    }

    //---------------------------------------------------------
    // convert xml to parse
    //---------------------------------------------------------
    public function _convert_xml_to_parse($xml, $encoding)
    {

        // not convert, if PHP default
        //  if ( ($encoding == 'utf-8') || ($encoding == 'us-ascii') || ($encoding == 'iso-8859-1') )
        if ($this->_check_known_encoding($encoding)) {
            $xml_cleaned = $this->_cleanup_xml($xml);
            return array($xml_cleaned, $encoding);
        } // convert
        elseif ($encoding) {
            $encoding_converted = $this->_SOURCE_ENCODINGS;
            $xml_converted      = $this->convert($xml, $encoding_converted, $encoding);
            $xml_cleaned        = $this->_cleanup_xml($xml_converted);
            return array($xml_cleaned, $encoding_converted);
        }

        // no action
        return array($xml, $encoding);
    }

    public function _cleanup_xml($text)
    {
        $text = $this->_strings->strip_control($text);
        $text = $this->_strings->strip_tab($text);
        return $text;
    }

    public function _check_known_encoding($enc)
    {
        $enc = strtolower($enc);
        if (in_array($enc, $this->_KNOWN_ENCODINGS)) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // convert class
    //---------------------------------------------------------
    public function convert($str, $to, $from)
    {
        return $this->_convert_encoding->convert($str, $to, $from);
    }

    //---------------------------------------------------------
    // select by mode
    //---------------------------------------------------------
    public function set_sel_mode($mode)
    {
        switch ($mode) {
            case HAPPY_LINUX_RSS_SEL_RDF:
            case HAPPY_LINUX_RSS_SEL_RSS:
            case HAPPY_LINUX_RSS_SEL_ATOM:
                $this->_SEL_MODE = $mode;
                break;

            case HAPPY_LINUX_RSS_SEL_OTHER:
            default:
                $this->_SEL_MODE = HAPPY_LINUX_RSS_SEL_OTHER;
                break;
        }
    }

    public function get_sel_mode()
    {
        return $this->_SEL_MODE;
    }

    public function set_template_rdf($val)
    {
        $this->_TEMPLATE_RDF = $val;
    }

    public function set_template_rss($val)
    {
        $this->_TEMPLATE_RSS = $val;
    }

    public function set_template_atom($val)
    {
        $this->_TEMPLATE_ATOM = $val;
    }

    public function set_template_other($val)
    {
        $this->_TEMPLATE_OTHER = $val;
    }

    public function get_sel_template()
    {
        $template = '';

        switch ($this->_SEL_MODE) {
            case HAPPY_LINUX_RSS_SEL_RDF:
                $template = $this->_TEMPLATE_RDF;
                break;

            case HAPPY_LINUX_RSS_SEL_RSS:
                $template = $this->_TEMPLATE_RSS;
                break;

            case HAPPY_LINUX_RSS_SEL_ATOM:
                $template = $this->_TEMPLATE_ATOM;
                break;

            case HAPPY_LINUX_RSS_SEL_OTHER:
            default:
                $template = $this->_TEMPLATE_OTHER;
                break;
        }

        return $template;
    }

    public function get_sel_find_link($html_text, $html_url)
    {
        $url = '';

        list($url_rdf, $url_rss, $url_atom) = $this->find_link($html_text, $html_url);

        switch ($this->_SEL_MODE) {
            case HAPPY_LINUX_RSS_SEL_RDF:
                $url = $url_rdf;
                break;

            case HAPPY_LINUX_RSS_SEL_RSS:
                $url = $url_rss;
                break;

            case HAPPY_LINUX_RSS_SEL_ATOM:
                $url = $url_atom;
                break;

            case HAPPY_LINUX_RSS_SEL_OTHER:
            default:
                break;
        }

        return $url;
    }

    //---------------------------------------------------------
    // lang_items
    //---------------------------------------------------------
    public function &get_lang_items()
    {
        $arr = array(
            'lang_site_desc'              => _HAPPY_LINUX_VIEW_SITE_DESCRIPTION,
            'lang_site_updated'           => _HAPPY_LINUX_VIEW_SITE_UPDATED,
            'lang_site_date'              => _HAPPY_LINUX_VIEW_SITE_DATE,
            'lang_site_webmaster'         => _HAPPY_LINUX_VIEW_SITE_WEBMASTER,
            'lang_site_language'          => _HAPPY_LINUX_VIEW_SITE_LANGUAGE,
            'lang_site_generator'         => _HAPPY_LINUX_VIEW_SITE_GENERATOR,
            'lang_site_category'          => _HAPPY_LINUX_VIEW_SITE_CATEGORY,
            'lang_site_description'       => _HAPPY_LINUX_VIEW_SITE_DESCRIPTION,
            'lang_site_docs'              => _HAPPY_LINUX_VIEW_RSS_SITE_DOCS,
            'lang_site_copyright'         => _HAPPY_LINUX_VIEW_RSS_SITE_COPYRIGHT,
            'lang_site_cloud'             => _HAPPY_LINUX_VIEW_RSS_SITE_CLOUD,
            'lang_site_ttl'               => _HAPPY_LINUX_VIEW_RSS_SITE_TTL,
            'lang_site_rating'            => _HAPPY_LINUX_VIEW_RSS_SITE_RATING,
            'lang_site_textinput'         => _HAPPY_LINUX_VIEW_RSS_SITE_TEXTINPUT,
            'lang_site_skiphours'         => _HAPPY_LINUX_VIEW_RSS_SITE_SKIPHOURS,
            'lang_site_skipdays'          => _HAPPY_LINUX_VIEW_RSS_SITE_SKIPDAYS,
            'lang_site_rights'            => _HAPPY_LINUX_VIEW_ATOM_SITE_RIGHTS,
            'lang_site_source'            => _HAPPY_LINUX_VIEW_ATOM_SITE_SOURCE,
            'lang_site_subtitle'          => _HAPPY_LINUX_VIEW_ATOM_SITE_SUBTITLE,
            'lang_site_id'                => _HAPPY_LINUX_VIEW_ATOM_SITE_ID,
            'lang_site_icon'              => _HAPPY_LINUX_VIEW_ATOM_SITE_ICON,
            'lang_site_logo'              => _HAPPY_LINUX_VIEW_ATOM_SITE_LOGO,
            'lang_site_lastbuilddate'     => _HAPPY_LINUX_VIEW_RSS_SITE_LASTBUILDDATE,
            'lang_site_pubdate'           => _HAPPY_LINUX_VIEW_RSS_SITE_PUBDATE,
            'lang_site_managingeditor'    => _HAPPY_LINUX_VIEW_RSS_SITE_MANAGINGEDITOR,
            'lang_site_link_self'         => _HAPPY_LINUX_VIEW_ATOM_SITE_LINK_SELF,
            'lang_site_author_name'       => _HAPPY_LINUX_VIEW_ATOM_SITE_AUTHOR_NAME,
            'lang_site_author_email'      => _HAPPY_LINUX_VIEW_ATOM_SITE_AUTHOR_EMAIL,
            'lang_site_author_uri'        => _HAPPY_LINUX_VIEW_ATOM_SITE_AUTHOR_URI,
            'lang_site_contributor_name'  => _HAPPY_LINUX_VIEW_ATOM_CONTRIBUTOR_NAME,
            'lang_site_contributor_email' => _HAPPY_LINUX_VIEW_ATOM_CONTRIBUTOR_EMAIL,
            'lang_site_contributor_uri'   => _HAPPY_LINUX_VIEW_ATOM_CONTRIBUTOR_URI,
            'lang_site_creator'           => _HAPPY_LINUX_VIEW_DC_CREATOR,
            'lang_title'                  => _HAPPY_LINUX_VIEW_TITLE,
            'lang_published'              => _HAPPY_LINUX_VIEW_PUBLISHED,
            'lang_updated'                => _HAPPY_LINUX_VIEW_UPDATED,
            'lang_created'                => _HAPPY_LINUX_VIEW_CREATED,
            'lang_summary'                => _HAPPY_LINUX_VIEW_SUMMARY,
            'lang_category'               => _HAPPY_LINUX_VIEW_CATEGORY,
            'lang_rights'                 => _HAPPY_LINUX_VIEW_RIGHTS,
            'lang_source'                 => _HAPPY_LINUX_VIEW_SOURCE,
            'lang_guid'                   => _HAPPY_LINUX_VIEW_RSS_GUID,
            'lang_pubdate'                => _HAPPY_LINUX_VIEW_RSS_PUBDATE,
            'lang_author'                 => _HAPPY_LINUX_VIEW_RSS_AUTHOR,
            'lang_comments'               => _HAPPY_LINUX_VIEW_RSS_COMMENTS,
            'lang_enclosure'              => _HAPPY_LINUX_VIEW_RSS_ENCLOSURE,
            'lang_enclosure_url'          => _HAPPY_LINUX_VIEW_ENCLOSURE_URL,
            'lang_enclosure_type'         => _HAPPY_LINUX_VIEW_ENCLOSURE_TYPE,
            'lang_enclosure_length'       => _HAPPY_LINUX_VIEW_ENCLOSURE_LENGTH,
            'lang_entry_id'               => _HAPPY_LINUX_VIEW_ATOM_ID,
            'lang_description'            => _HAPPY_LINUX_VIEW_DESCRIPTION,
            'lang_author_name'            => _HAPPY_LINUX_VIEW_AUTHOR_NAME,
            'lang_author_email'           => _HAPPY_LINUX_VIEW_AUTHOR_EMAIL,
            'lang_author_uri'             => _HAPPY_LINUX_VIEW_AUTHOR_URI,
            'lang_contributor_name'       => _HAPPY_LINUX_VIEW_ATOM_CONTRIBUTOR_NAME,
            'lang_contributor_email'      => _HAPPY_LINUX_VIEW_ATOM_CONTRIBUTOR_EMAIL,
            'lang_contributor_uri'        => _HAPPY_LINUX_VIEW_ATOM_CONTRIBUTOR_URI,
            'lang_subject'                => _HAPPY_LINUX_VIEW_DC_SUBJECT,
            'lang_publisher'              => _HAPPY_LINUX_VIEW_DC_PUBLISHER,
            'lang_creator'                => _HAPPY_LINUX_VIEW_DC_CREATOR,
            'lang_date'                   => _HAPPY_LINUX_VIEW_DC_DATE,
            'lang_format'                 => _HAPPY_LINUX_VIEW_DC_FORMAT,
            'lang_relation'               => _HAPPY_LINUX_VIEW_DC_RELATION,
            'lang_identifier'             => _HAPPY_LINUX_VIEW_DC_IDENTIFIER,
            'lang_coverage'               => _HAPPY_LINUX_VIEW_DC_COVERAGE,
            'lang_audience'               => _HAPPY_LINUX_VIEW_DC_AUDIENCE,
            'lang_encoded'                => _HAPPY_LINUX_VIEW_CONTENT_ENCODED,
        );

        return $arr;
    }

    //----- class end -----
}
