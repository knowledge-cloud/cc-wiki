<?php
session_start();
include '../conf.php';
$mw=$_REQUEST['mw'];
$link=mysql_connect($ccDB,$ccDBUsername,$ccDBPassword);
if(!$link) echo "failed";
mysql_select_db($ccDBName,$link);
$sql="select sns from ".$cc_account." where mw='$mw'";
$result=mysql_query($sql,$link);
$firstline=mysql_fetch_array($result);
$sns=$firstline['sns'];
//echo $sns;
$sns=explode(',', $sns);
$kaixin=$sns[1];
//echo $kaixin;
//$kaixin="123";
$userurl="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/getkaixinUser.php?access_token=".$kaixin;
$ch = curl_init($userurl);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);//关闭
//echo $response;
$user = new DOMDocument ();
$user->loadXML($response);
$users=$user->getElementsByTagName("user");
foreach ($users as $userInfo){
	$sns_id=$userInfo->getElementsByTagName('id');
	$result=$sns_id->item(0)->nodeValue;
}
if(!empty($result)){
	echo $kaixin;
}
	
