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
	$result=$result."[[Ontology/PersonId::".$user['sns_id']."| ]]";
	$result=$result."[[Ontology/Name::".$user['name']."| ]]";
	$result=$result."[[Ontology/Avatar::".$user['avatar']."| ]]";
	$result=$result."[[Ontology/PersonSource::".$user['source']."| ]]";
	$result=$result."[[Ontology/Timestamp::".date('Y-m-d H:i:s',time())."| ]]";
	$result=$result."[[Category:Ontology/Person]]";
	return $result;
}

function pageEdit($sns,$action,$dest,$access_token,$access_token_secret="")
{
	$user = getUser($sns,$access_token,$access_token_secret);
	if($user['sns_id']=='' || $user['sns_id']==NULL)
		return '';
	if(exist("Person_".$user['sns_id'])){
		$result='';
	}else{
		$result=initUserPage($user);
	}
	switch($action){
		case 'create':
			$result=$result."[[Ontology/CreateVotePage::".$dest."| ]]";
			break;
		case 'participate':
			$result=$result."[[Ontology/Participated::".$dest."| ]]";
			break;
		case 'support':
			$result=$result."[[Ontology/Support::".$dest."| ]]";
			break;
		case 'unsupport':
			$result=$result."[[Ontology/Unsupport::".$dest."| ]]";
			break;
	}
//	echo 'result: '.$result.'<br/>';
	savePage("Person_".$user['sns_id'],$result);
	return "Person_".$user['sns_id'];
}

?>
