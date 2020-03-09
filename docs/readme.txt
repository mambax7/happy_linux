$Id: readme.txt,v 1.8 2012/04/08 18:24:35 ohwada Exp $

=================================================
Version: 1.80
Date:   2012-04-02
Author: Kenichi OHWADA
URL:    https://linux2.ohwada.net/
Email:  webmaster@ohwada.net
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. Changed the followings with the update of Weblinks module
(1) Changed pagenavi, page_frame, object and basic_object class

2. Bugfix
(1) Some error in api/rss_parser.php

3. Langauge pack
(1) Added Russian ( CP1251 & UTF-8 )
Files in language directory and extra directory.
Special thanks, Anthony xoops-org.ru ,


=================================================
Version: 1.70
Date:   2012-03-01
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. RSS Parser
(1) suport base tag in RSS Auto Discovery
(2) remove spaces in the head of xml
(3) suport georss without <geo:point>

2. DB hanlder
(1) add SHOW COLUMNS

3. bugfix
(1) NOT identify XOOPS Cube 2.2 in link to module management
(2) an error in constructor of happy_linux_config_form


=================================================
Version: 1.60
Date:   2011-12-29
================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. Migrating to PHP 5.3
Deprecated features in PHP 5.3.x
https://www.php.net/manual/en/migration53.deprecated.php
(1) ereg

2. bugfix
(1) can not get georss from Google map
https://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1142&forum=9

(2) NOT set offset in command
https://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1210&forum=9


=================================================
Version: 1.50
Date:   2009-02-25
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. changed the following feature with the update of RSSC module
(1) support GeoRSS and MediaRSS in rss parser
(2) add media_content_medium in rss builder


=================================================
Version: 1.41
Date:   2008-01-04
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
(1) "Not version xx" in weblinks and rssc
https://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=861&forum=5

(2) wrong RSS timestamp
https://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=906&forum=8


=================================================
Version: 1.40
Date:   2008-02-24
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. changed the following feature with the update of Weblinks module
(1) added PageRank class which get pagerank form Google
(2) added plugin class which handle plugins
(3) added plugin_manage class which manage plugins
(4) added xml_build class which build xml
(5) added kml_build class which build kml
(6) added plugin langauge file

2. lang pack
(1) updated arabic
https://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=350&forum=3

(2) updated persian
https://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=387&forum=2


=================================================
Version: 1.30
Date:   2008-01-30
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. changed the following feature with the update of RSSC module
(1) added rss_view_item class and htmlout function which invalid JavaScript


=================================================
Version: 1.23
Date:   2008-01-18
Author: Kenichi OHWADA
URL:    https://linux2.ohwada.net/
Email:  webmaster@ohwada.net
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. support RSS for Piclens
not output media:group tag

2. langugae
updated German. thanks sato-san

3. bug fix
(1) Only variables should be assigned by reference 
https://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=758&forum=5

(2) unserialize(): Argument is not an string


=================================================
Version: 1.22
Date:   2007-12-29
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. bug fix
(1) not show smiley icon in weblinks
https://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=746&forum=5


=================================================
Version: 1.21
Date:   2007-11-24
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. added table_manage class which manage DB table


=================================================
Version: 1.20
Date:   2007-11-11
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. added admin's page
show server enviroment variables

2. changed the following feature with the update of Weblinks module
(1) added module_install class which support onInstall onUpdate

(1) added memory functuion which show memory usage

(2) added xoops_block_check class which check xoops_block table
https://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=707&forum=5

(3) change multibyte function which add space after Japanese punctuation mark
(4) change admin class which show module management
(5) bugfix: typo cobe -> cube
https://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=700&forum=5

3. changed the following feature with the update of RSSC module
(1) change strings class which replace empty if all space code when build summary
(2) defeat errors in PHP5 E_STRICT level

(3) bugfix: preg_match()
https://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=709&forum=9

4. changed the following feature with the update of WhatsNew module
(1) added date class which handle time zone
https://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=713&forum=8

(2) added build_cache class which handle template cache
(3) added config language file which handle module configration

4. lang pack
(1) added Italian first version
https://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=337&forum=2

(2) added Arabic first version
https://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=350&forum=3

(3) updateed Persian v1.11
https://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=343&forum=2


=================================================
Version: 1.11
Date:   2007-09-23
=================================================

This module is the library collection for modules distributing in Happy Linux

1. tempolary directory for banner image
https://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=694&forum=5

(1) check include /tmp in open_basedir
(2) admin can specify optional directory using preload

2. support PHP 5.2
(1) defeat errors in E_STRICT level
(2) change handling DB in command line mode

3. some bug fix with weblinks module


=================================================
Version: 1.10
Date:   2007-09-15
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. changed the following feature with the update of Weblinks module
(1) added mail_template class which edit email template 
(2) added mail_form class which show email form 
(3) added mail_send class which send email 
(4) added language file main.php 
(5) changed page_frame class which add op vriable
(6) changed error class which show error message
(7) changed system class which get module list


=================================================
Version: 1.01
Date:   2007-09-01
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. bug fix
(1) build_rss.php: Only variables should be assigned by reference
https://dev.xoops.org/modules/xfmod/tracker/?func=detail&aid=4697&group_id=1300&atid=1353
https://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=8&topic_id=681


=================================================
Version: 1.00
Date:   2007-08-01
Author: Kenichi OHWADA
URL:    https://linux2.ohwada.net/
Email:  webmaster@ohwada.net
=================================================

This module is the library collection for modules distributing in Happy Linux

* Changes *
1. Supported MySQL 4.1/5.x
(1) character code setting
https://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=9&topic_id=631
For exsample in Japanese, program fixed ujis (EUC-JP) in character code of MySQL.
Administrator can change character code, setting preload/charset.php.

(2) added following files
- api/bin.php
- preload/_charset.php
- language/english/charset.php
- language/japanese/charset.php

(3) added to set character code in BinBase class

1. changed the following feature with the update of Weblinks module
(1) added sanitize.php which sanitize value
(2) changed strings class which sanitize value
(3) changed object_handler class which get filed name
(4) changed error class whtch highlight error
(5) chnaged config_store_handler which get value
(6) changed html calss which base on W3C

3. RSS parser, RSS builder
(1) added w3cdtf.php which convert W3C date format
(2) changed rss_base_object class which convert date format
(3) changed rss_parse_object class which convert undefined character
(4) changed convert_encoding class and rss_build class which convert UTF-8

4. Multi Langage
(1) added German files
(2) added Germany (de) in locate
https://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=323&forum=2

5. bug fix 
(1) not sanitize site_url in rss
(2) in form class write by mistake.


=================================================
Version: 0.91
Date:   2007-07-20
=================================================

This module is the library collection for modules distributing in Happy Linux

* changes *
(1) add to check email format
(2) support GeoRSS and Media RSS

bug fix
(1) 4647: keyword "abc" match "abccc"


=================================================
Version: 0.90
Date:   2007-07-01
=================================================

This module is the library collection for modules distributing in Happy Linux

* changes *
1. changed the following feature with the update of RSSC module
(1) added admin class which judge XOOPS major version
(2) added extract_word class which extract words from phrase
(3) added kakasi class which handle kakasi
(4) added dir class which handle directory
(5) added bin_file class which handle file for command line
(6) divided 3 class, rss_base, rss_parse, rss_view from rss object class
(7) changed bin_base class which parse command line parameter
(8) changed manage class which modify two or more records
(9) changed file class which append line
(10) adopted debug_print_backtrace() for debug
(11) added 3 api files, rss_paser, rss_builder, admin
(12) added preload direcotry

2. multi language
(1) add Japanese UTF-8 files
(2) add lang_name_jp.php which identify Japanse laguage file name like japanese, jp_utf8


=================================================
Version: 0.80
Date:   2007-05-15
=================================================

This module is the library collection for modules distributing in Happy Linux

* changes *
1. changed the following feature with the update of Webliks module
(1) move magpie rss parser class and rss parser class from RSSC module
- magpie_parser
- magpie_cache
- rss_object
- rss_parser
- rss_utility

(2) porting weblog update class from WhatsNew module
- weblog update

(3) move languge file from RSSC module
- rss_view.php

(4) template languge file from RSSC module
- view_rss.html
- view_rdf.html
- view_atom.html

(5) can change site information when building RSS

2. Supported XoopsCube 2.1 
(1) added function check to insall legacy module
(2) change site author from "meta_author" to user with uid=1

3. Adapted GIJOE's myblocksadmin
- admin.php
- myblockform.php
- myblocksadmin.php
- mygroupperm.php
- mygrouppermform.php
- blocksadmin.inc.php
- updateblock.inc.php

4. others
(1) set "/tmp" in work directory in UNIX
(2) set 666 in permission, when apache create file, 
because admin can delete

5. Multi Languages
added persian files (translated by xoops persian)

* Notice *
I added many files.
Although there are no big problem, but I think that there are any small problem.
Welcome a bug report, a bug solution, and your hack, etc. 


=================================================
Version: 0.70
Date:   2007-02-20
=================================================

This module is the library collection for modules distributing in Happy Linux

* changes *
(1) added time class whitch measure execution time
(2) rather change with chnage of Weblinks module


=================================================
Version: 0.60
Date:   2006-12-17
=================================================

This module is the library collection for modules distributing in Happy Linux

* main changes *
1. added the following feature with the update of Webliks module
(1) added object_validate class whitch validate object for saving DB
(2) added debug class whitch show debug variables
(3) added method which split current time into year, month, day in form and strings class 

2. language
(1) added language file for lower compatible

3. locate
(1) added Iran (ir)

4. bug fix
(1) 4417: language singleton done not work correctly
https://dev.xoops.org/modules/xfmod/tracker/?func=detail&aid=4417&group_id=1199&atid=971
https://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=256&forum=2


=================================================
Version: 0.50
Date:   2006-11-20
=================================================

This module is the library collection for modules distributing in Happy Linux

* main changes *
1. added the following feature with the creation of happy_search module
(1) added method which get XOOPS system variable in search class
(2) added method which get integer array in post class
(3) added method which set checkbox form in object class
(4) added method which handle groupperm table in system class
(5) added method which convert UTF8 in convert_encoding class
(6) added method which implode array in strings class
(7) changed method which escape delmita in highlight class

2. some features for the developer.
(1) TEST object class
(2) TEST config_base class

3. bugfix
(1) 4378: dont strip slashes in conf_value when magic_quotes_gpc is on
https://dev.xoops.org/modules/xfmod/tracker/index.php?func=detail&aid=4378&group_id=1300&atid=1353
https://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=558&start=0#forumpost1975

(2) 4379: Undefined property: _flag_allow_url_fopen
(3) 4380: Only variables should be assigned by reference
https://dev.xoops.org/modules/xfmod/tracker/index.php?func=detail&aid=4379&group_id=1300&atid=1353
https://dev.xoops.org/modules/xfmod/tracker/index.php?func=detail&aid=4380&group_id=1300&atid=1353
https://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=256&forum=2


=================================================
Version: 0.40
Date:   2006-11-08
=================================================

This module is the library collection for modules distributing in Happy Linux

* main changes *
added the following feature with the change of RSSC module
(1) support proxy server
(2) change for form of config table
(3) small change for sanitize


=================================================
Version: 0.32
Date:   2006-10-29
=================================================

This module is the library collection for modules distributing in Happy Linux

* main changes *
(1) added persian lang pack
https://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=243&forum=5

(2) bug fix 4339: Fatal error: Call to undefined function: strcut() 
https://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=244&forum=5
https://dev.xoops.org/modules/xfmod/tracker/index.php?func=detail&aid=4339&group_id=1300&atid=1353


=================================================
Version: 0.31
Date:   2006-10-14
=================================================

This module is the library collection for modules distributing in Happy Linux

* main changes *
(1) added functions for Google search
(2) correct bug to highlight double


=================================================
Version: 0.30
Date:   2006-10-01
=================================================

This module is the library collection for modules distributing in Happy Linux

* main changes *
1. use in weblinks module
added the following feature.

(1) introduced the mechanism of selection countory (Locate) experimentally.
This can be select a language and a country independently.
use ccTLDs for the country code.
https://www.iana.org/cctld/
prepared three country code, Japan (jp), USA (us), United Kingdom (uk).

(2) added file handling class


=================================================
Version: 0.20
Date:   2006-09-10
=================================================

This module is the library collection for modules distributing in Happy Linux

* main changes *
(1) search
(1-1) added hightlight class
      reffer SmartSection module
(1-2) added function which build within keywords
(1-3) support fuzzy search (Jananese only)
      reffer Amethyst Blue's Search module

(2) added session ticket class (XoopsGTicket)
    reffer Perk's Tinyd module

(3) divid html builder class and form library class from form builder class
(4) added RDF/RSS/ATOM builder class
(5) added server environmet class
(6) added each language class
(7) gathered mutibyte function into one file
(8) corrected same typos


=================================================
Version: 0.10
Date:   2006-07-10
Author: Kenichi OHWADA
URL:    https://linux2.ohwada.net/
Email:  webmaster@ohwada.net
=================================================

This module is the library collection for modules distributing in Happy Linux

* overview *
This module is adopting the style of the module,
but this provide no application feature.
this is only program library collection.
It works even if you install this module, or not.

The module which is using this
- RSS Center

The module to plan to use in future.
- Weblinks
- What's New

* TODO *
In the future, I will adopt new framework which will be implemented in XOOPS Cube 2.1.
However, in the present, 
the are more platforms such as XOOPS 2.0, 2.2, 2.0 JP, Cube 2.1,
and then I concentrated as the ad hoc program library collection which can be used in the commonness to ther platfoems.
