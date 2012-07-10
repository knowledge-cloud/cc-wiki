<?php
session_start();
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/snsEditPage.php');
include_once (dirname(__FILE__).'/snsMapping.php');
include_once (dirname(__FILE__).'/importPhotos.php');
include_once (dirname(__FILE__).'/importWeibos.php');
function async($url){
        $ch = curl_init($url);//打开
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_exec($ch);
        curl_close($ch);//关闭
}
$action=$_SESSION['action'];
//echo "action: ".$action;
//echo "sns: ".$_REQUEST['sns'];
$mw=$_COOKIE['ccwikiUserName'];
$access_token=$_REQUEST['access_token'];
$access_token_secret='';
if($_REQUEST['sns']=="qqweibo"){
	$access_token_secret=$_REQUEST['access_token_secret'];
}
updateMapping($mw,$_REQUEST['sns'],$access_token,$access_token_secret);


switch ($action){
	case "create":
		$userPage=$_SESSION['userPage'];
		if($_REQUEST['sns']=="qqweibo"){
			pageEdit($_REQUEST['sns'],"create",$userPage,$access_token,$access_token_secret);
		}else{
			pageEdit($_REQUEST['sns'],"create",$userPage,$access_token);
		}
         	$_SESSION['c_createdatapage']="Person_".substr($uid,5);      
//		$keyword=$_SESSION['keyword'];
 //       	$keyword=rawurlencode($keyword);
	//	importPhotos($keyword,$userPage);
	//      importWeibos($keyword,$userPage);

          	header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/mashupwiki/Interface/MashupPageInterface.php?a=savePage");
		break;
	case "invite":
		$head_str="Location:http://".$ccHost.":".$ccPort."/".$ccSite."/index.php/Special:Invite?sns=".$_REQUEST['sns']."&access_token=".$access_token;
		if($_REQUEST['sns']=="qqweibo"){
			$head_str = $head_str."&access_token_secret=".$access_token_secret;
		}
		header($head_str);
		break;
	case "participate":
		$deal=$_SESSION['deal'];
		$deal=rawurlencode($deal);
		if($_REQUEST['sns']=="qqweibo"){
			pageEdit($_REQUEST['sns'],"participate",$deal,$access_token,$access_token_secret);
		}else{
			pageEdit($_REQUEST['sns'],"participate",$deal,$access_token);
		}
		header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/mashupwiki/Interface/MashupPageInterface.php?a=purgePage");
		break;
	case "interest":
		$deal=$_SESSION['deal'];
        	$deal=rawurlencode($deal);
		if($_REQUEST['sns']=="qqweibo"){
			pageEdit($_REQUEST['sns'],"interest",$deal,$access_token,$access_token_secret);
		}else{
			pageEdit($_REQUEST['sns'],"interest",$deal,$access_token);
		}
		header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/mashupwiki/Interface/MashupPageInterface.php?a=purgePage");
		break;
}

?>
