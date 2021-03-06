<?php
// $Id: rss_view.php,v 1.1 2012/04/08 18:22:28 ohwada Exp $

// 2007-05-12 K.OHWADA
// this is new file
// move from rssc module

//=========================================================
// Happy Linux Framework Module
// 2007-05-12 K.OHWADA
//=========================================================
// _LANGCODE: ru
// _CHARSET : cp1251
// Translator: Houston (Contour Design Studio http://www.cdesign.ru/)

// common
define('_HAPPYLINUX_VIEW_SITE_TITLE', '��������� �����');
define('_HAPPYLINUX_VIEW_SITE_LINK', '����� �����');
define('_HAPPYLINUX_VIEW_SITE_DESCRIPTION', '�������� �����');
define('_HAPPYLINUX_VIEW_SITE_PUBLISHED', '���������� �����');
define('_HAPPYLINUX_VIEW_SITE_UPDATED', '���������� �����');
define('_HAPPYLINUX_VIEW_SITE_DATE', '���� �����');
define('_HAPPYLINUX_VIEW_SITE_COPYRIGHT', '��������� ����� �����');
define('_HAPPYLINUX_VIEW_SITE_GENERATOR', '������������� �����');
define('_HAPPYLINUX_VIEW_SITE_CATEGORY', '��������� �����');
define('_HAPPYLINUX_VIEW_SITE_WEBMASTER', '���-������ �����');
define('_HAPPYLINUX_VIEW_SITE_LANGUAGE', '���� �����');
define('_HAPPYLINUX_VIEW_TITLE', '���������');
define('_HAPPYLINUX_VIEW_LINK', '�����');
define('_HAPPYLINUX_VIEW_DESCRIPTION', '��������');
define('_HAPPYLINUX_VIEW_SUMMARY', '������� ����������');
define('_HAPPYLINUX_VIEW_CONTENT', '����������');
define('_HAPPYLINUX_VIEW_PUBLISHED', '������������');
define('_HAPPYLINUX_VIEW_UPDATED', '���������');
define('_HAPPYLINUX_VIEW_CREATED', '�������');
define('_HAPPYLINUX_VIEW_CATEGORY', '���������');
define('_HAPPYLINUX_VIEW_RIGHTS', '�����');
define('_HAPPYLINUX_VIEW_SOURCE', '��������');
define('_HAPPYLINUX_VIEW_AUTHOR_NAME', '��� ������');
define('_HAPPYLINUX_VIEW_AUTHOR_URI', '����� ������');
define('_HAPPYLINUX_VIEW_AUTHOR_EMAIL', '����������� ����� ������');
define('_HAPPYLINUX_VIEW_IMAGE_TITLE', '��������� �����������');
define('_HAPPYLINUX_VIEW_IMAGE_URL', '����� �����������');
define('_HAPPYLINUX_VIEW_ENCLOSURE_URL', '��������� �����');
define('_HAPPYLINUX_VIEW_ENCLOSURE_TYPE', '��������� ���');
define('_HAPPYLINUX_VIEW_ENCLOSURE_LENGTH', '��������� �����');

// RSS
define('_HAPPYLINUX_VIEW_RSS_SITE_TITLE', _HAPPYLINUX_VIEW_SITE_TITLE);
define('_HAPPYLINUX_VIEW_RSS_SITE_LINK', _HAPPYLINUX_VIEW_SITE_LINK);
define('_HAPPYLINUX_VIEW_RSS_SITE_DESCRIPTION', _HAPPYLINUX_VIEW_SITE_DESCRIPTION);
define('_HAPPYLINUX_VIEW_RSS_SITE_LASTBUILDDATE', _HAPPYLINUX_VIEW_SITE_UPDATED);
define('_HAPPYLINUX_VIEW_RSS_SITE_PUBDATE', _HAPPYLINUX_VIEW_SITE_PUBLISHED);
define('_HAPPYLINUX_VIEW_RSS_SITE_GENERATOR', _HAPPYLINUX_VIEW_SITE_GENERATOR);
define('_HAPPYLINUX_VIEW_RSS_SITE_CATEGORY', _HAPPYLINUX_VIEW_SITE_CATEGORY);
define('_HAPPYLINUX_VIEW_RSS_SITE_WEBMASTER', _HAPPYLINUX_VIEW_SITE_WEBMASTER);
define('_HAPPYLINUX_VIEW_RSS_SITE_LANGUAGE', _HAPPYLINUX_VIEW_SITE_LANGUAGE);
define('_HAPPYLINUX_VIEW_RSS_SITE_COPYRIGHT', _HAPPYLINUX_VIEW_SITE_COPYRIGHT);
define('_HAPPYLINUX_VIEW_RSS_SITE_MANAGINGEDITOR', '�������� �����');
define('_HAPPYLINUX_VIEW_RSS_SITE_DOCS', '��������� �����');
define('_HAPPYLINUX_VIEW_RSS_SITE_CLOUD', '������ �����');
define('_HAPPYLINUX_VIEW_RSS_SITE_TTL', 'TTL �����');
define('_HAPPYLINUX_VIEW_RSS_SITE_RATING', '������ �����');
define('_HAPPYLINUX_VIEW_RSS_SITE_TEXTINPUT', '���� ������ �����');
define('_HAPPYLINUX_VIEW_RSS_SITE_SKIPHOURS', '������� ����� �����');
define('_HAPPYLINUX_VIEW_RSS_SITE_SKIPDAYS', '������� ���� �����');
define('_HAPPYLINUX_VIEW_RSS_IMAGE_TITLE', _HAPPYLINUX_VIEW_IMAGE_TITLE);
define('_HAPPYLINUX_VIEW_RSS_IMAGE_URL', _HAPPYLINUX_VIEW_IMAGE_URL);
define('_HAPPYLINUX_VIEW_RSS_IMAGE_WIDTH', '������ �����������');
define('_HAPPYLINUX_VIEW_RSS_IMAGE_HEIGHT', '������ �����������');
define('_HAPPYLINUX_VIEW_RSS_IMAGE_LINK', _HAPPYLINUX_VIEW_SITE_LINK);
define('_HAPPYLINUX_VIEW_RSS_TITLE', _HAPPYLINUX_VIEW_TITLE);
define('_HAPPYLINUX_VIEW_RSS_LINK', _HAPPYLINUX_VIEW_LINK);
define('_HAPPYLINUX_VIEW_RSS_DESCRIPTION', _HAPPYLINUX_VIEW_DESCRIPTION);
define('_HAPPYLINUX_VIEW_RSS_PUBDATE', _HAPPYLINUX_VIEW_PUBLISHED);
define('_HAPPYLINUX_VIEW_RSS_CATEGORY', _HAPPYLINUX_VIEW_CATEGORY);
define('_HAPPYLINUX_VIEW_RSS_SOURCE', _HAPPYLINUX_VIEW_SOURCE);
define('_HAPPYLINUX_VIEW_RSS_GUID', '������������� GUID RSS');
define('_HAPPYLINUX_VIEW_RSS_AUTHOR', '�����');
define('_HAPPYLINUX_VIEW_RSS_COMMENTS', '�����������');
define('_HAPPYLINUX_VIEW_RSS_ENCLOSURE', '��������');

// RDF
define('_HAPPYLINUX_VIEW_RDF_SITE_TITLE', _HAPPYLINUX_VIEW_SITE_TITLE);
define('_HAPPYLINUX_VIEW_RDF_SITE_LINK', _HAPPYLINUX_VIEW_SITE_LINK);
define('_HAPPYLINUX_VIEW_RDF_SITE_DESCRIPTION', _HAPPYLINUX_VIEW_SITE_DESCRIPTION);
define('_HAPPYLINUX_VIEW_RDF_SITE_PUBLISHER', _HAPPYLINUX_VIEW_SITE_WEBMASTER);
define('_HAPPYLINUX_VIEW_RDF_SITE_RIGHT', _HAPPYLINUX_VIEW_SITE_COPYRIGHT);
define('_HAPPYLINUX_VIEW_RDF_SITE_DATE', _HAPPYLINUX_VIEW_SITE_PUBLISHED);
define('_HAPPYLINUX_VIEW_RDF_SITE_TEXTINPUT', '���� ������ �����');
define('_HAPPYLINUX_VIEW_RDF_SITE_IMAGE', '����������� ����� ');
define('_HAPPYLINUX_VIEW_RDF_IMAGE_TITLE', _HAPPYLINUX_VIEW_IMAGE_TITLE);
define('_HAPPYLINUX_VIEW_RDF_IMAGE_URL', _HAPPYLINUX_VIEW_IMAGE_URL);
define('_HAPPYLINUX_VIEW_RDF_IMAGE_LINK', _HAPPYLINUX_VIEW_SITE_LINK);
define('_HAPPYLINUX_VIEW_RDF_TITLE', _HAPPYLINUX_VIEW_TITLE);
define('_HAPPYLINUX_VIEW_RDF_LINK', _HAPPYLINUX_VIEW_LINK);
define('_HAPPYLINUX_VIEW_RDF_DESCRIPTION', _HAPPYLINUX_VIEW_DESCRIPTION);
define('_HAPPYLINUX_VIEW_RDF_TEXTINPUT', '���� ������');

// ATOM
define('_HAPPYLINUX_VIEW_ATOM_SITE_TITLE', _HAPPYLINUX_VIEW_SITE_TITLE);
define('_HAPPYLINUX_VIEW_ATOM_SITE_LINK', _HAPPYLINUX_VIEW_SITE_LINK);
define('_HAPPYLINUX_VIEW_ATOM_SITE_PUBLISHED', _HAPPYLINUX_VIEW_SITE_PUBLISHED);
define('_HAPPYLINUX_VIEW_ATOM_SITE_UPDATED', _HAPPYLINUX_VIEW_SITE_UPDATED);
define('_HAPPYLINUX_VIEW_ATOM_SITE_RIGHTS', _HAPPYLINUX_VIEW_SITE_COPYRIGHT);
define('_HAPPYLINUX_VIEW_ATOM_SITE_GENERATOR', _HAPPYLINUX_VIEW_SITE_GENERATOR);
define('_HAPPYLINUX_VIEW_ATOM_SITE_CATEGORY', _HAPPYLINUX_VIEW_SITE_CATEGORY);
define('_HAPPYLINUX_VIEW_ATOM_SITE_LINK_ALTERNATE', _HAPPYLINUX_VIEW_SITE_LINK);
define('_HAPPYLINUX_VIEW_ATOM_SITE_LINK_SELF', '����������� ����� ATOM');
define('_HAPPYLINUX_VIEW_ATOM_SITE_ID', 'ID �����');
define('_HAPPYLINUX_VIEW_ATOM_SITE_CONTRIBUTOR', '�������� �����');
define('_HAPPYLINUX_VIEW_ATOM_SITE_SUBTITLE', '������������ �����');
define('_HAPPYLINUX_VIEW_ATOM_SITE_ICON', '������ �����');
define('_HAPPYLINUX_VIEW_ATOM_SITE_LOGO', '������� �����');
define('_HAPPYLINUX_VIEW_ATOM_SITE_SOURCE', '�������� �����');
define('_HAPPYLINUX_VIEW_ATOM_SITE_AUTHOR_NAME', _HAPPYLINUX_VIEW_SITE_WEBMASTER);
define('_HAPPYLINUX_VIEW_ATOM_SITE_AUTHOR_URI', '����� ���-�������');
define('_HAPPYLINUX_VIEW_ATOM_SITE_AUTHOR_EMAIL', '����������� ����� ���-�������');
define('_HAPPYLINUX_VIEW_ATOM_TITLE', _HAPPYLINUX_VIEW_TITLE);
define('_HAPPYLINUX_VIEW_ATOM_LINK', _HAPPYLINUX_VIEW_LINK);
define('_HAPPYLINUX_VIEW_ATOM_PUBLISHED', _HAPPYLINUX_VIEW_PUBLISHED);
define('_HAPPYLINUX_VIEW_ATOM_UPDATED', _HAPPYLINUX_VIEW_UPDATED);
define('_HAPPYLINUX_VIEW_ATOM_SUMMARY', _HAPPYLINUX_VIEW_SUMMARY);
define('_HAPPYLINUX_VIEW_ATOM_CONTENT', _HAPPYLINUX_VIEW_CONTENT);
define('_HAPPYLINUX_VIEW_ATOM_CATEGORY', _HAPPYLINUX_VIEW_CATEGORY);
define('_HAPPYLINUX_VIEW_ATOM_RIGHTS', _HAPPYLINUX_VIEW_RIGHTS);
define('_HAPPYLINUX_VIEW_ATOM_SOURCE', _HAPPYLINUX_VIEW_SOURCE);
define('_HAPPYLINUX_VIEW_ATOM_ID', 'ID ATOM');
define('_HAPPYLINUX_VIEW_ATOM_CONTRIBUTOR', '��������');
define('_HAPPYLINUX_VIEW_ATOM_AUTHOR_NAME', _HAPPYLINUX_VIEW_AUTHOR_NAME);
define('_HAPPYLINUX_VIEW_ATOM_AUTHOR_URI', _HAPPYLINUX_VIEW_AUTHOR_URI);
define('_HAPPYLINUX_VIEW_ATOM_AUTHOR_EMAIL', _HAPPYLINUX_VIEW_AUTHOR_EMAIL);
define('_HAPPYLINUX_VIEW_ATOM_CONTRIBUTOR_NAME', '��������');
define('_HAPPYLINUX_VIEW_ATOM_CONTRIBUTOR_URI', '����� ���������');
define('_HAPPYLINUX_VIEW_ATOM_CONTRIBUTOR_EMAIL', '����������� ����� ���������');

// Dublin Core
define('_HAPPYLINUX_VIEW_DC_TITLE', _HAPPYLINUX_VIEW_TITLE);
define('_HAPPYLINUX_VIEW_DC_DESCRIPTION', _HAPPYLINUX_VIEW_DESCRIPTION);
define('_HAPPYLINUX_VIEW_DC_RIGHTS', _HAPPYLINUX_VIEW_RIGHTS);
define('_HAPPYLINUX_VIEW_DC_PUBLISHER', '��������');
define('_HAPPYLINUX_VIEW_DC_CREATOR', '���������');
define('_HAPPYLINUX_VIEW_DC_DATE', '����');
define('_HAPPYLINUX_VIEW_DC_FORMAT', '������');
define('_HAPPYLINUX_VIEW_DC_RELATION', '�����������');
define('_HAPPYLINUX_VIEW_DC_IDENTIFIER', '�������������');
define('_HAPPYLINUX_VIEW_DC_COVERAGE', '�����');
define('_HAPPYLINUX_VIEW_DC_AUDIENCE', '���������');
define('_HAPPYLINUX_VIEW_DC_SUBJECT', '����');
define('_HAPPYLINUX_VIEW_CONTENT_ENCODED', _HAPPYLINUX_VIEW_CONTENT);

// require / option
define('_HAPPYLINUX_VIEW_SITE_TAG', '��� �����');
define('_HAPPYLINUX_VIEW_SITE_LOGO', '����������� �������� �����');
define('_HAPPYLINUX_VIEW_RSS_ATOM_REQUIRE', '���������� ��� RSS/ATOM');
define('_HAPPYLINUX_VIEW_RSS_REQUIRE', '���������� ��� RSS');
define('_HAPPYLINUX_VIEW_ATOM_REQUIRE', '���������� ��� ATOM');
define('_HAPPYLINUX_VIEW_OPTION', '�����');
define('_HAPPYLINUX_VIEW_IMAGE_TOO_BIG', '������ ����������� ������, ��� � ������������');
define('_HAPPYLINUX_VIEW_IMAGE_MAX', '������������ ������ �����������');
