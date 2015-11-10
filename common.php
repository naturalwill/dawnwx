<?php

//程序目录
define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);

//基本文件
include_once(S_ROOT.'./wechat-php-sdk/wechat.class.php');
include_once(S_ROOT.'./PHPMailer/PHPMailerAutoload.php');
if(!@include_once(S_ROOT.'./config.php')) {
	//header("Location: install/index.php");//安装
	echo 'No Access';
	exit();
}

?>