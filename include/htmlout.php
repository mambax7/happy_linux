<?php
// $Id: htmlout.php,v 1.1 2008/01/30 08:33:13 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2008-01-20 K.OHWADA
//=========================================================

//---------------------------------------------------------
// <script type="text/javascript" src="xxx.js"></script>
// <script>...</script>
//---------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_script($str)
{
    return preg_replace('|<\s*script.*?>.*?<\s*/\s*script\s*>|is', '', $str);
}

/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_replace_script($str)
{
    $str = preg_replace('|<\s*/\s*script\s*>|is', '&lt; /script &gt;', $str);
    $str = preg_replace('|<\s*script(.*?)>|is', '&lt; script \\1 &gt;', $str);

    return $str;
}

//---------------------------------------------------------
// <style>...</style>
//---------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_style($str)
{
    return preg_replace('|<\s*style.*?>.*?<\s*/\s*style\s*>|is', '', $str);
}

/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_replace_style($str)
{
    $str = preg_replace('|<\s*/\s*style\s*>|is', '&lt; /style &gt;', $str);
    $str = preg_replace('|<\s*style(.*?)>|is', '&lt; style \\1 &gt;', $str);

    return $str;
}

//---------------------------------------------------------
// <link rel="stylesheet" href="xxx.css" >
//---------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_link($str)
{
    return preg_replace('|<\s*link.*?>|is', '', $str);
}

/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_replace_link($str)
{
    return preg_replace('|<\s*link(.*?)>|is', '&lt; link \\1 &gt;', $str);
}

//---------------------------------------------------------
// <!-- ... -->
//---------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_comment($str)
{
    $str = preg_replace('|<\!--.*-->|is', '', $str);

    return $str;
}

/**
 * @param $str
 * @return string|string[]
 */
function happylinux_html_replace_comment($str)
{
    $str = str_replace('<!--', '&lt; !--', $str);
    $str = str_replace('-->', '-- &gt;', $str);

    return $str;
}

//---------------------------------------------------------
// <![CDATA[ ... ]]>
// <![CDATA[ ... ]]&gt;
//---------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_cdata($str)
{
    $str = preg_replace('|<\!\[CDATA\[.*\]\]>|is', '', $str);
    $str = preg_replace('|<\!\[CDATA\[.*\]\]&gt;|is', '', $str);

    return $str;
}

/**
 * @param $str
 * @return string|string[]
 */
function happylinux_html_replace_cdata($str)
{
    $str = str_replace('<![CDATA[', '&lt;![CDATA[', $str);
    $str = str_replace(']]>', ']] &gt;', $str);

    return $str;
}

//---------------------------------------------------------
// onmouseover="..."
// onclick="..."
//---------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_onmouse($str)
{
    return preg_replace('|on\w+=([\'\"]?).*?\\1|is', '', $str);
}

/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_replace_onmouse($str)
{
    return preg_replace('|on(\w+)=|', 'on_\\1=', $str);
}

//---------------------------------------------------------
// style="..."
//---------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_attr_style($str)
{
    return preg_replace('|style=([\'\"]?).*?\\1|is', '', $str);
}

/**
 * @param $str
 * @return string|string[]
 */
function happylinux_html_replace_attr_style($str)
{
    return str_replace('style=', 'style_=', $str);
}

//---------------------------------------------------------
// class="..."
//---------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_attr_class($str)
{
    return preg_replace('|class=([\'\"]?).*?\\1|is', '', $str);
}

/**
 * @param $str
 * @return string|string[]
 */
function happylinux_html_replace_attr_class($str)
{
    return str_replace('class=', 'class_=', $str);
}

//---------------------------------------------------------
// id="..."
//---------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_attr_id($str)
{
    return preg_replace('|id=([\'\"]?).*?\\1|is', '', $str);
}

/**
 * @param $str
 * @return string|string[]
 */
function happylinux_html_replace_attr_id($str)
{
    return str_replace('id=', 'id_=', $str);
}

//---------------------------------------------------------
// JavaScriprt
//---------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_javascript($str)
{
    return happylinux_html_replace_javascript($str, '');
}

/**
 * @param        $str
 * @param string $replace
 * @return string|string[]|null
 */
function happylinux_html_replace_javascript($str, $replace = 'java_script')
{
    return preg_replace('|javascript|is', $replace, $str);
}

/**
 * @param $str
 * @return bool
 */
function happylinux_html_check_javascript($str)
{
    if (preg_match('|javascript|is', $str)) {
        return true;
    }

    return false;
}

/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_javascript_colon($str)
{
    return happylinux_html_replace_javascript_colon($str, '');
}

/**
 * @param        $str
 * @param string $replace
 * @return string|string[]|null
 */
function happylinux_html_replace_javascript_colon($str, $replace = 'java_script_:')
{
    return preg_replace('|javascript:|is', $replace, $str);
}

/**
 * @param $str
 * @return bool
 */
function happylinux_html_check_javascript_colon($str)
{
    if (preg_match('|javascript:|is', $str)) {
        return true;
    }

    return false;
}

//---------------------------------------------------------
// vbscript:
//---------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_vbscript_colon($str)
{
    return happylinux_html_replace_vbscript_colon($str, '');
}

/**
 * @param        $str
 * @param string $replace
 * @return string|string[]|null
 */
function happylinux_html_replace_vbscript_colon($str, $replace = 'vb_script_:')
{
    return preg_replace('|vbscript:|is', $replace, $str);
}

/**
 * @param $str
 * @return bool
 */
function happylinux_html_check_vbscript_colon($str)
{
    if (preg_match('|vbscript:|is', $str)) {
        return true;
    }

    return false;
}

//---------------------------------------------------------
// about:
//---------------------------------------------------------
/**
 * @param $str
 * @return string|string[]|null
 */
function happylinux_html_remove_about_colon($str)
{
    return happylinux_html_replace_about_colon($str, '');
}

/**
 * @param        $str
 * @param string $replace
 * @return string|string[]|null
 */
function happylinux_html_replace_about_colon($str, $replace = 'about_:')
{
    return preg_replace('|about:|is', $replace, $str);
}

/**
 * @param $str
 * @return bool
 */
function happylinux_html_check_about_colon($str)
{
    if (preg_match('|about:|is', $str)) {
        return true;
    }

    return false;
}
