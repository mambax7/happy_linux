<?php

// $Id: compatible.php,v 1.1 2012/04/08 18:22:28 ohwada Exp $

// 2007-11-24 K.OHWADA
// _HAPPY_LINUX_CONF_TABLE_MANAGE

// 2007-11-01 K.OHWADA
// _HAPPY_LINUX_FAIL

// 2007-06-01 K.OHWADA
// _HAPPY_LINUX_AM_JUDGE

// 2007-05-12 K.OHWADA
// _HAPPY_LINUX_FORM_INIT_NOT

// 2007-02-20 K.OHWADA
// _HAPPY_LINUX_SKIP_TO_NEXT

//=========================================================
// Happy Linux Framework Module
// 2006-12-17 K.OHWADA
//=========================================================
// _LANGCODE: ru
// _CHARSET : cp1251
// Translator: Houston (Contour Design Studio http://www.cdesign.ru/)

//---------------------------------------------------------
// compatible for v1.21
//---------------------------------------------------------
// config
if (!defined('_HAPPY_LINUX_CONF_TABLE_MANAGE')) {
    // table manage
    define('_HAPPY_LINUX_CONF_TABLE_MANAGE', '���������� �������� ���� ������');
    define('_HAPPY_LINUX_CONF_TABLE_CHECK', '��������� ������� %s');
    define('_HAPPY_LINUX_CONF_TABLE_REINSTALL', '������������� ��������������, ���� ���������� ������');
    define('_HAPPY_LINUX_CONF_TABLE_CONFIG_RENEW', '���������� ������������ �������');
    define('_HAPPY_LINUX_CONF_TABLE_CONFIG_RENEW_DESC', '���������, ���� ���������� ������. <br>������������ �������� ������������. <br>���������� �������� ����� ����������. ');
}

//---------------------------------------------------------
// compatible for v1.20
//---------------------------------------------------------
// global
if (!defined('_HAPPY_LINUX_FAIL')) {
    define('_HAPPY_LINUX_WELCOME', '����� ���������� %s');
    define('_HAPPY_LINUX_FAIL', '�������');
    define('_HAPPY_LINUX_FAILED', '�� �������');
    define('_HAPPY_LINUX_REFRESH', '��������');
    define('_HAPPY_LINUX_REFRESHED', '���������');
    define('_HAPPY_LINUX_FINISH', '���������');
    define('_HAPPY_LINUX_FINISHED', '���������');
    define('_HAPPY_LINUX_PRINT', '������');
    define('_HAPPY_LINUX_SAMPLE', '������');
}

// admin
if (!defined('_HAPPY_LINUX_AM_MODULE')) {
    define('_HAPPY_LINUX_AM_MODULE', '���������� �������');
    define('_HAPPY_LINUX_AM_MODULE_DESC', '�������� ������ �������');
    define('_HAPPY_LINUX_AM_MODULE_UPDATE', '���������� ������');

    define('_HAPPY_LINUX_AM_SERVER_ENV', '���������� ��������� �����');
    define('_HAPPY_LINUX_AM_DIR_NOT_WRITABLE', '��� ���������� ���������� ��� ������');
    define('_HAPPY_LINUX_AM_MEMORY_LIMIT_TOO_SMALL', 'memory_limit ������� ���');
    define('_HAPPY_LINUX_AM_MEMORY_WEBLINKS_REQUIRE', '������ ���-������ ������� ������ ������, ��� %s MB');
    define('_HAPPY_LINUX_AM_MEMORY_DESC', '��� �������� �������� ����� ����������.<br>� ����������� �� ��������� �����, ������ ������ ������ ��� ������.');
}

//---------------------------------------------------------
// compatible for v0.90
//---------------------------------------------------------
// admin
if (!defined('_HAPPY_LINUX_AM_JUDGE')) {
    define('_HAPPY_LINUX_AM_JUDGE', '��������� judegs <b>%s</b>');
    define('_HAPPY_LINUX_AM_JUMP', '��� �������� �������������� ������������� ����� <b>%s</b> ���');
    define('_HAPPY_LINUX_AM_JUMP_IFNO1', '����������, ������� ���������, ���� �������� ������������� �� ��������������, ��� ��������� mis-judges.');
    define('_HAPPY_LINUX_AM_JUMP_IFNO2', '����������, ���������� <i>modules/happy_linux/preload/admin.php</i>, ����� %s ������ �����');
}

//---------------------------------------------------------
// compatible for v0.80
//---------------------------------------------------------
// form
if (!defined('_HAPPY_LINUX_FORM_INIT_NOT')) {
    define('_HAPPY_LINUX_FORM_INIT_NOT', '�� ���������������� ������� ������������');
    define('_HAPPY_LINUX_FORM_INIT_EXEC', '������������� ������� ������������');
    define('_HAPPY_LINUX_FORM_VERSION_NOT', '�� ������ %s');
    define('_HAPPY_LINUX_FORM_UPGRADE_EXEC', '���������� ������� ������������');
}

// admin
if (!defined('_HAPPY_LINUX_AM_GROUP')) {
    define('_HAPPY_LINUX_AM_GROUP', '���������� �������');
    define('_HAPPY_LINUX_AM_GROUP_DESC', '���������� ������� ������� ������');
    define('_HAPPY_LINUX_AM_BLOCK', '���������� ������');
    define('_HAPPY_LINUX_AM_BLOCK_DESC', '���������� ������� ������� �����');
    define('_HAPPY_LINUX_AM_GROUP_BLOCK', '������ / ���������� ������');
    define('_HAPPY_LINUX_AM_GROUP_BLOCK_DESC', '���������� ������� ������� ������ � �����');
    define('_HAPPY_LINUX_AM_TEMPLATE', '���������� ��������');
    define('_HAPPY_LINUX_AM_TEMPLATE_DESC', '���������� ��������');
}

// rss_view
if (!defined('_HAPPY_LINUX_VIEW_SITE_TITLE')) {
    include_once XOOPS_ROOT_PATH . '/modules/happy_linux/language/english/rss_view.php';
}

//---------------------------------------------------------
// compatible for v0.70
//---------------------------------------------------------
// global
if (!defined('_HAPPY_LINUX_SKIP_TO_NEXT')) {
    define('_HAPPY_LINUX_SKIP_TO_NEXT', '������� � ����������');
}

//---------------------------------------------------------
// compatible for v0.40
//---------------------------------------------------------
// global
if (!defined('_HAPPY_LINUX_GOTO_MAIN')) {
    define('_HAPPY_LINUX_GOTO_MAIN', '������� �� ������� ��������');
    define('_HAPPY_LINUX_GOTO_TOP', '������� �� ������� ��������');
    define('_HAPPY_LINUX_GOTO_ADMIN', '������� �� �������� �����������������');
    define('_HAPPY_LINUX_GOTO_MODULE', '������� � ������');
}

// form
if (!defined('_HAPPY_LINUX_FORM_ITEM')) {
    define('_HAPPY_LINUX_FORM_ITEM', '�����');
}
