<?php
include('../conf.php');
$access_token=$_REQUEST['access_token'];
//$access_token="131061046_05c9433b3e741be6f32e40440821de49";
$userurl="https://api.kaixin001.com/users/me.xml?access_token=".$access_token;
//echo $response;

$ch = curl_init($userurl);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$userResponse  = curl_exec($ch);
curl_close($ch);//关闭
//echo $userResponse;

$input=new DOMDocument();
$doc=new DOMDocument('1.0','UTF-8');
$doc->formatOutput=true;
$root=$doc->createElement("user");
$doc->appendChild($root);
$input->loadXML($userResponse);
$users=$input->getElementsByTagName("user");
foreach ($users as $user){
	$id=$user->getElementsByTagName("uid");
	$name=$user->getElementsByTagName("name");
	$url=$user->getElementsByTagName("logo50");
	$idname=$doc->createElement("id");
	$namename=$doc->createElement("name");
	$urlname=$doc->createElement("url");
	$cate=$doc->createElement("cate");
	$idname->appendChild($doc->createTextNode($id->item(0)->nodeValue));
	$namename->appendChild($doc->createTextNode($name->item(0)->nodeValue));
	$urlname->appendChild($doc->createTextNode($url->item(0)->nodeValue));
	$cate->appendChild($doc->createTextNode("kaixin"));
	$root->appendChild($idname);
	$root->appendChild($namename);
	$root->appendChild($urlname);
	$root->appendChild($cate);
}
echo $doc->saveXML();


?>
