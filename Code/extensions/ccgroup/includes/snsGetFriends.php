<?php
include_once (dirname(__FILE__).'/lib/OpenSDK/Tencent/Weibo.php');
include_once (dirname(__FILE__).'/../conf.php');
function getRenrenFriends($access_token){
	global $renren_secret;
	$method="friends.getFriends";
	$v="1.0";
	$format="XML";
	$content="access_token=".$access_token."format=".$format."method=".$method."v=".$v.$renren_secret;
	$sig=md5($content);
	$post_data="access_token=".$access_token."&format=".$format."&method=".$method."&v=".$v."&sig=".$sig;
	$url="http://api.renren.com/restserver.do";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
	$response  = curl_exec($ch);
	curl_close($ch);
	$output = array();
	$input = new DOMDocument();
	$input->loadXML($response);
	$friends=$input->getElementsByTagName("friend");
	foreach ($friends as $friend){
		$item = array();
		$id=$friend->getElementsByTagName("id")->item(0)->nodeValue;
		$name=$friend->getElementsByTagName("name")->item(0)->nodeValue;
		$photo=$friend->getElementsByTagName("tinyurl")->item(0)->nodeValue;
		$item['sns_id']=$id;
		$item['name']=$name;
		$item['avatar']=$photo;
		$item['source']="renren";
		$output[]=$item;
	}
	return $output;
}

function getKaixinFriends($access_token){
	$fields="";
	$url="https://api.kaixin001.com/friends/me.json?access_token=".$access_token."&fields=".$fields;
	$ch = curl_init($url);//打开
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	$response  = curl_exec($ch);
	curl_close($ch);//关闭
	$contents=json_decode($response,true);
	$output = array();
	foreach($contents['users'] as $content){
		$item = array();
		$item['sns_id'] = $content['uid'];
		$item['name'] = $content['name'];
		$item['avatar'] = $content['logo50'];
		$item['source']="kaixin";
		$output[]=$item;
	}
	return $output;
}

function getQqweiboFriends($oauth_token,$oauth_token_secret){
	OpenSDK_Tencent_Weibo::init($qqweibo_key, $qqweibo_secret);
	session_start();
	header('Content-Type: text/html; charset=utf-8');
        $_SESSION[OpenSDK_Tencent_Weibo::ACCESS_TOKEN]=$oauth_token;
        $_SESSION[OpenSDK_Tencent_Weibo::OAUTH_TOKEN_SECRET]=$oauth_token_secret;
	$api_name = 'friends/fanslist';
        $params=array(
                	'format'=>'xml',
                        'reqnum'=>'30',
                        'starindex'=>'0'
               );
        $response = OpenSDK_Tencent_Weibo::call($api_name,$params,"GET",false,true,false);
        $contents=json_decode($response,true);
	$output = array();
	foreach($contents['data']['info'] as $content){
		$item = array();
		$item['sns_id'] = $content['name'];
		$item['name'] = $content['nick'];
		$item['avatar'] = ($content['head']=="")?"http://img.kaixin001.com.cn/i/50_0_0.gif":$content['head']."/50.jpg";
		$item['source'] = "qqweibo";
		$output[]=$item;
	}	
	return $output;
}

?>
