<?php
session_start();
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/snsMapping2.php');

$mw=$_COOKIE['ccwikiUserName'];
/*
*������AccessToken��Ч��Ϊ1���£�RefreshToken��Ч��Ϊ2���£�ÿˢ��һ�λ������·�AccessToken��RefreshToken
*��Ѷ΢��AccessToken��Ч��Ϊ7��,Refresh_token����Ч��Ϊ3���£�ͨ��ˢ�»��ƿ��Ը���accesstoken��3���º��û�����������Ȩ���ɻ�ȡ��Ч��accesstoken
*������AccessToken��Ч��Ϊ1����,refreshToken��������
*/

 $access_token=$_REQUEST['access_token'];//��Ч��Ϊ1���µ�Access Token����Ч��Ϊ2���µ�Refresh Token
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
	 header($head_str); //����sesion�е�ֵ������Ӧ��ҳ��
  }
  else
	 echo "get access_token failed!";
?>
