<?php
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/import.php');

function importWeibos($title,$userpage)
{
	$source="tencent,sina";
	$weibodatas=getWeibos($source,$title);
	foreach ($weibodatas as $weibodata ){
		if(!exist("Person_".$weibodata['person']['sns_id'])){
	
                	$resultPerson=$askPerson;
			$resultPerson=$resultPerson."[[Ontology 0/sns_id::".$weibodata['person']['sns_id']."| ]]";
			$resultPerson=$resultPerson."[[Ontology 0/name::".$weibodata['person']['name']."| ]]";
			$resultPerson=$resultPerson."[[Ontology 0/avatar::".$weibodata['person']['avatar']."| ]]";
			$resultPerson=$resultPerson."[[Ontology 0/source::".$weibodata['person']['source']."| ]]";
			$resultPerson=$resultPerson."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
			$resultPerson=$resultPerson."[[Category:Ontology 0/Person]]"; 
			savePage("person_".$weibodata['person']['sns_id'],$resultPerson);
		}
	
	    	$resultWeibo="{{ #ask: [[{{PAGENAME}}]]
                        | ?Ontology 0/avatar
                        | ?Ontology 0/published
                        | ?Ontology 0/status
                        | ?Ontology 0/mid
                        | ?Ontology 0/published_time
                        | ?Ontology 0/source
                        | format=template
                        | template=ShowMicroblog
                      }}";
	    	$resultWeibo=$resultWeibo."[[Ontology 0/mid::".$weibodata['mid']."| ]]";
	    	$resultWeibo=$resultWeibo."[[Ontology 0/status::".substr($weibodata['status'],0,100)."| ]]";
	    	$resultWeibo=$resultWeibo."[[Ontology 0/published_time::".$weibodata['published_time']."| ]]";
	    	$resultWeibo=$resultWeibo."[[Ontology 0/source::".$weibodata['source']."| ]]";
	    	$resultWeibo=$resultWeibo."[[Ontology 0/published::Person_".$weibodata['person']['sns_id']."| ]]";
	    	$resultWeibo=$resultWeibo."[[Ontology 0/timestamp::".date('Y-m-d H:i:s',time())."| ]]";
	    	$resultWeibo=$resultWeibo."[[Ontology 0/userpage::".$userpage."| ]]";
	    	$resultWeibo=$resultWeibo."{{:Person_".$weibodata['person']['sns_id']."}}";
	    	$resultWeibo=$resultWeibo."[[Category:Ontology 0/Microblog]]";
	
		savePage("Microblog_".$weibodata['mid'],$resultWeibo);

	}
}
?>
