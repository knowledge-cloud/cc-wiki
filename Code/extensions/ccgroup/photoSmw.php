<?php
include '../conf.php';
function import($pagename,$content){
	global $ccHost;
	global $ccPort;
	global $ccSite;
	global $ccWiki;
	$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/import.php";
	$post_data="pageTitle=".$pagename."&pageContent=".$content."&section=new";
	$ch = curl_init();//打开
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
$response  = curl_exec($ch);
curl_close($ch);//关闭
}
function delete($pagename){
	if(file_exists('../data/'.$pagename)){
		unlink('../data/'.$pagename);
	}
}
$text=$_REQUEST['text'];
$userpage=$_REQUEST['userpage'];
$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/photoRdf.php?text=".$text;
$ch = curl_init($url);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);//关闭
$photodatas=json_decode($response,true);
foreach ($photodatas as $photodata ){
	$resultPhoto="{{ #ask: [[{{PAGENAME}}]]
                   | ?Ontology 0/photo_id
                   | ?Ontology 0/title
                   | ?Ontology 0/description
                   | ?Ontology 0/picture
                   | format=template
                   | template=ShowFlickr
                   }}";
	$resultPhoto=$resultPhoto."[[Ontology 0/photo_id::".$photodata['photo_id']."| ]]";
	$resultPhoto=$resultPhoto."[[Ontology 0/title::".$photodata['title']."| ]]";
	$resultPhoto=$resultPhoto."[[Ontology 0/description::".$photodata['description']."| ]]";
	$resultPhoto=$resultPhoto."[[Ontology 0/picture::".$photodata['picture']."| ]]";
	$resultPhoto=$resultPhoto."[[Ontology 0/picture::".$userpage."| ]]";
	$resultPhoto=$resultPhoto."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
	$resultPhoto=$resultPhoto."[[Category:Ontology 0/Photo]]";
	
	import("Photo_".$photodata['photo_id'],$resultPhoto);

}
?>