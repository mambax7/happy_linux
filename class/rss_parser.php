<?php
// $Id: rss_parser.php,v 1.6 2007/10/25 15:28:26 ohwada Exp $

// 2007-10-10 K.OHWADA
// PHP 5.2: Non-static method

// 2007-09-20 K.OHWADA
// PHP5.2: Assigning the return value of new by reference is deprecated

// 2007-06-01 K.OHWADA
// use happy_linux_rss_parse

// 2007-05-12 K.OHWADA
// this is new file
// porting form rssc_parse_handler.php

//=========================================================
// Happy Linux Framework Module
// 2007-05-12 K.OHWADA
//=========================================================

define('HAPPY_LINUX_CODE_PARSER_NOT_READ_XML_URL', 51);
define('HAPPY_LINUX_CODE_PARSER_NOT_FIND_ENCODING', 52);
define('HAPPY_LINUX_CODE_PARSER_FAILED', 53);

//=========================================================
// class happy_linux_rss_parser
// require happy_linux_magpie_parse class
//=========================================================
class happy_linux_rss_parser extends happy_linux_error
{
    public $_DEBUG_PRINT_ITEMS = false;

    // class instance
    public $_rss_utility;

    // local
    public $_xml_encoding_orig = null;
    public $_xml_encoding;
    public $_xml_data          = null;
    public $_html_text         = null;

    // encoding
    public $_local_encoding = _CHARSET;

    public $_xml_error_code = 0;
    public $_parse_result   = '';

    // language
    public $_LANG_ASSUME_ENCODING = 'assume xml encoding %s ,<br />because cannot detect encoding automatically';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        // class instance
        // PHP 5.2: Non-static method
        $this->_rss_utility = happy_linux_get_singleton('rss_utility');
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_rss_parser();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // discover XML url & read XML & parse XML
    //---------------------------------------------------------
    public function &discover_and_parse_by_html_url($html_url)
    {
        $false = false;

        if (!$this->_rss_utility->discover($html_url)) {
            $this->_set_errors($this->_rss_utility->getErrors());
            return $false;
        }

        $this->_html_text = $this->_rss_utility->get_html_text();

        $xml_mode     = $this->_rss_utility->get_xml_mode();
        $xml_url      = $this->_rss_utility->get_xmlurl_by_mode();
        $xml_encoding = '';

        $obj =& $this->parse_by_url($xml_url, $xml_encoding, $xml_mode);
        if (!is_object($obj)) {
            return $false;
        }

        return $obj;
    }

    public function get_html_text()
    {
        return $this->_html_text;
    }

    //---------------------------------------------------------
    // read XML & parse XML
    //---------------------------------------------------------
    public function &parse_by_url($xml_url, $xml_encoding = '')
    {
        $false = false;

        $xml_data = $this->_rss_utility->read_xml($xml_url);
        if (!$xml_data) {
            $this->_set_error_code(HAPPY_LINUX_CODE_PARSER_NOT_READ_XML_URL);
            $this->_set_errors($this->_rss_utility->getErrors());
            $this->_xml_error_code = $this->_rss_utility->getErrorCode();
            return $false;
        }

        if ($xml_encoding) {
            $encoding_orig            = $xml_encoding;
            $this->_xml_encoding_orig = $xml_encoding;
        } // find encoding, if empty
        else {
            $encoding_orig = $this->_rss_utility->find_encoding($xml_data);

            if (!$encoding_orig) {
                $this->_set_error_code(HAPPY_LINUX_CODE_PARSER_NOT_FIND_ENCODING);
                $this->_set_errors($this->_rss_utility->getErrors());
                $this->_xml_error_code = $this->_rss_utility->getErrorCode();
                return $false;
            }

            $this->_xml_encoding_orig = $encoding_orig;

            $ret = $this->_rss_utility->get_result_code();
            if ($ret == HAPPY_LINUX_RSS_CODE_XML_ENCODINGS_DEFAULT) {
                $this->_parse_result = sprintf($this->_LANG_ASSUME_ENCODING, $encoding_orig);
            }
        }

        list($xml_converted, $encoding_converted) = $this->_rss_utility->convert_to_parse($xml_data, $encoding_orig);

        $obj =& $this->parse($xml_converted, $encoding_converted, $xml_url);
        return $obj;
    }

    //---------------------------------------------------------
    // parse XML with user encoding
    //---------------------------------------------------------
    public function &parse_by_xml($xml_data, $xml_encoding)
    {
        list($xml_converted, $encoding_converted) = $this->_rss_utility->convert_to_parse($xml_data, $xml_encoding);

        $obj =& $this->parse($xml_converted, $encoding_converted);
        return $obj;
    }

    //---------------------------------------------------------
    // parse XML
    //---------------------------------------------------------
    public function &parse($xml_data, $xml_encoding, $xml_url = '')
    {
        $this->_xml_data     = $xml_data;
        $this->_xml_encoding = $xml_encoding;

        $false = false;

        $xml_data = $this->convert_xml_header_by_phpversion($xml_data, $xml_encoding, $this->_xml_encoding_orig);

        // Assigning the return value of new by reference is deprecated
        $magpie = new happy_linux_magpie_parse();
        $magpie->magpie_parse($xml_data, $xml_encoding, $xml_encoding, false);

        if (!$magpie) {
            $this->_set_error_code(HAPPY_LINUX_CODE_PARSER_FAILED);
            $this->_set_errors("cannot parse: url = $xml_url");
            return $false;
        }

        if ($magpie->ERROR) {
            $this->_set_error_code(HAPPY_LINUX_CODE_PARSER_FAILED);
            $this->_set_errors("cannot parse: url = $xml_url");
            $this->_set_errors($magpie->ERROR);
            return $false;
        }

        if ((count($magpie->channel) == 0) && (count($magpie->items) == 0)) {
            $this->_set_error_code(HAPPY_LINUX_CODE_PARSER_FAILED);
            $this->_set_errors("parse data is empty: url = $xml_url");
            return $false;
        }

        // object
        // Assigning the return value of new by reference is deprecated
        $obj = new happy_linux_rss_parse();

        $obj->set_xml_encoding($xml_encoding);
        $obj->set_local_encoding($this->_local_encoding);
        $obj->set_vars_from_parse($magpie);
        $obj->convert_to_local();
        $obj->build_for_store();

        if ($this->_DEBUG_PRINT_ITEMS) {
            echo "happy_linux_rss_parser->parse() <br />\n";
            print_r($magpie->items);
            echo "<hr />\n";
            print_r($obj->get_items());
            echo "<hr />\n";
        }

        return $obj;
    }

    //---------------------------------------------------------
    // auto detect encoding in parser, when PHP 5
    // and then change xml header
    // exsample:
    // < ? xml version="1.0" encoding="EUC-JP" ? >
    // to
    // < ? xml version="1.0" encoding="UTF-8" ? >
    //---------------------------------------------------------
    public function convert_xml_header_by_phpversion($xml_data, $xml_encoding, $xml_encoding_orig)
    {
        $xml_encoding      = strtoupper($xml_encoding);
        $xml_encoding_orig = strtoupper($xml_encoding_orig);

        if ($this->is_php5() && $xml_encoding_orig && ($xml_encoding != $xml_encoding_orig)) {
            $pattern     = '/encoding=[\'"]' . $xml_encoding_orig . '[\'"]/i';
            $replacement = 'encoding="' . $xml_encoding . '"';
            $xml_data    = preg_replace($pattern, $replacement, $xml_data);
        }

        return $xml_data;
    }

    public function is_php5()
    {
        if (substr(phpversion(), 0, 1) == 5) {
            return true;
        }
        return false;
    }

    //---------------------------------------------------------
    // set and get property
    //---------------------------------------------------------
    public function set_local_encoding($value)
    {
        $this->_local_encoding = $value;
    }

    public function get_xml_encoding()
    {
        return $this->_xml_encoding;
    }

    // BUG 4419: not detect xml encoding correctly
    public function get_xml_encoding_orig()
    {
        return $this->_xml_encoding_orig;
    }

    public function get_xml_data()
    {
        return $this->_xml_data;
    }

    public function get_xml_error_code()
    {
        return $this->_xml_error_code;
    }

    public function get_parse_result()
    {
        return $this->_parse_result;
    }

    //---------------------------------------------------------
    // set and get property of xml_utility
    //---------------------------------------------------------
    public function get_xml_mode()
    {
        $ret = $this->_rss_utility->get_xml_mode();
        return $ret;
    }

    public function get_rdf_url()
    {
        $ret = $this->_rss_utility->get_rdf_url();
        return $ret;
    }

    public function get_rss_url()
    {
        $ret = $this->_rss_utility->get_rss_url();
        return $ret;
    }

    public function get_atom_url()
    {
        $ret = $this->_rss_utility->get_atom_url();
        return $ret;
    }

    // --- class end ---
}
