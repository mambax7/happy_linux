<?php
// $Id: rss_view.php,v 1.2 2007/05/16 09:20:30 ohwada Exp $

// 2007-05-12 K.OHWADA
// this is new file
// move from rssc module

//=========================================================
// Happy Linux Framework Module
// 2007-05-06 K.OHWADA
// ͭ����������
//=========================================================

// common
define('_HAPPYLINUX_VIEW_SITE_TITLE', '������̾');
define('_HAPPYLINUX_VIEW_SITE_LINK', '������URL');
define('_HAPPYLINUX_VIEW_SITE_DESCRIPTION', '�����Ȥ�����');
define('_HAPPYLINUX_VIEW_SITE_PUBLISHED', '�����ȸ�����');
define('_HAPPYLINUX_VIEW_SITE_UPDATED', '�����ȹ�����');
define('_HAPPYLINUX_VIEW_SITE_DATE', '�����Ⱥ�����');
define('_HAPPYLINUX_VIEW_SITE_COPYRIGHT', '���������');
define('_HAPPYLINUX_VIEW_SITE_GENERATOR', '������������');
define('_HAPPYLINUX_VIEW_SITE_CATEGORY', '�����ȡ����ƥ���');
define('_HAPPYLINUX_VIEW_SITE_WEBMASTER', '�����ȴ�����');
define('_HAPPYLINUX_VIEW_SITE_LANGUAGE', '�����ȸ���');
define('_HAPPYLINUX_VIEW_TITLE', '�����ȥ�');
define('_HAPPYLINUX_VIEW_LINK', 'URL');
define('_HAPPYLINUX_VIEW_DESCRIPTION', '����');
define('_HAPPYLINUX_VIEW_SUMMARY', '����');
define('_HAPPYLINUX_VIEW_CONTENT', '����');
define('_HAPPYLINUX_VIEW_PUBLISHED', '������');
define('_HAPPYLINUX_VIEW_UPDATED', '������');
define('_HAPPYLINUX_VIEW_CREATED', '������');
define('_HAPPYLINUX_VIEW_CATEGORY', '���ƥ���');
define('_HAPPYLINUX_VIEW_RIGHTS', '���');
define('_HAPPYLINUX_VIEW_SOURCE', '����');
define('_HAPPYLINUX_VIEW_AUTHOR_NAME', '���̾');
define('_HAPPYLINUX_VIEW_AUTHOR_URI', '���URL');
define('_HAPPYLINUX_VIEW_AUTHOR_EMAIL', '��ԥ᡼��');
define('_HAPPYLINUX_VIEW_IMAGE_TITLE', '���������ȥ�');
define('_HAPPYLINUX_VIEW_IMAGE_URL', '����URL');
define('_HAPPYLINUX_VIEW_ENCLOSURE_URL', 'Ʊ���ե����� Url');
define('_HAPPYLINUX_VIEW_ENCLOSURE_TYPE', 'Ʊ���ե����� Type');
define('_HAPPYLINUX_VIEW_ENCLOSURE_LENGTH', 'Ʊ���ե����� Length');

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
define('_HAPPYLINUX_VIEW_RSS_SITE_MANAGINGEDITOR', '�������Խ���');
define('_HAPPYLINUX_VIEW_RSS_SITE_DOCS', '������ʸ��');
define('_HAPPYLINUX_VIEW_RSS_SITE_CLOUD', '�����ȡ����饦��');
define('_HAPPYLINUX_VIEW_RSS_SITE_TTL', '��������¸����');
define('_HAPPYLINUX_VIEW_RSS_SITE_RATING', '������ɾ��');
define('_HAPPYLINUX_VIEW_RSS_SITE_TEXTINPUT', '�����ȡ��ƥ���������');
define('_HAPPYLINUX_VIEW_RSS_SITE_SKIPHOURS', '�����ȡ������å׻���');
define('_HAPPYLINUX_VIEW_RSS_SITE_SKIPDAYS', '�����ȡ������å�����');
define('_HAPPYLINUX_VIEW_RSS_IMAGE_TITLE', _HAPPYLINUX_VIEW_IMAGE_TITLE);
define('_HAPPYLINUX_VIEW_RSS_IMAGE_URL', _HAPPYLINUX_VIEW_IMAGE_URL);
define('_HAPPYLINUX_VIEW_RSS_IMAGE_WIDTH', '��������');
define('_HAPPYLINUX_VIEW_RSS_IMAGE_HEIGHT', '�����ι⤵');
define('_HAPPYLINUX_VIEW_RSS_IMAGE_LINK', _HAPPYLINUX_VIEW_SITE_LINK);
define('_HAPPYLINUX_VIEW_RSS_TITLE', _HAPPYLINUX_VIEW_TITLE);
define('_HAPPYLINUX_VIEW_RSS_LINK', _HAPPYLINUX_VIEW_LINK);
define('_HAPPYLINUX_VIEW_RSS_DESCRIPTION', _HAPPYLINUX_VIEW_DESCRIPTION);
define('_HAPPYLINUX_VIEW_RSS_PUBDATE', _HAPPYLINUX_VIEW_PUBLISHED);
define('_HAPPYLINUX_VIEW_RSS_CATEGORY', _HAPPYLINUX_VIEW_CATEGORY);
define('_HAPPYLINUX_VIEW_RSS_SOURCE', _HAPPYLINUX_VIEW_SOURCE);
define('_HAPPYLINUX_VIEW_RSS_GUID', 'RSS guid');
define('_HAPPYLINUX_VIEW_RSS_AUTHOR', '���');
define('_HAPPYLINUX_VIEW_RSS_COMMENTS', '������');
define('_HAPPYLINUX_VIEW_RSS_ENCLOSURE', 'Ʊ��');

// RDF
define('_HAPPYLINUX_VIEW_RDF_SITE_TITLE', _HAPPYLINUX_VIEW_SITE_TITLE);
define('_HAPPYLINUX_VIEW_RDF_SITE_LINK', _HAPPYLINUX_VIEW_SITE_LINK);
define('_HAPPYLINUX_VIEW_RDF_SITE_DESCRIPTION', _HAPPYLINUX_VIEW_SITE_DESCRIPTION);
define('_HAPPYLINUX_VIEW_RDF_SITE_PUBLISHER', _HAPPYLINUX_VIEW_SITE_WEBMASTER);
define('_HAPPYLINUX_VIEW_RDF_SITE_RIGHT', _HAPPYLINUX_VIEW_SITE_COPYRIGHT);
define('_HAPPYLINUX_VIEW_RDF_SITE_DATE', _HAPPYLINUX_VIEW_SITE_PUBLISHED);
define('_HAPPYLINUX_VIEW_RDF_SITE_TEXTINPUT', '�����ȡ��ƥ���������');
define('_HAPPYLINUX_VIEW_RDF_SITE_IMAGE', '�����Ȳ���');
define('_HAPPYLINUX_VIEW_RDF_IMAGE_TITLE', _HAPPYLINUX_VIEW_IMAGE_TITLE);
define('_HAPPYLINUX_VIEW_RDF_IMAGE_URL', _HAPPYLINUX_VIEW_IMAGE_URL);
define('_HAPPYLINUX_VIEW_RDF_IMAGE_LINK', _HAPPYLINUX_VIEW_SITE_LINK);
define('_HAPPYLINUX_VIEW_RDF_TITLE', _HAPPYLINUX_VIEW_TITLE);
define('_HAPPYLINUX_VIEW_RDF_LINK', _HAPPYLINUX_VIEW_LINK);
define('_HAPPYLINUX_VIEW_RDF_DESCRIPTION', _HAPPYLINUX_VIEW_DESCRIPTION);
define('_HAPPYLINUX_VIEW_RDF_TEXTINPUT', '�ƥ���������');

// ATOM
define('_HAPPYLINUX_VIEW_ATOM_SITE_TITLE', _HAPPYLINUX_VIEW_SITE_TITLE);
define('_HAPPYLINUX_VIEW_ATOM_SITE_LINK', _HAPPYLINUX_VIEW_SITE_LINK);
define('_HAPPYLINUX_VIEW_ATOM_SITE_PUBLISHED', _HAPPYLINUX_VIEW_SITE_PUBLISHED);
define('_HAPPYLINUX_VIEW_ATOM_SITE_UPDATED', _HAPPYLINUX_VIEW_SITE_UPDATED);
define('_HAPPYLINUX_VIEW_ATOM_SITE_RIGHTS', _HAPPYLINUX_VIEW_SITE_COPYRIGHT);
define('_HAPPYLINUX_VIEW_ATOM_SITE_GENERATOR', _HAPPYLINUX_VIEW_SITE_GENERATOR);
define('_HAPPYLINUX_VIEW_ATOM_SITE_CATEGORY', _HAPPYLINUX_VIEW_SITE_CATEGORY);
define('_HAPPYLINUX_VIEW_ATOM_SITE_LINK_ALTERNATE', _HAPPYLINUX_VIEW_SITE_LINK);
define('_HAPPYLINUX_VIEW_ATOM_SITE_LINK_SELF', 'ATOM���Ȥ�URL');
define('_HAPPYLINUX_VIEW_ATOM_SITE_ID', '������ID');
define('_HAPPYLINUX_VIEW_ATOM_SITE_CONTRIBUTOR', '�����ȹ׸���');
define('_HAPPYLINUX_VIEW_ATOM_SITE_SUBTITLE', '����������');
define('_HAPPYLINUX_VIEW_ATOM_SITE_ICON', '�����ȡ���������');
define('_HAPPYLINUX_VIEW_ATOM_SITE_LOGO', '�����ȡ���');
define('_HAPPYLINUX_VIEW_ATOM_SITE_SOURCE', '�����Ⱦ���');
define('_HAPPYLINUX_VIEW_ATOM_SITE_AUTHOR_NAME', _HAPPYLINUX_VIEW_SITE_WEBMASTER);
define('_HAPPYLINUX_VIEW_ATOM_SITE_AUTHOR_URI', '������URL');
define('_HAPPYLINUX_VIEW_ATOM_SITE_AUTHOR_EMAIL', '�����ԥ᡼��');
define('_HAPPYLINUX_VIEW_ATOM_TITLE', _HAPPYLINUX_VIEW_TITLE);
define('_HAPPYLINUX_VIEW_ATOM_LINK', _HAPPYLINUX_VIEW_LINK);
define('_HAPPYLINUX_VIEW_ATOM_PUBLISHED', _HAPPYLINUX_VIEW_PUBLISHED);
define('_HAPPYLINUX_VIEW_ATOM_UPDATED', _HAPPYLINUX_VIEW_UPDATED);
define('_HAPPYLINUX_VIEW_ATOM_SUMMARY', _HAPPYLINUX_VIEW_SUMMARY);
define('_HAPPYLINUX_VIEW_ATOM_CONTENT', _HAPPYLINUX_VIEW_CONTENT);
define('_HAPPYLINUX_VIEW_ATOM_CATEGORY', _HAPPYLINUX_VIEW_CATEGORY);
define('_HAPPYLINUX_VIEW_ATOM_RIGHTS', _HAPPYLINUX_VIEW_RIGHTS);
define('_HAPPYLINUX_VIEW_ATOM_SOURCE', _HAPPYLINUX_VIEW_SOURCE);
define('_HAPPYLINUX_VIEW_ATOM_ID', 'ATOM id');
define('_HAPPYLINUX_VIEW_ATOM_CONTRIBUTOR', '�׸���');
define('_HAPPYLINUX_VIEW_ATOM_AUTHOR_NAME', _HAPPYLINUX_VIEW_AUTHOR_NAME);
define('_HAPPYLINUX_VIEW_ATOM_AUTHOR_URI', _HAPPYLINUX_VIEW_AUTHOR_URI);
define('_HAPPYLINUX_VIEW_ATOM_AUTHOR_EMAIL', _HAPPYLINUX_VIEW_AUTHOR_EMAIL);
define('_HAPPYLINUX_VIEW_ATOM_CONTRIBUTOR_NAME', '�׸���');
define('_HAPPYLINUX_VIEW_ATOM_CONTRIBUTOR_URI', '�׸���URL');
define('_HAPPYLINUX_VIEW_ATOM_CONTRIBUTOR_EMAIL', '�׸��ԥ᡼��');

// Dublin Core
define('_HAPPYLINUX_VIEW_DC_TITLE', _HAPPYLINUX_VIEW_TITLE);
define('_HAPPYLINUX_VIEW_DC_DESCRIPTION', _HAPPYLINUX_VIEW_DESCRIPTION);
define('_HAPPYLINUX_VIEW_DC_RIGHTS', _HAPPYLINUX_VIEW_RIGHTS);
define('_HAPPYLINUX_VIEW_DC_PUBLISHER', 'ȯ�Լ�');
define('_HAPPYLINUX_VIEW_DC_CREATOR', '����');
define('_HAPPYLINUX_VIEW_DC_DATE', '������');
define('_HAPPYLINUX_VIEW_DC_FORMAT', '����');
define('_HAPPYLINUX_VIEW_DC_RELATION', '�ط�');
define('_HAPPYLINUX_VIEW_DC_IDENTIFIER', 'ID');
define('_HAPPYLINUX_VIEW_DC_COVERAGE', '�ϰ�');
define('_HAPPYLINUX_VIEW_DC_AUDIENCE', '�ѵ�');
define('_HAPPYLINUX_VIEW_DC_SUBJECT', '����');
define('_HAPPYLINUX_VIEW_CONTENT_ENCODED', _HAPPYLINUX_VIEW_CONTENT);

// require / option
define('_HAPPYLINUX_VIEW_SITE_TAG', '������ ����');
define('_HAPPYLINUX_VIEW_SITE_LOGO', '�����ȤΥ�����');
define('_HAPPYLINUX_VIEW_RSS_ATOM_REQUIRE', 'RSS/ATOM��ɬ�ܹ��ܤǤ�');
define('_HAPPYLINUX_VIEW_RSS_REQUIRE', 'RSS��ɬ�ܹ��ܤǤ�');
define('_HAPPYLINUX_VIEW_ATOM_REQUIRE', 'ATOM��ɬ�ܹ��ܤǤ�');
define('_HAPPYLINUX_VIEW_OPTION', 'Ǥ�չ��ܤǤ�');
define('_HAPPYLINUX_VIEW_IMAGE_TOO_BIG', '���������������ʤ����礭��');
define('_HAPPYLINUX_VIEW_IMAGE_MAX', '�����������κ�����');
