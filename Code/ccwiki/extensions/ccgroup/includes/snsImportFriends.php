<?php
include_once (dirname(__FILE__).'/snsEditPage.php');
include_once (dirname(__FILE__).'/import.php');
include_once (dirname(__FILE__).'/snsGetUser.php');
include_once (dirname(__FILE__).'/snsGetFriends.php');
$sns = $_REQUEST['sns'];
$access_token = $_REQUEST['access_token'];
$openid = $_REQUEST['openid'];
//echo 'sns: '.$sns.'<br/>';
//echo 'access_token: '.$access_token.'<br/>';
switch($sns){
	case 'renren':
		$friends = getRenrenFriends($access_token);
		break;
	case 'kaixin':
		$friends = getKaixinFriends($access_token);
		break;
	case 'qqweibo':
		$friends = getQqweiboFriends($access_token, $openid);
		break;
}
//var_dump($friends);
$user = getUser($sns,$access_token,$openid);
foreach ($friends as $friend ){
	if(exist("Person_".$friend['sns_id']))
		continue;
	$resultFriend=initUserPage($friend);
    	savePage("Person_".$friend['sns_id'],$resultFriend);
}
if(exist("Person_".$user['sns_id']))
	$result='';
else
	$result=initUserPage($user);

foreach ($friends as $friend ){
	$result=$result."[[Ontology/Knows::Person_".$friend['sns_id']."| ]]";
}
savePage("Person_".$user['sns_id'],$result);
?>
