<?php
include "common.php";

$weObj = new Wechat($wechatoptions);
$weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
$type = $weObj->getRev()->getRevType();
switch($type) {
	case Wechat::MSGTYPE_TEXT:
			$weObj->text("hello, I'm wechat")->reply();
			exit;
			break;
	case Wechat::MSGTYPE_EVENT:
			if($weObj->getRevEvent()['event']==EVENT_SUBSCRIBE)
				$weObj->text("欢迎关注GDMU学生网管")->reply();
			break;
	case Wechat::MSGTYPE_IMAGE:
			break;
	default:
			$weObj->text("help info")->reply();
			break;
			
}