<?php

	include_once (dirname(__FILE__).'/../conf.php');
    global $qqweibo_key,$ccHost,$ccProxy;

	$access_token="2ad35211d244666aa091575e0e8c59fd";
	$openid="9C38229B891E22FB8BDB8F7FC041C83F";
	$name="ccwiki_zju";
	//$fopenids="A9FDEAA3FDEEDC3AEDB2DCFA9C3BF635";  //发私信用户的id，如xxx
	$content="testtest";
	$contentflag="1";

	//////////////////发送post请求,,腾讯微博返回的消息会自动打印出来
	$url="http://open.t.qq.com/api/private/add?";
	$data = array( 'oauth_consumer_key' => $qqweibo_key,'access_token' =>$access_token,'clientip'=>$ccHost,'openid' => $openid, 'oauth_version'=>'2.a','scope'=>'all','format'=>'json','content'=>$content,'name'=>$name,'pic'=>"",'contentflag'=>$contentflag);
	//var_dump($data);
	$ch = curl_init();
	if($ccProxy!="")
		curl_setopt($ch, CURLOPT_PROXY, $ccProxy);
	curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
	$response=curl_exec($ch);
	$contents=json_decode($response,true);
	if($contents['ret']!=0)
		echo $contents['msg'];	

?>
