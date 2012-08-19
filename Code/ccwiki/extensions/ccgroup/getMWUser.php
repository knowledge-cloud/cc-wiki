<?php
include('conf.php');
//print_r($_COOKIE);
if(isset($_COOKIE['ccwikiUserID'])){
	global $ccHost,$ccPort,$ccDB,$ccDBName,$ccDBUsername,$ccDBPassword;
	$user = $_COOKIE['ccwikiUserName'];
	$real_name = '';
	$email = '';
	$con = mysql_connect($ccDB, $ccDBUsername, $ccDBPassword);
	if(!$con){
		die('connect mysql faild: '.mysql_error());
	}
	mysql_select_db($ccDBName, $con);
	$sql_str = 'select user_real_name,user_email from user where user_name="'.$user.'"';
	$result = mysql_query($sql_str);
	if($row=mysql_fetch_array($result)){
		$real_name = $row['user_real_name'];
		$email = $row['user_email'];
	}
	mysql_close($con);
	$content="";
	$photo="";
	if($email!=''){
		$default="http://www.somewhere.com/homestar.jpg";
		$photo="http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode($default);
	}
	$content.="[[Ontology 0/sns_id::".$user."| ]]";
	$content.="[[Ontology 0/name::".$real_name."| ]]";
	$content.="[[Ontology 0/avatar::".$photo."| ]]";
	if($email!='')
		$content.="[[Ontology 0/email::".$email."| ]]";
	$content.="[[Ontology 0/source::MW| ]]";
	$content.="[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
	$content.="[[Category:Ontology 0/Person]]";
	$url='http://'.$ccHost.':'.$ccPort.'/'.$ccWiki.'/includes/saveMWuser/savePage.php';
	$vars='pageTitle=Person_MW_'.$user.'&pageContent='.$content;
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
	curl_exec($ch);
	curl_close($ch);
	echo 'userID: '.$_COOKIE['ccwikiUserID'].';user: '.$user.';realName: '.$real_name.';email: '.$email.'<br />';
	echo $content;
}
else{
	echo 'no user login in!<br />';
}
?>
