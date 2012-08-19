<?php
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/import.php');
include_once (dirname(__FILE__).'/getWeibos.php');

function importWeibos($title,$userpage)
{
	global $askPerson;
	$weibodatas=getWeibos($title);
	foreach ($weibodatas as $weibodata ){
		if(!exist("Person_".$weibodata['person']['sns_id'])){
                	$resultPerson=$askPerson;
			$resultPerson=$resultPerson."[[Ontology/PersonId::".$weibodata['person']['sns_id']."| ]]";
			$resultPerson=$resultPerson."[[Ontology/Name::".$weibodata['person']['name']."| ]]";
			$resultPerson=$resultPerson."[[Ontology/Avatar::".$weibodata['person']['avatar']."| ]]";
			$resultPerson=$resultPerson."[[Ontology/PersonSource::".$weibodata['person']['source']."| ]]";
			$resultPerson=$resultPerson."[[Ontology/Timestamp::".date('Y-m-d H:i:s',time())."| ]]";
			$resultPerson=$resultPerson."[[Category:Ontology/Person]]"; 
			savePage("person_".$weibodata['person']['sns_id'],$resultPerson);
		}
	
		if(!exist("Microblog_".$weibodata['mid'])){
			$resultWeibo="{{ #ask: [[{{PAGENAME}}]]
				| ?Ontology/Avatar
				| ?Ontology/MicroblogPublished
				| ?Ontology/Status
				| ?Ontology/MicroblogId
				| ?Ontology/MicroblogPublishedTime
				| ?Ontology/MicroblogSource
				| format=template
				| template=ShowMicroblog
				}}";
			$resultWeibo=$resultWeibo."[[Ontology/MicroblogId::".$weibodata['mid']."| ]]";
			$resultWeibo=$resultWeibo."[[Ontology/Status::".$weibodata['status']."| ]]";
			$resultWeibo=$resultWeibo."[[Ontology/MicroblogPublishedTime::".$weibodata['published_time']."| ]]";
			$resultWeibo=$resultWeibo."[[Ontology/MicroblogSource::".$weibodata['source']."| ]]";
			$resultWeibo=$resultWeibo."[[Ontology/MicroblogPublished::Person_".$weibodata['person']['sns_id']."| ]]";
			$resultWeibo=$resultWeibo."[[Ontology/Timestamp::".date('Y-m-d H:i:s',time())."| ]]";
			$resultWeibo=$resultWeibo."[[Ontology/MicroblogRelatedPage::".$userpage."| ]]";
			$resultWeibo=$resultWeibo."{{:Person_".$weibodata['person']['sns_id']."}}";
			$resultWeibo=$resultWeibo."[[Category:Ontology/Microblog]]";
			savePage("Microblog_".$weibodata['mid'],$resultWeibo);
		}else{
			$resultWeibo="[[Ontology/MicroblogRelatedPage::".$userpage."| ]]";
			savePage("Microblog_".$weibodata['mid'],$resultWeibo);
		}

	}
}
?>
