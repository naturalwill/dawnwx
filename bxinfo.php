<?php
include_once "common.php";
ob_clean();
header("Content-type: application/json; charset=utf-8"); 
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('wxbx')." WHERE status='0'");
$arr = array();
while($read = $_SGLOBAL['db']->fetch_array($query)){
	$arr[]=$read;
}
/*
if($text){
	echo 'send email ';
	echo sendemail($text,date("Y-m-d").'网管未读微信记录');
	echo '<br />';
	updatetable('wxbx', array('status'=>1), array('status'=>0));
	echo 'updatetable succeed';
}*/
if(!empty($_GET['info'])){
	echo json_encode($arr);
}
if(!empty($_GET['update'])){
	updatetable('wxbx', array('status'=>1), array('status'=>0));
}

exit();
