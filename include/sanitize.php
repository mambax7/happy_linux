<?php
// $Id: sanitize.php,v 1.2 2008/01/30 08:33:13 ohwada Exp $

// 2008-01-20 K.OHWADA
// happylinux_sanitize_var_export()

//=========================================================
// Happy Linux Framework Module
// 2007-08-01 K.OHWADA
//=========================================================

/**
 * @param $str
 * @return string
 */
function happylinux_sanitize($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_sanitize_text($str)
{
    $str = htmlspecialchars($str, ENT_QUOTES);
    $str = happylinux_undo_html_entity_name($str);
    $str = happylinux_undo_html_entity_numeric($str);

    return $str;
}

/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_sanitize_url($str)
{
    $str = happylinux_undo_htmlspecialchars($str);
    $str = htmlspecialchars($str, ENT_QUOTES);
    $str = happylinux_undo_html_entity_name($str);
    $str = happylinux_undo_html_entity_numeric($str);

    return $str;
}

/**
 * @param $arr
 * @return string
 */
function happylinux_sanitize_var_export($arr)
{
    return happylinux_sanitize(var_export($arr, true));
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
function happylinux_undo_htmlspecialchars($str)
{
    $arr = [
        '&amp;'  => '&',
        '&lt;'   => '<',
        '&gt;'   => '>',
        '&quot;' => '"',
        '&#39;'  => "'",
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
function happylinux_undo_html_entity_name($str)
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
function happylinux_undo_html_entity_numeric($str)
{
    return preg_replace('/\&amp\;\#([0-9]{2,10}\;)/', '&#\\1', $str);
}
