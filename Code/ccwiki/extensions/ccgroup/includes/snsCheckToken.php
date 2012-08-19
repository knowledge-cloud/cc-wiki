<?php
/*
这个文件用来检测token是否过期
*/
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/lib/HttpClient.class.php');
//IsTokenExpired("sinaweibo","2.00J3GbOCxrZJHC4077a745c3axPlgB","");
function  IsTokenExpired($sns,$access_token,$openid)
{
	switch($sns)
	{
		case 'renren':
			return checkRenrenToken($access_token);
		case 'kaixin':
			return checkKaixinToken($access_token);
		case 'qqweibo':
			return checkQqweiboToken($access_token,$openid);
		case 'sinaweibo':
			return checkSinaweiboToken($access_token);
	}
}

function  checkRenrenToken($access_token){
	global $renren_secret;
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
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$try=$try+1;
	}
	$input=new DOMDocument();
	$input->loadXML($response);

//	var_dump($response);
   if($input->getElementsByTagName("uid")->item(0)==NULL)
	return '1';
   else
	return '0';
}

function  checkKaixinToken($access_token){
	$userurl="https://api.kaixin001.com/users/me.xml?access_token=".$access_token;
	$response=false;
	$try=0;
	while($response==false&&$try<=10) //尝试十次
	{
		$ch = curl_init($userurl);//打开
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response= curl_exec($ch);
		curl_close($ch);//关闭
		$try=$try+1;
	}
	$input=new DOMDocument();
	$input->loadXML($response);
	if($input->getElementsByTagName("uid")->item(0)==NULL)
	return '1';
    else
	return '0';
}

 function checkQqweiboToken($access_token,$openid){
	global $qqweibo_key,$ccHost;
	$url="http://open.t.qq.com/api/user/info?";
	$url.="format=json&oauth_consumer_key=".$qqweibo_key."&access_token=".$access_token."&openid=".$openid."&clientip=".$ccHost."&oauth_version=2.a&scope=all";
	$response=false;
	$try=0;
	while($response==false&&$try<=10) //尝试十次
	{

		//$response = HttpClient::quickGet($url);// send http get request
		$ch = curl_init($url);//打开
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response= curl_exec($ch);
		//var_dump($response);
		curl_close($ch);//关闭

		$try=$try+1;
	}
		
	$content=json_decode($response,true);
	$out = array();
	$out['sns_id']=$content['data']['openid'];

	if($out['sns_id']=="")
	 return '1';
	else
	 return '0';
}

function  checkSinaweiboToken($access_token)
{
	global $sinaweibo_key,$ccHost;
	$screen_name="CCwiki";
	
	$url="https://api.weibo.com/2/users/show.json?access_token=".$access_token."&screen_name=".$screen_name;
    //echo $url;
	$ch = curl_init($url);//打开
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$response= curl_exec($ch);
	//var_dump($response);
	curl_close($ch);//关闭
	if(empty($content['screen_name']))
	{
		//echo "failed";
		return '1';
	}
	else
         return '0';
}
?>
