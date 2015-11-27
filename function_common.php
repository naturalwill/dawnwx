<?php

//获取到表名
function tname($name) {
	global $_SC;
	return $_SC['tablepre'].$name;
}

//添加数据
function inserttable($tablename, $insertsqlarr, $returnid=0, $replace = false, $silent=0) {
	global $_SGLOBAL;

	$insertkeysql = $insertvaluesql = $comma = '';
	foreach ($insertsqlarr as $insert_key => $insert_value) {
		$insertkeysql .= $comma.'`'.$insert_key.'`';
		$insertvaluesql .= $comma.'\''.$insert_value.'\'';
		$comma = ', ';
	}
	$method = $replace?'REPLACE':'INSERT';
	$_SGLOBAL['db']->query($method.' INTO '.tname($tablename).' ('.$insertkeysql.') VALUES ('.$insertvaluesql.')', $silent?'SILENT':'');
	if($returnid && !$replace) {
		return $_SGLOBAL['db']->insert_id();
	}
}

//更新数据
function updatetable($tablename, $setsqlarr, $wheresqlarr, $silent=0) {
	global $_SGLOBAL;

	$setsql = $comma = '';
	foreach ($setsqlarr as $set_key => $set_value) {//fix
		$setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value.'\'';
		$comma = ', ';
	}
	$where = $comma = '';
	if(empty($wheresqlarr)) {
		$where = '1';
	} elseif(is_array($wheresqlarr)) {
		foreach ($wheresqlarr as $key => $value) {
			$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	$_SGLOBAL['db']->query('UPDATE '.tname($tablename).' SET '.$setsql.' WHERE '.$where, $silent?'SILENT':'');
}

//写运行日志
function runlog($log, $file='wechat', $halt=0) {
	global $_SGLOBAL, $_SERVER;

	$nowurl = $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);
	$log = gmdate('Y-m-d H:i:s', $_SGLOBAL['timestamp'])."\t$type\t"."\t{$nowurl}\t".str_replace(array("\r", "\n"), array(' ', ' '), trim($log))."\n";
	$yearmonth = gmdate('Ym', $_SGLOBAL['timestamp']);
	$logdir = './data/log/';
	if(!is_dir($logdir)) mkdir($logdir, 0777);
	$logfile = $logdir.$yearmonth.'_'.$file.'.php';
	if(@filesize($logfile) > 2048000) {
		$dir = opendir($logdir);
		$length = strlen($file);
		$maxid = $id = 0;
		while($entry = readdir($dir)) {
			if(strexists($entry, $yearmonth.'_'.$file)) {
				$id = intval(substr($entry, $length + 8, -4));
				$id > $maxid && $maxid = $id;
			}
		}
		closedir($dir);
		$logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.php';
		@rename($logfile, $logfilebak);
	}
	if($fp = @fopen($logfile, 'a')) {
		@flock($fp, 2);
		fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>', "\r", "\n"), '', $log)."\n");
		fclose($fp);
	}
	if($halt) exit();
}


function getbxinfo($revdata){
	$revdataarr = explode(' ', $revdata);
	if(count($revdataarr)<=1) return '';
	
	for($i=count($revdataarr)-1;$i>=0;$i--){
		if(trim($revdataarr[$i])==''){
			unset($revdataarr[$i]);
		}
	}
	$revdataarr=array_values($revdataarr);
	var_dump($revdataarr);
	$bx=array();
	if(count($revdataarr)>=8){
		$bx['stunum']=strlen($revdataarr[1])==11?(ctype_digit($revdataarr[1])?$revdataarr[1]:''):'';
		if(empty($bx['stunum'])) return '学号输入有误。';
		
		$bx['stuname']=$revdataarr[2];
		
		$bx['sushe']=mb_substr($revdataarr[3],0,2,'UTF-8')=='东莞'?$revdataarr[3]:'';			
		if(empty($bx['sushe'])) return '宿舍输入有误。';
		
		$bx['fangjian']=ctype_digit($revdataarr[4])?(strlen($revdataarr[4])==3?$revdataarr[4]:''):'';
		if(empty($bx['fangjian'])) return '房间号输入有误。';
		
		$bx['port']=substr(strtoupper($revdataarr[5]),0,1);
		$bx['port']=in_array($bx['port'],array('A','B','C','D'))?$bx['port']:'';
		if(empty($bx['port'])) return '端口号输入有误。';
		
		
		$phonelen=strlen($bx['phone']=$revdataarr[6]);
		if($phonelen>11&&$phonele<=18){
			$phonearr = explode('/', $bx['phone']);
			$bx['phone']=strlen($phonearr[0])==11?(ctype_digit($phonearr[0])?$phonearr[0]:''):'';
			$bx['phone2']=strlen($phonearr[1])<=6?(ctype_digit($phonearr[1])?$phonearr[1]:''):'';
			if(empty($bx['phone2'])) return '联系电话输入有误。';
		}
		elseif($phonelen==11){
			$bx['phone']=ctype_digit($bx['phone'])?$bx['phone']:'';
		}
		if(empty($bx['phone'])) return '联系电话输入有误。';
		
		$bx['info']=$revdataarr[7];
		
		return $bx;
	}
	return '报修信息不完整或输入信息没有足够的空格分割。';
}

function sendemail($mailbody,$subject){
	
	global $_SC;
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';
	//Set the hostname of the mail server
		$mail->Host = "smtp.163.com";
	//Set the SMTP port number - likely to be 25, 465 or 587
	$mail->Port = 25;
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
		//Username to use for SMTP authentication
		$mail->Username = $_SC['username'];
		//Password to use for SMTP authentication
		$mail->Password = $_SC['password'];
		//Set who the message is to be sent from
		$mail->setFrom($_SC['fromaddr'], $_SC['fromname']);
		//Set an alternative reply-to address
		$mail->addReplyTo($_SC['replyaddr'], $_SC['replyname']);
		//Set who the message is to be sent to
		$mail->addAddress($_SC['sendaddr'], $_SC['sendname']);
	//Set the subject line
	$mail->Subject = $subject;
	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
	//Replace the plain text body with one created manually
	$mail->Body    = $mailbody;
	$mail->AltBody =$mailbody;
	//Attach an image file
	//$mail->addAttachment('images/phpmailer_mini.png');

	//send the message, check for errors
	if (!$mail->send()) {
		//echo "Mailer Error: " . $mail->ErrorInfo;
		//runlog("Mailer Error: " . $mail->ErrorInfo,'mail');
			return false;
	} else {
		//echo "Message sent!";
			return true;
	}
	
}


function curl($url, $data=array(), $timeout = 30)
{
    $ssl = substr($url, 0, 5) == "https" ? TRUE : FALSE;
    $ch = curl_init();
    $opt = array(
            CURLOPT_URL     => $url,
            CURLOPT_HEADER  => 0,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_TIMEOUT         => $timeout,
            );
	if(!empty($data)){
		$opt[CURLOPT_POST ]       = 1;
		$opt[CURLOPT_POSTFIELDS ] = $data;
	}
    if ($ssl)
    {
        //$opt[CURLOPT_SSL_VERIFYHOST] = 1;
        $opt[CURLOPT_SSL_VERIFYPEER] = FALSE;
    }
    curl_setopt_array($ch, $opt);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
?>