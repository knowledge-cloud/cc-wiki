<?php
include_once(dirname(__FILE__).'/../../ccgroup/conf.php');
session_start();
$DealAskWhere = $_REQUEST['DealAskWhere'];
$_SESSION["c_DealAskWhere"]=$DealAskWhere;
header("Location:http://".$ccHost.":".$ccPort."/".$ccSite."/index.php/Special:CreatePage");
?>
