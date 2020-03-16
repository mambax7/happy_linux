<?php
// $Id: compatible.RU.php,v 1.1 2012/04/08 18:22:28 ohwada Exp $

// 2007-11-24 K.OHWADA
// _HAPPYLINUX_CONF_TABLE_MANAGE

// 2007-11-01 K.OHWADA
// _HAPPYLINUX_FAIL

// 2007-06-01 K.OHWADA
// _HAPPYLINUX_AM_JUDGE

// 2007-05-12 K.OHWADA
// _HAPPYLINUX_FORM_INIT_NOT

// 2007-02-20 K.OHWADA
// _HAPPYLINUX_SKIP_TO_NEXT

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
if (!defined('_HAPPYLINUX_CONF_TABLE_MANAGE')) {
    // table manage
    define('_HAPPYLINUX_CONF_TABLE_MANAGE', '���������� �������� ���� ������');
    define('_HAPPYLINUX_CONF_TABLE_CHECK', '��������� ������� %s');
    define('_HAPPYLINUX_CONF_TABLE_REINSTALL', '������������� ��������������, ���� ���������� ������');
    define('_HAPPYLINUX_CONF_TABLE_CONFIG_RENEW', '���������� ������������ �������');
    define('_HAPPYLINUX_CONF_TABLE_CONFIG_RENEW_DESC', '���������, ���� ���������� ������. <br>������������ �������� ������������. <br>���������� �������� ����� ����������. ');
}

//---------------------------------------------------------
// compatible for v1.20
//---------------------------------------------------------
// global
if (!defined('_HAPPYLINUX_FAIL')) {
    define('_HAPPYLINUX_WELCOME', '����� ���������� %s');
    define('_HAPPYLINUX_FAIL', '�������');
    define('_HAPPYLINUX_FAILED', '�� �������');
    define('_HAPPYLINUX_REFRESH', '��������');
    define('_HAPPYLINUX_REFRESHED', '���������');
    define('_HAPPYLINUX_FINISH', '���������');
    define('_HAPPYLINUX_FINISHED', '���������');
    define('_HAPPYLINUX_PRINT', '������');
    define('_HAPPYLINUX_SAMPLE', '������');
}

// admin
if (!defined('_HAPPYLINUX_AM_MODULE')) {
    define('_HAPPYLINUX_AM_MODULE', '���������� �������');
    define('_HAPPYLINUX_AM_MODULE_DESC', '�������� ������ �������');
    define('_HAPPYLINUX_AM_MODULE_UPDATE', '���������� ������');

    define('_HAPPYLINUX_AM_SERVER_ENV', '���������� ��������� �����');
    define('_HAPPYLINUX_AM_DIR_NOT_WRITABLE', '��� ���������� ���������� ��� ������');
    define('_HAPPYLINUX_AM_MEMORY_LIMIT_TOO_SMALL', 'memory_limit ������� ���');
    define('_HAPPYLINUX_AM_MEMORY_WEBLINKS_REQUIRE', '������ ���-������ ������� ������ ������, ��� %s MB');
    define('_HAPPYLINUX_AM_MEMORY_DESC', '��� �������� �������� ����� ����������.<br>� ����������� �� ��������� �����, ������ ������ ������ ��� ������.');
}

//---------------------------------------------------------
// compatible for v0.90
//---------------------------------------------------------
// admin
if (!defined('_HAPPYLINUX_AM_JUDGE')) {
    define('_HAPPYLINUX_AM_JUDGE', '��������� judegs <b>%s</b>');
    define('_HAPPYLINUX_AM_JUMP', '��� �������� �������������� ������������� ����� <b>%s</b> ���');
    define('_HAPPYLINUX_AM_JUMP_IFNO1', '����������, ������� ���������, ���� �������� ������������� �� ��������������, ��� ��������� mis-judges.');
    define('_HAPPYLINUX_AM_JUMP_IFNO2', '����������, ���������� <i>modules/happylinux/preload/admin.php</i>, ����� %s ������ �����');
}

//---------------------------------------------------------
// compatible for v0.80
//---------------------------------------------------------
// form
if (!defined('_HAPPYLINUX_FORM_INIT_NOT')) {
    define('_HAPPYLINUX_FORM_INIT_NOT', '�� ���������������� ������� ������������');
    define('_HAPPYLINUX_FORM_INIT_EXEC', '������������� ������� ������������');
    define('_HAPPYLINUX_FORM_VERSION_NOT', '�� ������ %s');
    define('_HAPPYLINUX_FORM_UPGRADE_EXEC', '���������� ������� ������������');
}

// admin
if (!defined('_HAPPYLINUX_AM_GROUP')) {
    define('_HAPPYLINUX_AM_GROUP', '���������� �������');
    define('_HAPPYLINUX_AM_GROUP_DESC', '���������� ������� ������� ������');
    define('_HAPPYLINUX_AM_BLOCK', '���������� ������');
    define('_HAPPYLINUX_AM_BLOCK_DESC', '���������� ������� ������� �����');
    define('_HAPPYLINUX_AM_GROUP_BLOCK', '������ / ���������� ������');
    define('_HAPPYLINUX_AM_GROUP_BLOCK_DESC', '���������� ������� ������� ������ � �����');
    define('_HAPPYLINUX_AM_TEMPLATE', '���������� ��������');
    define('_HAPPYLINUX_AM_TEMPLATE_DESC', '���������� ��������');
}

// rss_view
if (!defined('_HAPPYLINUX_VIEW_SITE_TITLE')) {
    require_once XOOPS_ROOT_PATH . '/modules/happylinux/language/english/rss_view.php';
}

//---------------------------------------------------------
// compatible for v0.70
//---------------------------------------------------------
// global
if (!defined('_HAPPYLINUX_SKIP_TO_NEXT')) {
    define('_HAPPYLINUX_SKIP_TO_NEXT', '������� � ����������');
}

//---------------------------------------------------------
// compatible for v0.40
//---------------------------------------------------------
// global
if (!defined('_HAPPYLINUX_GOTO_MAIN')) {
    define('_HAPPYLINUX_GOTO_MAIN', '������� �� ������� ��������');
    define('_HAPPYLINUX_GOTO_TOP', '������� �� ������� ��������');
    define('_HAPPYLINUX_GOTO_ADMIN', '������� �� �������� �����������������');
    define('_HAPPYLINUX_GOTO_MODULE', '������� � ������');
}

// form
if (!defined('_HAPPYLINUX_FORM_ITEM')) {
    define('_HAPPYLINUX_FORM_ITEM', '�����');
}
