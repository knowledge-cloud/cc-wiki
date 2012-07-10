<?php
session_start();
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/snsMapping.php');
$_SESSION['action']=$_REQUEST['type'];
//echo "type: ".$_REQUEST['type'];
//echo "sns: ".$_REQUEST['sns'];
switch ($_REQUEST['type']){
	case 'create':
		$_SESSION['userPage']=$_REQUEST['userPage'];
		$_SESSION['keyword']=$_SESSION['c_keywords'];
		break;
	case 'participate':
		$_SESSION['deal']=$_REQUEST['deal'];
		break;
	case 'interest':
		$_SESSION['deal']=$_REQUEST['deal'];
		break;
	case 'invite':
		break;
}
$mw=$_COOKIE['ccwikiUserName'];
//If the user has not logined to MW
if(!isset($_COOKIE['ccwikiUserID'])){
	header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/index.php/Special:UserLogin");
}else{
	$token=getMapping($mw,$_REQUEST['sns']);
	if(empty($token)){
		header("Location:http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/login/".$_REQUEST['sns']."1.htm");
	}else{
		header("Location:http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/snsLogin.php?sns=".$_REQUEST['sns']."&access_token=".$token);
	}
}
?>
