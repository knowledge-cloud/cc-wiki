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
	
         $(".createpage").bind("click",function(){
            created=$.dialog({
                id: 'CreatePage',
                title:"创建新页面",
                content: 'url:'+mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/Interface/CreatePage1.php',
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc'
            });
        });
       
    $("#login_cjy").bind("click",function(){
            created=$.dialog({
                id: 'login',
                title:"登陆选项",
                content: 'url:'+mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/Interface/CreatePage2.php',
                lock:true,
                min:false,
                max:false,
                padding:'2px 1px 25px 10x',
                background:'#ccc'
            });
        });

 
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
		
                if(index=="2"){
              	 	var $loadbus=false;
                	if($("#bus").attr("init")!="true" && $("#bimapbushtml")){
				if($("#bus").html()=="")
 					$("#bus").html('<div id="bimapbushtml"><div class="busmap" id="container"></div><div class="busfrom">起点：<input type="text" id="from" class="input150"/>&nbsp;&nbsp;终点：<input type="text" id="to" class="input150"/>&nbsp;&nbsp;<input id="findway" name="" type="image" src="'+ccwiki.pagemanage.rootpath+'/skins/ccwiki/images/btn_search.png" /></div><div id="dvPolicy"><input id="policy0" checked="true" type="radio" name="pickPolicy" class="radiobox "/><label for="policy0">较便捷</label>&nbsp;&nbsp;<input id="policy1" type="radio" name="pickPolicy" class="radiobox"/><label for="policy1">可换乘</label>&nbsp;&nbsp;<input id="policy2" type="radio" name="pickPolicy" class="radiobox"/><label for="policy2">少步行</label></div> <div id="results" ></div></div>');
                  		$loadbus=true;
				if($("#mapscriptitem").html()==null)
					sPoints="";
				else
                        		sPoints=$.parseJSON($("#mapscriptitem").html());
				if($("#mapscriptmapcity").html()==null)
					mapcity="杭州";
				else
                        		mapcity=$("#mapscriptmapcity").html();
                        }
		        $(".tabs li").eq(2).addClass("curr").siblings().removeClass("curr");
		        $(".tabc").children("i").hide();
	                $(".tabc").children("i").eq(2).show();
                    	return;
                }
		if(index=="4"){
			disqus_url = "http://www.google.com" + window.location.pathname;
			if($("#comment").html()=="")
			{
				$("#comment").append('<div id="disqus_thread"style="width:725px;"></div><div id="div_disqus_url" style="display:none">'+ disqus_url +'</div>');
                    	
                    		(function() {
                        		var dsq = document.createElement("script");
					dsq.type = "text/javascript";
					dsq.async = true;
                        		dsq.src = "http://" + disqus_shortname + ".disqus.com/embed.js";
                        		(document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]).appendChild(dsq);
                    		})();
			}
		        $(".tabs li").eq(4).addClass("curr").siblings().removeClass("curr");
		        $(".tabc").children("i").hide();
	                $(".tabc").children("i").eq(4).show();
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
            checkpage:function(pagename,callbackfn){
                var $this=this;
                $.get(this.rootpath+this.apifile+"?action=query&format=json&titles="+encodeURI(pagename), function(data){
                    $this.checkpagecallback(data,callbackfn);
                });
            },
            checkpagecallback:function(json,fn){
                    if(json.query.pages[-1]){
                        fn(0,false)
                    }else{
                        fn(0,true)
                    }
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


            this.dealdetail.setback(function(){
                try {
/*
 * load the part of comments
 */
                    var tuantitle=$("#currdealname").val();

/*
 * load the subpage of photo
 */
                    $('.photoup').html('<input name="file_upload" type="file" id="file_upload" size="50" maxlength="100" />');
                    $.get(mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+"/extensions/mashupwiki/gb/getpiclist.php", {id: tuantitle},
                    function(data){
                        var  info="";
                        $(data).find('pic').each(function(){
                            var pic = $(this).text();
                            info = info+'<img src="'+mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/gb/uploads/'+tuantitle+'/'+pic+'" alt="" width="100" height="100" />';
                        })
                        $('#facesPhotoWrapper').html(info);								
                    });
                    $('#file_upload').uploadify({
                        'uploader'  : mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/gb/uploadify/uploadify.swf',
                        'script'    : mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/gb/uploadify/uploadify.php',
                        'cancelImg' : mediaWiki.config.values.wgServer+mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/gb/uploadify/cancel.png',
                        'folder'    : mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/gb/uploads',
                        'auto'      : true,
                        'scriptData'  : {'pic':tuantitle}, 
                        'method'         : 'GET', 
                        'onComplete': function(event, queueID, fileObj, response, data) {             //上传完成后的操作
                            var info='';
                            $(response).find('pic').each(function(){
                                var pic = $(this).text();
                                info = info+'<img src="'+mediaWiki.config.values.wgScriptPath+'/extensions/mashupwiki/gb/uploads/'+tuantitle+'/'+pic+'" alt="" width="100" height="100" />';
                                })
                                $('#facesPhotoWrapper').html(info);
                        }
                    });

/*
 * load the part of locations
 */

                }catch(e){
              //      jQuery17.dialog.alert("请清理Wiki缓存");
                }
            });
        },
        singlealert:true,
        alert:false,
        dealdetail:(new pageContorl("aboutgb")),
        dealpartici:(new pageContorl("particip")),
        dealintere:(new pageContorl("interep")),



        loadpage:function(dealname){
            lastpage=dealname;
	    if($('.tabc > p').length > 0)
	    	$('.tabc > p').remove();
	    if($('#bus').length == 0)
            	$('#pic').after('<i id="bus" style="display: none"></i>');
            this.alert=false;
            this.dealdetail.getpage(dealname,"detailview");
            this.dealpartici.getpage(dealname,"participview");
            this.dealintere.getpage(dealname,"interepview");
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
