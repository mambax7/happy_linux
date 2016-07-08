<?php
// $Id: config.php,v 1.4 2007/11/26 05:34:29 ohwada Exp $

// 2007-11-24
// table manage

//=========================================================
// Happy Linux Framework Module
// 2007-10-10 K.OHWADA
// Japanese UTF-8
//=========================================================

// bin command
define('_HAPPY_LINUX_CONF_COMMAND_MANAGE', 'コマンド管理');
define('_HAPPY_LINUX_CONF_CREATE_CONFIG', '設定ファイルの生成');
define('_HAPPY_LINUX_CONF_TEST_BIN', 'bin コマンドのテスト実行');
define('_HAPPY_LINUX_CONF_BIN', 'コマンド設定');
define('_HAPPY_LINUX_CONF_BIN_DESC', 'bin コマンドで使用します');
define('_HAPPY_LINUX_CONF_BIN_PASS', 'パスワード');
define('_HAPPY_LINUX_CONF_BIN_MAILTO', '送信先のメールアドレス');
define('_HAPPY_LINUX_CONF_BIN_SEND', 'メールの送信');
define('_HAPPY_LINUX_CONF_BIN_SEND_NON', '送信しない');
define('_HAPPY_LINUX_CONF_BIN_SEND_EXECUTE', '実行されたときに送信する');
define('_HAPPY_LINUX_CONF_BIN_SEND_ALWAYS', '常に送信する');

// rss
define('_HAPPY_LINUX_CONF_RSS_MANAGE', 'RDF/RSS/ATOM 管理');
define('_HAPPY_LINUX_CONF_RSS_MANAGE_DESC', 'RDF/RSS/ATOM を生成し表示する');
define('_HAPPY_LINUX_CONF_SHOW_RDF', 'RDF の表示');
define('_HAPPY_LINUX_CONF_SHOW_RSS', 'RSS の表示');
define('_HAPPY_LINUX_CONF_SHOW_ATOM', 'ATOM の表示');
define('_HAPPY_LINUX_CONF_DEBUG_RDF', 'RDF のデバック表示');
define('_HAPPY_LINUX_CONF_DEBUG_RSS', 'RSS のデバック表示');
define('_HAPPY_LINUX_CONF_DEBUG_ATOM', 'ATOM のデバック表示');

// template
define('_HAPPY_LINUX_CONF_TPL_COMPILED_CLEAR', 'テンプレート のコンパイル済みキャッシュのクリア');
define('_HAPPY_LINUX_CONF_TPL_COMPILED_CLEAR_DIR', '%s ディレクトリにあるテンプレートを変更したときには、実行すること');
define('_HAPPY_LINUX_CONF_RSS_CACHE_CLEAR', 'RSS のキャッシュのクリア');
define('_HAPPY_LINUX_CONF_RSS_CACHE_CLEAR_DESC', 'ゲスト・モードでは１時間キャッシュしています');
define('_HAPPY_LINUX_CONF_RSS_CACHE_CLEAR_TIME', 'ゲスト・モードでは <b>%s</b> 時間キャッシュしています');
define('_HAPPY_LINUX_CONF_RSS_CACHE_TIME', 'キャッシュ時間 (秒)');

define('_HAPPY_LINUX_CONF_NOT_WRITABLE', 'このディレクトリは書込み許可がない');

// 2007-11-24
// table manage
define('_HAPPY_LINUX_CONF_TABLE_MANAGE', 'DBテーブル管理');
define('_HAPPY_LINUX_CONF_TABLE_CHECK', '%s テーブルの検査');
define('_HAPPY_LINUX_CONF_TABLE_REINSTALL', 'エラーが検出されたときは、再インストールを推奨する');
define('_HAPPY_LINUX_CONF_TABLE_CONFIG_RENEW', 'Config テーブルの初期化');
define('_HAPPY_LINUX_CONF_TABLE_CONFIG_RENEW_DESC', 'エラーが検出されたときに、実行する。<br />現在の登録内容は廃棄されます。<br />実行後に再設定してください。');
