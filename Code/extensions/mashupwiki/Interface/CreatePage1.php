<?php
session_start();
$DealAskWhere = $_REQUEST['DealAskWhere'];
$_SESSION["c_DealAskWhere"]=$DealAskWhere;
header("Location:http://10.214.0.147:80/ccwiki/index.php/Special:CreatePage");
?>
