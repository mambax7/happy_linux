<?php
// $Id: build_xml.php,v 1.1 2008/02/26 15:35:42 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// this file include 4 classes
//   happy_linux_xml_base
//   happy_linux_xml_single_object
//   happy_linux_xml_iterate_object
//   happy_linux_build_xml
// 2008-02-17 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_xml_base
//=========================================================
class happy_linux_xml_base
{
    // replace control code
    public $_FLAG_REPLACE_CONTROL_CODE = true;
    public $_REPLACE_CHAR              = ' ';  // space

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_xml_base();
        }
        return $instance;
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
    public function xml_text($str)
    {
        return $this->xml_htmlspecialchars_strict($str);
    }

    public function xml_url($str)
    {
        return $this->xml_htmlspecialchars_url($str);
    }

    public function xml_htmlspecialchars($str)
    {
        $str = htmlspecialchars($str);
        $str = preg_replace("/'/", '&apos;', $str);
        return $str;
    }

    public function xml_htmlspecialchars_strict($str)
    {
        $str = $this->xml_strip_html_entity_char($str);
        $str = $this->xml_htmlspecialchars($str);
        return $str;
    }

    public function xml_htmlspecialchars_url($str)
    {
        $str = preg_replace('/&amp;/sU', '&', $str);
        $str = $this->xml_strip_html_entity_char($str);
        $str = $this->xml_htmlspecialchars($str);
        return $str;
    }

    public function xml_cdata($str, $flag_control = true, $flag_undo = true)
    {
        if ($flag_control) {
            $str = happy_linux_str_replace_control_code($str, '');
        }

        if ($flag_undo) {
            $str = $this->xml_undo_html_special_chars($str);
        }

        // not sanitize
        $str = $this->xml_convert_cdata($str);

        return $str;
    }

    public function xml_convert_cdata($str)
    {
        return preg_replace('/]]>/', ']]&gt;', $str);
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
    public function xml_undo_html_special_chars($str)
    {
        $str = preg_replace('/&gt;/i', '>', $str);
        $str = preg_replace('/&lt;/i', '<', $str);
        $str = preg_replace('/&quot;/i', '"', $str);
        $str = preg_replace('/&#039;/i', "'", $str);
        $str = preg_replace('/&amp;nbsp;/i', '&nbsp;', $str);
        return $str;
    }

    // --------------------------------------------------------
    // undo html entities
    //   &amp;abc;  -> &abc;
    // --------------------------------------------------------
    public function xml_undo_html_entity_char($str)
    {
        return preg_replace('/&amp;([0-9a-zA-z]+);/sU', '&\\1;', $str);
    }

    // --------------------------------------------------------
    // undo html entities
    //   &amp;#123; -> &#123;
    // --------------------------------------------------------
    public function xml_undo_html_entity_numeric($str)
    {
        return preg_replace('/&amp;#([0-9a-fA-F]+);/sU', '&#\\1;', $str);
    }

    // --------------------------------------------------------
    // strip html entities
    //   &abc; -> ' '
    // --------------------------------------------------------
    public function xml_strip_html_entity_char($str)
    {
        return preg_replace('/&[0-9a-zA-z]+;/sU', ' ', $str);
    }

    // --------------------------------------------------------
    // strip html entities
    //   &#123; -> ' '
    // --------------------------------------------------------
    public function xml_strip_html_entity_numeric($str)
    {
        return preg_replace('/&amp;#([0-9a-fA-F]+);/sU', '&#\\1;', $str);
    }

    //-----------------------------------------------
    // convert to utf
    //-----------------------------------------------
    public function xml_utf8($str)
    {
        $str = happy_linux_convert_to_utf8($str, _CHARSET);
        if ($this->_FLAG_REPLACE_CONTROL_CODE) {
            $str = happy_linux_str_replace_control_code($str, $this->_REPLACE_CHAR);
        }
        return $str;
    }

    //--------------------------------------------------------
    // xoops param
    //--------------------------------------------------------
    public function get_xoops_sitename()
    {
        global $xoopsConfig;
        return $xoopsConfig['sitename'];
    }

    public function get_xoops_module_name($dirname, $format = 'n')
    {
        $name           = false;
        $module_handler = xoops_getHandler('module');
        $obj            = $module_handler->getByDirname($dirname);
        if (is_object($obj)) {
            $name = $obj->getVar('name', $format);
        }
        return $name;
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_xml_single_object
//=========================================================
class happy_linux_xml_single_object extends happy_linux_xml_base
{
    public $_vars    = array();
    public $_TPL_KEY = 'single';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // set & get
    //---------------------------------------------------------
    public function clear_vars()
    {
        $this->_vars = array();
    }

    public function set_vars($val)
    {
        if (is_array($val) && count($val)) {
            $this->_vars = $val;
        }
    }

    public function get_vars()
    {
        return $this->_vars;
    }

    public function set($key, $val)
    {
        $this->_vars[$key] = $val;
    }

    public function get($key)
    {
        $ret = false;
        if (isset($this->_vars[$key])) {
            $ret =& $this->_vars[$key];
        }
        return $ret;
    }

    public function set_tpl_key($val)
    {
        $this->_TPL_KEY = $val;
    }

    public function get_tpl_key()
    {
        return $this->_TPL_KEY;
    }

    //---------------------------------------------------------
    // build
    //---------------------------------------------------------
    public function build()
    {
        $arr  = array();
        $vars = $this->get_vars();
        if (is_array($vars) && count($vars)) {
            $this->set_vars($this->_build($vars));
        }
    }

    public function _build(&$arr)
    {
        return $this->_build_text($arr);
    }

    public function _build_text(&$arr)
    {
        $ret = array();
        foreach ($arr as $k => $v) {
            if (!is_array($v)) {
                $ret[$k] = $this->xml_text($v);
            }
        }
        return $ret;
    }

    //---------------------------------------------------------
    // utf8
    //---------------------------------------------------------
    public function to_utf8()
    {
        $arr  = array();
        $vars = $this->get_vars();
        if (is_array($vars) && count($vars)) {
            $this->set_vars($this->_to_utf8($vars));
        }
    }

    public function _to_utf8(&$arr)
    {
        $ret = array();
        foreach ($arr as $k => $v) {
            if (!is_array($v)) {
                $ret[$k] = $this->xml_utf8($v);
            }
        }
        return $ret;
    }

    //---------------------------------------------------------
    // assign
    //---------------------------------------------------------
    public function assign(&$tpl)
    {
        $this->_assign($tpl, $this->_TPL_KEY, $this->get_vars());
    }

    public function append(&$tpl)
    {
        $this->_append($tpl, $this->_TPL_KEY, $this->get_vars());
    }

    public function _assign(&$tpl, $key, $val)
    {
        $tpl->assign($key, $val);
    }

    public function _append(&$tpl, $key, $val)
    {
        $tpl->append($key, $val);
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_xml_iterate_object
//=========================================================
class happy_linux_xml_iterate_object extends happy_linux_xml_single_object
{
    public $_TPL_KEY = 'iterate';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    //---------------------------------------------------------
    // build
    //---------------------------------------------------------
    public function build_iterate()
    {
        $vars = $this->get_vars();
        if (is_array($vars) && count($vars)) {
            $arr = array();
            foreach ($vars as $var) {
                $arr[] = $this->_build($var);
            }
            $this->set_vars($arr);
        }
    }

    //---------------------------------------------------------
    // utf8
    //---------------------------------------------------------
    public function to_utf8_iterate()
    {
        $vars = $this->get_vars();
        if (is_array($vars) && count($vars)) {
            $arr = array();
            foreach ($vars as $var) {
                $arr[] = $this->_to_utf8($var);
            }
            $this->set_vars($arr);
        }
    }

    //---------------------------------------------------------
    // append
    //---------------------------------------------------------
    public function append_iterate(&$tpl)
    {
        $tpl_key = $this->get_tpl_key();
        $vars    = $this->get_vars();

        if (is_array($vars) && count($vars)) {
            foreach ($vars as $var) {
                $this->_append($tpl, $tpl_key, $var);
            }
        }
    }

    // --- class end ---
}

//=========================================================
// class happy_linux_build_xml
//=========================================================
class happy_linux_build_xml extends happy_linux_xml_base
{
    public $_CONTENT_TYPE_HTML = 'Content-Type:text/html; charset=utf-8';
    public $_CONTENT_TYPE_XML  = 'Content-Type:text/xml;  charset=utf-8';

    // override
    public $_TEMPLATE_XML = null;

    // set param
    public $_view_title      = 'View XML';
    public $_view_goto_title = 'goto index';
    public $_view_goto_url   = null;

    //  object ( dummy )
    //  var $_obj_single  = null;
    //  var $_obj_iterate = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_build_xml();
        }
        return $instance;
    }

    //=========================================================
    // public
    //=========================================================
    public function build_xml()
    {
        // header
        happy_linux_http_output('pass');
        header($this->_CONTENT_TYPE_XML);

        echo $this->_build_template($this->_get_template());
    }

    public function view_xml()
    {
        // header
        header($this->_CONTENT_TYPE_HTML);

        $template = $this->_get_template();
        if ($template) {
            $xml  = $this->_build_template($template);
            $body = htmlspecialchars($xml, ENT_QUOTES);
        } else {
            $body = $this->build_highlight('No Template');
        }

        echo $this->build_html_header($this->_view_title);

        echo '<pre>';
        echo $body;
        echo '</pre>';
        echo "<br />\n";

        echo $this->build_html_footer();
    }

    public function build_html_header($title = null, $flag = true)
    {
        if (empty($title)) {
            $title = $this->_view_title;
        }

        $text = '<html><head>' . "\n";
        $text .= '<meta http-equiv="content-type" content="text/html; charset=utf-8" />' . "\n";
        $text .= '<title>' . $title . '</title>' . "\n";
        $text .= '</head>' . "\n";
        $text .= '<body>' . "\n";
        $text .= '<h3>' . $title . '</h3>' . "\n";
        if ($flag) {
            $text .= 'This is debug mode <br /><br />' . "\n";
        }
        $text .= '<hr />' . "\n";

        return $text;
    }

    public function build_html_footer()
    {
        $lang_close = $this->xml_utf8(_CLOSE);

        $goto = '';
        if ($this->_view_goto_url && $this->_view_goto_title) {
            $goto = '<a href="' . $this->_view_goto_url . '">';
            $goto .= $this->xml_utf8($this->_view_goto_title) . "</a>\n";
        }

        $text = '<hr />' . "\n";
        $text .= $goto;
        $text .= '<br />' . "\n";
        $text .= '<div style="text-align:center;">' . "\n";
        $text .= '<input value="' . $lang_close . '" type="button" onclick="javascript:window.close();" />' . "\n";
        $text .= '</div>' . "\n";
        $text .= '</body></html>' . "\n";

        return $text;
    }

    public function build_highlight($str)
    {
        $text = '<span style="color: #ff0000;">' . $str . '</span><br />' . "\n";
        return $text;
    }

    // --------------------------------------------------------
    // set param
    // --------------------------------------------------------
    public function set_template($val)
    {
        $this->set_template_xml($val);
    }

    public function set_template_xml($val)
    {
        $this->_TEMPLATE_XML = $val;
    }

    public function set_view_title($val)
    {
        $this->_view_title = $val;
    }

    public function set_view_goto_title($val)
    {
        $this->_view_goto_title = $val;
    }

    public function set_view_goto_url($val)
    {
        $this->_view_goto_url = $val;
    }

    //=========================================================
    // private
    //=========================================================
    public function _get_template()
    {
        return $this->_get_template_xml();
    }

    public function _get_template_xml()
    {
        return $this->_TEMPLATE_XML;
    }

    //=========================================================
    // override for caller
    //=========================================================
    public function _init_obj()
    {
        //  dummy
        //  $this->_obj_single  = new happy_linux_xml_single_object();
        //  $this->_obj_iterate = new happy_linux_xml_iterate_object();
    }

    public function _set_single($val)
    {
        //  dummy
        //  $this->_obj_single->set_vars( $val );
    }

    public function _set_iterate($val)
    {
        //  dummy
        //  $this->_obj_iterate->set_vars( $val );
    }

    public function _build_template($template)
    {
        //  dummy
        //  $this->_obj_single->build();
        //  $this->_obj_single->to_utf8();
        //  $this->_obj_iterate->build_iterate();
        //  $this->_obj_iterate->to_utf8_iterate();
        //  $tpl = new XoopsTpl();
        //  $this->_obj_single->assign( $tpl );
        //  $this->_obj_iterate->append_iterate( $tpl );
        //  return $tpl->fetch( $template );
    }

    // --- class end ---
}
