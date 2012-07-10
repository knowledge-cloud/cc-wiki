<?php
include_once(dirname(__FILE__).'/getWeibos.php');
$source='tencet,sina';
$d=getWeibos('杭州旅游',$source);
var_dump($d);
?>
