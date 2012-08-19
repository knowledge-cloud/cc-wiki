<?php
require_once( '../conf.php');
$friend = $_GET["friends"];
$message = $_GET["message"];
$token = $_GET["token"];      //accessToken
$openid = $_GET["openid"];    //openid
$sns = $_GET["sns"];
$friends = $friend[0];
$res = '';
for($i=1;$i<count($friend);$i++){
	$friends .= ','.$friend[$i];     //被邀请的朋友是以逗号隔开的
}
$tmpSite = 'http://' . $ccHost . ':' . $ccPort . '/' . $ccSite . '/extensions/ccgroup/includes/';
if($sns == "renren") {
	$url = $tmpSite. 'renrenMessage.php';
	$data = 'access_token=' .$token. '&notification=' .$message. '&to_ids=' .$friends;	
	$res = _curl_post($url, $data);
}
elseif($sns == "kaixin") {
	$url = $tmpSite . 'kaixinMessage.php';
	$data = 'access_token=' .$token. '&content=' .$message. '&fuids=' .$friends;
	$res = _curl_post($url, $data);
}
elseif($sns == "qqweibo") {
	foreach($friend as $personId)
	{
		$url = $tmpSite. 'qqweiboMessage.php';
		$data = 'access_token=' .$token. '&openid=' .$openid. '&content=' .$message. '&friend_name=' .$personId;
		$res = _curl_post($url, $data);
	}
}

header('Location:http://' . $ccHost . ':' . $ccPort . '/' . $ccSite. '/index.php/Special:Invite');

function _curl_post($url, $vars) {
     $ch = curl_init();
     curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_URL,$url);
     curl_setopt($ch, CURLOPT_POST, 1 );
     curl_setopt($ch, CURLOPT_HEADER, 0 ) ;
     curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
     $data = curl_exec($ch);
     curl_close($ch);
     if ($data)
         return $data;
     else
         return false;
}

?>
