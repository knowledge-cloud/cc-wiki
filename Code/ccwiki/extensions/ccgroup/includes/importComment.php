<?php
include_once (dirname(__FILE__).'/../conf.php');
include_once (dirname(__FILE__).'/import.php');

function importComment($deal,$content,$person,$score)
{
	$t = time();
	$time=date('YmdHis',$t);
	$Id = $deal."_".$person.'_'.$time;
	$page = "Comment_".$person.'_'.$time;
	if(!exist($page)){
		$resultComment="{{ #ask: [[{{PAGENAME}}]]
			| ?Ontology/CommentRelatedDeal
			| ?Ontology/Content
			| ?Ontology/CommentPublished
			| ?Ontology/CommentPublishedTime
			| ?Ontology/Score
			| ?Ontology/CommentId
			| format=template
			| template=ShowComment
			}}";
		$resultComment=$resultComment."[[Ontology/CommentId::".$Id."| ]]";
		$resultComment=$resultComment."[[Ontology/Content::".$content."| ]]";
		$resultComment=$resultComment."[[Ontology/CommentPublished::".$person."| ]]";
		$resultComment=$resultComment."{{:".$person."}}";
		$resultComment=$resultComment."[[Ontology/CommentPublishedTime::".date('Y-m-d H:i:s',$t)."| ]]";
		$resultComment=$resultComment."[[Ontology/Timestamp::".date('Y-m-d H:i:s',time())."| ]]";
		$resultComment=$resultComment."[[Ontology/Score::".$score."| ]]";
		$resultComment=$resultComment."[[Ontology/CommentRelatedDeal::".$deal."| ]]";
		$resultComment=$resultComment."[[Category:Ontology/Comment]]";
		savePage($page,$resultComment);
	}

}
?>
