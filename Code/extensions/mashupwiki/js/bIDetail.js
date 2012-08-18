var disqus_shortname = "zxlzr"; 
var disqus_developer = 1;
var disqus_url=http="http://www.google.com";
jQuery17(function($){
    $(".gblist li .qukankan").live("click",function(){
        var $deal=$(this).parent().children(".hcurrname").val();
        var $url=$(this).parent().children(".hcurrurl").val();
	window.open(encodeURI($url));
    });

    $("#partclick").live("click",function(){
            	var $url=mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/Interface/CreatePage2.php?ltype=part&deal='+encodeURI($("#currdealname").val())+'&url='+encodeURI($("#currdealhref").val());
		window.open(encodeURI($url));	
        });

        $("#supportclick").live("click",function(){
            part=$.dialog({
                id: 'part',
                title:"顶一下",
                content: 'url:'+mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+
                    '/extensions/mashupwiki/Interface/CreatePage2.php?ltype=support&deal='+encodeURI($("#currdealname").val())+
                    '&url='+encodeURI($("#currdealhref").val()),
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc',
   //              close:function(){ccwiki.dealintere.getpage($("#currdealname").val(),"interepview");}
                close:function(){}
            });
        });
        $("#unsupportclick").live("click",function(){
            part=$.dialog({
                id: 'part',
                title:"踩一下",
                content: 'url:'+mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+
                    '/extensions/mashupwiki/Interface/CreatePage2.php?ltype=unsupport&deal='+encodeURI($("#currdealname").val())+
                    '&url='+encodeURI($("#currdealhref").val()),
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc',
//                 close:function(){ccwiki.dealintere.getpage($("#currdealname").val(),"interepview");}
                close:function(){}
            });
        });
});
