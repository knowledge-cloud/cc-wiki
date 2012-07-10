<?php
include_once (dirname(__FILE__).'/../conf.php');
function getMapping($mw, $sns)
{
	$token_line=getTokenline($mw);
	return getToken($token_line,$sns);
}
function updateMapping($mw,$sns,$access_token,$access_token_secret=''){
	global $cc_account;
	$token_line = getTokenline($mw);	
	if(empty($token_line)){
		switch ($sns){
			case "renren":
				$sql="insert ".$cc_account." values('$mw','$access_token,,,')";
				break;
			case "kaixin":
				$sql="insert ".$cc_account." values('$mw',',$access_token,,')";
				break;
			case "qqweibo":
				$access_token=$access_token.";".$access_token_secret;
        			$sql="insert ".$cc_account." values('$mw',',,$access_token,')";
				break;
		}
		echo 'sql: '.$sql.'<br/>';
			sqlExecute($sql);
	}else{
		$existed_token=getToken($token_line,$sns);
		echo 'existed_token: '.$existed_token.'<br/>';
		if($sns=="qqweibo")
			$access_token = $access_token.";".$access_token_secret;
		if($existed_token!=$access_token){
			$tokens = explode(',',$token_line);
			switch ($sns){
				case "renren":
					$update=$access_token.",".$tokens[1].",".$tokens[2].",".$tokens[3];
					break;
				case "kaixin":
					$update=$tokens[0].",".$access_token.",".$tokens[2].",".$tokens[3];
					break;
				case "qqweibo":
					$update=$tokens[0].",".$tokens[1].",".$access_token.",".$tokens[3];
					break;
			}
			$sql="update ".$cc_account." set sns="."'$update'"." where mw='$mw'";
			echo 'sql: '.$sql.'<br/>';
			sqlExecute($sql);
		}
	}
}

function getToken($token_line, $sns)
{
	$tokens=explode(',',$token_line);
	switch($sns){
		case 'renren':
			return $tokens[0];	
			break;
		case 'kaixin':
			return $tokens[1];	
			break;
		case 'qqweibo':
			$token=explode(';',$tokens[2]);
			return $token[0];	
			break;
	}
}
function getTokenline($mw)
{ 
	global $cc_account;
	$sql="select sns from ".$cc_account." where mw='$mw'";
	$result = sqlExecute($sql);
	$firstline=mysql_fetch_array($result);
	return $firstline['sns'];
}
function sqlExecute($sql)
{
	global $ccDB,$ccDBName,$ccDBPassword,$ccDBUsername;
	$link=mysql_connect($ccDB,$ccDBUsername,$ccDBPassword);
	if(!$link) 
		echo "failed";
	mysql_select_db($ccDBName,$link);
	return mysql_query($sql,$link);
}



?>
