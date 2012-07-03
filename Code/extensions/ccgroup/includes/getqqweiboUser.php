<?php
//error_reporting('0');
//设置include_path 到 OpenSDK目录
set_include_path((dirname(__FILE__)) . '/lib/');
require_once 'OpenSDK/Tencent/Weibo.php';
include '../conf.php';
OpenSDK_Tencent_Weibo::init($qqweibo_key, $qqweibo_secret);
//打开sessionaccess_token=05d6de23f7d34668bc22f4d3c7a04c62&access_token_secret=67da7ce02b7934db967ae8085a6cd48d    
session_start();
header('Content-Type: text/html; charset=utf-8');
	$_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN]=$_REQUEST['oauth_token'];
	$_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET]=$_REQUEST['oauth_token_secret'];
//$_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN]="ccded29b875f4f97b2529e5173fc4f66";
//$_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET]="da2d055e4fe13a0d6f5e4316e248b14e";
	$api_name = 'user/info';
	$params=array(
					'format'=>'json',
					);
	$response = OpenSDK_Tencent_Weibo::call($api_name,$params,"GET",false,true,false);
	//echo $response;
	$content=json_decode($response,true);
	
   $doc=new DOMDocument('1.0', 'UTF-8');
    $doc->formatOutput=true;
    $root=$doc->createElement("user");
    $doc->appendChild($root);
    	$id=$doc->createElement("id");
    	$name=$doc->createElement("name");
    	$url=$doc->createElement("url");
    	$cate=$doc->createElement("cate");
    	$id->appendChild($doc->createTextNode($content['data']['name']));
    	$name->appendChild($doc->createTextNode($content['data']['nick']));
    	if($content['data']['head']==""){
    		$url->appendChild($doc->createTextNode("http://img.kaixin001.com.cn/i/50_0_0.gif"));
    	}
    	else{
    	$url->appendChild($doc->createTextNode($content['data']['head']."/50.jpg"));
    	}
    	$cate->appendChild($doc->createTextNode("tencent"));
    	$root->appendChild($id);
    	$root->appendChild($name);
    	$root->appendChild($url);
    	$root->appendChild($cate);
    echo $doc->saveXML();
    
?>
