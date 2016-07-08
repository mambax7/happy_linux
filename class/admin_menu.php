<?php
// $Id: admin_menu.php,v 1.4 2009/01/05 17:50:39 ohwada Exp $

// 2008-12-20 K.OHWADA
// extra in print_form_upgrade()

// 2007-11-24 K.OHWADA
// build_admin_bread_crumb()

//=========================================================
// Happy Linux Framework Module
// 2007-11-11 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_admin_menu
//=========================================================
class happy_linux_admin_menu
{
    public $_STYLE_HIGHLIGHT = 'color:#ff0000; font_weight:bold;';
    public $_STYLE_DIV       = 'background-color: #dde1de; border: 1px solid #808080; margin: 5px; padding: 10px 10px 5px 10px; width: 90%;';
    public $_STYLE_SPAN      = 'font-size: 120%; font-weight: bold; color: #000000;';
    public $_STYLE_ERROR     = 'color: #ff0000; background-color: #ffffe0; border: #808080 1px dotted; padding: 3px 3px 3px 3px;';

    public $_token_error = null;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // dummy
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_admin_menu();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // menu
    //---------------------------------------------------------
    public function build_header($dirname, $desc = null)
    {
        $text = '<h3>' . $dirname . ' : ' . $this->get_xoops_module_name() . "</h3>\n";
        if ($desc) {
            $text .= $desc . "<br /><br />\n";
        }
        return $text;
    }

    public function build_footer()
    {
        $text = "<br /><hr />\n";
        if (class_exists('happy_linux_time')) {
            $time = happy_linux_time::getInstance();
            $text .= $time->build_elapse_time() . "<br />\n";
        }
        if (function_exists('happy_linux_build_memory_usage_mb')) {
            $text .= happy_linux_build_memory_usage_mb() . "<br />\n";
        }
        return $text;
    }

    public function build_powerdby()
    {
        $url = 'http://linux2.ohwada.net/';
        if (function_exists('get_happy_linux_url')) {
            $url = get_happy_linux_url();
        }
        $text = '<div align="right">';
        $text .= '<a href="' . $url . '" target="_blank">';
        $text .= '<font size="-1">Powered by Happy Linux</font>';
        $text .= "</a></div>\n";
        $text .= '<div align="right"><font size="-1">';
        $text .= '&copy; 2004 - ' . date('Y') . ', Kenichi OHWADA';
        $text .= "</font></div>\n";
        return $text;
    }

    //-------------------------------------------------------------------
    // bread_crumb
    //-------------------------------------------------------------------
    public function build_admin_bread_crumb($name1, $url1 = '', $name2 = '')
    {
        $arr = array(
            array(
                'name' => $this->get_xoops_module_name(),
                'url'  => 'index.php',
            ),
        );

        if ($name1) {
            $arr[] = array(
                'name' => $name1,
                'url'  => $url1,
            );
        }

        if ($name2) {
            $arr[] = array(
                'name' => $name2,
            );
        }

        return $this->build_bread_crumb($arr);
    }

    public function build_bread_crumb($paths)
    {
        $arr = array();
        foreach ($paths as $path) {
            if (isset($path['url']) && isset($path['name']) && $path['url'] && $path['name']) {
                $url = '<a href="' . $this->sanitize($path['url']) . '">';
                $url .= $this->sanitize($path['name']) . '</a>';
                $arr[] = $url;
            } elseif (isset($path['name']) && $path['name']) {
                $arr[] = '<b>' . $this->sanitize($path['name']) . '</b>';
            }
        }

        $text = ' ';
        $text .= implode(' &gt;&gt; ', $arr);
        $text .= " <br />\n";

        return $text;
    }

    //-------------------------------------------------------------------
    // menu_table
    //-------------------------------------------------------------------
    public function build_menu_table($menu_arr, $MAX_COL = 5, $width = '', $outer = 'outer', $even = 'even', $odd = 'odd')
    {
        if (empty($width)) {
            $width = (int)(100 / $MAX_COL) - 1;
            if ($width <= 0) {
                $width = 1;
            }
            $width .= '%';
        }

        $col_count  = 0;
        $line_count = 0;
        $class      = $odd;

        $text = '<table class="' . $outer . '" cellpadding="4" cellspacing="1" >' . "\n";

        foreach ($menu_arr as $name => $url) {
            // column begin
            if ($col_count == 0) {
                if ($line_count % 2 == 0) {
                    $class = $odd;
                } else {
                    $class = $even;
                }

                $text .= '<tr>';
            }

            $class = ($class == $even) ? $odd : $even;
            $text .= '<td class="' . $class . '" width="' . $width . '" align="center" valign="bottom" >';

            if ($name && $url) {
                $text .= '<a href="' . $url . '"><b>' . $name . '</b></a>';
            } else {
                $text .= '&nbsp;';
            }

            $text .= "</td>\n";

            ++$col_count;

            // column end
            if ($col_count >= $MAX_COL) {
                $col_count = 0;
                ++$line_count;

                $text .= "</tr>\n";
            }
        }

        $col_count_2 = $col_count;

        if ($col_count_2 && ($col_count_2 < $MAX_COL)) {
            while ($col_count < $MAX_COL) {
                $class = ($class == $even) ? $odd : $even;
                $text .= '<td class="' . $class . '">&nbsp;</td>';
                ++$col_count;

                // column end
                if ($col_count >= $MAX_COL) {
                    $text .= "</tr>\n";
                }
            }
        }

        $text .= "</table><br />\n";
        return $text;
    }

    //---------------------------------------------------------
    // form
    //---------------------------------------------------------
    public function print_form_init()
    {
        xoops_error(_HAPPY_LINUX_FORM_INIT_NOT);
        echo $this->build_form('init', _HAPPY_LINUX_FORM_INIT_EXEC);
    }

    public function print_form_upgrade($ver, $extra = null)
    {
        $msg = sprintf(_HAPPY_LINUX_FORM_VERSION_NOT, $ver);
        if ($extra) {
            $msg .= "<br />\n" . $extra;
        }
        xoops_error($msg);
        echo $this->build_form('upgrade', _HAPPY_LINUX_FORM_UPGRADE_EXEC);
    }

    public function build_form($op, $title)
    {
        $form_name = $this->build_form_name();

        $text = '<div style="' . $this->_STYLE_DIV . '">';
        $text .= '<span style="' . $this->_STYLE_SPAN . '">';
        $text .= $title;
        $text .= "</span><br /><br />\n";
        $text .= '<form name="' . $form_name . '" id=". $form_name ." action="' . xoops_getenv('PHP_SELF') . '" method="post" >';
        $text .= $this->build_gticket_html();
        $text .= '<input type="hidden" name="op" id="op" value="' . $op . '" />';
        $text .= '<input type="submit" name="submit" id="submit" value="' . _HAPPY_LINUX_EXECUTE . '" />';
        $text .= '</form>';
        $text .= "</div><br />\n";
        return $text;
    }

    public function build_form_name()
    {
        return 'form_' . rand();
    }

    //---------------------------------------------------------
    // token
    //---------------------------------------------------------
    public function build_gticket_html()
    {
        global $xoopsGTicket;
        $text = '';
        if (is_object($xoopsGTicket)) {
            $text = $xoopsGTicket->getTicketHtml(rand()) . "\n";
        }
        return $text;
    }

    public function check_token()
    {
        return $this->check_gticket_token();
    }

    public function print_xoops_token_error()
    {
        xoops_error('Token Error');
        echo "<br />\n";
        echo $this->highlight_error($this->_token_error);
        echo "<br />\n";
    }

    public function check_gticket_token($allow_repost = false)
    {
        global $xoopsGTicket;
        if (is_object($xoopsGTicket)) {
            if (!$xoopsGTicket->check(true, '', $allow_repost)) {
                $this->_token_error = $xoopsGTicket->getErrors();
                return false;
            }
        }
        return true;
    }

    //---------------------------------------------------------
    // html
    //---------------------------------------------------------
    public function highlight_error($val)
    {
        $text = '<div style="' . $this->_STYLE_HIGHLIGHT . '" >';
        $text .= $val;
        $text .= "</div>\n";
    }

    public function highlight_number($num)
    {
        $text = $num;
        if ($num > 0) {
            $text = '<span style="' . $this->_STYLE_HIGHLIGHT . '" >';
            $text .= $num;
            $text .= '</span>';
        }
        return $text;
    }

    public function sanitize($str)
    {
        return htmlspecialchars($str, ENT_QUOTES);
    }

    //---------------------------------------------------------
    // xoops param
    //---------------------------------------------------------
    public function get_xoops_module_name($format = 's')
    {
        global $xoopsModule;
        return $xoopsModule->getVar('name', $format);
    }

    public function get_xoops_language()
    {
        global $xoopsConfig;
        return $xoopsConfig['language'];
    }

    // --- class end ---
}
