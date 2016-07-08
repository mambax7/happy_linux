<?php
// $Id: weblog_updates.php,v 1.4 2008/02/05 00:42:12 ohwada Exp $

// 2008-02-03 K.OHWADA
// set_timeout_connect()
// Assigning the return value of new by reference is deprecated

// 2007-10-10 K.OHWADA
// send_pings_by_param()

// 2007-05-12 K.OHWADA
// this is new file
// porting from whatsnew_weblog_updates.php

//=========================================================
// Happy Linux Framework Module
// 2007-05-12 K.OHWADA
//=========================================================

class happy_linux_weblog_updates
{
    public $_HTTP_METHOD  = 'POST';
    public $_CONTENT_TYPE = 'text/xml';

    // class
    public $_snoopy;

    // variable
    public $blog_name;
    public $blog_url;

    public $error;
    public $status;
    public $response_code;
    public $results;

    public $code;
    public $url;
    public $reason;

    // for debug
    public $flag_debug;

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        // class
        // Assigning the return value of new by reference is deprecated
        $this->_snoopy = new Snoopy();

        // for debug
        $this->reset_debug();
    }

    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new happy_linux_weblog_updates();
        }

        return $instance;
    }

    //---------------------------------------------------------
    // main function
    //---------------------------------------------------------
    public function send_pings_by_param($param)
    {
        $site_name    = $param['site_name'];
        $site_url     = $param['site_url'];
        $ping_servers = $param['ping_servers'];

        $print_level = isset($param['print_level']) ? (int)$param['print_level'] : 0;
        $log_level   = isset($param['log_level']) ? (int)$param['log_level'] : 0;
        $log_file    = isset($param['log_file']) ? $param['log_file'] : null;

        $timeout_connect = isset($param['timeout_connect']) ? (int)$param['timeout_connect'] : 0;
        $timeout_read    = isset($param['timeout_read']) ? (int)$param['timeout_read'] : 0;

        $flag_use_log = false;

        $today = date('Y/m/d H:i:s');

        if (($log_level > 0) && $log_file) {
            $flag_use_log = true;
            $fp           = fopen($log_file, 'a');
            fwrite($fp, "$today \n");
        }

        $ping_arr = explode("\n", $ping_servers);

        $this->set_timeout_connect($timeout_connect);
        $this->set_timeout_read($timeout_read);

        if ($print_level >= 2) {
            $this->set_debug();
        }

        if ($print_level >= 1) {
            echo "<hr />\n";
        }

        foreach ($ping_arr as $url) {
            $url = trim($url);
            if (empty($url)) {
                continue;
            }

            $this->set_blog_data($site_name, $site_url);
            $this->send_ping($url);
            $msg = $this->make_result();

            if ($print_level >= 1) {
                echo $msg;
                echo "<br /><br />\n";
            }

            if ($flag_use_log) {
                fwrite($fp, $msg);
            }
        }

        if ($print_level >= 1) {
            echo "<hr />\n";
        }

        if ($flag_use_log) {
            fclose($fp);
        }
    }

    //---------------------------------------------------------
    // set blog data
    // $name : my web site ( EUC-JP avalable )
    // $url  : my url
    //---------------------------------------------------------
    public function set_blog_data($name, $url)
    {
        $this->blog_name = $name;
        $this->blog_url  = $url;
    }

    //---------------------------------------------------------
    // send ping to server
    // $url  : server url
    // $code : return code
    //---------------------------------------------------------
    public function send_ping($url)
    {
        $this->url  = $url;
        $this->code = 1;    // NG

        if (empty($url)) {
            $this->error = 'no server url';
            return false;
        }

        if (empty($this->blog_name)) {
            $this->error = 'no blog name';
            return false;
        }

        if (empty($this->blog_url)) {
            $this->error = 'no blog url';
            return false;
        }

        // make message
        $payload      = $this->build_payload();
        $payload_utf8 = happy_linux_convert_to_utf8($payload);

        // print message
        if ($this->flag_debug) {
            $msg_url     = htmlspecialchars($url);
            $msg_payload = htmlspecialchars($payload);
            echo '<pre>';
            echo "---SEND--- \n";
            echo $msg_url;
            echo "\n\n";
            echo $msg_payload;
            echo "</pre> \n";
        }

        // send ping
        $ret = $this->http_request($url, $payload_utf8);

        // print message
        if ($this->flag_debug) {
            $msg_code    = htmlspecialchars($this->response_code);
            $msg_results = htmlspecialchars($this->results);
            print '<pre>';
            print "---RESPONSE--- \n";
            echo $msg_code;
            echo "\n\n";
            echo $msg_results;
            echo "</pre> \n";
        }

        if ($ret) {
            list($this->code, $this->reason) = $this->parse_response($this->results);
        }

        return $ret;
    }

    //---------------------------------------------------------
    //  snoopy class
    //---------------------------------------------------------
    public function http_request($url, $payload)
    {
        $this->status        = 0;
        $this->error         = '';
        $this->response_code = '';
        $this->results       = '';

        $this->_snoopy->port          = 80;
        $this->_snoopy->status        = 0;
        $this->_snoopy->results       = '';
        $this->_snoopy->response_code = '';

        $URI_PARTS = parse_url($url);
        if (!empty($URI_PARTS['host'])) {
            $this->_snoopy->host = $URI_PARTS['host'];
        } else {
            $this->error = 'no host in url';
            return false;
        }
        if (!empty($URI_PARTS['port'])) {
            $this->_snoopy->port = $URI_PARTS['port'];
        }
        if (!empty($URI_PARTS['path'])) {
            $path = $URI_PARTS['path'];
        }

        // set $fp in _connect()
        $this->_snoopy->_connect($fp);
        $ret = $this->_snoopy->_httprequest($path, $fp, $url, $this->_HTTP_METHOD, $this->_CONTENT_TYPE, $payload);
        $this->_snoopy->_disconnect($fp);

        if (!$ret) {
            $this->status        = $this->_snoopy->status;
            $this->error         = $this->_snoopy->error;
            $this->response_code = $this->_snoopy->response_code;
            return false;
        } else {
            $this->response_code = $this->_snoopy->response_code;
            $this->results       = $this->_snoopy->results;
        }

        return true;
    }

    public function set_timeout_connect($time)
    {
        if ((int)$time > 0) {
            $this->_snoopy->_fp_timeout = (float)$time;
        }
    }

    public function set_timeout_read($time)
    {
        if ((int)$time > 0) {
            $this->_snoopy->read_timeout = (float)$time;
        }
    }

    //---------------------------------------------------------
    //  build_payload
    //---------------------------------------------------------
    public function build_payload()
    {
        $payload = <<<END_OF_TEXT
<?xml version="1.0"?>
<methodCall>
  <methodName>weblogUpdates.ping</methodName>
   <params>
   <param>
     <value>$this->blog_name</value>
   </param>
   <param>
     <value>$this->blog_url</value>
   </param>
   </params>
</methodCall>
END_OF_TEXT;

        return $payload;
    }

    //---------------------------------------------------------
    //   parse response
    //---------------------------------------------------------
    // --- success ---
    // <methodResponse>
    //   <params>
    //   <param>
    //   <value>
    //   <struct>
    //     <member>
    //       <name>flerror</name>
    //       <value>
    //       <boolean>0</boolean>
    //       </value>
    //     </member>
    //     <member>
    //       <name>message</name>
    //       <value>Thanks for the ping.</value>
    //     </member>
    //   </struct>
    //   </value>
    //   </param>
    //   </params>
    // </methodResponse>
    //
    // --- fault ---
    // <methodResponse>
    //    <fault>
    //    <value>
    //    <struct>
    //      <member>
    //        <name>faultCode</name>
    //        <value><int>***</int></value>
    //      </member>
    //      <member>
    //        <name>faultString</name>
    //        <value><string>***</string></value>
    //      </member>
    //   </struct>
    //   </value>
    //   </fault>
    // </methodResponse>
    //---------------------------------------------------------
    public function parse_response($response)
    {
        $error   = 1;
        $message = 'no message';

        $member_arr = $this->parse_xml($response);

        // print message
        if ($this->flag_debug) {
            print '<pre>';
            print "--- PARSE --- \n";

            foreach ($member_arr as $name => $value) {
                print "$name: $value \n";
            }
            print "</pre> \n";
        }

        if (isset($member_arr['flerror'])) {
            $error   = $member_arr['flerror'];
            $message = $member_arr['message'];
        } elseif (isset($member_arr['faultCode'])) {
            $error   = $member_arr['faultCode'];
            $message = $member_arr['faultString'];
        }

        return array($error, $message);
    }

    //---------------------------------------------------------
    //   parse xml
    //---------------------------------------------------------
    public function parse_xml($xml)
    {
        preg_match_all('/<member>(.*?)<\/member>/is', $xml, $match1);
        $arr = $match1[1];

        $member_arr = array();

        foreach ($arr as $member) {
            if (preg_match('/<name>(.*)<\/name>/is', $member, $match2)) {
                $name = trim($match2[1]);
            }

            if (preg_match('/<value>(.*)<\/value>/is', $member, $match2)) {
                $value1 = $match2[1];
                $value2 = $value1;

                if (preg_match('/<boolean>(.*)<\/boolean>/is', $value1, $match3)) {
                    $value2 = trim($match3[1]);
                }

                if (preg_match('/<int>(.*)<\/int>/is', $value1, $match3)) {
                    $value2 = trim($match3[1]);
                }

                if (preg_match('/<string>(.*)<\/string>/is', $value1, $match3)) {
                    $value2 = trim($match3[1]);
                }
            }

            $member_arr[$name] = $value2;
        }

        return $member_arr;
    }

    //---------------------------------------------------------
    // make result message
    // $list : result list
    // $msg  : result message
    //---------------------------------------------------------
    public function make_result()
    {
        if ($this->code == 0) {
            $msg = 'ping send - ' . $this->url . " - OK <br />\n";
        } else {
            $msg = '<span style="color:#ff0000;">ping send - ' . $this->url . " - NG </span><br />\n";
            if ($this->error) {
                $msg .= $this->error . "<br />\n";
            }
            if ($this->reason) {
                $msg .= $this->reason . "<br />\n";
            }
        }
        return $msg;
    }

    //---------------------------------------------------------
    // set flag debug to 1
    //---------------------------------------------------------
    public function set_debug()
    {
        $this->flag_debug = 1;
    }

    //---------------------------------------------------------
    // reset flag debug to 0
    //---------------------------------------------------------
    public function reset_debug()
    {
        $this->flag_debug = 0;
    }

    // --- class end ---
}
