<?php
include('../conf.php');
$access_token=$_REQUEST['access_token'];
//$access_token="187237|6.05f53d44e16483069122dd348645577e.2592000.1340884800-427985954";
$method="users.getInfo";
$v="1.0";
$secret=$renren_secret;
$format="XML";
$content="access_token=".$access_token."format=".$format."method=".$method."v=".$v.$secret;
$sig=md5($content);
$post_data="access_token=".$access_token."&format=".$format."&method=".$method."&v=".$v."&sig=".$sig;
$url="http://api.renren.com/restserver.do";
$ch = curl_init();//打开
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
$response  = curl_exec($ch);
//echo $response;
curl_close($ch);//关闭
$input=new DOMDocument();
$doc=new DOMDocument('1.0','UTF-8');
$doc->formatOutput=true;
$root=$doc->createElement("user");
$doc->appendChild($root);
$input->loadXML($response);
$users=$input->getElementsByTagName("user");
foreach ($users as $user){
	$id=$user->getElementsByTagName("uid");
	$name=$user->getElementsByTagName("name");
	$url=$user->getElementsByTagName("tinyurl");
	$idname=$doc->createElement("id");
	$namename=$doc->createElement("name");
	$urlname=$doc->createElement("url");
	$cate=$doc->createElement("cate");
	$idname->appendChild($doc->createTextNode($id->item(0)->nodeValue));
	$namename->appendChild($doc->createTextNode($name->item(0)->nodeValue));
	$urlname->appendChild($doc->createTextNode($url->item(0)->nodeValue));
	$cate->appendChild($doc->createTextNode("renren"));
	$root->appendChild($idname);
	$root->appendChild($namename);
	$root->appendChild($urlname);
	$root->appendChild($cate);
}
echo $doc->saveXML();


?>
