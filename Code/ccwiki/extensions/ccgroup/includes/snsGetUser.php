<?php
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/lib/HttpClient.class.php');
function getRenrenUser($access_token){
	global $renren_secret,$ccProxy;
	$method="users.getInfo";
	$v="1.0";
	$secret=$renren_secret;
	$format="XML";
	$content="access_token=".$access_token."format=".$format."method=".$method."v=".$v.$secret;
	$sig=md5($content);
	$post_data="access_token=".$access_token."&format=".$format."&method=".$method."&v=".$v."&sig=".$sig;
	$url="http://api.renren.com/restserver.do";

	$response=false;
	$try=0;
	while($response==false&&$try<=10) //尝试十次
	{
		$ch = curl_init();//打开
		if($ccProxy!="")
			curl_setopt($ch, CURLOPT_PROXY, $ccProxy);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$try=$try+1;
	}
		//echo "XML:<br/>"; /////////////////test
		//var_dump($response);///////////////////test
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
	if($out['sns_id']=="")
		var_dump($response);
	return $out;
}

function getKaixinUser($access_token){
	global $ccProxy;
	$userurl="https://api.kaixin001.com/users/me.xml?access_token=".$access_token;
	$response=false;
	$try=0;
	while($response==false&&$try<=10) //尝试十次
	{
		$ch = curl_init($userurl);//打开
		if($ccProxy!="")
			curl_setopt($ch, CURLOPT_PROXY, $ccProxy);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response= curl_exec($ch);
		curl_close($ch);//关闭
		$try=$try+1;
	}
		//echo "XML:<br/>"; /////////////////test
		//var_dump($response);///////////////////test
	$input=new DOMDocument();
	$input->loadXML($response);
	$users=$input->getElementsByTagName("user");
	$out = array();
	if(sizeof($users)==1)
	{
		$out['sns_id']=$input->getElementsByTagName("uid")->item(0)->nodeValue;
		$out['name']=$input->getElementsByTagName("name")->item(0)->nodeValue;
		$out['avatar']=$input->getElementsByTagName("logo50")->item(0)->nodeValue;
		$out['source']='kaixin';
	}
	if($out['sns_id']=="")
		var_dump($response);
	return $out;
}

function getQqweiboUser($access_token,$openid){
	global $qqweibo_key,$ccHost,$ccProxy;
	$url="http://open.t.qq.com/api/user/info?";
	$url.="format=json&oauth_consumer_key=".$qqweibo_key."&access_token=".$access_token."&openid=".$openid."&clientip=".$ccHost."&oauth_version=2.a&scope=all";
		//echo $url."<br/>";//test
	$response=false;
	$try=0;
	while($response==false&&$try<=10) //尝试十次
	{
		//$response = HttpClient::quickGet($url);// send http get request
		$ch = curl_init($url);//打开
		if($ccProxy!="")
			curl_setopt($ch, CURLOPT_PROXY, $ccProxy);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response= curl_exec($ch);
		//var_dump($response);
		curl_close($ch);//关闭

		$try=$try+1;
	}
		//echo "json:"; /////////////////test
		//var_dump($response);///////////////////test
	$content=json_decode($response,true);
	$out = array();
	$out['sns_id']=$content['data']['openid'];
	$out['name']=$content['data']['nick'];
	$out['avatar'] = ($content['data']['head']=="")?"http://img.kaixin001.com.cn/i/50_0_0.gif":$content['data']['head']."/50.jpg";
	$out['source']='tencent';
		//var_dump($out);//////////////////////////////////test
		//echo "你好{$out['name']}, 你的UID是{$out['sns_id']}<br/>";  //test
		//echo "<img src=\"{$out['avatar']}\">";//test
	if($out['sns_id']=="")
		var_dump($response);
	return $out;
}

?>
