// JavaScript Document
jQuery17(function($){
	$(".xlk").click(function(){
		$(".xxlk").show();
	});
	$(".xxlk").hover(function(){
		$(".xxlk").show();	
	},function(){
		$(".xxlk").hide();	
	});
	
	$(".xxlk li").click(function(){
		$(".xlk").html($(this).html());
		$(".xxlk").hide();
	});
	
       
    $("#login_cjy").bind("click",function(){
		var url = mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+'/index.php/Special:UserLogin';
		window.open(url);
        });
/*
	$(".createvote").bind("click",function(){
            created=$.dialog({
                id: 'CreatePage',
                title:"创建新页面",
                content: 'url:'+mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/Interface/CreatePage1.php',
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc',
		close: function(){alert('hello');}
            });
        });
*/ 
        $(".dsq-post-tools li").css("display","block");
	$(".gblist li div:not(.gbprice)").live("click",function(){
            var cindex= $(".gblist").data("currindex");
            if(cindex){
                $(cindex).removeClass("curr");
                //$(cindex).children(".gbpic").children("img").removeClass("img_b3r");
            }else{
                $(".gblist li:first").removeClass("curr");
            }
            ccwiki.loadpage($(this).parent().attr("datapage"))
            $(this).parent().addClass("curr");
            //$(this).children(".gbpic").children("img").addClass("img_b3r"); 
            $(".gblist").data("currindex",$(this).parent());
	});
       
        if($(".gblist li").length>3){
            $pagecount=Math.ceil($(".gblist li").length/3);
            $(".gblist").bind("gofanye", function(event,index){
                //记录当前第几页 方便 上页 下页调用
                $("#gbbox2 .fanye").attr("currindex", index);
                //上下页面显示变更
                if($("#gbbox2 .fanye").children("span").hasClass("noable")){
                    $("#gbbox2 .fanye").children("span").removeClass("noable") 
                }
                if(index==1)
                    $("#gbbox2 .fanye").children("span").first().addClass("noable");
                if(index==$pagecount)
                    $("#gbbox2 .fanye").children("span").last().addClass("noable");
                //当前页面css变更
                $("#gbbox2 .fanye").children("span").removeClass("curr");
                $("#gbbox2 .fanye").children("span").eq(index).addClass("curr");
                //切换数据        
                $(".gblist li").each(function(i){
                    if(i<(index-1)*3 || i>index*3){
                        $(this).hide();
                    }else{
                        $(this).show();
                    }
                    if(i==((index-1)*3)){$(this).children("div:first").click();}
                }); 
            });
            //增加分页HTML显示
            $('<div class="fanye"></div>').attr("currindex",1).appendTo("#gbbox2");
            $('<span>',{
                "class": "back noable",
                text: "上一页",
                click: function(){
                    var $curr=parseInt($(this).parent().attr("currindex"));
                    if($curr>1){
                       $(".gblist").trigger("gofanye",[($curr-1)]); 
                       
                    }
                }
            }).appendTo("#gbbox2 .fanye");
            for(var i=1;i<=$pagecount;i++){
                var classname=i==1?"curr":"";
                $('<span>',{
                    "class": classname,
                    text: i,
                    click: function(){
                        $("gblist").trigger("gofanye",$(this).text()); 
                    }
                }).appendTo("#gbbox2 .fanye");
            }
            $('<span>',{
                    "class": "next",
                    text: "下一页",
                    click: function(){
                        var $curr=parseInt($(this).parent().attr("currindex"));
                        if($curr<$pagecount){
                            $(".gblist").trigger("gofanye",[($curr+1)]); 
                        }
                    }
                }).appendTo("#gbbox2 .fanye");;
        }
	$(".address li").live("click",function(){
             var index=$(".address li").index(this);
             if(smakers && smakers[index]){
                 smap.panTo(smakers[index].getPoint());
             }
             if(makers && makers[index]){
                 map.panTo(makers[index].getPoint());
             }
        });
	$(".tabs li").click(function(){
                var index=$(".tabs li").index(this);
		
                if(index=="1"){
              	 	var $loadbus=false;
                	if($("#bus").attr("init")!="true" && $("#bimapbushtml")){
				if($("#bus").html()=="")
 					$("#bus").html('<div id="bimapbushtml"><div class="busmap" id="container"></div><div class="busfrom">起点： <input type="text" id="from" class="input150"/>&nbsp;&nbsp;终点： <input type="text" id="to" class="input150"/>&nbsp;&nbsp;<input id="findway" name="" type="image" src="'+ccwiki.pagemanage.rootpath+'/skins/ccwiki/images/btn_search.png" /></div><div id="dvPolicy"><input id="policy0" checked="checked" type="radio" name="pickPolicy" class="radiobox"/><label for="policy0">较便捷 </label>&nbsp;&nbsp;<input id="policy1" type="radio" name="pickPolicy" class="radiobox"/><label for="policy1">可换乘 </label>&nbsp;&nbsp;<input id="policy2" type="radio" name="pickPolicy" class="radiobox"/><label for="policy2">少步行 </label></div> <div id="results" ></div></div>');                 		
                  		$loadbus=true;
				if($("#mapscriptitem").html()==null)
					sPoints="";
				else
                        		sPoints=$.parseJSON($("#mapscriptitem").html());
				if($("#mapscriptmapcity").html()==null)
					mapcity="杭州";
				else
                        		mapcity=$("#mapscriptmapcity").html();
        			InitS();
                        }
		        $(".tabs li").eq(1).addClass("curr").siblings().removeClass("curr");
		        $(".tabc").children("i").hide();
	                $(".tabc").children("i").eq(1).show();
                    	return;
                }
		
		if(index=="2"){
			if($("#weibo").html()=='' || $("#weibo #load").html()!=null){
            			ccwiki.pagemanage.checkpage($(".frtitle h2 span").html(),"Weiboview","weibo");
			}
		        $(".tabs li").eq(2).addClass("curr").siblings().removeClass("curr");
		        $(".tabc").children("i").hide();
	                $(".tabc").children("i").eq(2).show();
			return;
		}	
		if(index=="3"){
            		ccwiki.pagemanage.checkpage($(".gblist .curr").attr("datapage"),"Commentview","comment");
		        $(".tabs li").eq(3).addClass("curr").siblings().removeClass("curr");
		        $(".tabc").children("i").hide();
	                $(".tabc").children("i").eq(3).show();
			return;
		}	
		if(index=="4"){
            		ccwiki.pagemanage.checkpage($(".gblist .curr").attr("datapage"),"Jiepangview","jiepang");
		        $(".tabs li").eq(4).addClass("curr").siblings().removeClass("curr");
		        $(".tabc").children("i").hide();
	                $(".tabc").children("i").eq(4).show();
			return;
		}	
		if(index=="5"){
			if($("#taobao").html()=='' || $("#taobao #load").html()!=null){
            			ccwiki.pagemanage.checkpage($(".frtitle h2 span").html(),"Taobaoview","taobao");
			}
		        $(".tabs li").eq(5).addClass("curr").siblings().removeClass("curr");
		        $(".tabc").children("i").hide();
	                $(".tabc").children("i").eq(5).show();
			return;
		}	
	
		$(this).addClass("curr").siblings().removeClass("curr");
		$(this).parent().next(".tabc").children("i").hide();	
		$(this).parent().next(".tabc").children("i").eq($(".tabs li").index(this)).show();	
	});


        $(".xxlk li").click(function(){
                var index=$(".xxlk li").index(this);
                if(index>0)
                 $(".xxlk").parent().children("#id").val(index);	
	});
	ccwiki.init();
        if($(".gblist li").length>0){
            $(".gblist li div:first").click();
        }
});

var loadingalert=null;
var sPoints="",mapcity="";
var lastpage="";
    var created;
    var ccwiki={
        pagemanage:{
            rootpath:'http://127.0.0.1',
            apifile:'/api.php',
            csubpape:'/extensions/mashupwiki/Interface/MashupPageInterface.php',
            checkpage:function(pagename,type,tagid){
                var $this=this;
                $.get(ccwiki.pagemanage.rootpath+"/extensions/mashupwiki/Interface/MashupPageInterface.php"+"?a=exist&pagename="+type + "_" + pagename, function(data){
			if(data=='false'){
				if(type=="Weiboview")
					$("#"+tagid).html("<div id='load'><p>微博数据正在加载中...</p></div>");
				if(type=="Commentview")
					$("#"+tagid).html("<div id='load'><p>评论数据正在加载中...</p></div>");
				if(type=="Jiepangview")
					$("#"+tagid).html("<div id='load'><p>街旁数据正在加载中...</p></div>");
				if(type=="Taobaoview")
					$("#"+tagid).html("<div id='load'><p>淘宝数据正在加载中...</p></div>");
			}
				
			else{
				$("#"+tagid).load(ccwiki.pagemanage.rootpath+"/index.php?title="+encodeURI(type+"_"+pagename)+"&action=render",function(){

				if(type=="Commentview" && $(".commentbox").html()==null){
					$("#comment").html("<div class='commentbox'><textarea id='CommentContent' class='text750'></textarea><p>评分：<select id='CommentScore'><option value='1'>1.0</option><option value='2'>2.0</option><option value='3'>3.0</option><option value='4'>4.0</option><option value='5'>5.0</option></select> <input type='image' src='"+ccwiki.pagemanage.rootpath+"/skins/ccwiki/images/btn_comment.png' onclick='postComment()'/></p></div>");
				}
				if(type=="Weiboview" && $("#weibo .weibobox").html()==null){
					$("#weibo").html("<div class='weibobox'><textarea name='weibocontent' id='weibocontent' class='text2'></textarea><input type='image' class='wbbtn' name='Sina' src='"+ccwiki.pagemanage.rootpath+"/skins/ccwiki/images/btn_sinaweibo.png' onclick='postSinaWeibo()'/> <input type='image' class='wbbtn' name='Tecent' src='"+ccwiki.pagemanage.rootpath+"/skins/ccwiki/images/btn_qqweibo.png' onclick='postQQWeibo()'/></div>");
				}
				if(type=="Jiepangview" && $("#jiepang table").html()==null){
					$("#jiepang").html("<div id='empty'><p>本团购没有对应的街旁数据。</p></div>");
				}
                        	if($this.back!=null)
                            		setTimeout($this.back(),50);
                    		})
			}
					
                });
            },
            createpage:function(pageid,cate,callbackfn){
                var $this=this;
                $.post(this.rootpath+this.csubpape+"?a=createsubpage&format=json&id="+encodeURI(pageid)+"&cate="+encodeURI(cate), function(data){
                    $this.createpagecallback(data,callbackfn);
                });
            },
            createpagecallback:function(json,fn){
                fn(1,json);
            }
        },
        init:function(){
            $.ajaxSetup ({
                cache: false,
                error: function(xhr, ajaxOptions, thrownError){
                }
            });
            this.pagemanage.rootpath=mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath;

            this.dealpartici.setback(function(){
                 $('<input>',{
                    "type": "button",
                    "value":"邀请好友",
                    "class":"invite",
                    click: function(){
                       location.href=mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+'/index.php/Special:Invite?dealid='+encodeURI($("#currdealname").val());
                    }
                }).appendTo("#participheader");
            });
        },
        singlealert:true,
        alert:false,
        dealdetail:(new pageContorl("aboutgb")),
        dealpartici:(new pageContorl("particip")),



        loadpage:function(dealname){
            lastpage=dealname;
            this.alert=false;
            this.dealdetail.getpage(dealname,"detailview");
            this.dealpartici.getpage(dealname,"participview");
        }
    };
   function pageContorl(tagid){
           var _pageid="";
           var _pagename="";
           var _tagid=tagid;
           var $this=this;
           this.getpage=function(pageid,pagename){
                this._tagid=tagid;
                this._pageid=pageid;
                this._pagename=pagename;
                ccwiki.pagemanage.createpage($this._pageid, $this._tagid, $this.checkok);//测试模式下直接重新生成SUBPAGE
            };
            this.setback=function(fn){
                this.back=fn;
            }
            this.checkok=function(f,ok){
                if(ok){
                    $("#"+$this._tagid).load(ccwiki.pagemanage.rootpath+"/index.php?title="+encodeURI($this._pagename+" "+$this._pageid)+"&action=render",function(){
                        if($this.back!=null)
                            setTimeout($this.back(),50);
 //                        setTimeout(closeshow(),50);
                    })
                }else if( f==0){
                    ccwiki.pagemanage.createpage($this._pageid, $this._tagid, $this.checkok);
                }else{
 //                   closeshow()
                    if(ccwiki.singlealert && !ccwiki.alert){
                        ccwiki.alert=true;
                        alert('数据显示页面创建失败');
                    }
                }
            } 
        }
