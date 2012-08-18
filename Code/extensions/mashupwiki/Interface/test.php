<?php
include_once (dirname(__FILE__).'/../../ccgroup/conf.php');
session_start();
$_SESSION["deal"]="Deal 9rfavrpp";
$_SESSION["score"]="4.2";
$_SESSION["content"]="Test Good";
$_SESSION["mode"]="comment";
header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/extensions/ccgroup/includes/interface.php?type=comment");
?>
