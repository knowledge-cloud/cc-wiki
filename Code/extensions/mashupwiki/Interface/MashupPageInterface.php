<?php
session_start();
require_once dirname( __FILE__ ) .'/../Include/WikiPageImport.php';
require_once dirname( __FILE__ ) .'/../Include/MashPageBaseEdit.php';
/*
 * CCwiki对外接口
 * @author maguibo
 */
$action=$_REQUEST["a"];
$wkIm=new WikiPageImport();
$back=false;
switch ($action) {
    case "checkpage":
        if($wkIm->checkPage($_POST["pagename"])==-1)
           $back=true;
        break;
//Called when the the user logged in sucessfully
    case "savePage":
        $varrr=new MashPageBaseEdit($_SESSION["c_pagename"]);
        $pagename=$_SESSION["c_pagename"];
        if($varrr->createPage($_SESSION["c_cateory"], $_SESSION["c_keywords"], $_SESSION["c_createdatapage"]))
	{
             $back=true;
             header("Location:../../../index.php/Special:ConfigureGB?pagename=".rawurlencode($pagename));
             exit();
	}
        break;
//Called when the page is configured
    case "configPage":
        //var_dump($_REQUEST);
        $varrr=new MashPageBaseEdit($_REQUEST["pagename"]);
        $varrr->configPage("Deal",$_REQUEST["DealAskWhere"]);
        if($varrr->configFinish())
	{
	    header("Location:../../../index.php/".$_REQUEST["pagename"]);
            $back=true;
        }
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
        break;
    case "purgePage";
        $varrr=new WikiPageImport();
        $mode=$_SESSION["mode"];
        $deal=$_SESSION["deal"];
        switch($mode){
            case "part":
                $varrr->purgePage("participview Deal ".$deal);
                break;
            case "inter":
                $varrr->purgePage("interepview Deal ".$deal);
                break;
        }
        header("Location:".$_SESSION["url"]);
        break;        
}
echo json_encode($back);
