<?php
include_once (dirname(__FILE__).'/../conf.php');
session_start();
$status=$_SESSION['sinaContent'];
$access_token=$_REQUEST['access_token'];
$url="https://api.weibo.com/2/statuses/update.json";
//echo 'status: '.$status;
//$status="您好~ccwiki";//test
//$access_token="2.00KdCLJDxrZJHCa176b314e0f2AFHE";//test
$status=urldecode($status);
$post_data="access_token=".$access_token."&status=".$status;
$ch = curl_init();//打开
if($ccProxy!="")
	curl_setopt($ch, CURLOPT_PROXY, $ccProxy);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
$response  = curl_exec($ch);
curl_close($ch);//关闭
//echo $response;
 $contents=json_decode($response,true);
if(!empty($contents['text'])){
		echo '祝贺您！您在新浪微博上发布了如下微博：<br/>'.$_SESSION['sinaContent'];
	}else{
		echo '对不起！您的微博客发送失败，请重试。';	
	}
?>
