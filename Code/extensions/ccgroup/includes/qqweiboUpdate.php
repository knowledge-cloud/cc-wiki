<?php
session_start();
//error_reporting('0');
//设置include_path 到 OpenSDK目录
set_include_path((dirname(__FILE__)) . '/lib/');
require_once 'OpenSDK/Tencent/Weibo.php';
include '../conf.php';
OpenSDK_Tencent_Weibo::init($qqweibopost_key, $qqweibopost_secret);
//打开session
session_start();
header('Content-Type: text/html; charset=utf-8');
	$_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN]=$_SESSION['qqweibo_access_token'];
	$_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET]=$_SESSION['qqweibo_access_token_secret'];
//	$_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN]="87c60c5d6df44a15a278156747ec1b70";
//  $_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET]="a25f6a2430520f7e4823aa74da549490";
$content=$_REQUEST['content'];
//$ip=$_SERVER['REMOTE_ADDR'];
echo "qqweibo=".$_SESSION['qqweibo_access_token'];
echo "content=".$content;
$content=urldecode($content);
	$api_name = 't/add';
	$params=array(
					'format'=>'xml',
					'content'=>$content,
			        'clientip'=>"192.168.0.169",
			        'jing'=>'110.5',
			        'wei'=>'23.4'
					);
	$response = OpenSDK_Tencent_Weibo::call($api_name,$params,"post",false,true,false);
    print_r($response);
?>
