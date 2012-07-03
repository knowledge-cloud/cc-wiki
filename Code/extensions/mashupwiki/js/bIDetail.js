var disqus_shortname = "zxlzr"; 
var disqus_developer = 1;
var disqus_url=http="http://www.google.com";
jQuery17(function($){
    $(".gblist li .qukankan").live("click",function(){
        var $deal=$(this).parent().children(".hcurrname").val();
        var $url=$(this).parent().children(".hcurrurl").val();
        part=$.dialog({
                id: 'part',
                title:"抢购",
                content: 'url:'+mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+
                    '/extensions/mashupwiki/Interface/CreatePage2.php?ltype=part&deal='+encodeURI($deal)+
                    '&url='+encodeURI($url),
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc',
                close:function(){ccwiki.dealpartici.getpage($deal,"participview");}
            });
    });
     $(".gblist li .toupiao").live("click",function(){
        var $deal=$(this).parent().children(".hcurrname").val();
        var $url=$(this).parent().children(".hcurrurl").val();
        part=$.dialog({
                id: 'part',
                title:"投票",
                content: 'url:'+mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+
                    '/extensions/mashupwiki/Interface/CreatePage2.php?ltype=inter&deal='+encodeURI($deal)+
                    '&url='+encodeURI($url),
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc',
                close:function(){ccwiki.dealintere.getpage($deal,"interepview");}
            });
    });
    $("#partclick").live("click",function(){
            part=$.dialog({
                id: 'part',
                title:"抢购",
                content: 'url:'+mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+
                    '/extensions/mashupwiki/Interface/CreatePage2.php?ltype=part&deal='+encodeURI($("#currdealname").val())+
                    '&url='+encodeURI($("#currdealhref").val()),
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc',
                close:function(){ccwiki.dealpartici.getpage($("#currdealname").val(),"participview");}
            });
        });
        $("#intclick").live("click",function(){
            part=$.dialog({
                id: 'part',
                title:"投票",
                content: 'url:'+mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+
                    '/extensions/mashupwiki/Interface/CreatePage2.php?ltype=inter&deal='+encodeURI($("#currdealname").val())+
                    '&url='+encodeURI($("#currdealhref").val()),
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc',
                 close:function(){ccwiki.dealintere.getpage($("#currdealname").val(),"interepview");}
            });
        });
});
