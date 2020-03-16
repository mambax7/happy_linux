<?php

namespace XoopsModules\Happylinux;

// $Id: build_xml.php,v 1.1 2008/02/26 15:35:42 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// this file include 4 classes
//   happylinux_xml_base
//   happylinux_xml_single_object
//   happylinux_xml_iterate_object
//   happylinux_build_xml
// 2008-02-17 K.OHWADA
//=========================================================

//=========================================================
// class xml_base
//=========================================================

/**
 * Class XmlBase
 * @package XoopsModules\Happylinux
 */
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
    /**
     * @param $str
     * @return string|string[]|null
     */
    public function xml_text($str)
    {
        return $this->xml_htmlspecialchars_strict($str);
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function xml_url($str)
    {
        return $this->xml_htmlspecialchars_url($str);
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function xml_htmlspecialchars($str)
    {
        $str = htmlspecialchars($str, ENT_QUOTES | ENT_HTML5);
        $str = preg_replace("/'/", '&apos;', $str);

        return $str;
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function xml_htmlspecialchars_strict($str)
    {
        $str = $this->xml_strip_html_entity_char($str);
        $str = $this->xml_htmlspecialchars($str);

        return $str;
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public function xml_htmlspecialchars_url($str)
    {
        $str = preg_replace('/&amp;/sU', '&', $str);
        $str = $this->xml_strip_html_entity_char($str);
        $str = $this->xml_htmlspecialchars($str);

        return $str;
    }

    /**
     * @param      $str
     * @param bool $flag_control
     * @param bool $flag_undo
     * @return string|string[]|null
     */
    public function xml_cdata($str, $flag_control = true, $flag_undo = true)
    {
        if ($flag_control) {
            $str = happylinux_str_replace_control_code($str, '');
        }

        if ($flag_undo) {
            $str = $this->xml_undo_html_special_chars($str);
        }

        // not sanitize
        $str = $this->xml_convert_cdata($str);

        return $str;
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
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
    /**
     * @param $str
     * @return string|string[]|null
     */
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
    /**
     * @param $str
     * @return string|string[]|null
     */
    public function xml_undo_html_entity_char($str)
    {
        return preg_replace('/&amp;([0-9a-zA-z]+);/sU', '&\\1;', $str);
    }

    // --------------------------------------------------------
    // undo html entities
    //   &amp;#123; -> &#123;
    // --------------------------------------------------------
    /**
     * @param $str
     * @return string|string[]|null
     */
    public function xml_undo_html_entity_numeric($str)
    {
        return preg_replace('/&amp;#([0-9a-fA-F]+);/sU', '&#\\1;', $str);
    }

    // --------------------------------------------------------
    // strip html entities
    //   &abc; -> ' '
    // --------------------------------------------------------
    /**
     * @param $str
     * @return string|string[]|null
     */
    public function xml_strip_html_entity_char($str)
    {
        return preg_replace('/&[0-9a-zA-z]+;/sU', ' ', $str);
    }

    // --------------------------------------------------------
    // strip html entities
    //   &#123; -> ' '
    // --------------------------------------------------------
    /**
     * @param $str
     * @return string|string[]|null
     */
    public function xml_strip_html_entity_numeric($str)
    {
        return preg_replace('/&amp;#([0-9a-fA-F]+);/sU', '&#\\1;', $str);
    }

    //-----------------------------------------------
    // convert to utf
    //-----------------------------------------------
    /**
     * @param $str
     * @return string|string[]|null
     */
    public function xml_utf8($str)
    {
        $str = happylinux_convert_to_utf8($str, _CHARSET);
        if ($this->_FLAG_REPLACE_CONTROL_CODE) {
            $str = happylinux_str_replace_control_code($str, $this->_REPLACE_CHAR);
        }

        return $str;
    }

    //--------------------------------------------------------
    // xoops param
    //--------------------------------------------------------
    /**
     * @return mixed
     */
    public function get_xoops_sitename()
    {
        global $xoopsConfig;

        return $xoopsConfig['sitename'];
    }

    /**
     * @param        $dirname
     * @param string $format
     * @return bool
     */
    public function get_xoops_module_name($dirname, $format = 'n')
    {
        $name           = false;
        $moduleHandler = xoops_getHandler('module');
        $obj            = $moduleHandler->getByDirname($dirname);
        if (is_object($obj)) {
            $name = $obj->getVar('name', $format);
        }

        return $name;
    }

    // --- class end ---
}
