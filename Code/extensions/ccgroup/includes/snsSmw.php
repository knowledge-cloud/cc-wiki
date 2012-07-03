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
	if(empty($firstline))
	{
		return false;
	}
	else
		return true;
}
$source=$_REQUEST["source"];
$access_token=$_REQUEST["access_token"];
$access_token_secret=$_REQUEST["access_token_secret"];
$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/snsRdf.php?source=".$source."&access_token=".$access_token."&access_token_secret=".$access_token_secret;
$ch = curl_init($url);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);//关闭
$persons=json_decode($response,true);
foreach ($persons["knows"] as $friend ){
	if(exist("Person_".$friend['sns_id']))
		continue;
		$resultFriend=$askPerson;
		$resultFriend=$resultFriend."[[Ontology 0/sns_id::".$friend['sns_id']."| ]]";
		$resultFriend=$resultFriend."[[Ontology 0/name::".$friend['name']."| ]]";
		$resultFriend=$resultFriend."[[Ontology 0/avatar::".$friend['avatar']."| ]]";
		$resultFriend=$resultFriend."[[Ontology 0/source::".$friend['source']."| ]]";
		$resultFriend=$resultFriend."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
		$resultFriend=$resultFriend."[[Category:Ontology 0/Person]]";
	    import("Person_".$friend['sns_id'],$resultFriend);
	}
	
	if(exist("Person_".$persons['sns_id'])){
		$result='';
		foreach ($persons["knows"] as $friend ){
			$result=$result."[[Ontology 0/knows::Person_".$friend['sns_id']."| ]]";
                        //$result=$result."{{:Person_".$friend['sns_id']."}}";
		}
	}
	else{
	    $result=$askPerson;
		$result=$result."[[Ontology 0/sns_id::".$persons['sns_id']."| ]]";
		$result=$result."[[Ontology 0/name::".$persons['name']."| ]]";
		$result=$result."[[Ontology 0/avatar::".$persons['avatar']."| ]]";
		$result=$result."[[Ontology 0/source::".$persons['source']."| ]]";
		$result=$result."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
		foreach ($persons["knows"] as $friend ){
			$result=$result."[[Ontology 0/knows::Person_".$friend['sns_id']."| ]]";
                        //$result=$result."{{:Person_".$friend['sns_id']."}}";
		}
		$result=$result."[[Category:Ontology 0/Person]]";
	}
		import("Person_".$persons['sns_id'],$result);
?>
