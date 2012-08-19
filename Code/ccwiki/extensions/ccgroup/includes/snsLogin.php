<?php
session_start();
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/snsMapping2.php');

$mw=$_COOKIE['ccwikiUserName'];
/*
*开心网AccessToken有效期为1个月，RefreshToken有效期为2个月，每刷新一次会重新下发AccessToken和RefreshToken
*腾讯微博AccessToken有效期为7天,Refresh_token的有效期为3个月，通过刷新机制可以更新accesstoken，3个月后，用户必须重新授权方可获取有效的accesstoken
*人人网AccessToken有效期为1个月,refreshToken永不过期
*/

 $access_token=$_REQUEST['access_token'];//有效期为1个月的Access Token和有效期为2个月的Refresh Token
 $expires_in=$_REQUEST['expires_in'];
 $refresh_token=$_REQUEST['refresh_token'];

 $openid='';

if($_REQUEST['sns']=="qqweibo")
{
		$openid=$_REQUEST['openid'];
}

$type=$_SESSION['type'];
$head_str="Location:http://".$ccHost.":".$ccPort."/".$ccSite."/index.php/Special:".$type."?page=snsLogin&sns=".$_REQUEST['sns']."&access_token=".$access_token;
//echo $type;
switch($type)
{
	case 'qqweiboUpdate':
		$head_str="Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/ccgroup/includes/qqweiboUpdate.php?access_token=".$access_token;
	    break;
	case 'sinaweiboUpdate':
		$head_str="Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/ccgroup/includes/sinaweiboUpdate.php?access_token=".$access_token;
	    break;
}

if($_REQUEST['sns']=="qqweibo")
{
	$head_str = $head_str."&openid=".$openid;
}

//echo "accesstoken:".$access_token."<br/>";
//echo "openid:".$openid."<br/>";
//echo "expires_in:".$expires_in."<br/>";
//echo  "refresh_token:".$refresh_token."<br/>";
 //global $qqweibo_key;
 //echo $qqweibo_key;
  if($access_token!="")
  {
	 $sns=$_REQUEST['sns'];
	 updateToken($mw,$sns,$access_token,$openid); 
	 header($head_str); //根据sesion中的值跳到对应的页面
  }
  else
	 echo "get access_token failed!";
?>
