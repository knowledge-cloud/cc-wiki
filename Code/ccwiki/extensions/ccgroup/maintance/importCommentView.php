<?php
include_once (dirname(__FILE__).'/api.php');
include_once (dirname(__FILE__).'/../conf.php');
$link=mysql_connect($ccDB,$ccDBUsername,$ccDBPassword);
if(!$link) echo "failed";
$sql="select page_title from page where page_title like 'Deal%'";
mysql_select_db($ccDBName,$link);
$result=mysql_query($sql,$link);
while($row = mysql_fetch_row($result))
{
	if(!exist("Commentview_".$row[0]))
	{
		$content = '{{#ask: [[Category:Ontology/Comment]]
[[Ontology/CommentRelatedDeal::'.$row[0].']]
| ?Ontology/Content=content
| ?Ontology/MicroblogId=id
| ?Ontology/CommentPublishedTime=publishedTime
| ?Ontology/Source=source
| ?Ontology/Avatar=avatar
| ?Ontology/Score=score
| ?Ontology/Name=name
| format=bicomment
|}}[[Category:CommentSubpage]]';
		echo "import Commentview_".$row[0]."
";
		savePage("Commentview_".$row[0],$content);
	}
}
?>
