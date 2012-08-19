<?php
include_once (dirname(__FILE__).'/../../ccgroup/conf.php');
session_start();
$ltype=$_REQUEST["ltype"];
$str="";
switch($ltype){
    case "create":
         $_SESSION["c_pagename"]=$_REQUEST["pagename"];
         $_SESSION["c_category"]=$_REQUEST["category"];
         $_SESSION["c_keywords"]= $_REQUEST["keywords"];
	
	 header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/ccgroup/includes/interface.php?type=create");
         break;
    case "part":
         $_SESSION["deal"]=$_REQUEST["deal"];
         $_SESSION["mode"]="part";
         $_SESSION["url"]=$_REQUEST["url"];
	 header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/ccgroup/includes/interface.php?type=participate");
         break;
    case "support":
         $_SESSION["deal"]=$_REQUEST["deal"];
         $_SESSION["mode"]="support";
	 header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/ccgroup/includes/interface.php?type=support");
         break;
    case "unsupport":
         $_SESSION["deal"]=$_REQUEST["deal"];
         $_SESSION["mode"]="unsupport";
	 header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/ccgroup/includes/interface.php?type=unsupport");
         break;
    case "comment":
         $_SESSION["deal"]=$_REQUEST["deal"];
         $_SESSION["score"]=$_REQUEST["score"];
         $_SESSION["content"]=$_REQUEST["content"];
         $_SESSION["mode"]="comment";
	 header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/ccgroup/includes/interface.php?type=comment");
         break;
}
?>

