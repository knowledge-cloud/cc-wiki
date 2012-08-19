<?php
include_once (dirname(__FILE__).'/../includes/import.php');
include_once (dirname(__FILE__).'/../conf.php');

$link=mysql_connect($ccDB,$ccDBUsername,$ccDBPassword);
if(!$link) echo "failed";

$sql="select page_title from page where page_title like 'Location%'";
mysql_select_db($ccDBName,$link);
$result=mysql_query($sql,$link);
$count =0;
while($row = mysql_fetch_row($result))
{
	echo 'resave '. $row[0].'...
';
	savepage($row[0],'<!-- resave tag --!>');
	$count=$count+1;
	echo $count.'
';
  
}
$sql="select page_title from page where page_title like 'Deal%'";
$result=mysql_query($sql,$link);
$count =0;
while($row = mysql_fetch_row($result))
{
	echo 'resave '. $row[0].'...
';
	savepage($row[0],'<!-- resave tag --!>');
	$count=$count+1;
	echo $count.'
';
  
}

?>
