<?php
// $Id: index.php,v 1.1 2006/11/21 03:04:56 ohwada Exp $

//================================================================
// Happy Linux Framework Module
// 2006-11-18 K.OHWADA
//================================================================

require_once __DIR__ . '/dev_header.php';

dev_header();

echo "<h3>Development</h3>\n";
echo '<h4 style="color: #0000ff;">Warnig</h4>' . "\n";
echo "Use only in Development enviroment. <br><br>\n";

echo "<h4>Test</h4>\n";
echo '- <a href="test_object.php">test happylinux_object</a>' . "<br><br>\n";
echo '- <a href="test_config_base.php">test ConfigBase</a>' . "<br><br>\n";

dev_footer();
