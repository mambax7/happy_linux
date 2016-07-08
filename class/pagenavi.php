<?php
// $Id: pagenavi.php,v 1.2 2012/04/08 18:22:28 ohwada Exp $

// 2012-04-02 K.OHWADA
// calc_page_last()

// 2006-09-01 K.OHWADA
// add set_sortid()

// 2006-07-10 K.OHWADA
// this is new file
// porting from rssc_pagenavi.php

//=========================================================
// Happy Linux Framework Module
// 2006-07-10 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_pagenavi
//=========================================================
class happy_linux_pagenavi
{
    public $_MAX_PAGELIST = 10;
    public $_PAGE_FIRST   = 1;

    // input parameter
    public $_perpage = -1;
    public $_total   = 0;

    // GET POST parameter
    public $_page_current = 1;
    public $_sortid       = 0;

    // local
    public $_start;
    public $_end;
    public $_sortid_default = 0;
    public $_max_sortid     = 0;
    public $_flag_sortid    = 0;
    public $_sort_arr       = array();

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
            $instance = new happy_linux_pagenavi();
        }

        return $instance;
    }

    //=========================================================
    // Public
    //=========================================================

    //---------------------------------------------------------
    // build pagenavi
    //
    // script:
    //   acceptable type: NULL, foo.php, foo.php?, foo.php?bar=abc
    //---------------------------------------------------------
    public function build($script = '', $total = -1, $page = -1, $perpage = -1, $sortid = -1)
    {
        if ($total < 0) {
            $total = $this->_total;
        }
        if ($page < 0) {
            $page = $this->_page_current;
        }
        if ($perpage < 0) {
            $perpage = $this->_perpage;
        }

        if (empty($script)) {
            $script = xoops_getenv('PHP_SELF');
        }

        if (($perpage > 0) && ($perpage >= $total)) {
            return '';
        }

        if ($this->_flag_sortid) {
            $script = $this->_add_script_sortid($script, $sortid);
        }

        $script      = $this->_add_script_page($script);
        $script_page = $this->_sanitize_html_url($script);

        // Page Numbering
        list($page_current, $page_last, $start) = $this->_calc_page($total, $page, $perpage);

        $prev = $page_current - 1;
        $next = $page_current + 1;

        $half = (int)($this->_MAX_PAGELIST / 2);

        $start = $this->_PAGE_FIRST;
        $end   = $page_last;

        if ($page_last > ($page_current + $half)) {
            $start = $page_current - $half;

            if ($start < $this->_PAGE_FIRST) {
                $start = $this->_PAGE_FIRST;
            }

            $end = $start + $this->_MAX_PAGELIST;

            if ($end > $page_last) {
                $end = $page_last;
            }
        } elseif ($page_last > $this->_MAX_PAGELIST) {
            $end = $page_current + $half;

            if ($end > $page_last) {
                $end = $page_last;
            }

            $start = $end - $this->_MAX_PAGELIST;

            if ($start < $this->_PAGE_FIRST) {
                $start = $this->_PAGE_FIRST;
            }
        }

        $navi = '';

        if ($page_last > 1) {
            if ($prev > 0) {
                $navi .= "<a href='$script_page$prev'><b><u>&laquo;</u></b></a>&nbsp;";
            }

            if ($start != $this->_PAGE_FIRST) {
                $navi .= "<a href='$script_page$this->_PAGE_FIRST'>[$this->_PAGE_FIRST]</a>&nbsp;";
            }

            for ($i = $start; $i <= $end; ++$i) {
                if ($i == $page_current) {
                    $navi .= "<b>($i)</b>&nbsp;";
                } else {
                    $navi .= "<a href='$script_page$i'>$i</a>&nbsp;";
                }
            }

            if ($end != $page_last) {
                $navi .= "<a href='$script_page$page_last'>[$page_last]</a>&nbsp;";
            }

            if ($page_last >= $next) {
                $navi .= "<a href='$script_page$next'><b><u>&raquo;</u></b></a>&nbsp;";
            }
        }

        return $navi;
    }

    public function calc_page_last($total = -1, $perpage = -1)
    {
        if ($total < 0) {
            $total = $this->_total;
        }
        if ($perpage < 0) {
            $perpage = $this->_perpage;
        }

        return $this->_calc_last($total, $perpage);
    }

    //---------------------------------------------------------
    // calc Start
    //---------------------------------------------------------
    public function calcStart($total = -1, $page = -1, $perpage = -1)
    {
        if ($total < 0) {
            $total = $this->_total;
        }
        if ($page < 0) {
            $page = $this->_page_current;
        }
        if ($perpage < 0) {
            $perpage = $this->_perpage;
        }

        $this->_calc_page($total, $page, $perpage);

        return $this->_start;
    }

    public function calcEnd($total = -1, $start = -1, $perpage = -1)
    {
        if ($total < 0) {
            $total = $this->_total;
        }
        if ($start < 0) {
            $start = $this->_start;
        }
        if ($perpage < 0) {
            $perpage = $this->_perpage;
        }

        $end = $start + $perpage;

        if ($end > $total) {
            $end = $total;
        }

        $this->_end = $end;
        return $end;
    }

    //---------------------------------------------------------
    // GET paramter
    //---------------------------------------------------------
    public function getGetPage()
    {
        $page                = $this->get_get_int('page', 1);
        $this->_page_current = $page;
        return $page;
    }

    public function getGetSortid()
    {
        $sortid        = $this->get_get_int('sortid', $this->_sortid_default);
        $sortid        = $this->_check_sortid($sortid);
        $this->_sortid = $sortid;
        return $sortid;
    }

    public function get_get_int($key, $default = 0)
    {
        if (isset($_GET[$key])) {
            $val = (int)$_GET[$key];
        } else {
            $val = (int)$default;
        }

        return $val;
    }

    //---------------------------------------------------------
    // POST paramter
    //---------------------------------------------------------
    public function getPostPage()
    {
        $page                = $this->get_post_int('page', 1);
        $this->_page_current = $page;
        return $page;
    }

    public function get_post_int($key, $default = 0)
    {
        if (isset($_POST[$key])) {
            $val = (int)$_POST[$key];
        } else {
            $val = (int)$default;
        }

        return $val;
    }

    //---------------------------------------------------------
    // set and get parameter
    //---------------------------------------------------------
    public function set_sortid($value)
    {
        $this->_sortid = $this->_check_sortid((int)$value);
    }

    public function setPerpage($value)
    {
        $value = (int)$value;

        if ($value > 0) {
            $this->_perpage = $value;
        } else {
            $this->_perpage = 0;
        }
    }

    public function setTotal($value)
    {
        $value = (int)$value;

        if ($value > 0) {
            $this->_total = $value;
        } else {
            $this->_total = 0;
        }
    }

    public function set_sortid_default($value)
    {
        $value = (int)$value;

        if ($value < 0) {
            $this->_sortid_default = 0;
        } elseif ($value > $this->_max_sortid) {
            $this->_sortid_default = $this->_max_sortid;
        } else {
            $this->_sortid_default = $value;
        }
    }

    public function set_max_sortid($value)
    {
        $value = (int)$value;

        if ($value > 0) {
            $this->_max_sortid = $value;
        } else {
            $this->_max_sortid = 0;
        }
    }

    public function set_flag_sortid($value)
    {
        $this->_flag_sortid = (int)$value;
    }

    public function get_page_current()
    {
        return $this->_page_current;
    }

    //---------------------------------------------------------
    // sort parameter
    //---------------------------------------------------------
    public function clear_sort()
    {
        $this->_max_sortid = 0;
        $this->_sort_arr   = array();
    }

    public function add_sort($title, $sort, $order = 'ASC')
    {
        if (strtoupper($order) == 'DESC') {
            $order = 'DESC';
        } else {
            $order = 'ASC';
        }

        $this->_sort_arr[$this->_max_sortid] = array(
            'title' => $title,
            'sort'  => $sort,
            'order' => $order,
        );

        $this->_max_sortid++;
    }

    public function get_sort($sort_id = -1)
    {
        if ($sort_id == -1) {
            $sort_id = $this->_sortid;
        }

        $sort_id = $this->_check_sortid($sort_id);

        if ($this->_sort_arr[$sort_id]) {
            $ret = $this->_sort_arr[$sort_id];
            return $ret;
        }

        return false;
    }

    public function get_sort_value($sort_id = -1, $key)
    {
        if ($sort_id == -1) {
            $sort_id = $this->_sortid;
        }

        $sort_id = $this->_check_sortid($sort_id);

        if ($this->_sort_arr[$sort_id][$key]) {
            $ret = $this->_sort_arr[$sort_id][$key];
            return $ret;
        }

        return false;
    }

    //=========================================================
    // Private
    //=========================================================

    //---------------------------------------------------------
    // add_script_sortid
    //
    // sortid:
    //   normal: add sortid and page
    //       ex) foo.php? sortid=$sortid
    //   -1: substitute local variable
    //       ex) foo.php? sortid=$this->_sortid
    //   -2: dont add sortid
    //       ex) foo.php
    //---------------------------------------------------------
    public function _add_script_sortid($script, $sortid = -1)
    {
        if ($sortid == -2) {
            return $script;
        }

        if ($sortid == -1) {
            $sortid = $this->_sortid;
        }

        $type = $this->_analyze_script_type($script);

        // add 'sortid='
        if ($type == 1) {
            $script_sortid = $script . 'sortid=' . $sortid;
        } elseif ($type == 2) {
            $script_sortid = $script . '&sortid=' . $sortid;
        } else {
            $script_sortid = $script . '?sortid=' . $sortid;
        }

        return $script_sortid;
    }

    //---------------------------------------------------------
    // add_script_page
    //---------------------------------------------------------
    public function _add_script_page($script)
    {
        $type = $this->_analyze_script_type($script);

        // add 'page='
        if ($type == 1) {
            $script_page = $script . 'page=';
        } elseif ($type == 2) {
            $script_page = $script . '&page=';
        } else {
            $script_page = $script . '?page=';
        }

        return $script_page;
    }

    //---------------------------------------------------------
    // analyze_script_type
    //
    // script:
    //   type 0: foo.php
    //   type 1: foo.php?
    //   type 2: foo.php?bar=abc
    //---------------------------------------------------------
    public function _analyze_script_type($script)
    {
        $type = 0;  // foo.php

        // set script_type, if ? in script
        if (preg_match('/\?/', $script)) {
            $script_arr = explode('?', $script);

            if ($script_arr[1]) {
                $type = 2;  // foo.php?bar=abc
            } else {
                $type = 1;  // foo.php?
            }
        }

        return $type;
    }

    public function _sanitize_html_url($str)
    {
        $str = $this->_deny_javascript($str);
        $str = preg_replace('/&amp;/i', '&', $str);
        $str = htmlspecialchars($str, ENT_QUOTES);
        return $str;
    }

    // Checks if Javascript are included in string
    public function _deny_javascript($str)
    {
        $str = preg_replace('/[\x00-\x1F]/', '', $str);
        $str = preg_replace('/[\x7F]/', '', $str);

        if (preg_match('/javascript:/si', $str)) {
            return '';  // include Javascript
        }
        if (preg_match('/about:/si', $str)) {
            return '';  // include about
        }
        if (preg_match('/vbscript:/si', $str)) {
            return '';  // include vbscript
        }

        return $str;
    }

    //---------------------------------------------------------
    // calculation
    //---------------------------------------------------------
    public function _calc_page($total, $page, $perpage)
    {
        $current = 0;
        $last    = 0;
        $start   = 0;;

        if ($perpage <= 0) {
            return array($current, $last, $start);
        }

        $last = $this->_calc_last($total, $perpage);

        if ($last < 1) {
            $last = 1;
        }

        if ($page < 1) {
            $current = 1;
        } elseif ($page > $last) {
            $current = $last;
        } else {
            $current = $page;
        }

        $start        = ($current - 1) * $perpage;
        $this->_start = $start;

        return array($current, $last, $start);
    }

    public function _calc_last($total, $perpage)
    {
        $last = ceil($total / $perpage);
        return $last;
    }

    //---------------------------------------------------------
    // sortid
    //---------------------------------------------------------
    public function _check_sortid($id)
    {
        $id = (int)$id;

        if ($id < 0) {
            $id = 0;
        }

        if ($id > $this->_max_sortid) {
            $id = $this->_max_sortid;
        }

        return $id;
    }

    // --- class end ---
}
