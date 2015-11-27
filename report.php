<?php
include_once "common.php";
ob_clean();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('wxtextmsg')." WHERE status='0'");
$text = '';
while($read = $_SGLOBAL['db']->fetch_array($query)){
	foreach ($read as $key => $value) {
		$text .= $key.' : '.$value.'<br />';
	}
	$text .= '<br />';
}
if($text){
	echo 'send email ';
	echo sendemail($text,date("Y-m-d").'网管未读微信记录');
	echo '<br />';
	updatetable('wxtextmsg', array('status'=>1), array('status'=>0));
	echo 'updatetable succeed';
}
echo 'no new record';
exit();