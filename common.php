<?php

@define('IN_UCHOME', TRUE);
define('D_BUG', '0');
//程序目录
define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);

//基本文件
include_once(S_ROOT.'./wechat-php-sdk/wechat.class.php');
include_once(S_ROOT.'./PHPMailer/PHPMailerAutoload.php');
if(!@include_once(S_ROOT.'./config.php')) {
	
	echo 'No Access';
	exit();
}
//时间
date_default_timezone_set('Asia/Shanghai');

$mtime = explode(' ', microtime());
$_SGLOBAL['timestamp'] = $mtime[1];
$_SGLOBAL['supe_starttime'] = $_SGLOBAL['timestamp'] + $mtime[0];

//连接数据库
/*
include_once(S_ROOT.'./class_mysql.php');
$_SGLOBAL = array();
if(empty($_SGLOBAL['db'])) {
	$_SGLOBAL['db'] = new dbstuff;
	$_SGLOBAL['db']->charset = $_SC['dbcharset'];
	$_SGLOBAL['db']->connect($_SC['dbhost'], $_SC['dbuser'], $_SC['dbpw'], $_SC['dbname'], $_SC['pconnect']);
}
include_once(S_ROOT.'./function_common.php');
*/
?>