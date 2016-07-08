<?php
// $Id: test_object.php,v 1.1 2006/11/21 03:04:56 ohwada Exp $

//================================================================
// Happy Linux Framework Module
// 2006-11-18 K.OHWADA
//================================================================

include_once 'dev_header.php';

//================================================================
// class test_object
//================================================================
class test_object extends happy_linux_object
{

    //---------------------------------------------------------
    // constructor
    //---------------------------------------------------------
    public function __construct()
    {
        parent::__construct();

        $this->initVar('bool', XOBJ_DTYPE_BOOL);
        $this->initVar('int', XOBJ_DTYPE_INT);
        $this->initVar('float', XOBJ_DTYPE_FLOAT);
        $this->initVar('txtbox', XOBJ_DTYPE_TXTBOX);
        $this->initVar('txtarea', XOBJ_DTYPE_TXTAREA);
        $this->initVar('url', XOBJ_DTYPE_URL);
        $this->initVar('array', XOBJ_DTYPE_ARRAY);
        $this->initVar('other', XOBJ_DTYPE_OTHER);
    }

    // --- class end ---
}

//================================================================
// main
//================================================================

dev_header();
echo "<h3>test happy_linux_object</h3>\n";

$gpc = get_magic_quotes_gpc();
echo 'get_magic_quotes_gpc = ' . $gpc . "<br /><br />\n";

test_common('bool', 0, 0);
test_common('bool', 1, 1);
test_common('bool', 2, 1);

test_common('int', 123, 123);
test_common('int', 123.4, 123);
test_common('int', 'abc', 0);

test_common('float', 123.4, 123.4);
test_common('float', 'abc', 0);

test_common('txtbox', 'abc', 'abc');
test_common('txtbox', "ab \t cd \n ef \0", 'ab  cd  ef ');
test_common('txtbox', 'javascript:', 'java_script_:');
test_common('txtbox', 'ab &auml; cd &amp; ef & ', 'ab &auml; cd &amp; ef & ', 'ab &auml; cd &amp; ef &amp; ');

if ($gpc) {
    test_common('txtbox', "\'def\'", "'def'", htmlspecialchars("'def'", ENT_QUOTES));
} else {
    test_common('txtbox', "\'def\'", "\'def\'", htmlspecialchars("\'def\'", ENT_QUOTES));
}

test_common('txtarea', 'abc', 'abc');
test_common('txtarea', "ab \t cd \n ef \0", "ab \t cd \n ef ");
test_common('txtarea', 'javascript:', 'java_script_:');
test_common('txtarea', 'ab &auml; cd &amp; ef & ', 'ab &auml; cd &amp; ef & ', 'ab &auml; cd &amp; ef &amp; ');

if ($gpc) {
    test_common('txtarea', "\'def\'", "'def'", htmlspecialchars("'def'", ENT_QUOTES));
} else {
    test_common('txtarea', "\'def\'", "\'def\'", htmlspecialchars("\'def\'", ENT_QUOTES));
}

test_common('url', 'http://abc/', 'http://abc/');
test_common('url', "http://ab \t cd \n ef \0/", 'http://ab  cd  ef /');
test_common('url', "http://java\tscript\n:", '');
test_common('url', 'http://', '');
test_common('url', 'abc', '');
test_common('url', 'http://ab &auml; cd &amp; ef & /', 'http://ab &auml; cd &amp; ef & /', 'http://ab &amp;auml; cd &amp; ef &amp; /');

if ($gpc) {
    test_common('url', "http://\'def\'", "http://'def'", htmlspecialchars("http://'def'", ENT_QUOTES));
} else {
    test_common('url', "http://\'def\'", "http://\'def\'", htmlspecialchars("http://\'def\'", ENT_QUOTES));
}

test_common('other', 'abc', 'abc');
test_common('other', "ab \t cd \n ef \0", "ab \t cd \n ef \0");
test_common('other', 'javascript:', 'javascript:');
test_common('other', "\'def\'", "\'def\'");

test_array('array', 'abc', serialize('abc'), 'abc');
test_array('array', array('def'), serialize(array('def')), array('def'));

if ($gpc) {
    test_array('array', array("\'ghi\'"), serialize(array("'ghi'")), array("'ghi'"));
} else {
    test_array('array', array("\'ghi\'"), serialize(array("\'ghi\'")), array("\'ghi\'"));
}

dev_footer();
exit();
// === main end ==

function test_common($key, $set, $get_expect, $getvar_expect = null)
{
    $obj = new test_object();
    $obj->setVar($key, $set);
    $get    = $obj->get($key);
    $getvar = $obj->getVar($key);

    if ($getvar_expect === null) {
        $getvar_expect = $get_expect;
    }

    echo "$key : $set : $get : $getvar <br />\n";

    if (($get_expect != $get) || ($getvar_expect != $getvar)) {
        dev_print_error("unmatch $key : $set : $get_expect != $get : $getvar_expect != $getvar");
    }
}

function test_array($key, $set, $get_expect, $getvar_expect = null)
{
    $obj = new test_object();
    $obj->setVar($key, $set);
    $get    = $obj->get($key);
    $getvar = $obj->getVar($key);

    if ($getvar_expect === null) {
        $getvar_expect = $get_expect;
    }

    echo "$key : <br />\n";
    print_r($set);
    echo "<br />\n";
    print_r($get);
    echo "<br />\n";
    print_r($getvar);
    echo "<br /><br />\n";

    if (($get_expect != $get) || ($getvar_expect != $getvar)) {
        dev_print_error("unmatch $key : $set : $get_expect != $get : $getvar_expect != $getvar");
    }
}
