<?php
session_start();
include_once (dirname(__FILE__).'/../conf.php');
    global $qqweibo_key,$ccHost,$ccProxy;

	$access_token=$_REQUEST['access_token'];
	$openid=$_REQUEST['openid'];
	$content=$_SESSION['qqContent'];
	

	//////////////////发送post请求
	$url="http://open.t.qq.com/api/t/add?";
	$data = array( 'oauth_consumer_key' => $qqweibo_key,'access_token' =>$access_token,
	'clientip'=>$ccHost,'openid' => $openid, 'oauth_version'=>'2.a','scope'=>'all',
	'format'=>'json','content'=>$content, 'jing'=>'110.5', 'wei'=>'23.4',
	 'syncflag'=>'1');//syncflag是微博同步到控件标记，0同步 1不同步 默认为0

	$ch = curl_init();
	if($ccProxy!="")
		curl_setopt($ch, CURLOPT_PROXY, $ccProxy);
	curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
	$response=curl_exec($ch);

	$contents=json_decode($response,true);
	if($contents['ret']==0){
		echo '<html><body><p>祝贺您！您在腾讯微博客上发布了如下微博：'.$_SESSION['qqContent'].'</p><br/></body></html>';
	}else{
		echo '<html><body><p>对不起！您的微博客发送失败，请重试。</p></body></html>';	
	}
?>
