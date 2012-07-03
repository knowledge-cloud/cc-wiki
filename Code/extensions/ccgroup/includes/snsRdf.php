<?php
include '../conf.php';
$source=$_REQUEST["source"];
$access_token=$_REQUEST["access_token"];
$access_token_secret=$_REQUEST["access_token_secret"];
if ($source=="renren"){
    $userurl="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/getrenrenUser.php?access_token=".$access_token;
    $friendurl="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/renrenFriend.php?access_token=".$access_token;
}
elseif ($source=="kaixin"){
	$userurl="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/getkaixinUser.php?access_token=".$access_token;
	$friendurl="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/kaixinFriend.php?access_token=".$access_token;
	}
elseif ($source=="qqweibo"){
	$userurl="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/getqqweiboUser.php?oauth_token=".$access_token."&oauth_token_secret=".$access_token_secret;
	$friendurl="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/qqweiboFriend.php?oauth_token=".$access_token."&oauth_token_secret=".$access_token_secret;
	}
$ch = curl_init($userurl);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$userResponse  = curl_exec($ch);
curl_close($ch);//关闭

$ch = curl_init($friendurl);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$friendResponse  = curl_exec($ch);
curl_close($ch);//关闭

$all=array();
$user = new DOMDocument ();
$user->loadXML($userResponse);
$friend=new DOMDocument ();
$friend->loadXML($friendResponse);
$friend_count=1;

$friends=$friend->getElementsByTagName("friend");
$users=$user->getElementsByTagName("user");
foreach ($users as $userInfo){
	$sns_id=$userInfo->getElementsByTagName('id');
	$sns_id=$sns_id->item(0)->nodeValue;
	$all["sns_id"]=$sns_id;
	
	$name=$userInfo->getElementsByTagName('name');
	$name=$name->item(0)->nodeValue;
	$all["name"]=$name;
	
	$avatar=$userInfo->getElementsByTagName('url');
	$avatar=$avatar->item(0)->nodeValue;
	$all["avatar"]=$avatar;
	
	$source=$userInfo->getElementsByTagName('cate');
	$source=$source->item(0)->nodeValue;
	$all["source"]=$source;
	$knows=array();
 foreach ($friends as $data){
 	$person=array();
	
	$sns_id=$data->getElementsByTagName('id');
	$sns_id=$sns_id->item(0)->nodeValue;
	$person["sns_id"]=$sns_id;
	
	$name=$data->getElementsByTagName('name');
	$name=$name->item(0)->nodeValue;
	$person["name"]=$name;
	
	$avatar=$data->getElementsByTagName('url');
	$avatar=$avatar->item(0)->nodeValue;
	$person["avatar"]=$avatar;
	
	$source=$data->getElementsByTagName('cate');
	$source=$source->item(0)->nodeValue;
	$person["source"]=$source;
	
	$knows["person_".$friend_count]=$person;
	$friend_count++;
}
    $all["knows"]=$knows;  
}
$result=json_encode($all);
//$result=urlencode($result);
echo $result;
?>