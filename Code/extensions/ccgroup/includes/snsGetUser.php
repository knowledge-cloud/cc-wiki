<?php
include_once (dirname(__FILE__).'/lib/OpenSDK/Tencent/Weibo.php');
include_once (dirname(__FILE__).'/../conf.php');
function getRenrenUser($access_token){
	global $renren_secret;
	$method="users.getInfo";
	$v="1.0";
	$secret=$renren_secret;
	$format="XML";
	$content="access_token=".$access_token."format=".$format."method=".$method."v=".$v.$secret;
	$sig=md5($content);
	$post_data="access_token=".$access_token."&format=".$format."&method=".$method."&v=".$v."&sig=".$sig;
	$url="http://api.renren.com/restserver.do";
	$ch = curl_init();//打开
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
	$response  = curl_exec($ch);
	curl_close($ch);//关闭
	$input=new DOMDocument();
	$input->loadXML($response);
	$users=$input->getElementsByTagName("user");
	$out = array();
	if(sizeof($users)==1){
		$out['sns_id']=$input->getElementsByTagName("uid")->item(0)->nodeValue;
		$out['name']=$input->getElementsByTagName("name")->item(0)->nodeValue;
		$out['avatar']=$input->getElementsByTagName("tinyurl")->item(0)->nodeValue;
		$out['source']='renren';
	}
	return $out;
}

function getKaixinUser($access_token){
	$userurl="https://api.kaixin001.com/users/me.xml?access_token=".$access_token;
	$ch = curl_init($userurl);//打开
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$userResponse  = curl_exec($ch);
	curl_close($ch);//关闭
	$input=new DOMDocument();
	$input->loadXML($userResponse);
	$users=$input->getElementsByTagName("user");
	$out = array();
	if(sizeof($users)==1)
	{
		$out['sns_id']=$input->getElementsByTagName("uid")->item(0)->nodeValue;
		$out['name']=$input->getElementsByTagName("name")->item(0)->nodeValue;
		$out['avatar']=$input->getElementsByTagName("logo50")->item(0)->nodeValue;
		$out['source']='kaixin';
	}
	return $out;
}

function getQqweiboUser($oauth_token,$oauth_token_secret){
	OpenSDK_Tencent_Weibo::init($qqweibo_key, $qqweibo_secret);
	session_start();
	header('Content-Type: text/html; charset=utf-8');
	$_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN]=$oauth_token;
	$_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET]=$oauth_token_secret;
	$api_name = 'user/info';
	$params=array(
			'format'=>'json',
		);
	$response = OpenSDK_Tencent_Weibo::call($api_name,$params,"GET",false,true,false);
	//echo $response;
	$content=json_decode($response,true);
	$out = array();
	$out['sns_id']=$content['data']['name'];
	$out['name']=$content['data']['nick'];
	$out['avatar'] = ($content['data']['head']=="")?"http://img.kaixin001.com.cn/i/50_0_0.gif":$content['head']['head']."/50.jpg";
	$out['source']='qqweibo';
}

?>
