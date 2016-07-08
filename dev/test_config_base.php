<?php
// $Id: test_config_base.php,v 1.1 2006/11/21 03:04:56 ohwada Exp $

//================================================================
// Happy Linux Framework Module
// 2006-11-18 K.OHWADA
//================================================================

include_once 'dev_header.php';

//================================================================
// main
//================================================================

dev_header();
echo "<h3>test happy_linux_config_base</h3>\n";

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

test_common('text', 'abc', 'abc');
test_common('text', "ab \t cd \n ef \0", 'ab  cd  ef ');
test_common('text', 'javascript:', 'java_script_:');

if ($gpc) {
    test_common('text', "\'def\'", "'def'", htmlspecialchars("'def'", ENT_QUOTES));
} else {
    test_common('text', "\'def\'", "\'def\'", htmlspecialchars("\'def\'", ENT_QUOTES));
}

test_common('textarea', 'abc', 'abc');
test_common('textarea', "ab \t cd \n ef \0", "ab \t cd \n ef ");
test_common('textarea', 'javascript:', 'java_script_:');

if ($gpc) {
    test_common('textarea', "\'def\'", "'def'", htmlspecialchars("'def'", ENT_QUOTES));
} else {
    test_common('textarea', "\'def\'", "\'def\'", htmlspecialchars("\'def\'", ENT_QUOTES));
}

test_common('other', 'abc', 'abc');
test_common('other', "ab \t cd \n ef \0", "ab \t cd \n ef \0");
test_common('other', 'javascript:', 'javascript:');

test_array('array', 'ab1|ab2', serialize(array('ab1', 'ab2')), array('ab1', 'ab2'));
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
    $obj = new happy_linux_config_base();
    $obj->set('conf_valuetype', $key);
    $obj->setConfValueForInput($set);
    $get    = $obj->get('conf_value');
    $getvar = $obj->getConfValueForOutput();

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
    $obj = new happy_linux_config_base();
    $obj->set('conf_valuetype', $key);
    $obj->setConfValueForInput($set);
    $get    = $obj->get('conf_value');
    $getvar = $obj->getConfValueForOutput();

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
