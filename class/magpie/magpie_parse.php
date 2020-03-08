<?php
// $Id: magpie_parse.php,v 1.2 2011/12/29 18:13:10 ohwada Exp $

// 2011-12-29 K.OHWADA
// Notice [PHP]: Undefined index: rel

// 2009-02-20 K.OHWADA
// media_rss

// 2006-09-01 K.OHWADA
// some RSS have twe or more enclosure tag

// 2006-07-08 K.OHWADA
// corresponding to podcast
// RSS enclosure tag

// 2006-06-04 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// base on MagpieRSS v0.72
// 2006-06-04 K.OHWADA
//=========================================================

/**
 * Project:     MagpieRSS: a simple RSS integration tool
 * File:        rss_parse.inc  - parse an RSS or Atom feed
 *               return as a simple object.
 *
 * Handles RSS 0.9x, RSS 2.0, RSS 1.0, and Atom 0.3
 *
 * The lastest version of MagpieRSS can be obtained from:
 * https://magpierss.sourceforge.net
 *
 * For questions, help, comments, discussion, etc., please join the
 * Magpie mailing list:
 * magpierss-general@lists.sourceforge.net
 *
 * @author           Kellan Elliott-McCrea <kellan@protest.net>
 * @version          0.7a
 * @license          GPL
 */
define('HAPPY_LINUX_MAGPIE_RSS', 'RSS');
define('HAPPY_LINUX_MAGPIE_ATOM', 'Atom');

// require_once (MAGPIE_DIR . 'rss_utils.inc');

/**
 * Hybrid parser, and object, takes RSS as a string and returns a simple object.
 *
 * see: rss_fetch.inc for a simpler interface with integrated caching support
 */
//class MagpieRSS {
class happy_linux_magpie_parse
{
    public $MAGPIE_DEBUG = false;

    public $parser;

    public $current_item = [];  // item currently being parsed
    public $items        = [];  // collection of parsed items
    public $channel      = [];  // hash of channel fields
    public $textinput    = [];
    public $image        = [];
    public $feed_type;
    public $feed_version;
    public $encoding     = '';       // output encoding of parsed rss

    //  var $_source_encoding = '';     // only set if we have to parse xml prolog
    public $source_encoding = '';     // only set if we have to parse xml prolog

    public $ERROR   = '';
    public $WARNING = '';

    // define some constants

    public $_CONTENT_CONSTRUCTS = ['content', 'summary', 'info', 'title', 'tagline', 'copyright'];
    public $_KNOWN_ENCODINGS    = ['UTF-8', 'US-ASCII', 'ISO-8859-1'];

    // parser variables, useless if you're not a parser, treat as private
    public $stack             = []; // parser stack
    public $inchannel         = false;
    public $initem            = false;
    public $incontent         = false; // if in Atom <content mode="xml"> field
    public $intextinput       = false;
    public $inimage           = false;
    public $current_namespace = false;

    /**
     *  Set up XML parser, parse source, and return populated RSS object..
     */
    public function __construct()
    {
        // dummy
    }

    //  function MagpieRSS ($source, $output_encoding='ISO-8859-1',
    //                      $input_encoding=null, $detect_encoding=true)
    public function magpie_parse($source, $output_encoding = 'ISO-8859-1', $input_encoding = null, $detect_encoding = true)
    {
        # if PHP xml isn't compiled in, die
        #
        if (!function_exists('xml_parser_create')) {
            $this->error("Failed to load PHP's XML Extension. " . 'https://www.php.net/manual/en/ref.xml.php', E_USER_ERROR);
        }

        list($parser, $source) = $this->create_parser($source, $output_encoding, $input_encoding, $detect_encoding);

        if (!is_resource($parser)) {
            $this->error("Failed to create an instance of PHP's XML parser. " . 'https://www.php.net/manual/en/ref.xml.php', E_USER_ERROR);
        }

        $this->parser = $parser;

        # pass in parser, and a reference to this object
        # setup handlers
        #
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, 'feed_start_element', 'feed_end_element');

        xml_set_character_data_handler($this->parser, 'feed_cdata');

        $status = xml_parse($this->parser, $source);

        if (!$status) {
            $errorcode = xml_get_error_code($this->parser);
            if (XML_ERROR_NONE != $errorcode) {
                $xml_error  = xml_error_string($errorcode);
                $error_line = xml_get_current_line_number($this->parser);
                $error_col  = xml_get_current_column_number($this->parser);
                $errormsg   = "$xml_error at line $error_line, column $error_col";

                $this->error($errormsg);
            }
        }

        xml_parser_free($this->parser);

        $this->normalize();
    }

    public function feed_start_element($p, $element, &$attrs)
    {
        $el    = $element = mb_strtolower($element);
        $attrs = array_change_key_case($attrs, CASE_LOWER);

        // check for a namespace, and split if found
        $ns = false;
        if (mb_strpos($element, ':')) {
            list($ns, $el) = explode(':', $element, 2);
        }
        if ($ns and 'rdf' != $ns) {
            $this->current_namespace = $ns;
        }

        # if feed type isn't set, then this is first element of feed
        # identify feed from root element
        #
        if (!isset($this->feed_type)) {
            if ('rdf' == $el) {
                $this->feed_type    = HAPPY_LINUX_MAGPIE_RSS;
                $this->feed_version = '1.0';
            } elseif ('rss' == $el) {
                $this->feed_type = HAPPY_LINUX_MAGPIE_RSS;

                //              $this->feed_version = $attrs['version'];
                if (isset($attrs['version'])) {
                    $this->feed_version = $attrs['version'];
                }
            } elseif ('feed' == $el) {
                $this->feed_type = HAPPY_LINUX_MAGPIE_ATOM;

                //              $this->feed_version = $attrs['version'];
                if (isset($attrs['version'])) {
                    $this->feed_version = $attrs['version'];
                }

                $this->inchannel = true;
            }

            return;
        }

        if ('channel' == $el) {
            $this->inchannel = true;
        } elseif ('item' == $el or 'entry' == $el) {
            $this->initem = true;
            if (isset($attrs['rdf:about'])) {
                $this->current_item['about'] = $attrs['rdf:about'];
            }
        }

        // if we're in the default namespace of an RSS feed,
        //  record textinput or image fields
        elseif (HAPPY_LINUX_MAGPIE_RSS == $this->feed_type and '' == $this->current_namespace and 'textinput' == $el) {
            $this->intextinput = true;
        } elseif (HAPPY_LINUX_MAGPIE_RSS == $this->feed_type and '' == $this->current_namespace and 'image' == $el) {
            $this->inimage = true;
        } # handle atom content constructs
        elseif (HAPPY_LINUX_MAGPIE_ATOM == $this->feed_type and in_array($el, $this->_CONTENT_CONSTRUCTS)) {
            // avoid clashing w/ RSS mod_content
            if ('content' == $el) {
                $el = 'atom_content';
            }

            $this->incontent = $el;
        } // if inside an Atom content construct (e.g. content or summary) field treat tags as text
        elseif (HAPPY_LINUX_MAGPIE_ATOM == $this->feed_type and $this->incontent) {
            // if tags are inlined, then flatten
            $attrs_str = implode(' ', array_map('happy_linux_magpie_map_attrs', array_keys($attrs), array_values($attrs)));

            $this->append_content("<$element $attrs_str>");

            array_unshift($this->stack, $el);
        }

        // Atom support many links per containging element.
        // Magpie treats link elements of type rel='alternate'
        // as being equivalent to RSS's simple link element.
        //
        elseif (HAPPY_LINUX_MAGPIE_ATOM == $this->feed_type and 'link' == $el) {
            // Undefined index: rel
            if (isset($attrs['rel'])) {
                if ('alternate' == $attrs['rel']) {
                    $link_el = 'link';
                } else {
                    $link_el = 'link_' . $attrs['rel'];
                }
                if (isset($attrs['href'])) {
                    $this->append($link_el, $attrs['href']);
                }
            }
        }

        // RSS enclosure
        // some RSS have twe or more enclosure tag
        // https://www.podcastnavi.com/p_edit/index-utf8.xml
        elseif (HAPPY_LINUX_MAGPIE_RSS == $this->feed_type and 'enclosure' == $el) {
            $this->current_item['enclosure'][] = $attrs;
        } // media_rss
        elseif (('media' == $this->current_namespace) && ('content' == $el)) {
            $this->current_item['media']['content'] = $attrs;
        } // some RSS have twe or more thumbnail tag
        elseif (('media' == $this->current_namespace) && ('thumbnail' == $el)) {
            $this->current_item['media']['thumbnail'][] = $attrs;
        } // ATOM 1.0 category
        elseif (HAPPY_LINUX_MAGPIE_ATOM == $this->feed_type and 'category' == $el) {
            if (isset($attrs['term'])) {
                $this->append('category', $attrs['term']);
            }
        } // set stack[0] to current element
        else {
            array_unshift($this->stack, $el);
        }
    }

    public function feed_cdata($p, $text)
    {
        if (HAPPY_LINUX_MAGPIE_ATOM == $this->feed_type and $this->incontent) {
            $this->append_content($text);
        } else {
            $current_el = implode('_', array_reverse($this->stack));
            $this->append($current_el, $text);
        }
    }

    public function feed_end_element($p, $el)
    {
        $el = mb_strtolower($el);

        if ('item' == $el or 'entry' == $el) {
            $this->items[]      = $this->current_item;
            $this->current_item = [];
            $this->initem       = false;
        } elseif (HAPPY_LINUX_MAGPIE_RSS == $this->feed_type and '' == $this->current_namespace and 'textinput' == $el) {
            $this->intextinput = false;
        } elseif (HAPPY_LINUX_MAGPIE_RSS == $this->feed_type and '' == $this->current_namespace and 'image' == $el) {
            $this->inimage = false;
        } elseif (HAPPY_LINUX_MAGPIE_ATOM == $this->feed_type and in_array($el, $this->_CONTENT_CONSTRUCTS)) {
            $this->incontent = false;
        } elseif ('channel' == $el or 'feed' == $el) {
            $this->inchannel = false;
        } elseif (HAPPY_LINUX_MAGPIE_ATOM == $this->feed_type and $this->incontent) {
            // balance tags properly
            // note:  i don't think this is actually neccessary
            if ($this->stack[0] == $el) {
                $this->append_content("</$el>");
            } else {
                $this->append_content("<$el />");
            }

            array_shift($this->stack);
        } else {
            array_shift($this->stack);
        }

        $this->current_namespace = false;
    }

    public function concat(&$str1, $str2 = '')
    {
        if (!isset($str1)) {
            $str1 = '';
        }
        $str1 .= $str2;
    }

    public function append_content($text)
    {
        if ($this->initem) {
            $this->concat($this->current_item[$this->incontent], $text);
        } elseif ($this->inchannel) {
            $this->concat($this->channel[$this->incontent], $text);
        }
    }

    // smart append - field and namespace aware
    public function append($el, $text)
    {
        if (!$el) {
            return;
        }
        if ($this->current_namespace) {
            if ($this->initem) {
                $this->concat($this->current_item[$this->current_namespace][$el], $text);
            } elseif ($this->inchannel) {
                $this->concat($this->channel[$this->current_namespace][$el], $text);
            } elseif ($this->intextinput) {
                $this->concat($this->textinput[$this->current_namespace][$el], $text);
            } elseif ($this->inimage) {
                $this->concat($this->image[$this->current_namespace][$el], $text);
            }
        } else {
            if ($this->initem) {
                $this->concat($this->current_item[$el], $text);
            } elseif ($this->intextinput) {
                $this->concat($this->textinput[$el], $text);
            } elseif ($this->inimage) {
                $this->concat($this->image[$el], $text);
            } elseif ($this->inchannel) {
                $this->concat($this->channel[$el], $text);
            }
        }
    }

    public function normalize()
    {
        // if atom populate rss fields
        if ($this->is_atom()) {
            //          $this->channel['description'] = $this->channel['tagline'];

            for ($i = 0; $i < count($this->items); ++$i) {
                $item = $this->items[$i];
                if (isset($item['summary'])) {
                    $item['description'] = $item['summary'];
                }
                if (isset($item['atom_content'])) {
                    $item['content']['encoded'] = $item['atom_content'];
                }

                // commnet out
                //              $atom_date = (isset($item['issued']) ) ? $item['issued'] : $item['modified'];
                //                if ( $atom_date ) {
                //                    $epoch = parse_w3cdtf($atom_date);
                //                    if ($epoch and $epoch > 0) {
                //                        $item['date_timestamp'] = $epoch;
                //                    }
                //                }

                $this->items[$i] = $item;
            }
        } elseif ($this->is_rss()) {
            //          $this->channel['tagline'] = $this->channel['description'];

            for ($i = 0; $i < count($this->items); ++$i) {
                $item = $this->items[$i];
                if (isset($item['description'])) {
                    $item['summary'] = $item['description'];
                }
                if (isset($item['content']['encoded'])) {
                    $item['atom_content'] = $item['content']['encoded'];
                }

                // commnet out
                //                if ( $this->is_rss() == '1.0' and isset($item['dc']['date']) ) {
                //                    $epoch = parse_w3cdtf($item['dc']['date']);
                //                    if ($epoch and $epoch > 0) {
                //                        $item['date_timestamp'] = $epoch;
                //                    }
                //                }
                //                elseif ( isset($item['pubdate']) ) {
                //                    $epoch = @strtotime($item['pubdate']);
                //                    if ($epoch > 0) {
                //                        $item['date_timestamp'] = $epoch;
                //                    }
                //                }

                $this->items[$i] = $item;
            }
        }
    }

    public function is_rss()
    {
        if (HAPPY_LINUX_MAGPIE_RSS == $this->feed_type) {
            return $this->feed_version;
        }

        return false;
    }

    public function is_atom()
    {
        if (HAPPY_LINUX_MAGPIE_ATOM == $this->feed_type) {
            return $this->feed_version;
        }

        return false;
    }

    /**
     * return XML parser, and possibly re-encoded source
     * @param $source
     * @param $out_enc
     * @param $in_enc
     * @param $detect
     * @return array
     */
    public function create_parser($source, $out_enc, $in_enc, $detect)
    {
        if (5 == mb_substr(phpversion(), 0, 1)) {
            $parser = $this->php5_create_parser($in_enc, $detect);
        } else {
            list($parser, $source) = $this->php4_create_parser($source, $in_enc, $detect);
        }
        if ($out_enc) {
            $this->encoding = $out_enc;
            xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, $out_enc);
        }

        return [$parser, $source];
    }

    /**
     * Instantiate an XML parser under PHP5
     *
     * PHP5 will do a fine job of detecting input encoding
     * if passed an empty string as the encoding.
     *
     * All hail libxml2!
     * @param $in_enc
     * @param $detect
     * @return resource
     */
    public function php5_create_parser($in_enc, $detect)
    {
        // by default php5 does a fine job of detecting input encodings
        if (!$detect && $in_enc) {
            return xml_parser_create($in_enc);
        }

        return xml_parser_create('');
    }

    /**
     * Instaniate an XML parser under PHP4
     *
     * Unfortunately PHP4's support for character encodings
     * and especially XML and character encodings sucks.  As
     * long as the documents you parse only contain characters
     * from the ISO-8859-1 character set (a superset of ASCII,
     * and a subset of UTF-8) you're fine.  However once you
     * step out of that comfy little world things get mad, bad,
     * and dangerous to know.
     *
     * The following code is based on SJM's work with FoF
     * @see https://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
     * @param $source
     * @param $in_enc
     * @param $detect
     * @return array
     */
    public function php4_create_parser($source, $in_enc, $detect)
    {
        if (!$detect) {
            return [xml_parser_create($in_enc), $source];
        }

        if (!$in_enc) {
            if (preg_match('/<?xml.*encoding=[\'"](.*?)[\'"].*?>/m', $source, $m)) {
                $in_enc                = mb_strtoupper($m[1]);
                $this->source_encoding = $in_enc;
            } else {
                $in_enc = 'UTF-8';
            }
        }

        if ($this->known_encoding($in_enc)) {
            return [xml_parser_create($in_enc), $source];
        }

        // the dectected encoding is not one of the simple encodings PHP knows

        // attempt to use the iconv extension to
        // cast the XML to a known encoding
        // @see https://php.net/iconv

        if (function_exists('iconv')) {
            $encoded_source = iconv($in_enc, 'UTF-8', $source);
            if ($encoded_source) {
                return [xml_parser_create('UTF-8'), $encoded_source];
            }
        }

        // iconv didn't work, try mb_convert_encoding
        // @see https://php.net/mbstring
        if (function_exists('mb_convert_encoding')) {
            $encoded_source = mb_convert_encoding($source, 'UTF-8', $in_enc);
            if ($encoded_source) {
                return [xml_parser_create('UTF-8'), $encoded_source];
            }
        }

        // else
        $this->error("Feed is in an unsupported character encoding. ($in_enc) " . 'You may see strange artifacts, and mangled characters.', E_USER_NOTICE);

        return [xml_parser_create(), $source];
    }

    public function known_encoding($enc)
    {
        $enc = mb_strtoupper($enc);
        if (in_array($enc, $this->_KNOWN_ENCODINGS)) {
            return $enc;
        }

        return false;
    }

    public function error($errormsg, $lvl = E_USER_WARNING)
    {
        // append PHP's error message if track_errors enabled
        if (isset($php_errormsg)) {
            $errormsg .= " ($php_errormsg)";
        }

        //        if ( $this->MAGPIE_DEBUG ) {
        //            trigger_error( $errormsg, $lvl);
        //        }
        //        else {
        //            error_log( $errormsg, 0);
        //        }

        $notices = E_USER_NOTICE | E_NOTICE;
        if ($lvl & $notices) {
            $this->WARNING = $errormsg;
        } else {
            $this->ERROR = $errormsg;
        }
    }
} // end class RSS

// function map_attrs($k, $v) {
function happy_linux_magpie_map_attrs($k, $v)
{
    return "$k=\"$v\"";
}

// patch to support medieval versions of PHP4.1.x,
// courtesy, Ryan Currie, ryan@digibliss.com

if (!function_exists('array_change_key_case')) {
    define('CASE_UPPER', 1);
    define('CASE_LOWER', 0);

    function array_change_key_case($array, $case = CASE_LOWER)
    {
        if ($case = CASE_LOWER) {
            $cmd = strtolower;
        } elseif ($case = CASE_UPPER) {
            $cmd = strtoupper;
        }
        foreach ($array as $key => $value) {
            $output[$cmd($key)] = $value;
        }

        return $output;
    }
}
