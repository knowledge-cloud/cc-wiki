<?php
include_once (dirname(__FILE__).'/snsMapping2.php');
/*
echo 'test1: '+getToken('test_mw','renren')+'
';
updateToken('test_mw','renren','sssssssssssssssssss');
echo 'test1: '+getToken('test_mw','renren')+'
';
updateToken('test_mw','renren','ttttttttttttttttt');
echo 'test1: '+getToken('test_mw','renren')+'
';
*/
//setDefaultSNS('test_mw','renren');
/*
if(setDefaultSNS('test_mw','kaixin')==FALSE)
	echo 'FALSE';
else
	echo 'TRUE';
*/
//updateToken('test_mw','qqweibo','ttttttttttttttttt','ken');
echo getDefaultSns('test_mw');
?>
