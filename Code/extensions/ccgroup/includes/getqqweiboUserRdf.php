<?php
include '../conf.php';
$access_token=$_REQUEST['access_token'];
$access_token_secret=$_REQUEST['access_token_secret'];
//$access_token="05d6de23f7d34668bc22f4d3c7a04c62";
//$access_token_secret="67da7ce02b7934db967ae8085a6cd48d";

$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/getqqweiboUser.php?oauth_token=".$access_token."&oauth_token_secret=".$access_token_secret;
$ch = curl_init($url);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);//关闭
$all=array();
$input = new DOMDocument ();
$input->loadXML($response);
$datas=$input->getElementsByTagName("user");
foreach ($datas as $data){
	$sns_id=$data->getElementsByTagName('id');
	$sns_id=$sns_id->item(0)->nodeValue;
	$all["sns_id"]=$sns_id;
	
	$name=$data->getElementsByTagName('name');
	$name=$name->item(0)->nodeValue;
	$all["name"]=$name;
	
	$avatar=$data->getElementsByTagName('url');
	$avatar=$avatar->item(0)->nodeValue;
	$all["avatar"]=$avatar;
	
	$source=$data->getElementsByTagName('cate');
	$source=$source->item(0)->nodeValue;
	$all["source"]=$source;
}
$result=json_encode($all);
//$result=urlencode($result);
echo $result;
