<?php
include_once (dirname(__FILE__).'/api.php');
include_once (dirname(__FILE__).'/../conf.php');
$sql="select page_title from page where page_title like '%discount'";
$link=mysql_connect($ccDB,$ccDBUsername,$ccDBPassword);
if(!$link) 
	echo "failed";
mysql_select_db($ccDBName,$link);
$result=mysql_query($sql,$link);
while($row = mysql_fetch_row($result))
{
	echo 'delete '. $row[0].'...
';
	deletePage($row[0]);
}
?>
