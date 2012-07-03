<script type="text/javascript" src="<?php echo $skinpath?>js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $skinpath?>js/lhgdialog.js"></script>
<script>var jQuery17=jQuery.noConflict();</script>
<script type="text/javascript" src="<?php echo $skinpath?>js/basic.js"></script>
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
    echo '<a href="'.htmlspecialchars($item['href']).'" class="white" >注销</a>';
}else{
 //  echo '<a href="'.htmlspecialchars($item['href']).'" class="white" >登录</a>';
   echo '<a href="javascript:;" class="white" id="login_cjy" >登录</a>';
}?>
丨<a href="#" class="white">帮助中心</a></div>
</div>

<div class="menu"><ul><?php echo $menuhtml;?>
</ul>
<div class="menur"><a href="javascript:;" class="createpage">新建页面</a></div>
</div>
</div>
<!-- main start -->
<div class="main">
