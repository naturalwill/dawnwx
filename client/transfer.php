<?php
//程序目录
define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
include_once(S_ROOT.'./config.php');

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
	/* if(!empty($cookiefile)){
		if(file_exists($cookiefile)){
			$opt[CURLOPT_COOKIEFILE] = $cookiefile;
		}
		$opt[CURLOPT_COOKIEJAR] = $cookiefile;
	} */
    curl_setopt_array($ch, $opt);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


header("Content-type: text/plain; charset=utf-8"); 

$jsondata =json_decode(curl($infourl));

foreach($jsondata as $wxdata){
	
	$data=curl($reporturl);
	preg_match_all('/<input.+name="([^".]+)".*value="([^".]+)".*\/>/i', $data, $matches);
	$list=array();
	$count=count($matches[0]);
	for($i=0;$i<$count;$i++){
		$list[$matches[1][$i]]=$matches[2][$i];
	}
	$list['xuehao']=$wxdata->stunum;
	$list['IPaddr']=$wxdata->stunum;
	$list['tbname']=$wxdata->stuname;
	$list['tbtel']=$wxdata->phone;
	$list['ddlshushe']=$wxdata->sushe;
	$list['fanhao']=$wxdata->fangjian.$wxdata->port;
	$list['MACaddr']='00-00-00-00-00-00';
	$list['ddltype']='PPPOE宽带拨号失败请填写错误号';
	$list['tbsuitation']=$wxdata->info;
	$list['tbdescription']=$wxdata->CreateTime.' 通过微信报修';
	$list['__EVENTTARGET']='';
	$list['__EVENTARGUMENT']='';
	$list['__LASTFOCUS']='';
	unset($list['Button2']);
	$data=curl($reporturl,$list);
	
	preg_match_all('/<script([^>.]*)>.*alert\(\'([^\'.]+)\'\).*<\/script>/i', $data, $matches);
	echo $wxdata->stuname.' ' .htmlspecialchars($matches[2][0])."\n";
}
echo 'OK!';
?>
