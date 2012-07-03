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
	echo $firstline;
	if(empty($firstline))
	{
		return false;
	}
	else
		return true;
}
$title=$_REQUEST["title"];
$source=$_REQUEST["source"];
$userpage=$_REQUEST['userPage'];
$source="tencent,sina";
//$title="火锅";
$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/weiboRdf.php?title=".$title."&source=".$source;
$ch = curl_init($url);//打开
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$response  = curl_exec($ch);
curl_close($ch);//关闭
$weibodatas=json_decode($response,true);
foreach ($weibodatas as $weibodata ){
	if(!exist("Person_".$weibodata['person']['sns_id'])){
	
                $resultPerson=$askPerson;
		$resultPerson=$resultPerson."[[Ontology 0/sns_id::".$weibodata['person']['sns_id']."| ]]";
		$resultPerson=$resultPerson."[[Ontology 0/name::".$weibodata['person']['name']."| ]]";
		$resultPerson=$resultPerson."[[Ontology 0/avatar::".$weibodata['person']['avatar']."| ]]";
		$resultPerson=$resultPerson."[[Ontology 0/source::".$weibodata['person']['source']."| ]]";
		$resultPerson=$resultPerson."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
		$resultPerson=$resultPerson."[[Category:Ontology 0/Person]]"; 
		import("person_".$weibodata['person']['sns_id'],$resultPerson);
	    
	}
	
	    $resultWeibo="{{ #ask: [[{{PAGENAME}}]]
                        | ?Ontology 0/avatar
                        | ?Ontology 0/published
                        | ?Ontology 0/status
                        | ?Ontology 0/mid
                        | ?Ontology 0/published_time
                        | ?Ontology 0/source
                        | format=template
                        | template=ShowMicroblog
                      }}";
	    $resultWeibo=$resultWeibo."[[Ontology 0/mid::".$weibodata['mid']."| ]]";
	    $resultWeibo=$resultWeibo."[[Ontology 0/status::".substr($weibodata['status'],0,100)."| ]]";
	    $resultWeibo=$resultWeibo."[[Ontology 0/published_time::".$weibodata['published_time']."| ]]";
	    $resultWeibo=$resultWeibo."[[Ontology 0/source::".$weibodata['source']."| ]]";
	    $resultWeibo=$resultWeibo."[[Ontology 0/published::Person_".$weibodata['person']['sns_id']."| ]]";
	    $resultWeibo=$resultWeibo."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
	    $resultWeibo=$resultWeibo."[[Ontology 0/userpage::".$userpage."| ]]";
	    $resultWeibo=$resultWeibo."{{:Person_".$weibodata['person']['sns_id']."}}";
	    $resultWeibo=$resultWeibo."[[Category:Ontology 0/Microblog]]";
	
		import("Microblog_".$weibodata['mid'],$resultWeibo);

}
?>
