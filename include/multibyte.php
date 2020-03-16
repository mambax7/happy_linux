<?php

use XoopsModules\Happylinux;

// $Id: multibyte.php,v 1.10 2007/12/23 00:36:17 ohwada Exp $

// 2007-12-22 K.OHWADA
// BUG: not show smile icon

// 2007-10-10 K.OHWADA
// happylinux_str_add_space_after_punctuation()

// 2007-08-01 K.OHWADA
// happylinux_mb_language() happylinux_shorten()

// 2007-06-10 K.OHWADA
// happylinux_detect_encoding()

// 2006-10-26 K.OHWADA
// BUG 4339: Fatal error: Call to undefined function: strcut()

// 2006-09-10 K.OHWADA
// this is new file

//=========================================================
// Happy Linux Framework Module
// 2006-09-01 K.OHWADA
//=========================================================

//---------------------------------------------------------
// subsutite for multibyte string function
//---------------------------------------------------------

/**
 * @param      $str
 * @param      $to
 * @param null $from
 * @return string
 */
function happylinux_convert_encoding($str, $to, $from = null)
{
    if (function_exists('mb_convert_encoding')) {
        if ($from) {
            return mb_convert_encoding($str, $to, $from);
        }

        return mb_convert_encoding($str, $to);
    }

    return $str;
}

/**
 * @param      $str
 * @param null $encoding
 * @return string
 */
function happylinux_convert_to_utf8($str, $encoding = null)
{
    if (empty($encoding)) {
        $encoding = _CHARSET;
    }

    if (function_exists('mb_convert_encoding')) {
        $str = mb_convert_encoding($str, 'UTF-8', $encoding);
    } else {
        $str = utf8_encode($str);
    }

    return $str;
}

/**
 * @param      $str
 * @param null $encoding
 * @return string
 */
function happylinux_convert_from_utf8($str, $encoding = null)
{
    if (empty($encoding)) {
        $encoding = _CHARSET;
    }

    if (function_exists('mb_convert_encoding')) {
        $str = mb_convert_encoding($str, $encoding, 'UTF-8');
    } else {
        $str = utf8_decode($str);
    }

    return $str;
}

/**
 * @param        $str
 * @param string $option
 * @param null   $encoding
 * @return string
 */
function happylinux_convert_kana($str, $option = 'KV', $encoding = null)
{
    if (function_exists('mb_convert_kana')) {
        if ($encoding) {
            return mb_convert_kana($str, $option, $encoding);
        }

        return mb_convert_kana($str, $option);
    }

    return $str;
}

/**
 * @param null $encoding
 * @return bool|string
 */
function happylinux_internal_encoding($encoding = null)
{
    if (function_exists('mb_internal_encoding')) {
        if ($encoding) {
            return mb_internal_encoding($encoding);
        }

        return mb_internal_encoding();
    }
}

/**
 * @param      $str
 * @param null $encoding_list
 * @param null $strict
 * @return bool|false|string
 */
function happylinux_detect_encoding($str, $encoding_list = null, $strict = null)
{
    if (function_exists('mb_detect_encoding')) {
        if ($encoding_list && $strict) {
            return mb_detect_encoding($str, $encoding_list, $strict);
        } elseif ($encoding_list) {
            return mb_detect_encoding($str, $encoding_list);
        }

        return mb_detect_encoding($str);
    }

    return false;
}

/**
 * @param      $str
 * @param      $start
 * @param      $length
 * @param null $encoding
 * @return string
 */
function happylinux_strcut($str, $start, $length, $encoding = null)
{
    if (function_exists('mb_strcut')) {
        if ($encoding) {
            return mb_strcut($str, $start, $length, $encoding);
        }

        return mb_strcut($str, $start, $length);
    }

    // BUG 4339: Fatal error: Call to undefined function: strcut()
    // strcut -> substr
    return mb_substr($str, $start, $length);
}

/**
 * @param null $encoding
 * @return bool|string
 */
function happylinux_http_output($encoding = null)
{
    if (function_exists('mb_http_output')) {
        if ($encoding) {
            return mb_http_output($encoding);
        }

        return mb_http_output();
    }
}

/**
 * @param null $language
 * @return bool|string
 */
function happylinux_mb_language($language = null)
{
    if (function_exists('mb_language')) {
        if ($language) {
            return mb_language($language);
        }

        return mb_language();
    }
}

/**
 * @param      $mailto
 * @param      $subject
 * @param      $message
 * @param null $headers
 * @param null $parameter
 * @return bool
 */
function happylinux_send_mail($mailto, $subject, $message, $headers = null, $parameter = null)
{
    if (function_exists('mb_send_mail')) {
        if ($parameter) {
            return mb_send_mail($mailto, $subject, $message, $headers, $parameter);
        } elseif ($headers) {
            return mb_send_mail($mailto, $subject, $message, $headers);
        }

        return mb_send_mail($mailto, $subject, $message);
    }

    if ($parameter) {
        return mail($mailto, $subject, $message, $headers, $parameter);
    } elseif ($headers) {
        return mail($mailto, $subject, $message, $headers);
    }

    return mail($mailto, $subject, $message);
}

/**
 * @param      $pattern
 * @param      $replace
 * @param      $string
 * @param null $option
 * @return false|string
 */
function happylinux_mb_ereg_replace($pattern, $replace, $string, $option = null)
{
    if (function_exists('mb_ereg_replace')) {
        if ($option) {
            return mb_ereg_replace($pattern, $replace, $string, $option);
        }

        return mb_ereg_replace($pattern, $replace, $string);
    }
}

//---------------------------------------------------------
// shorten strings
// max: plus=shorten, 0=null, -1=unlimited
//---------------------------------------------------------
/**
 * @param        $str
 * @param        $max
 * @param string $tail
 * @return string|null
 */
function happylinux_mb_shorten($str, $max, $tail = ' ...')
{
    $text = $str;
    if (($max > 0) && (mb_strlen($str) > $max)) {
        $text = happylinux_strcut($str, 0, $max) . $tail;
    } elseif (0 == $max) {
        $text = null;
    }

    return $text;
}

//---------------------------------------------------------
// build summary
//---------------------------------------------------------
/**
 * @param        $str
 * @param        $max
 * @param string $tail
 * @param bool   $is_japanese
 * @return string|null
 */
function happylinux_mb_build_summary($str, $max, $tail = ' ...', $is_japanese = false)
{
    if ($is_japanese) {
        $str = happylinux_convert_kana($str, 's');
        $str = happylinux_str_add_space_after_punctuation_ja($str);
    }

    // BUG: not show smile icon
    //  $str = happylinux_str_add_space_after_punctuation($str);

    $str = happylinux_str_add_space_after_tag($str);
    $str = strip_tags($str);
    $str = happylinux_str_replace_control_code($str);
    $str = happylinux_str_replace_tab_code($str);
    $str = happylinux_str_replace_return_code($str);
    $str = happylinux_str_replace_html_space_code($str);
    $str = happylinux_str_replace_continuous_space_code($str);
    $str = happylinux_str_set_empty_if_only_space($str);
    $str = happylinux_mb_shorten($str, $max, $tail);

    return $str;
}

/**
 * @param $str
 * @return string|string[]
 */
function happylinux_str_add_space_after_tag($str)
{
    return happylinux_str_add_space_after_str('>', $str);
}

// BUG: not show smile icon
// recommend not to use, because strong side effect
// "abc.gif" => "abc. gif"
/**
 * @param $str
 * @return string|string[]
 */
function happylinux_str_add_space_after_punctuation($str)
{
    $str = happylinux_str_add_space_after_str(',', $str);
    $str = happylinux_str_add_space_after_str('.', $str);

    return $str;
}

/**
 * @param $str
 * @return false|string
 */
function happylinux_str_add_space_after_punctuation_ja($str)
{
    // japanese punctuation mark
    if (defined('_HAPPYLINUX_JA_KUTEN')) {
        $str = happylinux_mb_add_space_after_str(_HAPPYLINUX_JA_KUTEN, $str);
        $str = happylinux_mb_add_space_after_str(_HAPPYLINUX_JA_DOKUTEN, $str);
        $str = happylinux_mb_add_space_after_str(_HAPPYLINUX_JA_PERIOD, $str);
        $str = happylinux_mb_add_space_after_str(_HAPPYLINUX_JA_COMMA, $str);
    }

    return $str;
}

/**
 * @param $word
 * @param $string
 * @return string|string[]
 */
function happylinux_str_add_space_after_str($word, $string)
{
    return str_replace($word, $word . ' ', $string);
}

/**
 * @param $word
 * @param $string
 * @return false|string
 */
function happylinux_mb_add_space_after_str($word, $string)
{
    return happylinux_mb_ereg_replace($word, $word . ' ', $string);
}

/**
 * @param $str
 * @return string
 */
function happylinux_str_set_empty_if_only_space($str)
{
    $temp = happylinux_str_replace_space_code($str, '');
    if (0 == mb_strlen($temp)) {
        $str = '';
    }

    return $str;
}

/**
 * @return bool
 */
function happylinux_is_japanese()
{
    global $xoopsConfig;
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/lang_name_ja.php';
    if (in_array($xoopsConfig['language'], happylinux_get_lang_name_ja())) {
        return true;
    }

    return false;
}

//---------------------------------------------------------
// TAB \x09 \t
// LF  \xOA \n
// CR  \xOD \r
//---------------------------------------------------------
/**
 * @param        $str
 * @param string $replace
 * @return string|string[]|null
 */
function happylinux_str_replace_control_code($str, $replace = ' ')
{
    $str = preg_replace('/[\x00-\x08]/', $replace, $str);
    $str = preg_replace('/[\x0B-\x0C]/', $replace, $str);
    $str = preg_replace('/[\x0E-\x1F]/', $replace, $str);
    $str = preg_replace('/[\x7F]/', $replace, $str);

    return $str;
}

/**
 * @param        $str
 * @param string $replace
 * @return string|string[]|null
 */
function happylinux_str_replace_tab_code($str, $replace = ' ')
{
    return preg_replace("/\t/", $replace, $str);
}

/**
 * @param        $str
 * @param string $replace
 * @return string|string[]|null
 */
function happylinux_str_replace_return_code($str, $replace = ' ')
{
    $str = preg_replace("/\n/", $replace, $str);
    $str = preg_replace("/\r/", $replace, $str);

    return $str;
}

/**
 * @param        $str
 * @param string $replace
 * @return string|string[]|null
 */
function happylinux_str_replace_html_space_code($str, $replace = ' ')
{
    return preg_replace('/&nbsp;/i', $replace, $str);
}

/**
 * @param        $str
 * @param string $replace
 * @return string|string[]|null
 */
function happylinux_str_replace_space_code($str, $replace = ' ')
{
    return preg_replace("/[\x20]/", $replace, $str);
}

/**
 * @param        $str
 * @param string $replace
 * @return string|string[]|null
 */
function happylinux_str_replace_continuous_space_code($str, $replace = ' ')
{
    return preg_replace("/[\x20]+/", $replace, $str);
}
