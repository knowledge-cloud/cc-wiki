<?php

session_start();
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/snsMapping2.php');
include_once (dirname(__FILE__).'/snsEditPage.php');
include_once (dirname(__FILE__).'/async.php');
include_once (dirname(__FILE__).'/importComment.php');
include_once (dirname(__FILE__).'/snsCheckToken.php');

if(!isset($_COOKIE['ccwikiUserID'])){
	header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/index.php/Special:UserLogin");
	return;
}
$mw=$_COOKIE['ccwikiUserName'];
$sns = getDefaultSns($mw);
$token=getToken($mw,$sns);
$token_secret='';
if(empty($token)){
	header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/index.php/Special:Mapping");
	return;
}
if($sns=="qqweibo"){
	$tokens=explode(";",$token);
	$token_secret=$tokens[1];
	$token=$tokens[0];
}
if(IsTokenExpired($sns,$token,$token_secret)=='1'){
	header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/index.php/Special:Mapping?page=interface.php&msg=请重新绑定".$sns);
	return;
}

switch ($_REQUEST['type']){
	case 'create':
		$userPage=$_SESSION['c_pagename'];
		if($sns=="qqweibo"){
			$person_page = pageEdit($sns,"create",$userPage,$token,$token_secret);
		}else{
			$person_page = pageEdit($sns,"create",$userPage,$token);
		}
		if($person_page==''){
			echo 'Failed to get your sns information from '.$sns.', you can try again!';
			break;
		}
         	$_SESSION['c_createdatapage']=$person_page;      
		$keywords=$_SESSION['c_keywords'];
		async_call('http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/includes/subpageThread.php?keywords='.$keywords.'&userPage='.$userPage);	

        	header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/mashupwiki/Interface/MashupPageInterface.php?a=savePage");
		break;
	case 'participate':
		$deal=$_SESSION['deal'];
		$deal=rawurldecode($deal);
		$url = $_SESSION['url'];
		if($sns=="qqweibo"){
			$person_page=pageEdit($sns,"participate",$deal,$token,$token_secret);
		}else{
			$person_page=pageEdit($sns,"participate",$deal,$token);
		}
		if($person_page==''){
			echo 'Failed to get your sns information from '.$sns.', you can try again!';
			break;
		}
		header("Location:".$url);
		break;
	case 'comment':
		$deal=$_SESSION['deal'];
		$deal=rawurldecode($deal);
		if($sns=="qqweibo"){
			$user=getUser($sns,$token,$token_secret);
		}else{
			$user=getUser($sns,$token,"");
		}
		if($user['sns_id']=='' || $user['sns_id']==NULL){
			echo 'Failed to get your sns information from '.$sns.', you can try again!';
			break;
		}
		importComment($deal,$_SESSION['content'],'Person_'.$user['sns_id'],$_SESSION['score']);
		echo json_encode(true);
		
		break;
	default:
		$deal=$_SESSION['deal'];
		$deal=rawurldecode($deal);
		if($sns=="qqweibo"){
			$person_page=pageEdit($sns,$_REQUEST['type'],$deal,$token,$token_secret);
		}else{
			$person_page=pageEdit($sns,$_REQUEST['type'],$deal,$token);
		}
		if($person_page==''){
			echo 'Failed to get your sns information from '.$sns.', you can try again!';
			break;
		}
		header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/mashupwiki/Interface/MashupPageInterface.php?a=purgePage");
}

?>
