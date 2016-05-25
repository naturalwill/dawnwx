<?php
include_once("common.php");

$weObj = new Wechat($wechatoptions);
$weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
$type = $weObj->getRev()->getRevType();
switch($type) {
	case Wechat::MSGTYPE_TEXT:
			break;
	case Wechat::MSGTYPE_EVENT:
			$revenent=$weObj->getRevEvent();
			if($revenent['event']==Wechat::EVENT_SUBSCRIBE)
				$weObj->text(WG_GZ)->reply();
			break;
	case Wechat::MSGTYPE_IMAGE:
			$weObj->text($info.WG_BXGS)->reply();
			break;
	default:
			$weObj->text("help info")->reply();
			break;
			
}

