<?php
// $Id: sanitize.php,v 1.2 2008/01/30 08:33:13 ohwada Exp $

// 2008-01-20 K.OHWADA
// happy_linux_sanitize_var_export()

//=========================================================
// Happy Linux Framework Module
// 2007-08-01 K.OHWADA
//=========================================================

function happy_linux_sanitize($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

function happy_linux_sanitize_text($str)
{
    $str = htmlspecialchars($str, ENT_QUOTES);
    $str = happy_linux_undo_html_entity_name($str);
    $str = happy_linux_undo_html_entity_numeric($str);
    return $str;
}

function happy_linux_sanitize_url($str)
{
    $str = happy_linux_undo_htmlspecialchars($str);
    $str = htmlspecialchars($str, ENT_QUOTES);
    $str = happy_linux_undo_html_entity_name($str);
    $str = happy_linux_undo_html_entity_numeric($str);
    return $str;
}

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
function happy_linux_undo_htmlspecialchars($str)
{
    $arr = array(
        '&amp;'  => '&',
        '&lt;'   => '<',
        '&gt;'   => '>',
        '&quot;' => '"',
        '&#39;'  => "'",
        '&#039;' => "'",
        '&apos;' => "'",
    );
    $str = strtr($str, $arr);
    return $str;
}

// --------------------------------------------------------
// exsample (c): &amp;copy; -> &copy;
// --------------------------------------------------------
function happy_linux_undo_html_entity_name($str)
{
    return preg_replace('/\&amp\;([0-9a-zA-Z]{2,10}\;)/', '&\\1', $str);
}

// --------------------------------------------------------
// exsample <!>: &amp;#033; -> &#033;
// --------------------------------------------------------
function happy_linux_undo_html_entity_numeric($str)
{
    return preg_replace('/\&amp\;\#([0-9]{2,10}\;)/', '&#\\1', $str);
}
