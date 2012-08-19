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
	if(!exist("Jiepangview_".$row[0]))
	{
		$content = '{{#ask: [[Category:Ontology/Shop]]
[[Ontology/ShopRelatedDeal::'.$row[0].']]
| ?Ontology/ShopName=name
| ?Ontology/Address=address
| ?Ontology/City=city
| ?Ontology/PhotoUrl=url
| format=bijiepang
| source=wiki
| merge=false
|}}[[Category:JiepangSubpage]]';
		echo "import Jiepangview_".$row[0]."
";
		savePage("Jiepangview_".$row[0],$content);
	}
}
?>
