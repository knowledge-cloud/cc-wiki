<?php
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/import.php');
include_once (dirname(__FILE__).'/snsGetUser.php');
function getUser($sns,$access_token,$access_token_secret)
{
	global $ccHost,$ccPort,$ccWiki;
	switch($sns){
		case 'renren':
			return getRenrenUser($access_token);
			break;
		case 'kaixin':
			return getKaixinUser($access_token);
			break;
		case 'qqweibo':
			return getQqweiboUser($access_token,$access_token_secret);
			break;
	}
	
}
function initUserPage($user){
	global $askPerson;
	$result=$askPerson;
	$result=$result."[[Ontology 0/sns_id::".$user['sns_id']."| ]]";
	$result=$result."[[Ontology 0/name::".$user['name']."| ]]";
	$result=$result."[[Ontology 0/avatar::".$user['avatar']."| ]]";
	$result=$result."[[Ontology 0/source::".$user['source']."| ]]";
	$result=$result."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
	$result=$result."[[Category:Ontology 0/Person]]";
	return $result;
}
function pageEdit($sns,$action,$dest,$access_token,$access_token_secret="")
{
	$user = getUser($sns,$access_token,$access_token_secret);
	if(exist("Person_".$user['sns_id'])){
		$result='';
	}else{
		$result=initUserPage($user);
	}
	switch($action){
		case 'create':
			$result=$result."[[Ontology 0/create::".$dest."| ]]";
			break;
		case 'participate':
			$result=$result."[[Ontology 0/participated::Deal_".$dest."| ]]";
			break;
		case 'interest':
			$result=$result."[[Ontology 0/interested::Deal_".$dest."| ]]";
			break;
	}
	savePage("Person_".$user['sns_id'],$result);
}


?>
