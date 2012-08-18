function postComment(){
	var content = $("#CommentContent").val();	
	var score = $("#CommentScore").val();
	var deal = $("#currdealname").val();
	if(content=='' || deal==''){
		alert('请输入评论内容!');
	}
	else{
	$.post('http://'+ccHost+':'+ccPort+'/'+ccSite+'/extensions/mashupwiki/Interface/CreatePage2.php?ltype=comment&deal='+deal+'&score='+score+'&content='+content,function(data){
		if(data=='true'){
			alert('评论成功，感谢您的参与！');
			ccwiki.pagemanage.checkpage($(".gblist .curr").attr("datapage"),"Commentview","comment");
		        $(".tabs li").eq(3).addClass("curr").siblings().removeClass("curr");
		        $(".tabc").children("i").hide();
	                $(".tabc").children("i").eq(3).show();
		}else{
			alert('对不起，评论失败！您可能需要重新绑定SNS帐号。');	
			window.open('http://'+ccHost+':'+ccPort+'/'+ccSite+'/index.php/Special:Mapping');
		}
	});
	}
}
