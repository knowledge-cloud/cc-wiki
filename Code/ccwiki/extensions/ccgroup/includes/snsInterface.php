<?php
session_start();
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/snsMapping2.php');
include_once (dirname(__FILE__).'/snsCheckToken.php');


$_SESSION['type']=$_REQUEST['type']; 
$sns=$_REQUEST['sns'];
//echo "type: ".$_REQUEST['type'];
//echo "sns: ".$sns;
if(!isset($_COOKIE['ccwikiUserID']))//If the user has not logined to MW
{
		header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/index.php/Special:UserLogin");
}
else
{
	switch($_REQUEST['type']) //��������ҳ���type��mapping����invite����
		{
		case 'mapping':
			header("Location:http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/login/".$sns."1.htm");
			return;
		case 'unmapping':
			if(unMapping($_COOKIE['ccwikiUserName'],$sns)==TRUE)
				echo "<script>alert('Unmapping Sucessfully!')</script>";
			else
				echo "<script>alert('Failed to Unmapping!')</script>";
			header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/index.php/Special:UserInfo");
			return;
		case 'qqweiboUpdate':
			$sns='qqweibo';
			$_SESSION['qqContent']=$_REQUEST['content'];
			break;
		case 'sinaweiboUpdate':
			$sns='sinaweibo';
			$_SESSION['sinaContent']=$_REQUEST['content'];
			break;
		case 'invite':
					    
			break;
		case 'UserInfo':
			break;
		}
			$mw=$_COOKIE['ccwikiUserName'];
			$access_token=getToken($mw,$sns); //�����ݿ��л��access_token;
			$openid=""; 
			if($sns=='qqweibo'&&!empty($access_token))
			{
				$data=explode(";",$access_token);
				$access_token=$data[0];
				$openid=$data[1];
			}
			if(empty($access_token)||IsTokenExpired($sns,$access_token,$openid)=='1')//������һ���ж�token�ǲ��ǹ���
			{
				 header("Location:http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/login/".$sns."1.htm");
			}
			else
			{
				 header("Location:http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/snsLogin.php?sns=".$sns."&access_token=".$access_token."&openid=".$openid);
			}
		    break;
}

?>
