$Id: readme_ja.txt,v 1.8 2012/04/08 18:24:36 ohwada Exp $

=================================================
Version: 1.80
Date:   2012-04-0２
Author: Kenichi OHWADA
URL:    http://linux.ohwada.jp/
Email:  webmaster@ohwada.jp
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. weblinks モジュールの変更に伴い、下記の変更を行った
(1) pagenavi page_frame object basic_object クラスを変更した

2. バグ修正
(1) api/rss_parser.php に誤りがあった

3. 言語ファイル
(1) ロシア語 ( CP1251 & UTF-8 ) を追加した
language ディレクトリの他に extra ディレクトリにも置いている
多謝 Anthony xoops-org.ru 


=================================================
Version: 1.70
Date:   2012-03-01
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. RSS Parser
(1) RSS Auto Discovery にて base タグに対応した
(2) 頭に空白がある xml は空白を削除した
(3) <geo:point> のない georss に対応した

2. DB ハンドラー
(1) SHOW COLUMNS を追加した

3. バグ修正
(1) モジュール管理へのリンクで XOOPS Cube 2.2 と識別できかった
(2) happy_linux_config_form のコンストラクタに誤りがあった


=================================================
Version: 1.60
Date:   2011-12-29
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. PHP 5.3 対応
PHP 5.3.x で推奨されない機能 を修正した
http://www.php.net/manual/ja/migration53.deprecated.php
(1) ereg

2. バグ修正
(1) Google mapからgeorssの取得ができない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1142&forum=9

(2) コマンドの offset が指定できない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=1210&forum=9

=================================================
Version: 1.50
Date:   2009-02-25
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. rssc モジュールの変更に伴い、下記の変更を行った
(1) RSS解析を GeoRSS とMediaRSS に対応した
(2) RSS生成に media_content_medium を追加した


=================================================
Version: 1.41
Date:   2009-01-04
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
(1) weblinks や rssc にて「バージョン xx ではない」
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=861&forum=5

(2) RSSのタイムスタンプがずれる
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=906&forum=8


=================================================
Version: 1.40
Date:   2008-02-24
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. weblinks モジュールの変更に伴い、下記の変更を行った
(1) Google PageRank を取得する pagerank クラスを新設した
(2) プラグインの処理を行う plugin クラスを新設した
(3) プラグインの管理を行う plugin_manage クラスを新設した
(4) xml 生成を行う build_xml クラスを新設した
(5) kml 生成を行う build_kml クラスを新設した
(6) 言語ファイル plugin を新設した

2. 言語ファイル
(1) アラビア語 更新
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=350&forum=3

(2) ペルシア語
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=387&forum=2


=================================================
Version: 1.30
Date:   2008-01-30
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. rssc モジュールの変更に伴い、下記の変更を行った
(1) JavaScript 関連の無効化の処理を行う rss_view_item クラスと htmlout 関数を新設した


=================================================
Version: 1.23
Date:   2008-01-18
Author: Kenichi OHWADA
URL:    http://linux.ohwada.jp/
Email:  webmaster@ohwada.jp
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. RSS の Piclens 対応
media:group タグを出力しない

2. 言語
ドイツ語 更新

3. バグ対策
(1) Only variables should be assigned by reference 
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=758&forum=5

(2) unserialize(): Argument is not an string


=================================================
Version: 1.22
Date:   2007-12-29
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. バグ対策
(1) weblinks にて、スマイリーアイコンが表示されない
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=746&forum=5


=================================================
Version: 1.21
Date:   2007-11-24
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. DB テーブル管理を行う table_manage.php クラスを追加した


=================================================
Version: 1.20
Date:   2007-11-11
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. 管理者画面を追加した
サーバー環境変数を表示した

2. weblinks モジュールの変更に伴い、下記の変更を行った
(1) onInstall onUpdate に対応した module_install クラスを新設した

(2) メモリ使用量を表示する memory 関数を新設した

(3) ブロック・テーブルの検査を行う xoops_block_check クラスを新設した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=707&forum=5

(4) multibyte 関数に日本語句読点の後ろに空白文字を追加する処理を追加した
(5) admin クラスにモジュール管理を追加した

(6) typo 修正： cobe -> cube
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=700&forum=5

3. rssc モジュールの変更に伴い、下記の変更を行った
(1) strings クラスに 要約作成時に全て空白文字ならば空にする処理を追加した
(2) PHP5 の E_STRICT レベルのエラーを潰した

(3) バグ修正： preg_match()
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=709&forum=9

4. whatsnew モジュールの変更に伴い、下記の変更を行った
(1) タイムゾーンを扱うために date クラスを新設した
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=713&forum=8

(2) テンプレート・キャシュを扱うために build_cache クラスを新設した
(3) モジュール設定のための config 言語ファイルを新設した

5. 言語
(1) イタリア語を追加した
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=337&forum=2

(2) アラビア語を追加した
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=350&forum=3

(3) ペルシャ語を更新した
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=343&forum=2


=================================================
Version: 1.11
Date:   2007-09-23
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. バナー画像の一時保管ディレクトリ
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=694&forum=5

(1) /tmp が open_basedir に含まれるか検査した
(2) 管理者が preload にて任意のディレクトリを指定できる

2. PHP 5.2 対応
(1) E_STRICT レベルのエラーを潰した
(2) コマンドライン・モードのDB処理を変更した

3. weblinks モジュールのバグ対策に伴い、いくつか変更を行った


=================================================
Version: 1.10
Date:   2007-09-15
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. weblinks モジュールの変更に伴い、下記の変更を行った
(1) メールのテンプレートの編集を行う mail_template クラスを新設した
(2) メールのフォームを表示する mail_form クラスを新設した
(3) メールの送信を行う mail_send クラスを新設した
(4) 言語ファイル main.php を追加した
(5) page_frame クラスに op 変数を追加した
(6) error クラスのエラー表示を変更した
(7) system クラスにモジュール一覧の取得を追加した


=================================================
Version: 1.01
Date:   2007-09-01
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. バグ対策
(1) RSS配信で Noitce エラーが表示される
http://dev.xoops.org/modules/xfmod/tracker/?func=detail&aid=4697&group_id=1300&atid=1353
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=8&topic_id=681


=================================================
Version: 1.00
Date:   2007-08-05
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. MySQL 4.1/5.x の対応
(1) 文字コードの指定
http://linux.ohwada.jp/modules/newbb/viewtopic.php?forum=9&topic_id=631
日本語では、MySQL の文字コードは ujis (EUC-JP) に固定にしていた。
管理者が preload/charset.php を設置して、任意の文字コードが指定できるように変更した。

(2) 下記のファイルを新設した
- api/bin.php
- preload/_charset.php
- language/english/charset.php
- language/japanese/charset.php

(3) bin_base クラスに文字コードの設定を追加した

2. weblinks モジュールの変更に伴い、下記の変更を行った
(1) サニタイズ処理を行う sanitize.php を新設した
(2) strings クラスのサニタイズ処理を変更した
(3) object_handler クラスにフィールド項目名の取得を追加した
(4) error クラスにエラー表示のハイライトを追加した
(5) config_store_handler クラスに設定値の取得を追加した
(6) html クラスを W3C 準拠に変更した

3. RSS解析, RSS配信
(1) W3C形式の日付を変換する w3cdtf.php を新設した
(2) rss_base_object クラスの日付処理を変更した
(3) rss_parse_object クラスに未定義文字の処理を追加した
(4) convert_encoding クラスと rss_build クラスの UTF-8 変換を変更した

4. 多言語
(1) ドイツ語を追加した
(2) ドイツ国を追加した
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=323&forum=2

5. バグ対策
(1) RSS 表示の site_url がサニタイズされない
(2) form クラスの誤記を修正した


=================================================
Version: 0.91
Date:   2007-07-20
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
(1) メールアドレスの検査を追加した
(2) GeoRSS と Media RSS に対応した

バグ対策
(1) 4647: keyword "abc" match "abccc"


=================================================
Version: 0.90
Date:   2007-07-01
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. RSSC モジュールの変更に伴い、下記の変更を行った
(1) XOOPS のメジャー・バージョンを判定する admin クラスを新設した
(2) 分かち書きを行う extract_word クラスを新設した
(3) kakasi の操作を行う kakasi クラスを新設した
(4) ディレクトリ操作を行う dir クラスを新設した
(5) コマンド実行時のファイルを扱う bin_file クラスに新設した
(6) rss オブジェクト・クラスを rss_base と rss_parse と rss_view に分割した
(7) bin_base クラスにコマンド・パラメータの解析を追加した
(8) manage クラスに一括変更を追加した
(9) file クラスに追記書込みを追加した
(10) デバックのために debug_print_backtrace() を採用した
(11) rss_paser と rss_builder と admin の api ファイルを追加した
(12) preload ディレィトリを追加した

2. 多言語
(1) 日本語 UTF-8 ファイルを追加した
(2) 日本語 とする言語ファイル名 japanese, jp_utf8 を設定するために lang_name_jp.php を新設した


=================================================
Version: 0.80
Date:   2007-05-15
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 変更内容
1. WhatsNew モジュールの変更に伴い、下記の変更を行った
(1) magpie rss parser および rss 解析のクラスを RSSC モジュールより移設した
- magpie_parser
- magpie_cache
- rss_object
- rss_parser
- rss_utility

(2) weblog update のクラスを WhatsNew モジュールより移植した
- weblog update

(3) 言語ファイルを RSSC モジュールより移設した
- rss_view.php

(4) テンプレートを RSSC モジュールより移設した
- view_rss.html
- view_rdf.html
- view_atom.html

(5) RSS 生成時にサイト情報を変更可能にした

2. XoopsCube 2.1 に対応した
(1) legacy モジュールがインストールされているかを確認する関数を追加した
(2) サイト管理者を meta_author から uid=1 のユーザに変更した

3. GIJOE さんの myblocksadmin を採用した
- admin.php
- myblockform.php
- myblocksadmin.php
- mygroupperm.php
- mygrouppermform.php
- blocksadmin.inc.php
- updateblock.inc.php

4. そのほか
(1) UNIX 環境にて、work ディレクトリ に /tmp を指定した
(2) appache 権限で作成したファイルを消去できるように、
ファイル作成時にアクセス権 を 666 にした

5. 多言語 対応
ペルシャ語ファイル (xoops persian 翻訳)

● 注意
多くのプログラムを追加しています。
大きな問題はないはずですが、小さな問題はあると思います。
バグ報告やバグ解決などを歓迎します。


=================================================
Version: 0.70
Date:   2007-02-20
Author: Kenichi OHWADA
URL:    http://linux.ohwada.jp/
Email:  webmaster@ohwada.jp
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

変更内容
(1) プログラムの実行時間を計測するための time クラスを新設した
(2) Weblinks モジュールの変更に伴い、若干変更した


=================================================
Version: 0.60
Date:   2006-12-17
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

変更内容
1. Weblinks モジュールの変更に伴い、下記の機能を追加した
(1) データベースに保存するオブジェクトを加工する object_validate クラスを新設した
(2) デバック変数の表示する debug クラスを新設した
(3) フォームクラスと文字クラスに現在時を年月日に分割するメソッドを追加した

2. 言語ファイル
(1) 下位互換用の言語ファイルを追加した

3. 地域
(1) イラン (ir) を追加した

4. バグの修正
(1) 4417: language singleton done not work correctly
http://dev.xoops.org/modules/xfmod/tracker/?func=detail&aid=4417&group_id=1199&atid=971
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=256&forum=2


=================================================
Version: 0.50
Date:   2006-11-20
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

変更内容
1. happy_search モジュールの新設に伴い、下記の機能を追加した
(1) 検索クラスにXOOPSシステム変数を取得するメソッドの追加
(2) POST変数クラスに整数配列を取得するメソッドの追加
(3) オブジェクト・クラスにcheckbox形式の変数を登録するためのメソッドの追加
(4) システム・クラスにgrouppermテーブルを操作するメソッドの追加
(5) 文字エンコード変換クラスにUTF8変換するメソッドの追加
(6) 文字クラスに配列を連結するメソッドの追加
(7) ハイライト・クラスに区切り文字をエスケープする変更

2. 開発者向けに下記の機能を新設した
(1) オブジェクト・クラスをテストする
(2) 設定オブジェクト・クラスをテストする

3. バグの修正
(1) 4378: POST変数にバックスラッシュが付加される
http://dev.xoops.org/modules/xfmod/tracker/index.php?func=detail&aid=4378&group_id=1300&atid=1353
http://linux.ohwada.jp/modules/newbb/viewtopic.php?topic_id=558&start=0#forumpost1975

(2) 4379: Undefined property: _flag_allow_url_fopen
(3) 4380: Only variables should be assigned by reference
http://dev.xoops.org/modules/xfmod/tracker/index.php?func=detail&aid=4379&group_id=1300&atid=1353
http://dev.xoops.org/modules/xfmod/tracker/index.php?func=detail&aid=4380&group_id=1300&atid=1353
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=256&forum=2


=================================================
Version: 0.40
Date:   2006-11-08
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

変更内容
RSSC モジュールの変更に伴い、下記の機能を追加した
(1) プロキシ・サーバーへの対応
(2) 設定テーブルのフォーム用の変更
(3) サニタイズの小変更


=================================================
Version: 0.32
Date:   2006-10-29
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

変更内容
(1) ペルシャ語を追加した
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=243&forum=5

(2) バグ修正 4339：非マルチバイト環境で Fatal error が発生する
http://linux2.ohwada.net/modules/newbb/viewtopic.php?topic_id=244&forum=5
http://dev.xoops.org/modules/xfmod/tracker/index.php?func=detail&aid=4339&group_id=1300&atid=1353


=================================================
Version: 0.31
Date:   2006-10-14
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

変更内容
(1) Google検索用の機能を追加した
(2) ２重にハイライトされるバグを修正した


=================================================
Version: 0.30
Date:   2006-10-01
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

変更内容
1. WebLinks で採用した
それに合わせて、下記の機能を追加した

(1) 地域選択 (Locate) の仕組みを実験的に導入した。
言語と国・地域を独立に選択する仕組みです。
ccTLDs の国コードを採用した。
http://www.iana.org/cctld/
日本(jp)、米国(us)、英国(uk) の３つを用意した。

(2) file 関連のクラスを追加した


=================================================
Version: 0.20
Date:   2006-09-10
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

変更内容
(1) 検索
(1-1) ハイライト表示クラス を追加した
      SmartSection を参考に流用した
(1-2) キーワードを中心に要約する関数を追加した
(1-3) ゆらぎ検索 を追加した（日本語のみ）
      Amethyst Blue にて配布している検索モジュールを参考にした

(2) セッションチケット・クラス (XoopsGTicket) を追加した
    Peak にて配布している Tinyd から流用した

(3) form生成クラス から html生成クラス と form ライブラリ・クラス を分離した
(4) RDF/RSS/ATOM 生成クラス を追加した
(5) サーバー環境変数のクラス を追加した
(6) 言語別のクラス を追加した
(7) マルチバイト関数 を１つのファイルにまとめた
(8) typo の修正


=================================================
Version: 0.10
Date:   2006-07-10
=================================================

このモジュールは、Happy Linux で配布しているモジュール用のライブラリ集です

● 概要
モジュールの形態をとっていますが、
このモジュール単体では 何のアプリケーション機能を提供しない
プログラム・ライブラリ集です。
モジュール・インストールをしても、しなくとも動作します。

これを利用しているモジュール
・RSSセンター

今後 利用する予定のモジュール
・WebLinks
・What's New

● TODO
将来的には、XOOPS Cube 2.1 で実装される予定の フレームワークを利用するつもりです。
しかし、現行は、 XOOPS 2.0、2.2、2.0 JP、Cube 2.1 など複数のプラットホームがあるため、
それらに共通で使用できるアドホックなプログラム・ライブラリ集としてまとめました。
