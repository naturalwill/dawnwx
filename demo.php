<?php
include_once "common.php";

$weObj = new Wechat($wechatoptions);
$weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
$type = $weObj->getRev()->getRevType();
switch($type) {
	case Wechat::MSGTYPE_TEXT:
			$revdata=$weObj->getRevData();
			//测试用户专享
			if(in_array($revdata['FromUserName'], $testusers) ){
				if("report"==$revdata['Content']){
					//$text = curl('http://119.29.78.76/gdmuwx/report.php');
					$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('wxtextmsg')." WHERE status='0'");
					$text = '';
					while($read = $_SGLOBAL['db']->fetch_array($query)){
						foreach ($read as $key => $value) {
							$text .= $key.' : '.$value."\n";
						}
						$text .= "\n";
					}
					if(empty($text)){
						$text='no new message';
					}else{
						$text=substr($text,0,strlen($text)-2*strlen("\n"));
						updatetable('wxtextmsg', array('status'=>1), array('status'=>0));
					}
					$weObj->text($text)->reply();
					exit;
				}
			}
			
			unset($revdata['MsgType']);
			$revdata['CreateTime']=date("Y-m-d H:i:s",$revdata['CreateTime']);
			inserttable('wxtextmsg',$revdata);
			if(preg_match("/.*报修.*/", $revdata['Content'])){
				$time24=date("Y-m-d H:i:s",time()-(1 * 24 * 60 * 60));
				//$weObj->text(serialize(array('status'=>'服务器正在维护中...','revdata'=> $revdata,'test'=>"SELECT * FROM  WHERE FromUserName='{$revdata['FromUserName']}' AND CreateTime > {$time24} ORDER BY CreateTime desc limit 1")))->reply();
				//exit;
				$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('wxbx')." WHERE FromUserName='{$revdata['FromUserName']}' AND CreateTime > '{$time24}' ORDER BY CreateTime desc limit 1");
				if($nearbx = $_SGLOBAL['db']->fetch_array($query)){
					$msg='你好！'.$nearbx['stuname']."同学，你在 {$nearbx['CreateTime']} 已经报修了！24小时之内，您已经报修一次了，请耐心等待，谢谢！";
					$weObj->text($msg)->reply();
					exit();
				} 
				$info = getbxinfo($revdata['Content']);
				if(is_array($info)){
					$info['CreateTime']=$revdata['CreateTime'];
					$info['FromUserName']=$revdata['FromUserName'];
					$info['ToUserName']=$revdata['ToUserName'];
					$info['MsgId']=$revdata['MsgId'];
					$id=inserttable('wxbx',$info,1);
					//sendemail($revdata['Content'],date("Y-m-d").'网络报修');
					$weObj->text('报修成功！你是第'.$id.'个使用微信报修的用户。')->reply();
				}
				else{
					$weObj->text($info.WG_BXGS)->reply();
				}
				exit;
			}elseif(preg_match("/test.*/", $revdata['Content'])){
				$weObj->text(serialize(array('status'=>'testing...','revdata'=> $revdata,'test'=>true)))->reply();
				exit;
			}
			
			//$weObj->text('测试中: '.json_encode($revdata))->reply();
			exit;
			break;
	case Wechat::MSGTYPE_EVENT:
			if($weObj->getRevEvent()['event']==Wechat::EVENT_SUBSCRIBE)
				$weObj->text("欢迎关注GDMU学生网管。如果你的网络出现问题，请回复“报修”。")->reply();
			break;
	case Wechat::MSGTYPE_IMAGE:
			break;
	default:
			$weObj->text("help info")->reply();
			break;
			
}

