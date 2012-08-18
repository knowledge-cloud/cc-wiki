function postSinaWeibo(){
	var content = $("#weibocontent").val();
	if(content == ""){
		alert("请输入微博内容！");
	}else{
/*
		created=$.dialog({
                id: 'PostSinaWeibo',
                title:"发布新浪微博",
               content: 'url:'+ 'http://'+ccHost+':'+ccPort+'/'+ccwiki+'/includes/snsInterface.php?type=sinaweiboUpdate&content='+content,
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc'
            });
*/
		window.open('http://'+ccHost+':'+ccPort+'/'+ccWiki+'/includes/snsInterface.php?type=sinaweiboUpdate&content='+content);

	}
}

function postQQWeibo(){
	var content = $("#weibocontent").val();
	if(content == ""){
		alert("请输入微博内容！");
	}else{
		window.open('http://'+ccHost+':'+ccPort+'/'+ccWiki+'/includes/snsInterface.php?type=qqweiboUpdate&content='+content);
/*
		created=$.dialog({
                id: 'PostSinaWeibo',
                title:"发布新浪微博",
               	content: 'url:'+ 'http://'+ccHost+':'+ccPort+'/'+ccwiki+'/includes/snsInterface.php?type=qqweiboUpdate&content='+content,
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc'
            });
*/
	}
		
}

