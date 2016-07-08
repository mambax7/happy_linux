<?php
// $Id: search.php,v 1.1 2006/09/11 07:44:37 ohwada Exp $

// 2006-09-01 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-09-01 K.OHWADA
//=========================================================

include_once XOOPS_ROOT_PATH . '/modules/happy_linux/include/multibyte.php';

//---------------------------------------------------------
// porting from Suin's Search module <http://suin.jp/>
//---------------------------------------------------------
function happy_linux_build_search_context($text, $word_array, $max = 255)
{
    if (!is_array($word_array)) {
        $word_array = array();
    }

    $ret    = '';
    $q_word = str_replace(' ', '|', preg_quote(implode(' ', $word_array), '/'));

    if (preg_match("/$q_word/i", $text, $match)) {
        $ret = ltrim(preg_replace('/\s+/', ' ', $text));
        list($pre, $aft) = preg_split("/$q_word/i", $ret, 2);
        $m   = (int)($max / 2);
        $ret = (strlen($pre) > $m) ? '... ' : '';
        $ret .= happy_linux_strcut($pre, max(strlen($pre) - $m + 1, 0), $m) . $match[0];
        $m = $max - strlen($ret);
        $ret .= happy_linux_strcut($aft, 0, min(strlen($aft), $m));
        if (strlen($aft) > $m) {
            $ret .= ' ...';
        }
    }

    if (!$ret) {
        $ret = happy_linux_strcut($text, 0, $max);
    }

    return $ret;
}
