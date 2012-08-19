<?php
include('function.php');
include('deletePage.php');
include('../../conf.php');
///保存一个wiki文件
function  savePage($pageTitle, $pageContent){
	try {
                global $settings;
                $token = login($settings['user'], $settings['pass']);

		echo '<br />'.$token;
                login($settings['user'], $settings['pass'], $token);
		$xml=httpRequest($settings['wikiroot']."/api.php?action=query&prop=info|revisions&intoken=edit&titles=Main%20Page","");
		echo '<br />'.$token.'<br />';
 		$token=substr($xml,stripos($xml,"token")+12,34);
		echo $token;
				
                $fields['title'] = $pageTitle;
		if($settings['section']!=null)
			$fields['section'] = $settings['section'];
		if($settings['sectiontitle']!=null)
			$fields['sectiontitle'] = $settings['sectiontitle'];
		$fields['text'] = $pageContent;
		if($settings['sectiontitle']!=null)
			$fields['summary'] = $settings['summary'];
                $fields['action'] = 'edit';
                $fields['token'] = $token;	
                $xml1=httpRequest($settings['wikiroot']."/api.php",$fields);
	        echo $xml1;
				
        } catch (Exception $e) {
                die("FAILED in savePage: " . $e->getMessage());
        }
}

$settings['wikiroot'] = "http://".$ccHost.":".$ccPort."/".$ccSite;
$pageTitle=$_REQUEST['pageTitle'];
$pageContent=$_REQUEST['pageContent'];
$settings['section']='new';

$settings['user'] = $ccUsername;
$settings['pass'] = $ccPassword;

$settings['cookiefile'] = "cookies.tmp";

$con = mysql_connect($ccDB, $ccDBUsername, $ccDBPassword);
if(!$con){
	die('could not connect:'.mysql_error());
}
mysql_select_db($ccDBName,$con);
$sql_str='select * from page where page_title="'.$pageTitle.'"';
echo $sql_str;
$result=mysql_query($sql_str);
if($row=mysql_fetch_array($result)){
	deletePage($pageTitle);
}
mysql_close($con);
savePage($pageTitle, $pageContent);
?>
