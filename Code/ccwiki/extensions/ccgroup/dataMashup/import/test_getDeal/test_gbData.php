<?php
require_once 'getDeal.php';
$result = getDeal('meituan.xml');
//$result = getDeal('ftuan.xml');  //shops is always empty.
//$result = getDeal('lashou.xml');
//$result = getDeal('manzuo.xml');
//$result = getDeal('nuomi.xml');
//$result = getDeal('wowo.xml');
var_dump($result);
?>
