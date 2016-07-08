<?php
// $Id: mail_send.php,v 1.1 2007/09/15 06:47:26 ohwada Exp $

//=========================================================
// Happy Linux Framework Module
// 2007-09-01 K.OHWADA
//=========================================================

//=========================================================
// class happy_linux_mail_send
//=========================================================
class happy_linux_mail_send extends happy_linux_error
{
    public $_post;

    public $_LANG_ERR_NO_TO_EMAIL = _HAPPY_LINUX_MAIL_NO_TO_EMAIL;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->_post = happy_linux_post::getInstance();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_mail_send();
        }
        return $instance;
    }

    //---------------------------------------------------------
    // send email
    //---------------------------------------------------------
    public function send_email_by_post($debug = false)
    {
        $param = array(
            'to_emails'  => $this->_post->get_post_text('to_email'),
            'from_name'  => $this->_post->get_post_text('from_name'),
            'from_email' => $this->_post->get_post_text('from_email'),
            'subject'    => $this->_post->get_post_text('subject'),
            'body'       => $this->_post->get_post_text('body'),
            'debug'      => $debug,
        );

        return $this->send($param);
    }

    public function send($param)
    {
        $to_emails  = isset($param['to_emails']) ? $param['to_emails'] : null;
        $users      = isset($param['users']) ? $param['users'] : null;
        $subject    = isset($param['subject']) ? $param['subject'] : null;
        $body       = isset($param['body']) ? $param['body'] : null;
        $tags       = isset($param['tags']) ? $param['tags'] : null;
        $debug      = isset($param['debug']) ? $param['debug'] : false;
        $from_name  = isset($param['from_name']) ? $param['from_name'] : $this->get_xoops_sitename();
        $from_email = isset($param['from_email']) ? $param['from_email'] : $this->get_xoops_adminmail();

        if (empty($to_emails) && empty($users)) {
            $this->_set_errors($this->_LANG_ERR_NO_TO_EMAIL);
            return false;
        }

        $this->clear_errors_logs();

        // mail start
        $mailer =& getMailer();
        $mailer->reset();
        $mailer->setFromName($from_name);
        $mailer->setFromEmail($from_email);
        $mailer->setSubject($subject);
        $mailer->setBody($body);
        $mailer->useMail();

        if ($to_emails) {
            $mailer->setToEmails($to_emails);
        }

        if (is_array($users) && count($users)) {
            $mailer->setToUsers($users);
        }

        if (is_array($tags) && count($tags)) {
            $mailer->assign($tags);
        }

        $ret = $mailer->send($debug);
        if (!$ret) {
            $this->_set_errors($mailer->getErrors(false));
            return false;
        }

        $this->_set_log($mailer->getSuccess(false));
        return true;
    }

    //---------------------------------------------------------
    // get system param
    //---------------------------------------------------------
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

    // --- class end ---
}
