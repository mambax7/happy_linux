<?php
// $Id: build_rss.php,v 1.19 2009/02/25 08:57:04 ohwada Exp $

// 2009-02-20 K.OHWADA
// media_content_medium

// 2008-01-10 K.OHWADA
// set_media_group_default()

// 2007-11-11 K.OHWADA
// happy_linux_rss_default

// 2007-10-10 K.OHWADA
// divid to happy_linux_build_rss
// divid to happy_linux_date

// 2007-08-31 K.OHWADA
// BUG 4697: Notice [PHP]: Only variables should be assigned by reference

// 2007-08-01 K.OHWADA
// multibyte.php

// 2007-07-16 K.OHWADA
// georss
// media rss

// 2007-05-12 K.OHWADA
// for XC 2.1
// get_default_site_url() etc
// clear_compiled_tpl()

// 2007-03-01 K.OHWADA
// $_SITE_AUTHOR_NAME_DEFAULT

// 2006-09-20 K.OHWADA
// this is new file
// porting from rssc_build_base

//=========================================================
// Happy Linux Framework Module
// 2006-09-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// spec
// xml:  http://www.w3.org/TR/REC-xml/
// rdf:  http://web.resource.org/rss/1.0/spec
// rss:  http://blogs.law.harvard.edu/tech/rss
// atom: http://www.mnot.net/drafts/draft-nottingham-atom-format-02.html
// dc:      http://web.resource.org/rss/1.0/modules/dc/ http://dublincore.org/documents/
// content: http://web.resource.org/rss/1.0/modules/content/
// geo:     http://www.w3.org/2003/01/geo/wgs84_pos
// georss:  http://georss.org/
// media:   http://search.yahoo.com/mrss
//---------------------------------------------------------

//=========================================================
// class builder base
//=========================================================
class happy_linux_build_rss extends happy_linux_build_cache
{

    // constant
    public $_CACHE_ID_GUEST = HAPPY_LINUX_RSS_CACHE_ID_GUEST;
    public $_CACHE_ID_USER  = HAPPY_LINUX_RSS_CACHE_ID_USER;

    public $_MODULE_ID_DEFUALT   = 1;
    public $_HEADER_BUILD        = 'Content-Type:text/xml;  charset=utf-8';
    public $_HEADER_VIEW         = 'Content-Type:text/html; charset=utf-8';
    public $_RSS_DOCS            = 'http://backend.userland.com/rss/';
    public $_MAX_ITEMS           = 20;
    public $_CACHE_TIME_ONE_HOUR = 3600;   // one hour

    // replace control code
    public $_FLAG_REPLACE_CONTROL_CODE = true;
    public $_REPLACE_CHAR              = ' ';   // space

    // class instance
    public $_default;
    public $_system;
    public $_strings;
    public $_convert;
    public $_image;

    // http://web.resource.org/rss/1.0/spec#s5.5.3
    // (Suggested) Maximum Length: 500
    public $_max_summary = 500;

    public $_xoops_uid = 0;

    // site information
    public $_site_url;
    public $_site_name;
    public $_site_desc;
    public $_site_tag;
    public $_site_year;
    public $_site_copyright;
    public $_site_link_self;
    public $_site_author_name;
    public $_site_author_email;
    public $_site_author_uri;

    public $_site_image_url    = '';
    public $_site_image_link   = '';
    public $_site_image_title  = '';
    public $_site_image_width  = '';
    public $_site_image_height = '';

    // set param
    public $_channel               = array();
    public $_items                 = array();
    public $_view_title            = 'view cache';
    public $_view_goto_title       = 'goto index';
    public $_view_goto_url         = null;
    public $_flag_default_timezone = false;

    public $_cache_time_guest = 0;
    public $_cache_time_user  = 0;
    public $_flag_force_guest = false;
    public $_flag_force_user  = false;

    public $_media_group_default = null;

    // local variable
    public $_count_line = 1;

    // override
    public $_GENERATOR      = 'XOOPS Happy Linux';
    public $_GENERATOR_URI  = 'http://linux2.ohwada.net/';
    public $_CATEGORY       = 'Happy Linux';
    public $_TITLE_RDF      = 'RDF Feeds';
    public $_TITLE_RSS      = 'RSS Feeds';
    public $_TITLE_ATOM     = 'ATOM Feeds';
    public $_TITLE_OTHER    = 'Feeds';
    public $_TEMPLATE_RDF   = null;
    public $_TEMPLATE_RSS   = null;
    public $_TEMPLATE_ATOM  = null;
    public $_TEMPLATE_OTHER = null;
    public $_MODE_BUILD     = 'other';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        // class instance
        $this->_default = happy_linux_rss_default::getInstance();
        $this->_system  = happy_linux_system::getInstance();
        $this->_strings = happy_linux_strings::getInstance();
        $this->_convert = happy_linux_convert_encoding::getInstance();
        $this->_image   = happy_linux_image_size::getInstance();
        $this->_date    = happy_linux_date::getInstance();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_build_rss();
        }
        return $instance;
    }

    //=========================================================
    // public
    //=========================================================
    public function build_rss()
    {
        // header
        happy_linux_http_output('pass');
        header($this->_HEADER_BUILD);

        $cache_id   = $this->_CACHE_ID_GUEST;
        $cache_time = $this->_cache_time_guest;
        $flag_force = $this->_flag_force_guest;

        if ($this->_system->is_user()) {
            $cache_id   = $this->_CACHE_ID_USER;
            $cache_time = $this->_cache_time_user;
            $flag_force = $this->_flag_force_user;
        }

        echo $this->build_cache_by_cache_id($cache_id, $this->_get_template(), $cache_time, $flag_force);
    }

    public function view_rss()
    {
        $template = $this->_get_template();
        if (empty($template)) {
            echo '<span style="color: #ff0000;">No Template</span><br />' . "\n";
            return;
        }

        // header
        header($this->_HEADER_VIEW);

        $xml   = $this->build_cache($this->_get_template(), 0, true);
        $body  = htmlspecialchars($xml, ENT_QUOTES);
        $title = $this->_get_title();

        $goto = '';
        if ($this->_view_goto_url && $this->_view_goto_title) {
            $goto = '<a href="' . $this->_view_goto_url . '">';
            $goto .= $this->_utf8($this->_view_goto_title) . "</a>\n";
        }

        ?>
        <html>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
            <title><?php echo $title;
                ?></title>
        </head>
        <body>
        <h3><?php echo $title;
            ?></h3>
        This is debug mode <br/> <br/>
        <hr/>
        <pre>
<?php echo $body;
?>
</pre>
        <hr/>
        <?php echo $goto;
        ?>
        </body>
        </html>
        <?php

    }

    public function rebuild_rss()
    {
        echo $this->rebuild_cache($this->_get_template());
    }

    public function clear_all_guest_cache()
    {
        if ($this->_TEMPLATE_RDF) {
            $this->clear_cache_by_cache_id($this->_CACHE_ID_GUEST, $this->_TEMPLATE_RDF);
        }
        if ($this->_TEMPLATE_RSS) {
            $this->clear_cache_by_cache_id($this->_CACHE_ID_GUEST, $this->_TEMPLATE_RSS);
        }
        if ($this->_TEMPLATE_ATOM) {
            $this->clear_cache_by_cache_id($this->_CACHE_ID_GUEST, $this->_TEMPLATE_ATOM);
        }
        if ($this->_TEMPLATE_OTHER) {
            $this->clear_cache_by_cache_id($this->_CACHE_ID_GUEST, $this->_TEMPLATE_OTHER);
        }
    }

    //---------------------------------------------------------
    // utlity
    //---------------------------------------------------------
    public function build_site_copyright($author = null, $year = null)
    {
        if (empty($author)) {
            $author = $this->get_default_site_author_name();
        }
        if (empty($year)) {
            $year = $this->get_default_site_year();
        }
        $val = 'Copyright (c) ' . $year . ', ' . $author;
        return $val;
    }

    public function build_entry_id($mid = null, $aid = null, $site_tag = null, $year = null)
    {
        if (empty($mid)) {
            $mid = $this->_MODULE_ID_DEFUALT;
        }
        if (empty($aid)) {
            $aid = $this->_count_line;
        }
        if (empty($site_tag)) {
            $site_tag = $this->_site_tag;
        }
        if (empty($year)) {
            $year = $this->_site_year;
        }
        $val = "tag:$site_tag,$year://1.$mid.$aid";;
        return $val;
    }

    //---------------------------------------------------------
    // site information
    //---------------------------------------------------------
    public function get_default_site_url()
    {
        return $this->_default->get_default_site_url();
    }

    public function get_default_site_name()
    {
        return $this->_default->get_default_site_name();
    }

    public function get_default_site_desc()
    {
        return $this->_default->get_default_site_desc();
    }

    public function get_default_site_tag()
    {
        return $this->_default->get_default_site_tag();
    }

    public function get_default_site_link_self()
    {
        return $this->_default->get_default_site_link_self();
    }

    public function get_default_site_author_name()
    {
        return $this->_default->get_default_site_author_name();
    }

    public function get_default_site_author_email()
    {
        return $this->_default->get_default_site_author_email();
    }

    public function get_default_site_author_uri()
    {
        return $this->_default->get_default_site_author_uri();
    }

    public function parse_site_tag($url)
    {
        return $this->_default->parse_site_tag($url);
    }

    //---------------------------------------------------------
    // site image
    //---------------------------------------------------------
    public function get_default_site_image_logo()
    {
        return $this->_default->get_default_site_image_logo();
    }

    public function get_site_image_width_max()
    {
        return $this->_default->get_site_image_width_max();
    }

    public function get_site_image_height_max()
    {
        return $this->_default->get_site_image_height_max();
    }

    public function get_site_image_size($logo)
    {
        return $this->_default->get_site_image_size($logo);
    }

    public function check_site_image_width($width)
    {
        return $this->_default->check_site_image_width($width);
    }

    public function check_site_image_height($height)
    {
        return $this->_default->check_site_image_height($height);
    }

    // --------------------------------------------------------
    // set param
    // --------------------------------------------------------
    public function set_site_url($value)
    {
        $this->_site_url = $value;
    }

    public function set_site_name($value)
    {
        $this->_site_name = $value;
    }

    public function set_site_desc($value)
    {
        $this->_site_desc = $value;
    }

    public function set_site_tag($value)
    {
        $this->_site_tag = $value;
    }

    public function set_site_year($value)
    {
        $this->_site_year = $value;
    }

    public function set_site_copyright($value)
    {
        $this->_site_copyright = $value;
    }

    public function set_site_link_self($value)
    {
        $this->_site_link_self = $value;
    }

    public function set_site_author_name($value)
    {
        $this->_site_author_name = $value;
    }

    public function set_site_author_email($value)
    {
        $this->_site_author_email = $value;
    }

    public function set_site_author_uri($value)
    {
        $this->_site_author_uri = $value;
    }

    public function set_site_image_url($value)
    {
        $this->_site_image_url = $value;
    }

    public function set_site_image_link($value)
    {
        $this->_site_image_link = $value;
    }

    public function set_site_image_title($value)
    {
        $this->_site_image_title = $value;
    }

    public function set_site_image_width($value)
    {
        $this->_site_image_width = (int)$value;
    }

    public function set_site_image_height($value)
    {
        $this->_site_image_height = (int)$value;
    }

    public function set_max_summary($value)
    {
        $this->_max_summary = (int)$value;
    }

    public function set_channel($value)
    {
        $this->_channel = $value;
    }

    public function set_items($value)
    {
        $this->_items = $value;
    }

    public function set_view_title($value)
    {
        $this->_view_title = $value;
    }

    public function set_view_goto_title($value)
    {
        $this->_view_goto_title = $value;
    }

    public function set_view_goto_url($value)
    {
        $this->_view_goto_url = $value;
    }

    public function set_mode($val)
    {
        switch ($val) {
            case 'rdf':
            case 'atom':
            case 'rss':
                $this->_MODE_BUILD = $val;
                break;

            default:
                $this->_MODE_BUILD = 'other';
                break;
        }
    }

    public function set_header_build($value)
    {
        $this->_HEADER_BUILD = $value;
    }

    public function set_generator($value)
    {
        $this->_GENERATOR = $value;
    }

    public function set_generator_uri($value)
    {
        $this->_GENERATOR_URI = $value;
    }

    public function set_category($value)
    {
        $this->_CATEGORY = $value;
    }

    public function set_template_rdf($value)
    {
        $this->_TEMPLATE_RDF = $value;
    }

    public function set_template_rss($value)
    {
        $this->_TEMPLATE_RSS = $value;
    }

    public function set_template_atom($value)
    {
        $this->_TEMPLATE_ATOM = $value;
    }

    public function set_template_other($value)
    {
        $this->_TEMPLATE_OTHER = $value;
    }

    public function set_rdf_template($value)
    {
        $this->_TEMPLATE_RDF = $value;
    }

    public function set_rss_template($value)
    {
        $this->_TEMPLATE_RSS = $value;
    }

    public function set_atom_template($value)
    {
        $this->_TEMPLATE_ATOM = $value;
    }

    public function set_other_template($value)
    {
        $this->_TEMPLATE_OTHER = $value;
    }

    public function set_title_rdf($value)
    {
        $this->_TITLE_RDF = $value;
    }

    public function set_title_rss($value)
    {
        $this->_TITLE_RSS = $value;
    }

    public function set_title_atom($value)
    {
        $this->_TITLE_ATOM = $value;
    }

    public function set_title_other($value)
    {
        $this->_TITLE_OTHER = $value;
    }

    public function set_rdf_title($value)
    {
        $this->_TITLE_RDF = $value;
    }

    public function set_rss_title($value)
    {
        $this->_TITLE_RSS = $value;
    }

    public function set_atom_title($value)
    {
        $this->_TITLE_ATOM = $value;
    }

    public function set_other_title($value)
    {
        $this->_TITLE_OTHER = $value;
    }

    public function set_flag_default_timezone($val)
    {
        $this->_flag_default_timezone = (bool)$val;
    }

    public function set_cache_time_guest($value)
    {
        $this->_cache_time_guest = (int)$value;
    }

    public function set_cache_time_user($value)
    {
        $this->_cache_time_user = (int)$value;
    }

    public function set_flag_force_guest($value)
    {
        $this->_flag_force_guest = (bool)$value;
    }

    public function set_flag_force_user($value)
    {
        $this->_flag_force_user = (bool)$value;
    }

    public function set_cache_id_guest($value)
    {
        $this->_CACHE_ID_GUEST = $value;
    }

    public function set_cache_id_user($value)
    {
        $this->_CACHE_ID_USER = $value;
    }

    public function set_media_group_default($value)
    {
        $this->_media_group_default = $value;
    }

    //=========================================================
    // private
    //=========================================================
    public function _init_rss()
    {
        $this->_xoops_uid = $this->_system->get_uid();
        $this->_init_site_information();
        $this->_set_default_timezone();
    }

    public function _init_site_information()
    {
        $this->set_site_name($this->get_default_site_name());
        $this->set_site_desc($this->get_default_site_desc());
        $this->set_site_url($this->get_default_site_url());
        $this->set_site_tag($this->get_default_site_tag());
        $this->set_site_year($this->get_default_site_year());
        $this->set_site_link_self($this->get_default_site_link_self());
        $this->set_site_author_name($this->get_default_site_author_name());
        $this->set_site_author_email($this->get_default_site_author_email());
        $this->set_site_author_uri($this->get_default_site_author_uri());
        $this->set_site_copyright($this->build_site_copyright());
    }

    public function _get_title()
    {
        switch ($this->_MODE_BUILD) {
            case 'rss':
                $ret = $this->_TITLE_RSS;
                break;

            case 'rdf':
                $ret = $this->_TITLE_RDF;
                break;

            case 'atom':
                $ret = $this->_TITLE_ATOM;
                break;

            case 'other':
            default:
                $ret = $this->_TITLE_OTHER;
                break;
        }
        return $ret;
    }

    // --------------------------------------------------------
    // meke title
    // --------------------------------------------------------
    public function _build_xml_title($title, $flag_char = 1, $flag_numeric = 1)
    {
        if ($flag_char) {
            $title = $this->_strip_html_entity_char($title);
        }

        if ($flag_numeric) {
            $title = $this->_strip_html_entity_numeric($title);
        }

        $title = $this->_xml_htmlspecialchars($title);

        return $title;
    }

    // --------------------------------------------------------
    // meke content
    // --------------------------------------------------------
    public function _build_xml_content($content, $flag_control = 1, $flag_undo = 1)
    {
        if ($flag_control) {
            $content = $this->_strings->strip_control($content);
        }

        if ($flag_undo) {
            $content = $this->_undo_html_special_chars($content);
        }

        // not sanitize
        $cont = $this->_convert_cdata($content);

        return $cont;
    }

    public function _convert_cdata($text)
    {
        $text = preg_replace('/]]>/', ']]&gt;', $text);
        return $text;
    }

    // --------------------------------------------------------
    // meke summary
    // --------------------------------------------------------
    public function _build_xml_summary($sum, $flag_char = 1, $flag_numeric = 1, $flag_control = 1, $flag_undo = 1, $flag_return = 1, $flag_tab = 1, $flag_style = 1, $flag_space = 1)
    {
        if ($flag_char) {
            $sum = $this->_strip_html_entity_char($sum);
        }

        if ($flag_numeric) {
            $sum = $this->_strip_html_entity_numeric($sum);
        }

        if ($flag_control) {
            $sum = $this->_strings->strip_control($sum);
        }

        if ($flag_undo) {
            $sum = $this->_undo_html_special_chars($sum);
        }

        if ($flag_return) {
            $sum = $this->_strings->strip_return($sum);
        }

        if ($flag_tab) {
            $sum = $this->_strings->strip_tab($sum);
        }

        if ($flag_style) {
            $sum = $this->_strings->strip_style_tag($sum);
        }

        $sum = strip_tags($sum);

        if ($flag_space) {
            $sum = $this->_strings->strip_space($sum);
        }

        $sum = $this->_strings->shorten_text($sum, $this->_max_summary);
        $sum = $this->_xml_htmlspecialchars($sum);

        return $sum;
    }

    // --------------------------------------------------------
    // htmlspecialchars
    // http://www.w3.org/TR/REC-xml/#dt-markup
    // http://www.fxis.co.jp/xmlcafe/tmp/rec-xml.html#dt-markup
    //   &  -> &amp;    // without html entity
    //   <  -> &lt;
    //   >  -> &gt;
    //   "  -> &quot;
    //   '  -> &apos;
    // --------------------------------------------------------
    public function _xml($text)
    {
        $ret = $this->_xml_htmlspecialchars_strict($text);
        return $ret;
    }

    public function _xml_url($text)
    {
        $ret = $this->_xml_htmlspecialchars_url($text);
        return $ret;
    }

    public function _xml_htmlspecialchars($text)
    {
        $text = htmlspecialchars($text);
        $text = preg_replace("/'/", '&apos;', $text);
        return $text;
    }

    public function _xml_htmlspecialchars_strict($text)
    {
        $text = $this->_strip_html_entity_char($text);
        $text = $this->_xml_htmlspecialchars($text);
        return $text;
    }

    public function _xml_htmlspecialchars_url($text)
    {
        $text = preg_replace('/&amp;/sU', '&', $text);
        $text = $this->_strip_html_entity_char($text);
        $text = $this->_xml_htmlspecialchars($text);
        return $text;
    }

    // --------------------------------------------------------
    // undo XOOPS HtmlSpecialChars
    //   &lt;   -> <
    //   &gt;   -> >
    //   &quot; -> "
    //   &#039; -> '
    //   &amp;  -> &
    //   &amp;nbsp; -> &nbsp;
    // --------------------------------------------------------
    public function _undo_html_special_chars($text)
    {
        $text = preg_replace('/&gt;/i', '>', $text);
        $text = preg_replace('/&lt;/i', '<', $text);
        $text = preg_replace('/&quot;/i', '"', $text);
        $text = preg_replace('/&#039;/i', "'", $text);
        $text = preg_replace('/&amp;nbsp;/i', '&nbsp;', $text);
        return $text;
    }

    // --------------------------------------------------------
    // undo html entities
    //   &amp;abc;  -> &abc;
    // --------------------------------------------------------
    public function _undo_html_entity_char($text)
    {
        $ret = preg_replace('/&amp;([0-9a-zA-z]+);/sU', '&\\1;', $text);
        return $ret;
    }

    // --------------------------------------------------------
    // undo html entities
    //   &amp;#123; -> &#123;
    // --------------------------------------------------------
    public function _undo_html_entity_numeric($text)
    {
        $ret = preg_replace('/&amp;#([0-9a-fA-F]+);/sU', '&#\\1;', $text);
        return $ret;
    }

    // --------------------------------------------------------
    // strip html entities
    //   &abc; -> ' '
    // --------------------------------------------------------
    public function _strip_html_entity_char($text)
    {
        $ret = preg_replace('/&[0-9a-zA-z]+;/sU', ' ', $text);
        return $ret;
    }

    // --------------------------------------------------------
    // strip html entities
    //   &#123; -> ' '
    // --------------------------------------------------------
    public function _strip_html_entity_numeric($text)
    {
        $ret = preg_replace('/&amp;#([0-9a-fA-F]+);/sU', '&#\\1;', $text);
        return $ret;
    }

    //-----------------------------------------------
    // use convert class
    //-----------------------------------------------
    public function _utf8($str)
    {
        $str = happy_linux_convert_to_utf8($str, _CHARSET);
        if ($this->_FLAG_REPLACE_CONTROL_CODE) {
            $str = $this->_strings->replace_control($str, $this->_REPLACE_CHAR);
        }
        return $str;
    }

    //=========================================================
    // assign to template
    //=========================================================

    //---------------------------------------------------------
    // http://web.resource.org/rss/1.0/spec
    // required paramter
    // channel elements
    //   - title
    //   - link
    // item elements
    //   - title
    //   - link
    //---------------------------------------------------------
    public function _assign_rdf(&$tpl)
    {
        $channel_list = array(
            'title',
            'link',
            'description',
            'dc_language',
            'dc_date'
        );

        $item_list = array(
            'link',
            'title',
            'description',
            'dc_subject',
            'dc_creator',
            'dc_date',
            'content_encoded'
        );

        $channel =& $this->_build_rdf_channel();
        $items   =& $this->_build_rdf_items();

        $tpl->assign('xml_lang', $this->_utf8($channel['xml_lang']));

        $this->_assign_channel($tpl, $channel_list, $channel, 'channel');
        $this->_assign_items($tpl, $item_list, $items, 'items');
    }

    public function &_build_rdf_channel()
    {
        $ret =& $this->_build_common_channel();
        return $ret;
    }

    public function &_build_rdf_items()
    {
        $arr               = array();
        $this->_count_line = 1;
        $items             =& $this->_get_items();

        if (is_array($items) && count($items)) {
            foreach ($items as $item) {
                $arr[] = $this->_build_rdf_item($item);
                $this->_count_line++;
            }
        }

        return $arr;
    }

    public function &_build_rdf_item_default(&$item)
    {
        $ret =& $this->_build_common_item($item);
        return $ret;
    }

    //---------------------------------------------------------
    // http://blogs.law.harvard.edu/tech/rss
    // required paramter
    // channel elements
    //   - title
    //   - link
    //   - description
    // item elements
    //   - title
    //   - link
    //   - description
    //---------------------------------------------------------
    public function _assign_rss(&$tpl)
    {
        $channel_list = array(
            'title',
            'link',
            'docs',
            'generator',
            'category',
            'copyright',
            'language',
            'webmaster',
            'lastbuild',
            'pubdate',
            'description',
            'managingeditor',
            'atom_link'
        );

        $image_list = array(
            'url',
            'width',
            'height',
            'title',
            'link'
        );

        $item_list = array(
            'link',
            'guid',
            'title',
            'description',
            'category',
            'pubdate',
            'dc_creator',
            'content_encoded',
            'geo_lat',
            'geo_long',
            'georss_point',
            'media_group',
            'media_title',
            'media_description',
            'media_text',
            'media_keywords',
            'media_credit',
            'media_content_url',
            'media_content_type',
            'media_content_medium',
            'media_content_filesize',
            'media_content_height',
            'media_content_width',
            'media_thumbnail_url',
            'media_thumbnail_height',
            'media_thumbnail_width',
            'media_thumbnail_medium_url',
            'media_thumbnail_medium_height',
            'media_thumbnail_medium_width',
            'media_thumbnail_large_url',
            'media_thumbnail_large_height',
            'media_large_thumbnail_width'
        );

        $channel =& $this->_build_rss_channel();
        $items   =& $this->_build_rss_items();

        $this->_assign_channel($tpl, $channel_list, $channel);
        $this->_assign_image($tpl, $image_list, $channel, 'channel');
        $this->_assign_items($tpl, $item_list, $items, 'items');
    }

    public function &_build_rss_channel()
    {
        $channel =& $this->_build_common_channel();

        $channel['pubdate'] = $channel['date_rfc822'];

        // lastbuild
        $updated_unix = $this->_get_last_updated_unix($this->_get_items());
        if ($updated_unix) {
            $channel['lastbuild'] = $this->_date_rfc822($updated_unix);
        } else {
            $channel['lastbuild'] = '';
        }

        return $channel;
    }

    public function &_build_rss_items()
    {
        $arr               = array();
        $this->_count_line = 1;
        $items             =& $this->_get_items();

        if (is_array($items) && count($items)) {
            foreach ($items as $item) {
                $arr[] = $this->_build_rss_item($item);
                $this->_count_line++;
            }
        }

        return $arr;
    }

    public function &_build_rss_item_default(&$item)
    {
        $arr =& $this->_build_common_item($item);

        $arr['guid']         = $this->_get_item_guid($arr);
        $arr['pubdate']      = $this->_get_item_pubdate($arr);
        $arr['georss_point'] = $this->_get_item_georss_point($arr);
        $arr['media_group']  = $this->_get_item_media_group($arr);

        return $arr;
    }

    //---------------------------------------------------------
    // http://www.mnot.net/drafts/draft-nottingham-atom-format-02.html
    // required paramter
    // feed elements
    //   - id
    //   - title
    //   - updated
    //   - author name
    // entry elements
    //   - id
    //   - title
    //   - updated
    //   - author name
    //   - summary or content
    //---------------------------------------------------------
    public function _assign_atom(&$tpl)
    {
        $channel_list = array(
            'title',
            'link_alt',
            'link_self',
            'id',
            'rights',
            'updated',
            'generator',
            'generator_uri',
            'author_name',
            'author_uri',
            'author_email'
        );

        $item_list = array(
            'link',
            'id',
            'title',
            'content',
            'summary',
            'category',
            'updated',
            'published',
            'author_name',
            'author_uri',
            'author_email'
        );

        $channel =& $this->_build_atom_channel();
        $entrys  =& $this->_build_atom_entrys();

        $tpl->assign('xml_lang', $this->_utf8($channel['xml_lang']));

        $this->_assign_channel($tpl, $channel_list, $channel, 'feed');
        $this->_assign_items($tpl, $item_list, $entrys, 'entrys');
    }

    public function &_build_atom_channel()
    {
        $channel =& $this->_build_common_channel();

        // atom id
        $site_id       = 'tag:' . $this->_site_tag . ',' . $this->_site_year . '://1';
        $channel['id'] = $this->_xml($site_id);

        // date
        $channel['updated'] = $channel['date_iso8601'];

        return $channel;
    }

    public function &_build_atom_entrys()
    {
        $arr               = array();
        $this->_count_line = 1;
        $items             =& $this->_get_items();

        if (is_array($items) && count($items)) {
            foreach ($items as $entry) {
                $arr[] = $this->_build_atom_entry($entry);
                $this->_count_line++;
            }
        }

        return $arr;
    }

    public function &_build_atom_entry_default(&$entry)
    {
        $arr =& $this->_build_common_item($entry);

        // title
        $arr['title'] = $this->_build_xml_title($entry['title'], 0, 0);

        // must content or summary
        if (empty($arr['content']) && empty($arr['summary'])) {
            $arr['summary'] = $arr['title'];
        }

        // must author_name
        if (empty($arr['author_name'])) {
            $arr['author_name']  = $this->_xml($this->_site_author_name);
            $arr['author_uri']   = '';
            $arr['author_email'] = '';
        }

        // atom id
        if ($arr['entry_id']) {
            $arr['id'] = $arr['entry_id'];
        } else {
            $arr['id'] = $this->_xml($this->build_entry_id());
        }

        // date
        $arr['updated']   = $arr['updated_iso8601'];
        $arr['published'] = $arr['published_iso8601'];

        return $arr;
    }

    //---------------------------------------------------------
    // common
    //---------------------------------------------------------
    // BUG 4697: Notice [PHP]: Only variables should be assigned by reference
    public function &_build_common_channel()
    {
        $site_url_xml          = $this->_xml_url($this->_site_url);
        $site_author_email_xml = $this->_xml($this->_site_author_email);
        $site_copyright_xml    = $this->_xml($this->_site_copyright);
        $language_xml          = $this->_xml(_LANGCODE);

        $time             = time();
        $date_rfc822_xml  = $this->_xml($this->_date_rfc822($time));
        $date_iso8601_xml = $this->_xml($this->_date_iso8601($time));

        $site_link_self_xml = $this->_xml_url($this->_site_link_self);
        $site_email_xml     = $this->_xml($this->_build_site_email());

        $ret = array(
            //url
            'link'           => $site_url_xml,
            'link_alt'       => $site_url_xml,
            'author_uri'     => $site_url_xml,
            'link_self'      => $site_link_self_xml,
            'atom_link'      => $site_link_self_xml,
            'image_url'      => $this->_xml_url($this->_site_image_url),
            'image_link'     => $this->_xml_url($this->_site_image_link),

            // text
            'generator_uri'  => $this->_xml_url($this->_GENERATOR_URI),
            'language'       => $language_xml,
            'copyright'      => $site_copyright_xml,
            'rights'         => $site_copyright_xml,
            'author_email'   => $site_author_email_xml,
            'webmaster'      => $site_email_xml,
            'managingeditor' => $site_email_xml,
            'title'          => $this->_xml($this->_site_name),
            'description'    => $this->_xml($this->_site_desc),
            'generator'      => $this->_xml($this->_GENERATOR),
            'category'       => $this->_xml($this->_CATEGORY),
            'docs'           => $this->_xml($this->_RSS_DOCS),
            'author_name'    => $this->_xml($this->_site_author_name),
            'author_uri'     => $this->_xml($this->_site_author_uri),
            'image_width'    => $this->_xml($this->_site_image_width),
            'image_height'   => $this->_xml($this->_site_image_height),
            'image_title'    => $this->_xml($this->_site_image_title),

            // time
            'date_unix'      => $time,
            'date_rfc822'    => $date_rfc822_xml,
            'date_iso8601'   => $date_iso8601_xml,

            'xml_lang'    => $language_xml,
            'dc_language' => $language_xml,
            'dc_date'     => $date_iso8601_xml,
        );

        return $ret;
    }

    //---------------------------------------------------------
    // <webMaster>luksa@dallas.example.com (Frank Luksa)</webMaster>
    // <managingEditor>luksa@dallas.example.com (Frank Luksa)</managingEditor>
    //---------------------------------------------------------
    public function _build_site_email()
    {
        $text = $this->_site_author_email . ' (' . $this->_site_author_name . ')';
        return $text;
    }

    public function _get_last_updated_unix($items)
    {
        $time = false;
        if (isset($items[0]['updated_unix'])) {
            $time = $items[0]['updated_unix'];
        }
        return $time;
    }

    public function _get_item_guid($item)
    {
        $val = null;
        if (isset($item['guid']) && $item['guid']) {
            $val = $item['guid'];
        } elseif (isset($item['link'])) {
            $val = $item['link'];
        }
        return $val;
    }

    public function _get_item_pubdate($item)
    {
        $val = null;
        if (isset($item['pubdate']) && $item['pubdate']) {
            $val = $item['pubdate'];
        } elseif (isset($item['date_rfc822'])) {
            $val = $item['date_rfc822'];
        }
        return $val;
    }

    public function _get_item_georss_point($item)
    {
        $val = null;
        if (isset($item['georss_point']) && $item['georss_point']) {
            $val = $item['georss_point'];
        } elseif (isset($item['geo_lat']) && ($item['geo_lat'] != '')
                  && isset($item['geo_long'])
                  && ($item['geo_long'] != '')
        ) {
            $val = $item['geo_lat'] . ' ' . $item['geo_long'];
        }
        return $val;
    }

    public function _get_item_media_group($item)
    {
        $val = null;
        if (isset($item['media_group']) && $item['media_group']) {
            $val = $item['media_group'];
        } elseif ((isset($item['media_content_url']) && $item['media_content_url'])
                  || (isset($item['media_thumbnail_url']) && $item['media_thumbnail_url'])
        ) {
            $val = $this->_media_group_default;
        }
        return $val;
    }

    public function _assign_channel(&$tpl, &$list, &$channel, $prefix = 'channel')
    {
        foreach ($list as $name) {
            if (isset($channel[$name])) {
                $key = $prefix . '_' . $name;
                $tpl->assign($key, $this->_utf8($channel[$name]));
            }
        }
    }

    public function _assign_image(&$tpl, &$list, &$channel)
    {
        foreach ($list as $name) {
            $key = 'image_' . $name;
            if (isset($channel[$key])) {
                $tpl->assign($key, $this->_utf8($channel[$key]));
            }
        }
    }

    public function _assign_items(&$tpl, &$list, &$items, $tpl_key = 'items')
    {
        foreach ($items as $item) {
            $tpl->append($tpl_key, $this->_utf8_item($tpl, $list, $item));
        }
    }

    public function _utf8_item(&$tpl, &$list, &$item)
    {
        $arr = array();
        foreach ($list as $name) {
            if (isset($item[$name])) {
                $arr[$name] = $this->_utf8($item[$name]);
            }
        }
        return $arr;
    }

    //---------------------------------------------------------
    // class date
    //---------------------------------------------------------
    public function _set_default_timezone()
    {
        if ($this->_flag_default_timezone) {
            $this->_date->set_default_timezone_by_xoops_default_timezone();
        }
    }

    public function _date_rfc822($time)
    {
        return $this->_date->date_rfc822_user($time);
    }

    public function _date_iso8601($time)
    {
        return $this->_date->date_iso8601_user($time);
    }

    // for lower compatible: caller whatsnew
    public function _iso8601_date($time)
    {
        return $this->_date_iso8601($time);
    }

    public function get_default_site_year()
    {
        return $this->_date->date_year_user();
    }

    //=========================================================
    // override into build_cache
    //=========================================================
    public function _assign_cache(&$tpl)
    {
        $this->_init_rss();
        $this->_init_option();

        switch ($this->_MODE_BUILD) {
            case 'rdf':
                $this->_assign_rdf($tpl);
                break;

            case 'atom':
                $this->_assign_atom($tpl);
                break;

            case 'rss':
                $this->_assign_rss($tpl);
                break;

            case 'other':
            default:
                $this->_assign_other($tpl);
                break;
        }
    }

    public function _get_template()
    {
        switch ($this->_MODE_BUILD) {
            case 'rdf':
                $ret = $this->_TEMPLATE_RDF;
                break;

            case 'atom':
                $ret = $this->_TEMPLATE_ATOM;
                break;

            case 'rss':
                $ret = $this->_TEMPLATE_RSS;
                break;

            case 'other';
            default:
                $ret = $this->_TEMPLATE_OTHER;
                break;
        }
        return $ret;
    }

    //=========================================================
    // override for caller
    //=========================================================
    public function _assign_other(&$tpl)
    {
        // dummy
    }

    public function _init_option()
    {
        // dummy
    }

    public function &_get_channel()
    {
        return $this->_channel;
    }

    public function &_get_items()
    {
        return $this->_items;
    }

    public function &_build_common_item(&$item)
    {
        return $item;
    }

    public function &_build_rdf_item(&$item)
    {
        $ret =& $this->_build_rdf_item_default($item);
        return $ret;
    }

    public function &_build_rss_item(&$item)
    {
        $ret =& $this->_build_rss_item_default($item);
        return $ret;
    }

    public function &_build_atom_entry(&$entry)
    {
        $ret =& $this->_build_atom_entry_default($entry);
        return $ret;
    }

    // --- class end ---
}

?>
