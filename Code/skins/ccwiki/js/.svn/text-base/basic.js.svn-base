// JavaScript Document
$(function(){
	
	
	
	
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
	
	$(".gblist li").live("mouseover",function(){
		$(this).addClass("curr");
		$(this).children(".gbpic").children("img").addClass("img_b3r");
	});
	
	$(".gblist li").live("mouseout",function(){
		$(this).removeClass("curr");
		$(this).children(".gbpic").children("img").removeClass("img_b3r");
	});
	
	$(".tabs li").click(function(){
		$(this).addClass("curr").siblings().removeClass("curr");
		$(this).parent().next(".tabc").children("li").hide();	
		$(this).parent().next(".tabc").children("li").eq($(".tabs li").index(this)).show();	
	});
	
	$(".mapmore").live("click",function(){
		$(".tabs li").eq(2).addClass("curr").siblings().removeClass("curr");
		$(".tabc li").hide();
		$(".tabc li").eq(2).show();
	});
	
});