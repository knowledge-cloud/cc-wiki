<?php
session_start();
require_once dirname( __FILE__ ) .'/../Include/WikiPageImport.php';
require_once dirname( __FILE__ ) .'/../Include/MashPageBaseEdit.php';
/*
 * CCwiki对外接口
 */
$action=$_REQUEST["a"];
$back=false;
$wkIm=new WikiPageImport();
switch ($action) {
    case "exist":
	if($wkIm->checkPage($_REQUEST["pagename"])==-1)	
           	$back=false;
	else
		$back=true;
	echo json_encode($back);
        break;
    case "checkpage":
        if($wkIm->checkPage($_POST["pagename"])==-1)
           $back=true;
        break;

    case "savePage":
        $pagename=$_SESSION["c_pagename"];
	$varrr=new MashPageBaseEdit($pagename);
	if($varrr->createPage($_SESSION["c_category"], $_SESSION["c_keywords"], $_SESSION["c_createdatapage"],$_SESSION["c_DealAskWhere"])){
            	$back=true;
         	header("Location:../../../index.php/".$pagename);
        }
	echo json_encode($back);
        break;

    case "createsubpage":
        $varrr=new MashPageBaseEdit($_REQUEST["id"]);
	if($_REQUEST["cate"]=="aboutgb")
	{
		if($varrr->queryDetailSub())
		{
			echo "Page exist";
			$back=true;
			break;
		}
	} 
        if($varrr->createSub($_REQUEST["cate"]))
            $back=true;
	echo json_encode($back);
        break;
    case "purgePage";
        $varrr=new WikiPageImport();
        $mode=$_SESSION["mode"];
        $deal=$_SESSION["deal"];
        switch($mode){
            case "part":
		echo 'deal: '.$deal.'<br/>';
                $varrr->purgePage("participview Deal ".$deal);
        	header("Location:".$_SESSION["url"]);
                break;
            case "support":
//                $varrr->purgePage("interepview Deal ".$deal);
		  echo "顶成功!感谢您的投票!"."</br>";
                break;
            case "unsupport":
//                $varrr->purgePage("interepview Deal ".$deal);
		echo "踩成功!感谢您的投票!"."</br>";
        }
        break;        
}
