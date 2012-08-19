<?php
include_once (dirname(__FILE__).'/../../includes/import.php');
//translate the XML file into array: activities
function getActivity($file)
{
}

//import data page of Activity 
function importActivity($file)
{
	$activities = getActivity($file);
	foreach($activities as $activity){
		$result="{{ #ask: [[{{PAGENAME}}]]
                            | ?Ontology/Id
                            | ?Ontology/Description
                            | ?Ontology/Timestamp
                            | ?Ontology/Url
                            | ?Ontology/ValidFrom
                            | ?Ontology/ValidThrough
                            | format=template
                            | template=ShowActivity
                         }}";

		$result=$result."[[Ontology/Id::".$activity['id']."|".$activity['id']." ]]";
		$result=$result."[[Ontology/Description::".$activity['description']."|".$activity['description']." ]]";
		$result=$result."[[Ontology/Url::".$activity['url']."|".$activity['url']." ]]";
		$result=$result."[[Ontology/ValidFrom::".$activity['validFrom']."|".$activity['validFrom']." ]]";
		$result=$result."[[Ontology/ValidThrough::".$activity['validThrough']."|".$activity['validThrough']." ]]";
	
		$result = $result."[[Category:Ontology/Activity]]";
		$timestamp = date('Y-m-d H:i:s',time());
		$result=$result."[[Ontology/Timestamp::".$timestamp."|".$timestamp." ]]";
		
		$activity_name='Activity_'.$activity['shop_page_name'].'_'.$activity['id'];

		if(!exist($activity_name)){
			savePage($activity_name,$result);
		}
	}
}

?>
