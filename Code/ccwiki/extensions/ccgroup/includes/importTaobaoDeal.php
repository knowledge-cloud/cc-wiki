<?php
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/import.php');
include_once (dirname(__FILE__).'/getTaobaoDeal.php');

function importTaobaoDeal($title,$userpage)
{
	$deals=getTaobaoDeal($title);
	foreach ($deals as $deal ){
	
	    	$resultTaobao="{{ #ask: [[{{PAGENAME}}]]
                        | ?Ontology/TaobaoDealId
                        | ?Ontology/Volume
                        | ?Ontology/TaobaoRelatedDeal
                        | ?Ontology/Price
                        | ?Ontology/TaobaoDealPicture
                        | ?Ontology/TaobaoDealUrl
                        | ?Ontology/TaobaoDealTitle
                        | ?Ontology/TaobaoDealScore
                        | format=template
                        | template=ShowTaobaoDeal
                      }}";
	    	$resultTaobao=$resultTaobao."[[Ontology/TaobaoDealId::".$deal['TaobaoDealId']."| ]]";
	    	$resultTaobao=$resultTaobao."[[Ontology/Volume::".$deal['Volume']."| ]]";
	    	$resultTaobao=$resultTaobao."[[Ontology/TaobaoRelatedPage::".$userpage."| ]]";
	    	$resultTaobao=$resultTaobao."[[Ontology/Price::".$deal['Price']."| ]]";
	    	$resultTaobao=$resultTaobao."[[Ontology/TaobaoDealPicture::".$deal['TaobaoDealPicture']."| ]]";
	    	$resultTaobao=$resultTaobao."[[Ontology/TaobaoDealUrl::".$deal['TaobaoDealUrl']."| ]]";
	    	$resultTaobao=$resultTaobao."[[Ontology/TaobaoDealTitle::".$deal['TaobaoDealTitle']."| ]]";
	    	$resultTaobao=$resultTaobao."[[Ontology/TaobaoDealScore::".$deal['TaobaoDealScore']."| ]]";
	    	$resultTaobao=$resultTaobao."[[Ontology/Timestamp::".date('Y-m-d H:i:s',time())."| ]]";
	    	$resultTaobao=$resultTaobao."[[Category:Ontology/TaobaoDeal]]";
		echo 'save TaobaoDeal_'.$deal['TaobaoDealId'].'
';
		savePage("TaobaoDeal_".$deal['TaobaoDealId'],$resultTaobao);
//		echo 'content: '.$resultTaobao.'
//';

	}
}
?>
