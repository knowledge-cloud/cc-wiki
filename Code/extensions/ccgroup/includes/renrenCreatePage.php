<?php
session_start();
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
function exist($page){
	global $ccHost;
	global $ccPort;
	global $ccSite;
	global $ccWiki;
	global $ccDB;
	global $ccDBUsername;
	global $ccDBPassword;
	global $ccDBName;
	global $cc_conf_gb;
	global $cc_page;
	$link=mysql_connect($ccDB,$ccDBUsername,$ccDBPassword);
	if(!$link) echo "failed";
	mysql_select_db($ccDBName,$link);
	$sql="select * from page where page_title='$page'";
	$result=mysql_query($sql,$link);
	$firstline=mysql_fetch_array($result);
	echo $firstline;
	if(empty($firstline))
	{
		return false;
	}
	else 
		return true;
}
$access_token=$_REQUEST['access_token'];
$userPage=$_REQUEST['userPage'];
$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/getrenrenUserRdf.php?access_token=".$access_token;
$ch = curl_init($url);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);//关闭
$user=json_decode($response,true);
$result='';
if(exist("Person_".$user['sns_id'])){
	$result=$result."[[Ontology 0/create::".$userPage."| ]]";
}
else{
$result=$askPerson;
$result=$result."[[Ontology 0/sns_id::".$user['sns_id']."| ]]";
$result=$result."[[Ontology 0/name::".$user['name']."| ]]";
$result=$result."[[Ontology 0/avatar::".$user['avatar']."| ]]";
$result=$result."[[Ontology 0/source::".$user['source']."| ]]";
$result=$result."[[Ontology 0/create::".$userPage."| ]]";
$result=$result."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
$result=$result."[[Category:Ontology 0/Person]]";
}
import("Person_".$user['sns_id'],$result);
echo  $user['sns_id'];
?>
