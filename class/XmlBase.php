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
// class xml_base
//=========================================================
class XmlBase
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
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    // --------------------------------------------------------
    // htmlspecialchars
    // https://www.w3.org/TR/REC-xml/#dt-markup
    // https://www.fxis.co.jp/xmlcafe/tmp/rec-xml.html#dt-markup
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
