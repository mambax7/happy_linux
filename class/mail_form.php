<?php
// $Id: mail_form.php,v 1.1 2007/09/15 06:47:26 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-09-01 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_mail_form
//=========================================================
class happy_linux_mail_form extends happy_linux_form
{
    public $_FORM_NAME = 'mail_form';
    public $_OP        = 'send_mail';
    public $_BODY_ROWS = 10;
    public $_BODY_COLS = 60;
    public $_MAX_USER  = 50;

    public $_STYLE_BOLD   = 'font-size:x-small; font-weight:bold;';
    public $_STYLE_NORMAL = 'font-size:x-small; font-weight:normal;';

    public $_LANG_WEBMASTER    = _HAPPY_LINUX_MAIL_WEBMASTER;
    public $_LANG_SUBJECT_FROM = _HAPPY_LINUX_MAIL_SUBJECT_FROM;
    public $_LANG_HELLO        = _HAPPY_LINUX_MAIL_HELLO;
    public $_LANG_SUBMIT       = _HAPPY_LINUX_MAIL_SUBMIT;
    public $_LANG_SUBMIT_NEXT  = _HAPPY_LINUX_MAIL_SUBMIT_NEXT;

    public $_LANG_SKIP   = _HAPPY_LINUX_SKIP_TO_NEXT;
    public $_LANG_CANCEL = _CANCEL;

    // modules/system/language/xxx/admin/mailusers.php
    public $_LANG_SEND_TO_USERS = _AM_SENDMTOUSERS;
    public $_LANG_USERS_LABEL   = _AM_SENDTOUSERS2;
    public $_LANG_FROM_NAME     = _AM_MAILFNAME;
    public $_LANG_FROM_EMAIL    = _AM_MAILFMAIL;
    public $_LANG_SUBJECT       = _AM_MAILSUBJECT;
    public $_LANG_BODY          = _AM_MAILBODY;
    public $_LANG_MAILTAGS      = _AM_MAILTAGS;
    public $_LANG_MAILTAGS1     = _AM_MAILTAGS1;
    public $_LANG_MAILTAGS2     = _AM_MAILTAGS2;
    public $_LANG_MAILTAGS3     = _AM_MAILTAGS3;
    public $_LANG_MAILTAGS4     = _AM_MAILTAGS4;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_mail_form();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // print form
    //---------------------------------------------------------
    public function print_form_email()
    {
        $param = array(
            'op'          => 'send_email',
            'user_list'   => array(),
            'link_list'   => array(),
            'users_label' => $this->build_to_email_input(),
            'body'        => $this->get_body('xxx'),
        );

        $this->print_form($param);
    }

    public function print_form_user()
    {
        list($user_list, $users_label) = $this->get_post_memberslist();

        $param = array(
            'op'              => 'send_user',
            'user_list'       => $user_list,
            'users_label'     => $users_label,
            'subject_caption' => $this->get_subject_caption_user(),
            'body_caption'    => $this->get_body_caption_user(),
            'body'            => $this->get_body('{X_UNAME}'),
        );

        $this->print_form($param);
    }

    public function print_form(&$param)
    {
        $user_list   = isset($param['user_list']) ? $param['user_list'] : null;
        $hidden_list = isset($param['hidden_list']) ? $param['hidden_list'] : null;
        $users_label = isset($param['users_label']) ? $param['users_label'] : $this->build_to_email_input();
        $subject_cap = isset($param['subject_caption']) ? $param['subject_caption'] : $this->get_subject_caption();
        $body_cap    = isset($param['body_caption']) ? $param['body_caption'] : $this->get_body_caption();

        $form_name  = isset($param['form_name']) ? $this->sanitize_text($param['form_name']) : $this->get_form_name();
        $op         = isset($param['op']) ? $this->sanitize_text($param['op']) : $this->get_op();
        $from_name  = isset($param['from_name']) ? $this->sanitize_text($param['from_name']) : $this->get_xoops_sitename();
        $from_email = isset($param['from_email']) ? $this->sanitize_text($param['from_email']) : $this->get_xoops_adminmail();
        $subject    = isset($param['subject']) ? $this->sanitize_text($param['subject']) : $this->get_subject();
        $body       = isset($param['body']) ? $this->sanitize_textarea($param['body']) : $this->get_body();

        $body_rows = isset($param['body_rows']) ? (int)$param['body_rows'] : $this->_BODY_ROWS;
        $body_cols = isset($param['body_cols']) ? (int)$param['body_cols'] : $this->_BODY_COLS;
        $start     = isset($param['start']) ? (int)$param['start'] : 0;

        echo $this->build_form_begin($form_name);
        echo $this->build_token();
        echo $this->build_html_input_hidden('op', $op);
        echo $this->build_html_input_hidden('start', $start);

        if (is_array($user_list) && count($user_list)) {
            foreach ($user_list as $uid) {
                echo $this->build_html_input_hidden('user_list[]', $uid);
            }
        }

        if (is_array($hidden_list) && count($hidden_list)) {
            foreach ($hidden_list as $k => $v) {
                $key = '_hidden_' . $this->sanitize_text($k);
                $val = $this->sanitize_text($v);
                echo $this->build_html_input_hidden($key, $val);
            }
        }

        echo $this->build_form_table_begin();
        echo $this->build_form_table_title($this->_LANG_SEND_TO_USERS);
        echo $this->build_form_table_line($this->_LANG_USERS_LABEL, $users_label);
        echo $this->build_form_table_text($this->_LANG_FROM_NAME, 'from_name', $from_name);
        echo $this->build_form_table_text($this->_LANG_FROM_EMAIL, 'from_email', $from_email);
        echo $this->build_form_table_text($subject_cap, 'subject', $subject);
        echo $this->build_form_table_textarea($body_cap, 'body', $body, $body_rows, $body_cols);

        $ele_button = $this->build_submit_button($param);
        echo $this->build_form_table_line('', $ele_button, 'foot', 'foot');

        echo $this->build_form_table_end();
        echo $this->build_form_end();
    }

    public function print_form_next(&$param)
    {
        $user_list   = isset($param['user_list']) ? $param['user_list'] : null;
        $hidden_list = isset($param['hidden_list']) ? $param['hidden_list'] : null;
        $users_label = isset($param['users_label']) ? $param['users_label'] : $this->build_to_email_input();
        $subject_cap = isset($param['subject_caption']) ? $param['subject_caption'] : $this->get_subject_caption();
        $body_cap    = isset($param['body_caption']) ? $param['body_caption'] : $this->get_body_caption();

        $form_name  = isset($param['form_name']) ? $this->sanitize_text($param['form_name']) : $this->get_form_name();
        $op         = isset($param['op']) ? $this->sanitize_text($param['op']) : $this->get_op();
        $from_name  = isset($param['from_name']) ? $this->sanitize_text($param['from_name']) : $this->get_sitename();
        $from_email = isset($param['from_email']) ? $this->sanitize_text($param['from_email']) : $this->get_adminmail();
        $subject    = isset($param['subject']) ? $this->sanitize_text($param['subject']) : $this->get_subject();
        $body       = isset($param['body']) ? $this->sanitize_textarea($param['body']) : $this->get_body();

        $start = isset($param['start']) ? (int)$param['start'] : 0;

        if (!isset($param['submit'])) {
            $param['submit'] = $this->get_submit_next();
        }

        echo "<br />\n";
        echo $this->build_form_begin($form_name);
        echo $this->build_token();
        echo $this->build_html_input_hidden('op', $op);
        echo $this->build_html_input_hidden('start', $start);
        echo $this->build_html_input_hidden('from_name', $from_name);
        echo $this->build_html_input_hidden('from_email', $from_email);
        echo $this->build_html_input_hidden('subject', $subject);
        echo $this->build_html_input_hidden('body', $body);

        if (is_array($user_list) && count($user_list)) {
            foreach ($user_list as $uid) {
                echo $this->build_html_input_hidden('user_list[]', $uid);
            }
        }

        if (is_array($hidden_list) && count($hidden_list)) {
            foreach ($hidden_list as $k => $v) {
                $key = '_hidden_' . $this->sanitize_text($k);
                $val = $this->sanitize_text($v);
                echo $this->build_html_input_hidden($key, $val);
            }
        }

        echo $this->build_form_table_begin();
        echo $this->build_form_table_title($this->_LANG_SEND_TO_USERS);

        $ele_button = $this->build_submit_button($param);
        echo $this->build_form_table_line('', $ele_button, 'foot', 'foot');

        echo $this->build_form_table_end();
        echo $this->build_form_end();
    }

    public function build_submit_button(&$param)
    {
        $submit      = isset($param['submit']) ? $this->sanitize_text($param['submit']) : $this->get_submit();
        $url_cancel  = isset($param['url_cancel']) ? $this->sanitize_url($param['url_cancel']) : null;
        $flag_skip   = isset($param['flag_skip']) ? (bool)$param['flag_skip'] : false;
        $flag_cancel = isset($param['flag_cancel']) ? (bool)$param['flag_cancel'] : false;

        $ele_button = $this->build_html_input_submit('submit', $submit);
        if ($flag_skip) {
            $ele_button .= ' ' . $this->build_html_input_submit('skip', $this->_LANG_SKIP);
        }
        if ($flag_cancel) {
            $ele_button .= ' ' . $this->build_html_input_button_location('cancel', $this->_LANG_CANCEL, $url_cancel);
        }
        return $ele_button;
    }

    //---------------------------------------------------------
    // get POST param
    //---------------------------------------------------------
    public function get_post_memberslist()
    {
        $user_list   = array();
        $users_label = '';

        if (isset($_POST['memberslist_id']) && is_array($_POST['memberslist_id'])) {
            $user_list     =& $_POST['memberslist_id'];
            $user_count    = count($user_list);
            $display_names = '';

            for ($i = 0; $i < $user_count; ++$i) {
                $uid   = (int)$user_list[$i];
                $uname = $this->get_uname_by_uid($uid);
                $display_names .= "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $uid . "' target='_blank'>" . $uname . '</a>, ';
            }

            $users_label = substr($display_names, 0, -2);
        }

        return array($user_list, $users_label);
    }

    //---------------------------------------------------------
    // get param
    //---------------------------------------------------------
    public function get_form_name()
    {
        return $this->_FORM_NAME;
    }

    public function get_op()
    {
        return $this->_OP;
    }

    public function get_submit()
    {
        return $this->_LANG_SUBMIT;
    }

    public function get_submit_next()
    {
        return sprintf($this->_LANG_SUBMIT_NEXT, $this->_MAX_USER);
    }

    public function get_subject_caption()
    {
        return $this->_LANG_SUBJECT;
    }

    public function get_body_caption()
    {
        return $this->_LANG_BODY;
    }

    public function get_subject_caption_user()
    {
        return $this->build_mail_caption($this->_LANG_SUBJECT, $this->_LANG_MAILTAGS, $this->_LANG_MAILTAGS2);
    }

    public function get_body_caption_user()
    {
        $desc2 = $this->_LANG_MAILTAGS1 . "<br />\n";
        $desc2 .= $this->_LANG_MAILTAGS2 . "<br />\n" . $desc2 .= $this->_LANG_MAILTAGS3 . "<br />\n" . $desc2 .= $this->_LANG_MAILTAGS4;

        return $this->build_mail_caption($this->_LANG_BODY, $this->_LANG_MAILTAGS, $desc2);
    }

    public function build_mail_caption($title, $desc1, $desc2)
    {
        $caption = $title . "<br /><br />\n";
        if ($desc1) {
            $caption .= '<span style="' . $this->_STYLE_BOLD . '">' . $desc1 . "</span><br />\n";
        }
        if ($desc2) {
            $caption .= '<span style="' . $this->_STYLE_NORMAL . '">' . $desc2 . "</span>\n";
        }
        return $caption;
    }

    public function get_subject()
    {
        $text = sprintf($this->_LANG_SUBJECT_FROM, $this->get_xoops_sitename());
        return $text;
    }

    public function get_body($name = '')
    {
        $SITE_URL       = $this->get_xoops_siteurl();
        $SITE_NAME      = $this->get_xoops_sitename();
        $SITE_ADMINMAIL = $this->get_xoops_adminmail();

        $hello     = sprintf($this->_LANG_HELLO, $name);
        $webmaster = $this->_LANG_WEBMASTER;

        $text = <<<END_OF_TEXT
$hello

-----------
$SITE_NAME ({$SITE_URL})
$webmaster
$SITE_ADMINMAIL
-----------
END_OF_TEXT;

        return $text;
    }

    public function build_to_email_input($email = '')
    {
        return $this->build_html_input_text('to_email', $email);
    }

    public function build_to_email_label_hidden($email)
    {
        $text = $this->sanitize_text($email);
        $text .= ' ';
        $text .= $this->build_html_input_hidden('to_email', $email);
        return $text;
    }

    //---------------------------------------------------------
    // get system param
    //---------------------------------------------------------
    public function get_xoops_siteurl()
    {
        return XOOPS_URL . '/';
    }

    public function get_xoops_sitename()
    {
        global $xoopsConfig;
        return $xoopsConfig['sitename'];
    }

    public function get_xoops_adminmail()
    {
        global $xoopsConfig;
        return $xoopsConfig['adminmail'];
    }

    // name for "anonymous" if not found
    public function get_uname_by_uid($uid, $usereal = 0)
    {
        $uname = XoopsUser::getUnameFromId($uid, $usereal);
        return $uname;
    }

    //---------------------------------------------------------
    // set parameter
    //---------------------------------------------------------
    public function set_max_user($val)
    {
        $this->_MAX_USER = (int)$val;
    }

    // --- class end ---
}
