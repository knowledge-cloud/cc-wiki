<?php
include_once (dirname(__FILE__).'/importWeibos.php');
include_once (dirname(__FILE__).'/importTaobaoDeal.php');
include_once (dirname(__FILE__) .'/../../mashupwiki/Include/MashPageBaseEdit.php');
include_once (dirname(__FILE__).'/import.php');
		$keywords=$_REQUEST['keywords'];
		$userPage=$_REQUEST['userPage'];
		importWeibos($keywords,$userPage);
		if(exist('Weiboview_'.$userPage))
			deletePage('Weiboview_'.$userPage);
		$varrr=new MashPageBaseEdit($userPage);
		$varrr->createSub("weibo");

		importTaobaoDeal($keywords,$userPage);
		if(exist('Taobaoview_'.$userPage))
			deletePage('Taobaoview_'.$userPage);
		$varrr=new MashPageBaseEdit($userPage);
		$varrr->createSub("taobao");
?>
