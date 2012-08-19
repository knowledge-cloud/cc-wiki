function postSinaWeibo(){
	var content = $("#weibocontent").val();
	if(content == ""){
		alert("请输入微博内容！");
	}else{
		$.post('http://'+ccHost+':'+ccPort+'/'+ccSite+'/extensions/mashupwiki/Interface/MashupPageInterface.php?a=login',function(data){
			if(data=="false"){
				window.open('http://'+ccHost+':'+ccPort+'/'+ccSite+'/index.php/Special:UserLogin');
			}else{
				var dlg = new J.ui.dialog({
					id: 'PostSinaWeibo',
					title:"发布新浪微博",
					cover:true,
					width:680,
					height:520,
					padding:'2px 1px 25px 10x',
					page: 'http://'+ccHost+':'+ccPort+'/'+ccWiki+'/includes/snsInterface.php?type=sinaweiboUpdate&content='+content
            			});
    				dlg.ShowDialog();
			}
		});
	}
}

function postQQWeibo(){
	var content = $("#weibocontent").val();
	if(content == ""){
		alert("请输入微博内容！");
	}else{
		$.post('http://'+ccHost+':'+ccPort+'/'+ccSite+'/extensions/mashupwiki/Interface/MashupPageInterface.php?a=login',function(data){
			if(data=="false"){
				window.open('http://'+ccHost+':'+ccPort+'/'+ccSite+'/index.php/Special:UserLogin');
			}else{
				var dlg = new J.ui.dialog({
					id: 'PostSinaWeibo',
					title:"发布腾讯微博",
					cover:true,
					width:680,
					height:520,
					padding:'2px 1px 25px 10x',
					page: 'http://'+ccHost+':'+ccPort+'/'+ccWiki+'/includes/snsInterface.php?type=qqweiboUpdate&content='+content
            			});
    				dlg.ShowDialog();
			}
		});
	}
		
}

