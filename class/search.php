<?php
// $Id: search.php,v 1.10 2007/07/04 10:53:22 ohwada Exp $

// 2007-06-23 K.OHWADA
// add default in get_post_get_action()

// 2006-11-20 K.OHWADA
// for happy_search
// add check_xoops_enable_search() get_post_get_uid()

// 2006-10-14 K.OHWADA
// add get_query_utf8_urlencode()

// 2006-09-20 K.OHWADA
// fuzzy search
// add build_candidate_array() etc

// 2006-07-10 K.OHWADA
// this is new file
// porting from rssc_search_handler.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

define('HAPPY_LINUX_SEARCH_CODE_SQL_NO_CAN', 31);
define('HAPPY_LINUX_SEARCH_CODE_SQL_CAN', 32);
define('HAPPY_LINUX_SEARCH_CODE_SQL_MERGE', 33);

define('HAPPY_LINUX_SEARCH_CODE_HANKAKU', 35);
define('HAPPY_LINUX_SEARCH_CODE_ZENKAKU', 36);

// for Japanese EUC-JP
define('HAPPY_LINUX_SEARCH_ZENKAKU_EISU', '/\xA3[\xC1-\xFA]/');
define('HAPPY_LINUX_SEARCH_HANKAKU_EISU', '/[A-Za-z0-9]/');
define('HAPPY_LINUX_SEARCH_ZENKAKU_KANA', '/\xA5[\xA1-\xF6]/');
define('HAPPY_LINUX_SEARCH_HANKAKU_KANA', '/\x8E[\xA6-\xDF]/');

//=========================================================
// class happy_linux_search
//=========================================================
class happy_linux_search
{
    // class
    public $_strings;
    public $_post;
    public $_system;

    // post
    public $_post_action;
    public $_post_andor;
    public $_post_query;
    public $_post_uid;
    public $_post_mid;
    public $_post_start;
    public $_post_mids;
    public $_post_showcontext;

    // input param
    public $_min_keyword         = 5;
    public $_flag_cabdicate      = true;
    public $_flag_cabdicate_once = false;

    // result
    public $_query;
    public $_query_array;
    public $_ignore_array;
    public $_candidate_array;
    public $_candidate_keyword_array;
    public $_merged_query_array;
    public $_mode_andor;
    public $_sel_and;
    public $_sel_or;
    public $_sel_exact;
    public $_sql_andor;
    public $_sql_query_array;
    public $_query_urlencode;
    public $_query_utf8_urlencode;
    public $_merged_urlencode;
    public $_merged_utf8_urlencode;

    // local
    public $_time_start;
    public $_is_japanese;

    public $_LANG_ZENKAKU = 'zenkaku';
    public $_LANG_HANKAKU = 'hankaku';

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        $this->_strings = happy_linux_strings::getInstance();
        $this->_post    = happy_linux_post::getInstance();
        $this->_system  = happy_linux_system::getInstance();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_search();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // get $_POST & $_GET
    //---------------------------------------------------------
    public function get_post_get_action($default = 'search')
    {
        $action = $this->_post->get_post_get_text('action');

        switch ($action) {
            case 'search';
            case 'results':
            case 'showall':
            case 'showallbyuser':
                $ret = $action;
                break;

            default:
                $ret = $default;
                break;
        }

        $this->_post_action = $ret;
        return $ret;
    }

    public function get_post_get_andor($default = 'AND')
    {
        $andor = $this->_post->get_post_get_text('andor');

        switch ($andor) {
            case 'AND';
            case 'OR';
            case 'exact';
                $ret = $andor;
                break;

            default:
                $ret = $default;
                break;
        }

        $this->_post_andor = $ret;
        return $ret;
    }

    public function get_post_get_query()
    {
        $this->_post_query = trim($this->_post->get_post_get_text('query'));
        return $this->_post_query;
    }

    public function get_post_get_uid()
    {
        $this->_post_uid = $this->_post->get_post_get_int('uid');
        return $this->_post_uid;
    }

    public function get_post_get_mid()
    {
        $this->_post_mid = $this->_post->get_post_get_int('mid');
        return $this->_post_mid;
    }

    public function get_post_get_start()
    {
        $this->_post_start = $this->_post->get_post_get_int('start');
        return $this->_post_start;
    }

    public function get_post_get_mids()
    {
        $this->_post_mids = $this->_post->get_post_get_array_int('mids');
        return $this->_post_mids;
    }

    public function get_post_get_showcontext($default = 1)
    {
        $this->_post_showcontext = $this->_post->get_post_get_int('showcontext', $default);
        return $this->_post_showcontext;
    }

    //--------------------------------------------------------
    // parse query
    //--------------------------------------------------------
    public function parse_query_default()
    {
        $ret = $this->parse_query($this->_post_query, $this->_post_andor, false);
        return $ret;
    }

    public function parse_query($query = '', $andor = '', $gpc = true)
    {
        if ($query && $gpc) {
            $query = $this->_strings->strip_slashes_gpc($query);
        } elseif (empty($query)) {
            $query = $this->_post_query;
        }

        if (empty($andor)) {
            $andor = $this->_post_andor;
        }

        $this->_query                   = $query;
        $this->_query_array             = array();
        $this->_ignore_array            = array();
        $this->_candidate_array         = array();
        $this->_candidate_keyword_array = array();
        $this->_merged_query_array      = array();
        $this->_mode_andor              = '';
        $this->_sel_and                 = '';
        $this->_sel_or                  = '';
        $this->_sel_exact               = '';

        $this->_is_japanese = $this->_system->is_japanese();

        if ($query == '') {
            return false;
        }

        if (($andor != 'OR') && ($andor != 'exact') && ($andor != 'AND')) {
            $andor = 'AND';
        }

        if ($andor != 'exact') {
            $query_han      = $this->_convert_space_zen_to_han($query);
            $query_temp_arr = preg_split('/[\s,]+/', $query_han);

            foreach ($query_temp_arr as $q) {
                $q = trim($q);

                if (strlen($q) >= $this->_min_keyword) {
                    $this->_query_array[] = $q;
                    $this->_build_candidate($q);
                } else {
                    $this->_ignore_array[] = $q;
                }
            }

            if ($andor == 'OR') {
                $this->_sel_or = 'selected';
            } else {
                $this->_sel_and = 'selected';
            }
        } else {
            $this->_query_array = array($query);
            $this->_sel_exact   = 'selected';
        }

        $this->_mode_andor = $andor;

        if (count($this->_query_array) == 0) {
            return false;
        }

        $this->_merged_query_array = $this->_strings->merge_unique_array($this->_query_array, $this->_candidate_keyword_array);

        return true;
    }

    //--------------------------------------------------------
    // build query
    //--------------------------------------------------------
    public function check_build_sql_query_array($query_array = '', $candidate_keyword_array = '', $andor = '')
    {
        if (empty($candidate_keyword_array)) {
            $candidate_keyword_array = $this->_candidate_keyword_array;
        }

        if (empty($query_array)) {
            $query_array = $this->_query_array;
        }

        if (empty($andor)) {
            $andor = $this->_mode_andor;
        }

        $this->_sql_andor       = $andor;
        $this->_sql_query_array = $query_array;

        if (is_array($candidate_keyword_array) && (count($candidate_keyword_array) > 0)) {
            if ((count($query_array) == 1) || ($andor == 'OR')) {
                $this->_build_sql_query_array($candidate_keyword_array, $query_array, $andor);
                return HAPPY_LINUX_SEARCH_CODE_SQL_MERGE;
            } else {
                return HAPPY_LINUX_SEARCH_CODE_SQL_CAN;
            }
        }
        return HAPPY_LINUX_SEARCH_CODE_SQL_NO_CAN;
    }

    public function _build_sql_query_array($query_array, $candidate_keyword_array, $andor)
    {
        $this->_sql_andor       = 'OR';
        $this->_sql_query_array = $this->_strings->merge_unique_array($query_array, $candidate_keyword_array);
    }

    //--------------------------------------------------------
    // build sql
    //--------------------------------------------------------
    public function build_single_double_where($field_name, $query_array1, $query_array2 = null, $andor = 'AND')
    {
        $where  = '';
        $where1 = '';
        $where2 = '';

        if (is_array($query_array1) && (count($query_array1) > 0)) {
            $where1 = $this->build_single_where($field_name, $query_array1, $andor);
        }

        if (is_array($query_array2) && (count($query_array2) > 0)) {
            $where2 = $this->build_single_where($field_name, $query_array2, $andor);
        }

        if ($where1 && $where2) {
            $where = ' ( ' . $where1 . ' OR ' . $where2 . ' ) ';
        } elseif ($where1) {
            $where = $where1;
        } elseif ($where2) {
            $where = $where2;
        }

        return $where;
    }

    public function build_multi_where($field_name_array, $query_array, $andor = 'AND')
    {
        $where = '';
        $arr   = array();

        if (is_array($field_name_array) && (count($field_name_array) > 0)) {
            foreach ($field_name_array as $name) {
                $arr[] = $this->_build_single_where($name, $query_array, $andor);
            }
        }

        if (count($arr) > 0) {
            $where = ' ( ';
            $where .= implode(' OR ', $arr);
            $where .= ' ) ';
        }

        return $where;
    }

    public function build_single_where($field_name, $query_array, $andor = 'AND')
    {
        $where = '';

        if (is_array($query_array)) {
            $count = count($query_array);

            if ($count > 0) {
                $q = addslashes($query_array[0]);
                $where .= ' ( ' . $field_name . " LIKE '%" . $q . "%' ";

                for ($i = 1; $i < $count; ++$i) {
                    $q = addslashes($query_array[$i]);
                    $where .= $andor . ' ';
                    $where .= $field_name . " LIKE '%" . $q . "%' ";
                }

                $where .= ') ';
            }
        }

        return $where;
    }

    //--------------------------------------------------------
    // set param
    //--------------------------------------------------------
    public function set_min_keyword($value)
    {
        $this->_min_keyword = (int)$value;
    }

    public function set_flag_cabdicate($value)
    {
        $this->_flag_cabdicate = (int)$value;
    }

    public function set_flag_cabdicate_once($value)
    {
        $this->_flag_cabdicate_once = (int)$value;
    }

    public function set_lang_zenkaku($value)
    {
        $this->_LANG_ZENKAKU = $value;
    }

    public function set_lang_hankaku($value)
    {
        $this->_LANG_HANKAKU = $value;
    }

    //--------------------------------------------------------
    // get query
    //--------------------------------------------------------
    public function get_query($format = null)
    {
        $ret = $this->_post_query;
        if ($format == 's') {
            $ret = htmlspecialchars($ret, ENT_QUOTES);
        }
        return $ret;
    }

    public function get_query_for_form($glue = ' ', $format = 's')
    {
        $ret = $this->implode_query_array($this->_query_array, $glue, $format);
        return $ret;
    }

    public function get_query_for_google($glue = '+', $format = null)
    {
        $ret = $this->implode_query_array($this->_query_array, $glue, $format);
        return $ret;
    }

    public function get_query_urlencode()
    {
        $ret = $this->urlencode_implode_array($this->_query_array);
        return $ret;
    }

    public function get_query_utf8_urlencode()
    {
        $ret = $this->urlencode_utf8_implode_array($this->_query_array);
        return $ret;
    }

    public function &get_query_array($format = 's')
    {
        if ($format) {
            $arr =& $this->_strings->sanitize_array_text($this->_query_array);
        } else {
            $arr =& $this->_query_array;
        }
        return $arr;
    }

    public function get_merged_query_array()
    {
        return $this->_merged_query_array;
    }

    public function get_merged_urlencode()
    {
        $ret = $this->urlencode_implode_array($this->_merged_query_array);
        return $ret;
    }

    public function get_merged_utf8_urlencode()
    {
        $ret = $this->urlencode_utf8_implode_array($this->_merged_query_array);
        return $ret;
    }

    public function implode_query_array($arr, $glue = ' ', $format = null)
    {
        $ret = $this->_strings->implode_array($glue, $arr);
        if ($format == 's') {
            $ret = htmlspecialchars($ret, ENT_QUOTES);
        }
        return $ret;
    }

    public function urlencode_implode_array($arr, $glue = ' ')
    {
        return $this->_strings->urlencode_from_array($arr, $glue);
    }

    public function urlencode_utf8_implode_array($arr, $glue = ' ')
    {
        return $this->_strings->utf8_urlencode_from_array($arr, $glue);
    }

    //--------------------------------------------------------
    // get param
    //--------------------------------------------------------
    public function get_action($format = null)
    {
        $ret = $this->_post_action;
        if ($format == 's') {
            $ret = htmlspecialchars($ret, ENT_QUOTES);
        }
        return $ret;
    }

    public function get_start()
    {
        return (int)$this->_post_start;
    }

    public function get_andor()
    {
        return $this->_mode_andor;
    }

    public function get_and()
    {
        return $this->_sel_and;
    }

    public function get_or()
    {
        return $this->_sel_or;
    }

    public function get_exact()
    {
        return $this->_sel_exact;
    }

    public function &get_ignore_array($format = 's')
    {
        if ($format) {
            $arr =& $this->_strings->sanitize_array_text($this->_ignore_array);
        } else {
            $arr =& $this->_ignore_array;
        }
        return $arr;
    }

    public function &get_candidate_array($format = 's')
    {
        if ($format) {
            $arr =& $this->_strings->sanitize_array_text($this->_candidate_array);
        } else {
            $arr =& $this->_candidate_array;
        }
        return $arr;
    }

    public function &get_candidate_keyword_array()
    {
        $arr =& $this->_candidate_keyword_array;
        return $arr;
    }

    public function get_count_query_array()
    {
        if (is_array($this->_query_array)) {
            return count($this->_query_array);
        }
        return false;
    }

    public function get_count_ignore_array()
    {
        if (is_array($this->_ignore_array)) {
            return count($this->_ignore_array);
        }
        return false;
    }

    public function get_count_candidate_array()
    {
        if (is_array($this->_candidate_array)) {
            return count($this->_candidate_array);
        }
        return false;
    }

    public function get_sql_andor()
    {
        return $this->_sql_andor;
    }

    public function get_sql_query_array()
    {
        return $this->_sql_query_array;
    }

    public function get_lang_ignoredwors()
    {
        return sprintf(_SR_IGNOREDWORDS, $this->_min_keyword);
    }

    public function get_lang_keytooshort()
    {
        return sprintf(_SR_KEYTOOSHORT, $this->_min_keyword, ceil($this->_min_keyword / 2));
    }

    //--------------------------------------------------------
    // XOOPS system parameter
    //--------------------------------------------------------
    public function check_xoops_enable_search()
    {
        return $this->_system->check_config_search_enable_search();
    }

    public function get_xoops_keyword_min()
    {
        return $this->_system->get_config_search_keyword_min();
    }

    //=========================================================
    // Private
    //=========================================================

    //--------------------------------------------------------
    // convert for Japanese EUC-JP
    // porting from suin's search <http://suin.jp/>
    //--------------------------------------------------------
    public function _convert_space_zen_to_han($str)
    {
        if (!$this->_is_japanese || !function_exists('mb_convert_kana')) {
            return $str;
        }

        return mb_convert_kana($str, 's');
    }

    public function _build_candidate($q)
    {
        if (!$this->_flag_cabdicate) {
            return;
        }

        if (!$this->_is_japanese || !function_exists('mb_convert_kana')) {
            return;
        }

        // Zenkaku Eisu
        // option a: Convert "zen-kaku" alphabets and numbers to "han-kaku"
        if (preg_match(HAPPY_LINUX_SEARCH_ZENKAKU_EISU, $q)) {
            $keyword = mb_convert_kana($q, 'a');
            $this->_set_candidate_array($q, $keyword, HAPPY_LINUX_SEARCH_CODE_HANKAKU);

            if ($this->_flag_cabdicate_once) {
                return;
            }
        }

        // Hankaku Eisu
        // option A: Convert "han-kaku" alphabets and numbers to "zen-kaku"
        // (Characters included in "a", "A" options are U+0021 - U+007E excluding U+0022, U+0027, U+005C, U+007E)
        if (preg_match(HAPPY_LINUX_SEARCH_HANKAKU_EISU, $q)) {
            $keyword = mb_convert_kana($q, 'A');
            $this->_set_candidate_array($q, $keyword, HAPPY_LINUX_SEARCH_CODE_ZENKAKU);

            if ($this->_flag_cabdicate_once) {
                return;
            }
        }

        // Zenkaku Katakana
        // option k: Convert "zen-kaku kata-kana" to "han-kaku kata-kana"
        // option c: Convert "zen-kaku kata-kana" to "zen-kaku hira-kana"
        if (preg_match(HAPPY_LINUX_SEARCH_ZENKAKU_KANA, $q)) {
            $keyword_k = mb_convert_kana($q, 'k');
            $this->_set_candidate_array($q, $keyword_k, HAPPY_LINUX_SEARCH_CODE_HANKAKU);

            if ($this->_flag_cabdicate_once) {
                return;
            }

            $keyword_c = mb_convert_kana($q, 'c');
            $this->_set_candidate_array($q, $keyword_c, HAPPY_LINUX_SEARCH_CODE_ZENKAKU);

            if ($this->_flag_cabdicate_once) {
                return;
            }
        }

        // Hankaku Katakana
        // option K: Convert "han-kaku kata-kana" to "zen-kaku kata-kana"
        // option H: Convert "han-kaku kata-kana" to "zen-kaku hira-kana"
        // option V: Collapse voiced sound notation and convert them into a character. Use with "K","H"
        if (preg_match(HAPPY_LINUX_SEARCH_HANKAKU_KANA, $q)) {
            $keyword_kv = mb_convert_kana($q, 'KV');
            $this->_set_candidate_array($q, $keyword_kv, HAPPY_LINUX_SEARCH_CODE_ZENKAKU);

            if ($this->_flag_cabdicate_once) {
                return;
            }

            $keyword_hv = mb_convert_kana($q, 'HV');
            $this->_set_candidate_array($q, $keyword_hv, HAPPY_LINUX_SEARCH_CODE_ZENKAKU);

            if ($this->_flag_cabdicate_once) {
                return;
            }
        }

        // option h: Convert "zen-kaku hira-kana" to "han-kaku kata-kana"
        // option C: Convert "zen-kaku hira-kana" to "zen-kaku kata-kana"
        $keyword_h  = mb_convert_kana($q, 'h');
        $keyword_cc = mb_convert_kana($q, 'C');

        if ($q != $keyword_h) {
            $this->_set_candidate_array($q, $keyword_h, HAPPY_LINUX_SEARCH_CODE_HANKAKU);

            if ($this->_flag_cabdicate_once) {
                return;
            }
        }

        if ($q != $keyword_cc) {
            $this->_set_candidate_array($q, $keyword_cc, HAPPY_LINUX_SEARCH_CODE_ZENKAKU);

            if ($this->_flag_cabdicate_once) {
                return;
            }
        }
    }

    public function _set_candidate_array($q, $keyword, $type)
    {
        if (strlen($keyword) < $this->_min_keyword) {
            return;
        }

        if ($q == $keyword) {
            return;
        }

        $this->_candidate_keyword_array[] = $keyword;

        if ($type == HAPPY_LINUX_SEARCH_CODE_ZENKAKU) {
            $this->_candidate_array[] = array(
                'keyword' => $keyword,
                'type'    => HAPPY_LINUX_SEARCH_CODE_ZENKAKU,
                'lang'    => $this->_LANG_ZENKAKU,
            );
        } else {
            $this->_candidate_array[] = array(
                'keyword' => $keyword,
                'type'    => HAPPY_LINUX_SEARCH_CODE_HANKAKU,
                'lang'    => $this->_LANG_HANKAKU,
            );
        }
    }

    //----- class end -----
}
