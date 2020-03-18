<?php

// $Id: rss_parse_object.php,v 1.3 2012/03/17 16:08:32 ohwada Exp $

// 2012-03-01 K.OHWADA
// <geo:lat>lat</geo:lat>

// 2010-11-07 K.OHWADA
// BUG: NOT parse http://maps.google.co.jp/maps/

// 2009-02-20 K.OHWADA
// _build_geo() _build_media_content()

// 2008-01-30 K.OHWADA
// typo: create_item_singlel

// 2007-09-20 K.OHWADA
// PHP5.2
// Assigning the return value of new by reference is deprecated
// Declaration of happy_linux_rss_parse_channel::convert() should be compatible with that of happy_linux_rss_parse_basic::convert()
// Declaration of happy_linux_rss_parse_item_single::build() should be compatible with that of happy_linux_rss_parse_basic::build() in happy_linux\class\rss_parse_object.php
// Non-static method happy_linux_convert_encoding::getInstance() should not be called statically, assuming $this from incompatible context

// 2007-08-01 K.OHWADA
// strip_control_array()

// 2007-06-01 K.OHWADA
// divid from rss_object.php

// 2007-05-12 K.OHWADA
// this is new file
// porting form rssc_xml_object.php

//=========================================================
// Happy Linux Framework Module
// this file contains 7 classes
//   happy_linux_rss_parse
//   happy_linux_rss_parse_basic
//   happy_linux_rss_parse_channel
//   happy_linux_rss_parse_image
//   happy_linux_rss_parse_textinput
//   happy_linux_rss_parse_items
//   happy_linux_rss_parse_item_single
// 2007-05-12 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_rss_parse
//=========================================================

/**
 * Class happy_linux_rss_parse
 */
class happy_linux_rss_parse extends happy_linux_rss_base
{
    // cached data
    public $_converted_data;

    // encoding
    public $_local_encoding = _CHARSET;
    public $_xml_encoding   = 'utf-8';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // set & get vars
    //---------------------------------------------------------

    /**
     * @param $obj
     */
    public function set_vars_from_parse($obj)
    {
        $control = [
            'feed_type'       => $obj->feed_type,
            'feed_version'    => $obj->feed_version,
            'source_encoding' => $obj->source_encoding,
            'encoding'        => $obj->encoding,
        ];

        $this->set_control($control);
        $this->set_channel($obj->channel);
        $this->set_image($obj->image);
        $this->set_textinput($obj->textinput);
        $this->set_items($obj->items);
    }

    /**
     * @return bool
     */
    public function get_source_encoding()
    {
        $arr = $this->get_control();

        $ret = false;
        if (isset($arr['source_encoding'])) {
            $ret = &$arr['source_encoding'];
        }

        return $ret;
    }

    /**
     * @return bool
     */
    public function &get_converted_data()
    {
        $ret = false;
        if (isset($this->_converted_data)) {
            $ret = &$this->_converted_data;
        }

        return $ret;
    }

    //---------------------------------------------------------
    // convert from parse to local
    //---------------------------------------------------------
    public function convert_to_local()
    {
        $to   = $this->_local_encoding;
        $from = $this->_xml_encoding;

        // BUG: sometime cannot parse
        if (isset($this->_channel_obj) && is_object($this->_channel_obj)) {
            $this->_channel_obj->convert($to, $from);
        }

        if (isset($this->_items_obj) && is_object($this->_items_obj)) {
            $this->_items_obj->convert($to, $from);
        }

        if (isset($this->_image_obj) && is_object($this->_image_obj)) {
            $this->_image_obj->convert($to, $from);
        }

        if (isset($this->_textinput_obj) && is_object($this->_textinput_obj)) {
            $this->_textinput_obj->convert($to, $from);
        }

        $this->_converted_data = &$this->get_vars();
    }

    //---------------------------------------------------------
    // build_for_store
    //---------------------------------------------------------
    public function build_for_store()
    {
        // BUG: sometime cannot parse
        if (isset($this->_items_obj) && is_object($this->_items_obj)) {
            $site_title = $this->get_channel_by_key('title');
            $site_link  = $this->get_channel_by_key('link');
            $this->_items_obj->build($site_title, $site_link, $this->_control_obj);
        }
    }

    //---------------------------------------------------------
    // set and get property
    //---------------------------------------------------------

    /**
     * @param $value
     */
    public function set_xml_encoding($value)
    {
        $this->_xml_encoding = $value;
    }

    /**
     * @param $value
     */
    public function set_local_encoding($value)
    {
        $this->_local_encoding = $value;
    }

    //---------------------------------------------------------
    // create
    // overload this function
    //---------------------------------------------------------

    /**
     * @return \happy_linux_rss_base_basic|\happy_linux_rss_parse_channel
     */
    public function &create_channel()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_parse_channel();

        return $obj;
    }

    /**
     * @return \happy_linux_rss_base_basic|\happy_linux_rss_parse_image
     */
    public function &create_image()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_parse_image();

        return $obj;
    }

    /**
     * @return \happy_linux_rss_base_basic|\happy_linux_rss_parse_textinput
     */
    public function &create_textinput()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_parse_textinput();

        return $obj;
    }

    /**
     * @return \happy_linux_rss_base_basic|\happy_linux_rss_parse_items
     */
    public function &create_items()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_parse_items();

        return $obj;
    }

    /**
     * @return \happy_linux_rss_base_basic|\happy_linux_rss_parse_item_single
     */
    public function &create_single_item()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_parse_item_single();

        return $obj;
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_parse_basic
//=========================================================

/**
 * Class happy_linux_rss_parse_basic
 */
class happy_linux_rss_parse_basic extends happy_linux_rss_base_basic
{
    // class
    public $_convert;

    // control
    public $_feed_type = null;
    public $_feed_version;
    public $_source_encoding;
    public $_encoding;

    public $_REPLACE_CHAR = ' ';    // space

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        // class
        // Non-static method happy_linux_convert_encoding::getInstance() should not be called statically, assuming $this from incompatible context
        $this->_convert = new happy_linux_convert_encoding();
    }

    //---------------------------------------------------------
    // convert parse to local
    //---------------------------------------------------------
    // Declaration of happy_linux_rss_parse_channel::convert() should be compatible with that of happy_linux_rss_parse_basic::convert()

    /**
     * @param $to
     * @param $from
     */
    public function convert($to, $from)
    {
        // no action
    }

    /**
     * @param $arr1
     * @param $to
     * @param $from
     * @return array|bool|string|string[]|null
     */
    public function &_convert_block(&$arr1, $to, $from)
    {
        $arr2 = &$this->_convert->convert_array($arr1, $to, $from);
        if ($this->_strings->check_in_encoding_array($to)) {
            $arr2 = $this->_strings->replace_control_array($arr2, $this->_REPLACE_CHAR);
        }

        return $arr2;
    }

    /**
     * @param $str
     * @param $to
     * @param $from
     * @return string|string[]|null
     */
    public function _convert_strings($str, $to, $from)
    {
        $str = $this->_convert->convert($str, $to, $from);
        if ($this->_strings->check_in_encoding_array($to)) {
            $str = $this->_strings->replace_control($str, $this->_REPLACE_CHAR);
        }

        return $str;
    }

    //---------------------------------------------------------
    // build
    //---------------------------------------------------------
    // Declaration of happy_linux_rss_parse_item_single::build() should be compatible with that of happy_linux_rss_parse_basic::build() in happy_linux\class\rss_parse_object.php

    /**
     * @param $site_title
     * @param $site_link
     * @param $obj
     */
    public function build($site_title, $site_link, $obj)
    {
        // no action
    }

    public function format()
    {
        // no action
    }

    /**
     * @param $key
     * @param $time_unix
     */
    public function _set_unixtime($key, $time_unix)
    {
        if ($time_unix) {
            $this->set($key, $time_unix);
            $this->set($key . '_long', formatTimestamp($time_unix, 'l'));
            $this->set($key . '_short', formatTimestamp($time_unix, 's'));
            $this->set($key . '_mysql', formatTimestamp($time_unix, 'mysql'));
        }
    }

    //---------------------------------------------------------
    // control
    //---------------------------------------------------------

    /**
     * @param $obj
     */
    public function set_control_obj($obj)
    {
        if (is_object($obj)) {
            $this->_feed_type       = $obj->get('feed_type');
            $this->_feed_version    = $obj->get('feed_version');
            $this->_source_encoding = $obj->get('source_encoding');
            $this->_encoding        = $obj->get('encoding');
        }
    }

    /**
     * @return bool
     */
    public function is_rss()
    {
        if (HAPPY_LINUX_MAGPIE_RSS == $this->_feed_type) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function is_atom()
    {
        if (HAPPY_LINUX_MAGPIE_ATOM == $this->_feed_type) {
            return true;
        }

        return false;
    }

    //---------------------------------------------------------
    // set parameter
    //---------------------------------------------------------

    /**
     * @param $val
     */
    public function set_local_encoding($val)
    {
        $this->_local_encoding = $val;
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_parse_channel
//=========================================================

/**
 * Class happy_linux_rss_parse_channel
 */
class happy_linux_rss_parse_channel extends happy_linux_rss_parse_basic
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // convert parse to local
    //---------------------------------------------------------

    /**
     * @param $to
     * @param $from
     */
    public function convert($to, $from)
    {
        $arr = &$this->_convert_block($this->get_vars(), $to, $from);
        $this->set_vars($arr);
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_parse_image
//=========================================================

/**
 * Class happy_linux_rss_parse_image
 */
class happy_linux_rss_parse_image extends happy_linux_rss_parse_basic
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // convert parse to local
    //---------------------------------------------------------

    /**
     * @param $to
     * @param $from
     */
    public function convert($to, $from)
    {
        $arr = &$this->_convert_block($this->get_vars(), $to, $from);
        $this->set_vars($arr);
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_parse_textinput
//=========================================================

/**
 * Class happy_linux_rss_parse_textinput
 */
class happy_linux_rss_parse_textinput extends happy_linux_rss_parse_basic
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // convert parse to local
    //---------------------------------------------------------

    /**
     * @param $to
     * @param $from
     */
    public function convert($to, $from)
    {
        $arr = &$this->_convert_block($this->get_vars(), $to, $from);
        $this->set_vars($arr);
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_parse_items
//=========================================================

/**
 * Class happy_linux_rss_parse_items
 */
class happy_linux_rss_parse_items extends happy_linux_rss_base_items
{
    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // cnvert
    //---------------------------------------------------------

    /**
     * @param $to
     * @param $from
     */
    public function convert($to, $from)
    {
        foreach ($this->_item_objs as $i => $obj) {
            $this->_item_objs[$i]->convert($to, $from);
        }
    }

    //---------------------------------------------------------
    // build for store
    //---------------------------------------------------------

    /**
     * @param $site_title
     * @param $site_link
     * @param $control_obj
     */
    public function build($site_title, $site_link, &$control_obj)
    {
        foreach ($this->_item_objs as $i => $obj) {
            $this->_item_objs[$i]->build($site_title, $site_link, $control_obj);
        }
    }

    //---------------------------------------------------------
    // create
    // overload this function
    //---------------------------------------------------------
    // typo: create_item_singlel

    /**
     * @return \happy_linux_rss_base_basic|\happy_linux_rss_parse_item_single
     */
    public function &create_item_single()
    {
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_parse_item_single();

        return $obj;
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_rss_parse_item_single
//=========================================================

/**
 * Class happy_linux_rss_parse_item_single
 */
class happy_linux_rss_parse_item_single extends happy_linux_rss_parse_basic
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

    public $_highlight = null;

    // nonstandard fulltext tag
    public $_use_fulltext = true;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        if (class_exists('happy_linux_highlight')) {
            $this->_highlight = happy_linux_highlight::getInstance();
            $this->_highlight->set_replace_callback('happy_linux_highlighter_by_class');
            $this->_highlight->set_class('rssc_highlight');
        }
    }

    //---------------------------------------------------------
    // convert from parse tolocal
    //---------------------------------------------------------

    /**
     * @param $to
     * @param $from
     */
    public function convert($to, $from)
    {
        $arr = $this->_convert_block($this->get_vars(), $to, $from);
        $this->set_vars($arr);
    }

    //---------------------------------------------------------
    // build for store
    //---------------------------------------------------------

    /**
     * @param $site_title
     * @param $site_link
     * @param $control_obj
     */
    public function build($site_title, $site_link, $control_obj)
    {
        $this->set_control_obj($control_obj);

        $item_orig = $this->get_vars();    // save original value
        [$enc_url, $enc_type, $enc_length] = $this->_build_enclosure();

        [$geo_lat, $geo_long] = $this->_build_geo();

        [
            $media_content_url,
            $media_content_type,
            $media_content_medium,
            $media_content_filesize,
            $media_content_width,
            $media_content_height,
        ] = $this->_build_media_content();

        [
            $media_thumbnail_url,
            $media_thumbnail_width,
            $media_thumbnail_height,
        ] = $this->_build_media_thumbnail();

        $arr = [
            'site_title'             => $site_title,
            'site_link'              => $site_link,
            'title'                  => $this->get_rss_var('title'),
            'link'                   => $this->_build_link(),
            'entry_id'               => $this->get_rss_var('id'),
            'guid'                   => $this->get_rss_var('guid'),
            'category'               => $this->_build_category(),
            'author_name'            => $this->_build_author_name(),
            'author_email'           => $this->_build_author_email(),
            'author_uri'             => $this->_build_author_uri(),
            'published_unix'         => $this->_build_published_unix(),
            'updated_unix'           => $this->_build_updated_unix(),
            'content'                => $this->_build_content(),
            'summary'                => $this->_build_summary_for_format(),
            'item_orig'              => $item_orig,

            // enclosure
            'enclosure_url'          => $enc_url,
            'enclosure_type'         => $enc_type,
            'enclosure_length'       => $enc_length,

            // geo
            'geo_lat'                => $geo_lat,
            'geo_long'               => $geo_long,

            // media
            'media_content_url'      => $media_content_url,
            'media_content_type'     => $media_content_type,
            'media_content_medium'   => $media_content_medium,
            'media_content_filesize' => $media_content_filesize,
            'media_content_width'    => $media_content_width,
            'media_content_height'   => $media_content_height,
            'media_thumbnail_url'    => $media_thumbnail_url,
            'media_thumbnail_width'  => $media_thumbnail_width,
            'media_thumbnail_height' => $media_thumbnail_height,
        ];

        $this->set_vars($arr);
    }

    //-------------------------------------------------
    // restrict feed_type
    //-------------------------------------------------
    // some feed have no link
    // ex) http://radiozzz.com/Podcast/casty/rss.xml
    //-------------------------------------------------

    /**
     * @return bool|mixed
     */
    public function _build_link()
    {
        if ($this->is_set('link')) {
            return $this->get_rss_var('link');
        } elseif ($this->is_rss() && $this->is_set('guid')) {
            return $this->get_rss_var('guid');
        } elseif ($this->is_rss() && $this->is_set('enclosure_url')) {
            return $this->get_rss_var('enclosure_url');
        }

        return false;
    }

    /**
     * @return bool|mixed
     */
    public function _build_category()
    {
        // ATOM, RSS
        if ($this->is_set('category')) {
            return $this->get_rss_var('category');
        } // ATOM 0.3, RDF
        elseif ($this->is_set('dc', 'subject')) {
            return $this->get_rss_var('dc', 'subject');
        }

        return false;
    }

    /**
     * @return bool|mixed
     */
    public function _build_author_name()
    {
        // ATOM
        if ($this->is_set('author_name')) {
            return $this->get_rss_var('author_name');
        } // RSS, RDF
        elseif ($this->is_set('dc', 'creator')) {
            return $this->get_rss_var('dc', 'creator');
        }

        return false;
    }

    /**
     * @return bool|mixed
     */
    public function _build_author_email()
    {
        // ATOM
        if ($this->is_set('author_email')) {
            return $this->get_rss_var('author_email');
        } // RSS
        elseif ($this->is_rss() && $this->is_set('author')) {
            return $this->get_rss_var('author');
        }

        return false;
    }

    /**
     * @return bool|mixed
     */
    public function _build_author_uri()
    {
        // ATOM
        if ($this->is_set('author_uri')) {
            return $this->get_rss_var('author_uri');
        } // ATOM 0.3
        elseif ($this->is_atom() && $this->is_set('author_url')) {
            return $this->get_rss_var('author_url');
        }

        return false;
    }

    /**
     * @return bool|mixed
     */
    public function _build_content()
    {
        $val = false;

        // ATOM content
        if ($this->is_atom() && $this->is_set('atom_content') && $this->get_rss_var('atom_content')) {
            $val = $this->get_rss_var('atom_content');
        } // RSS, RDF content
        elseif ($this->is_rss() && $this->is_set('content', 'encoded')) {
            $val = $this->get_rss_var('content', 'encoded');
        } // ATOM content
        elseif ($this->is_atom() && $this->is_set('content') && $this->get_rss_var('content')) {
            $val = $this->get_rss_var('content');
        } // ATOM summary
        elseif ($this->is_atom() && $this->is_set('summary')) {
            $val = $this->get_rss_var('summary');
        } // RSS, RDF fulltext
        elseif ($this->is_rss() && $this->_use_fulltext && $this->is_set('fulltext')) {
            $val = $this->get_rss_var('fulltext');
        } // RSS, RDF description
        elseif ($this->is_rss() && $this->is_set('description')) {
            $val = $this->get_rss_var('description');
        } elseif ($this->is_set('dc', 'description')) {
            $val = $this->get_rss_var('dc', 'description');
        }

        return $val;
    }

    /**
     * @return int
     */
    public function _build_published_unix()
    {
        $unixtime = 0;

        // ATOM
        if ($this->is_set('published')) {
            $unixtime = $this->get_unixtime_w3cdtf($this->get_rss_var('published'));
        } // ATOM 0.3
        elseif ($this->is_atom() && $this->is_set('issued')) {
            $unixtime = $this->get_unixtime_w3cdtf($this->get_rss_var('issued'));
        } // RSS
        elseif ($this->is_rss() && $this->is_set('pubdate')) {
            $unixtime = $this->get_unixtime_rfc822($this->get_rss_var('pubdate'));
        } // RDF
        elseif ($this->is_set('dc', 'date')) {
            $unixtime = $this->get_unixtime_w3cdtf($this->get_rss_var('dc', 'date'));
        }

        return $unixtime;
    }

    /**
     * @return int
     */
    public function _build_updated_unix()
    {
        $unixtime = 0;

        // ATOM
        if ($this->is_set('updated')) {
            $unixtime = $this->get_unixtime_w3cdtf($this->get_rss_var('updated'));
        } // ATOM 0.3
        elseif ($this->is_atom() && $this->is_set('modified')) {
            $unixtime = $this->get_unixtime_w3cdtf($this->get_rss_var('modified'));
        } // RSS
        elseif ($this->is_rss() && $this->is_set('pubdate')) {
            $unixtime = $this->get_unixtime_rfc822($this->get_rss_var('pubdate'));
        } // RDF
        elseif ($this->is_set('dc', 'date')) {
            $unixtime = $this->get_unixtime_w3cdtf($this->get_rss_var('dc', 'date'));
        }

        return $unixtime;
    }

    /**
     * @return null|array
     */
    public function _build_enclosure()
    {
        $url    = null;
        $type   = null;
        $length = null;

        // RSS
        if ($this->is_rss()) {
            [$url, $type, $length] = $this->_get_enclosure_list();
        }

        return [$url, $type, $length];
    }

    //-------------------------------------------------
    //  <geo:point>
    //    <geo:lat>lat</geo:lat>
    //    <geo:long>long</geo:long>
    //  </geo:point>
    //
    //  <geo:lat>lat</geo:lat>
    //  <geo:long>long</geo:long>
    //
    //  <georss:point>lat long</georss:point>
    //-------------------------------------------------

    /**
     * @return array
     */
    public function _build_geo()
    {
        $lat  = null;
        $long = null;

        // <geo:point>
        if ($this->is_set('geo', 'point_lat')
            || $this->is_set('geo', 'point_long')) {
            $lat  = $this->get_rss_var('geo', 'point_lat');
            $long = $this->get_rss_var('geo', 'point_long');
            // <geo:lat>lat</geo:lat>
        } elseif ($this->is_set('geo', 'lat')
                  || $this->is_set('geo', 'long')) {
            $lat  = $this->get_rss_var('geo', 'lat');
            $long = $this->get_rss_var('geo', 'long');
            // <georss:point>lat long</georss:point>
        } elseif ($this->is_set('georss', 'point')) {
            $lat_long = $this->get_rss_var('georss', 'point');

            // BUG: NOT parse http://maps.google.co.jp/maps/
            $arr = $this->_str_to_array($lat_long, ' ');
            if (isset($arr[0]) && isset($arr[1])) {
                $lat  = $arr[0];
                $long = $arr[1];
            }
        }

        return [$lat, $long];
    }

    /**
     * @return array
     */
    public function _build_media_content()
    {
        $url      = null;
        $type     = null;
        $medium   = null;
        $filesize = null;
        $width    = null;
        $height   = null;

        $content = $this->get_rss_var('media', 'content');
        if (isset($content['url'])) {
            $url = $content['url'];
        }
        if (isset($content['type'])) {
            $type = $content['type'];
        }
        if (isset($content['medium'])) {
            $medium = $content['medium'];
        }
        if (isset($content['filesize'])) {
            $filesize = $content['filesize'];
        }
        if (isset($content['width'])) {
            $width = $content['width'];
        }
        if (isset($content['height'])) {
            $height = $content['height'];
        }

        return [$url, $type, $medium, $filesize, $width, $height];
    }

    /**
     * @return null[]
     */
    public function _build_media_thumbnail()
    {
        $url    = null;
        $width  = null;
        $height = null;

        $thumbnail = $this->get_rss_var('media', 'thumbnail');
        if (isset($thumbnail[0]['url'])) {
            $url = $thumbnail[0]['url'];
        }
        if (isset($thumbnail[0]['width'])) {
            $width = $thumbnail[0]['width'];
        }
        if (isset($thumbnail[0]['height'])) {
            $height = $thumbnail[0]['height'];
        }

        return [$url, $width, $height];
    }

    //-------------------------------------------------
    // utility
    //-------------------------------------------------

    /**
     * @param $str
     * @param $pattern
     * @return array
     */
    public function _str_to_array($str, $pattern)
    {
        $arr1 = explode($pattern, $str);
        $arr2 = [];
        foreach ($arr1 as $v) {
            $v = trim($v);
            if ('' == $v) {
                continue;
            }
            $arr2[] = $v;
        }

        return $arr2;
    }

    // --- class end ---
}
