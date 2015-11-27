<?php


$wechatoptions = array(
		//'logcallback'=>'runlog',
		//'debug'=>true,
		'token'=>'mytoken', //填写你设定的key
        'encodingaeskey'=>'encodingaeskey' //填写加密用的EncodingAESKey，如接口为明文模式可忽略
	);
//测试用户
$testusers = array('wechatid');//填写你的微信号id

//define('UC_PPP', 20);
define('WG_BXGS','报修格式：“报修 学号 姓名 宿舍号 房间号 端口号 联系电话 故障情况描述”。如：“报修 13207060001 张三 东莞2栋 219 A 13650328888/678888 今天上午10点时不能上网，错误691”。（PS：各个字段用空格分隔；联系电话必须要有长号，如有短号可以在长号后加“/”用以区分；留1、留2的宿舍号请填写“东莞留1”、“东莞留2”）');

//Ucenter Home配置参数
$_SC = array();
$_SC['dbhost']  		= '127.1.1.1'; //服务器地址
$_SC['dbuser']  		= 'root'; //用户
$_SC['dbpw'] 	 		= 'root'; //密码
$_SC['dbcharset'] 		= 'utf8'; //字符集
$_SC['pconnect'] 		= 0; //是否持续连接
$_SC['dbname']  		= 'mydatabase'; //数据库
$_SC['tablepre'] 		= ''; //表名前缀
$_SC['charset'] 		= 'utf-8'; //页面字符集


//email
$_SC['username'] = "gdmunm@163.com"；
$_SC['password'] = "password";
$_SC['fromaddr'] = 'gdmunm@163.com';
$_SC['fromname'] = 'GDMU学生网管';
$_SC['replyaddr'] = 'replyaddr@qq.com';
$_SC['replyname'] = '起源';

$_SC['sendaddr'] = 'sendaddr@qq.com';
$_SC['sendname'] = 'Jier';

?>