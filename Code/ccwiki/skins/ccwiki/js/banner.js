// JavaScript Document
jQuery17(function(){
	$(".banner").hover(function(){
		$("div[class$=array]").show();
	},function(){
		$("div[class$=array]").hide();
	})
	
	$(".fl div #toc").attr('style','display:none');	
	
	var imgurl=new Array('banner01.jpg','banner02.jpg','banner03.jpg');
	var aurl=new Array(ccHost+':'+ccPort+'/'+ccSite+'/index.php/买车团购',ccHost+':'+ccPort+'/'+ccSite+'/index.php/杭州酒店团购',ccHost+':'+ccPort+'/'+ccSite+'/index.php/杭州美食团购');
	$(".transparent a").attr("href","http://"+aurl[0]);
	$("#banner").attr("src","../images/"+imgurl[0]);
	
	Basic();
	function Basic(){		
		Auto();
		$(".banner").hover(Stop,Auto);
	};
	var x=0;
	
	function fmove(){
		x++;
		(x>2)?x=0:x=x;		
		$("#banner").fadeOut(500,function(){$(this).attr("src","../images/"+imgurl[x])});
		$(".transparent a").attr("href","http://"+aurl[x]);
		$("#banner").fadeIn(500);
		$("#banner").fadeIn(2000)	
		
		
	}
	//左移
	$(".larray").click(function(){
		x--;	
		(x<0)?x=2:x=x;			
		$("#banner").fadeOut(500,function(){$(this).attr("src","../images/"+imgurl[x])});
		$(".transparent a").attr("href","http://"+aurl[x]);
		$("#banner").fadeIn(500);		
			
	});
	//右移
	$(".rarray").click(function(){	
		x++;	
		(x>2)?x=0:x=x;	
		$("#banner").fadeOut(500,function(){$(this).attr("src","../images/"+imgurl[x])});
		$(".transparent a").attr("href","http://"+aurl[x]);
		$("#banner").fadeIn(500);					
			
	});
	//自动
	function Auto(){
		intervalId=window.setInterval(fmove,3000);
	};
	//停止
	function Stop(){
		window.clearInterval(intervalId);
	}
        $(".morelink").wrapInner(function(){
            return "<a class=\"white\" href='"+mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+
                "/index.php/"+$(this).attr("id")+"'></a>";
        })

});
