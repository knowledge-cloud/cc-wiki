<script type="text/javascript" src="<?php echo $skinpath?>js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $skinpath?>js/lhgcore.min.js"></script>
<script type="text/javascript" src="<?php echo $skinpath?>js/lhgdialog.js"></script>
<script>var jQuery17=jQuery.noConflict();</script>
<script type="text/javascript" src="<?php echo $skinpath?>js/basic.js"></script>
<script type="text/javascript" src="<?php echo $skinpath?>../../extensions/mashupwiki/js/weibo.js"></script>
<script type="text/javascript" src="<?php echo $skinpath?>../../extensions/mashupwiki/js/bIDetail.js"></script>
<script type="text/javascript" src="<?php echo $skinpath?>../../extensions/mashupwiki/js/bIGallery.js"></script>
<script type="text/javascript" src="<?php echo $skinpath?>../../extensions/mashupwiki/js/mapjs.js"></script>
<script type="text/javascript" src="<?php echo $skinpath?>../../extensions/mashupwiki/js/comment.js"></script>
<script type="text/javascript" src="<?php echo $skinpath?>../../extensions/ccgroup/conf.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.2"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/CityList/1.2/src/CityList_min.js"></script>

<!--[if IE 6]> 
	<script type="text/javascript" src="js/DD_belatedPNG.js" ></script>
	
	<script type="text/javascript">
	DD_belatedPNG.fix('.top h1,.login,.transparent img,.larray img,.rarray img');
	</script>
	<![endif]-->
<script type="text/javascript" src="<?php echo $skinpath?>js/banner.js"></script>
</head>
<body>
<div id="lockwindow"></div>  
<!--header start -->
<div class="header">
<div class="top">
<h1><a href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href']);?>" title="CC维基网">CC维基网</a></h1>
<div class="login">
<?php if($islogin){
    echo '<a href="/ccwiki/index.php/Special:UserInfo" class="white">您好, '.$_COOKIE['ccwikiUserName'].'!</a>';
    echo ' | <a href="'.htmlspecialchars($item['href']).'" class="white" >注销</a>';
}else{
   echo '<a href="/ccwiki/index.php/Special:UserLogin" class="white">登陆</a>';
}?>
丨<a href="/ccwiki/index.php?title=Special:UserLogin&type=signup" class="white">注册</a></div>
</div>

<div class="menu"><ul><?php echo $menuhtml;?>
</ul>
<div class="menur"><a href="/ccwiki/index.php/Special:UserInfo">个人主页</a></div>
</div>
</div>
<!-- main start -->
<div class="main">
