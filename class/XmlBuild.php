<?php

namespace XoopsModules\Happy_linux;

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
// class XmlBuild
//=========================================================
class XmlBuild extends XmlBase
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
        if (null === $instance) {
            $instance = new static();
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
        echo "<br>\n";

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
            $text .= 'This is debug mode <br><br>' . "\n";
        }
        $text .= '<hr>' . "\n";

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

        $text = '<hr>' . "\n";
        $text .= $goto;
        $text .= '<br>' . "\n";
        $text .= '<div style="text-align:center;">' . "\n";
        $text .= '<input value="' . $lang_close . '" type="button" onclick="javascript:window.close();" />' . "\n";
        $text .= '</div>' . "\n";
        $text .= '</body></html>' . "\n";

        return $text;
    }

    public function build_highlight($str)
    {
        $text = '<span style="color: #ff0000;">' . $str . '</span><br>' . "\n";

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
