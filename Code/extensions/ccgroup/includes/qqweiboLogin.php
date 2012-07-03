<?php
session_start();
include '../conf.php';
function jump($url){
	$ch = curl_init($url);//打开
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	$response  = curl_exec($ch);
	curl_close($ch);//关闭
        return $response;
}
function async($url){
        $ch = curl_init($url);//打开
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_exec($ch);
        curl_close($ch);//关闭
}

$action=$_SESSION['action'];
$access_token=$_REQUEST['access_token'];
$access_token_secret=$_REQUEST['access_token_secret'];
//$access_token="05d6de23f7d34668bc22f4d3c7a04c62";
//$access_token_secret="67da7ce02b7934db967ae8085a6cd48d";
$mw=$_COOKIE['ccwikiUserName'];
$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/qqweiboConfig.php?access_token=".$access_token."&mw=".$mw."&access_token_secret=".$access_token_secret;
jump($url);

if($action=="create"){
	$userPage=$_SESSION['userPage'];
        $userPage=rawurlencode($userPage);
	 $keyword=$_SESSION['keyword'];
        $keyword=rawurlencode($keyword);
        $url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/qqweiboCreatePage.php?access_token=".$access_token."&access_token_secret=".$access_token_secret."&userPage=".$userPage;
        $uid=jump($url);
$_SESSION['c_createdatapage']="Person_".substr($uid,5);
$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/weiboSmw.php?title=".$keyword."&userPage=".$userPage;
        async($url);
    $url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/photoSmw.php?text=".$keyword."&userPage=".$userPage;
         async($url);
         header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/mashupwiki/Interface/MashupPageInterface.php?a=savePage");

}

elseif ($action=="invite"){
	header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/index.php/Special:Invite?type=qqweibo&access_token=".$access_token."&access_token_secret=".$access_token_secret);
}
elseif ($action=="participate"){
	$deal=$_SESSION['deal'];
        $deal=rawurlencode($deal);
	$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/qqweiboParticipate.php?access_token=".$access_token."&access_token_secret=".$access_token_secret."&deal=".$deal;
	async($url);
header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/mashupwiki/Interface/MashupPageInterface.php?a=purgePage");

}
elseif ($action=="interest"){
	$deal=$_SESSION['deal'];
        $deal=rawurlencode($deal);
	$url="http://".$ccHost.":".$ccPort."/".$ccWiki."/includes/qqweiboInterest.php?access_token=".$access_token."&access_token_secret=".$access_token_secret."&deal=".$deal;
	async($url);
header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/mashupwiki/Interface/MashupPageInterface.php?a=purgePage");

}
?>
