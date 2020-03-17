<?php

// $Id: sanitize.php,v 1.1 2010/11/07 14:59:12 ohwada Exp $

// 2008-01-20 K.OHWADA
// happy_linux_sanitize_var_export()

//=========================================================
// Happy Linux Framework Module
// 2007-08-01 K.OHWADA
//=========================================================

/**
 * @param $str
 * @return string
 */
function happy_linux_sanitize($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

/**
 * @param $str
 * @return string|string[]|null
 */
function happy_linux_sanitize_text($str)
{
    $str = htmlspecialchars($str, ENT_QUOTES);
    $str = happy_linux_undo_html_entity_name($str);
    $str = happy_linux_undo_html_entity_numeric($str);

    return $str;
}

/**
 * @param $str
 * @return string|string[]|null
 */
function happy_linux_sanitize_url($str)
{
    $str = happy_linux_undo_htmlspecialchars($str);
    $str = htmlspecialchars($str, ENT_QUOTES);
    $str = happy_linux_undo_html_entity_name($str);
    $str = happy_linux_undo_html_entity_numeric($str);

    return $str;
}

/**
 * @param $arr
 * @return string
 */
function happy_linux_sanitize_var_export($arr)
{
    return happy_linux_sanitize(var_export($arr, true));
}

// --------------------------------------------------------
// Invert special characters from HTML entities
//   &amp;   =>  &
//   &lt;    =>  <
//   &gt;    =>  >
//   &quot;  =>  "
//   &#39;   =>  '
//   &#039;  =>  '
//   &apos;  =>  ' (xml format)
// --------------------------------------------------------
/**
 * @param $str
 * @return string
 */
function happy_linux_undo_htmlspecialchars($str)
{
    $arr = [
        '&amp;' => '&',
        '&lt;' => '<',
        '&gt;' => '>',
        '&quot;' => '"',
        '&#39;' => "'",
        '&#039;' => "'",
        '&apos;' => "'",
    ];
    $str = strtr($str, $arr);

    return $str;
}

// --------------------------------------------------------
// exsample (c): &amp;copy; -> &copy;
// --------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happy_linux_undo_html_entity_name($str)
{
    return preg_replace('/\&amp\;([0-9a-zA-Z]{2,10}\;)/', '&\\1', $str);
}

// --------------------------------------------------------
// exsample <!>: &amp;#033; -> &#033;
// --------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happy_linux_undo_html_entity_numeric($str)
{
    return preg_replace('/\&amp\;\#([0-9]{2,10}\;)/', '&#\\1', $str);
}
