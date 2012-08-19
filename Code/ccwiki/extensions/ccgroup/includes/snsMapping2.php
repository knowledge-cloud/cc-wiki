<?php
include_once (dirname(__FILE__).'/../conf.php');

function unMapping($mw,$sns){
	global $cc_mapping;
	$sql="update ".$cc_mapping." set ".$sns."=NULL where mw='$mw'";
	sqlExecute($sql);
	return TRUE;
}
function updateToken($mw,$sns,$access_token,$openid=''){
	global $cc_mapping;
	$line = getTokenline($mw);	
	if(empty($line)){
		switch ($sns){
			case "renren":
				$sql="insert ".$cc_mapping."(mw,renren) values('$mw','$access_token')";
				break;
			case "kaixin":
				$sql="insert ".$cc_mapping."(mw,kaixin) values('$mw','$access_token')";
				break;
			case "qqweibo":
				$access_token=$access_token.";".$openid;
        			$sql="insert ".$cc_mapping."(mw,qqweibo) values('$mw','$access_token')";
				break;
			case "sinaweibo":
				$sql="insert ".$cc_mapping."(mw,sinaweibo) values('$mw','$access_token')";
				break;
		}
	}else{
		if($sns=="qqweibo")
			$access_token = $access_token.";".$openid;
		$sql="update ".$cc_mapping." set ".$sns."='$access_token' where mw='$mw'";
	}
	sqlExecute($sql);
	return TRUE;
}

function setDefaultSNS($mw,$sns)
{
	global $cc_mapping;
	$token = getToken($mw,$sns);
	if(!empty($token)){
		$sql="update ".$cc_mapping." set default_sns='$sns' where mw='$mw'";
		sqlExecute($sql);
		return TRUE;
	}else{
		return FALSE;
	}
		
}

function getToken($mw, $sns)
{
	global $cc_mapping;
	if($sns=='')
		return '';
	$sql="select ".$sns." from ".$cc_mapping." where mw='$mw'";
	$result = sqlExecute($sql);
	$firstline=mysql_fetch_array($result);
	return $firstline[$sns];
	
}

function getDefaultSns($mw)
{
	global $cc_mapping;
	if($mw=='')
		return '';
	$sql="select default_sns from ".$cc_mapping." where mw='$mw'";
	$result = sqlExecute($sql);
	$firstline=mysql_fetch_array($result);
	return $firstline['default_sns'];
}

function getTokenline($mw)
{
	global $cc_mapping;
	$sql="select mw from ".$cc_mapping." where mw='$mw'";
	$result = sqlExecute($sql);
	$firstline=mysql_fetch_array($result);
	return $firstline['mw'];
}

function sqlExecute($sql)
{
	global $ccDB,$ccDBName,$ccDBPassword,$ccDBUsername;
	$link=mysql_connect($ccDB,$ccDBUsername,$ccDBPassword);
	if(!$link) 
		echo "failed";
	mysql_select_db($ccDBName,$link);
	//echo mysql_query($sql,$link);
	return mysql_query($sql,$link);
}



?>
