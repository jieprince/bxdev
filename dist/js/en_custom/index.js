// JavaScript Document
$(document).ready(function(){
    /**幻灯片开始**/
	$(".index_focus").hover(function(){
		$(this).find(".index_focus_pre,.index_focus_next").stop(true, true).fadeTo("show", 1)
	},function(){
		$(this).find(".index_focus_pre,.index_focus_next").fadeOut()
	});
	
	$(".index_focus").slide({
		titCell: ".slide_nav a ",
		mainCell: ".bd ul",
		delayTime: 500,
		interTime: 3500,
		prevCell:".index_focus_pre",
		nextCell:".index_focus_next",
		effect: "fold",
		autoPlay: true,
		trigger: "click",
		startFun:function(i){
			$(".index_focus_info").eq(i).find("h3").css("display","block").fadeTo(1000,1);
			$(".index_focus_info").eq(i).find(".text").css("display","block").fadeTo(1000,1);
		}
	});
	/**幻灯片结束**/
	
	/*首页常见问题选项卡*/
	$('#que_titleList li a').off("click").on("click",function(){
		var var_index = $(this).parent().index();
		$('#que_titleList li a.pactive').removeClass("pactive");
		$(this).addClass("pactive");
		$('#que_content_list div.itemchild').hide();
		$('#que_content_list div.itemchild').eq(var_index).show();
	});
	//合作伙伴
	/* 图片滚动效果 */
	$(".mr_frbox").slide({
		titCell: "",
		mainCell: ".mr_frUl ul",
		autoPage: true,
		effect: "leftLoop",
		autoPlay: true,
		vis: 7
	});
	
	/* 合作伙伴 --鼠标悬停图片效果 */
	$('.og_prev,.og_next').hover(function(){
			$(this).fadeTo('fast',1);
		},function(){
			$(this).fadeTo('fast',0.7);
	})

	/***不需要自动滚动，去掉即可***/
	//time = window.setInterval(function(){
	//	$('.og_next').click();	
	//},5000);
	/***不需要自动滚动，去掉即可***/
	linum = $('.mainlist li').length;//图片数量
	w = linum * 150;//ul宽度
	$('.piclist').css('width', w + 'px');//ul宽度
	$('.swaplist').html($('.mainlist').html());//复制内容
	
	$('.og_next').click(function(){
		
		if($('.swaplist,.mainlist').is(':animated')){
			$('.swaplist,.mainlist').stop(true,true);
		}
		
		if($('.mainlist li').length>7){//多于4张图片
			ml = parseInt($('.mainlist').css('left'));//默认图片ul位置
			sl = parseInt($('.swaplist').css('left'));//交换图片ul位置
			 
			if(ml<=0 && ml>w*-1){//默认图片显示时
				 
				$('.swaplist').css({left: '1000px'});//交换图片放在显示区域右侧
				$('.mainlist').animate({left: ml - 1000 + 'px'},'slow');//默认图片滚动				
				if(ml==(w-1000)*-1){//默认图片最后一屏时
					$('.swaplist').animate({left: '0px'},'slow');//交换图片滚动
				}
			}else{//交换图片显示时
				 
				$('.mainlist').css({left: '1000px'})//默认图片放在显示区域右
				$('.swaplist').animate({left: sl - 1000 + 'px'},'slow');//交换图片滚动
				if((sl>=(w-1000)*-1)){//交换图片最后一屏时
					$('.mainlist').animate({left: '0px'},'slow');//默认图片滚动
				}
			}
		}
	})
	$('.og_prev').click(function(){
		
		if($('.swaplist,.mainlist').is(':animated')){
			$('.swaplist,.mainlist').stop(true,true);
		}
		
		if($('.mainlist li').length>4){
			ml = parseInt($('.mainlist').css('left'));
			sl = parseInt($('.swaplist').css('left'));
			if(ml<=0 && ml>w*-1){
				$('.swaplist').css({left: w * -1 + 'px'});
				$('.mainlist').animate({left: ml + 1000 + 'px'},'slow');				
				if(ml==0){
					$('.swaplist').animate({left: (w - 1000) * -1 + 'px'},'slow');
				}
			}else{
				$('.mainlist').css({left: (w - 1000) * -1 + 'px'});
				$('.swaplist').animate({left: sl + 1000 + 'px'},'slow');
				if(sl==0){
					$('.mainlist').animate({left: '0px'},'slow');
				}
			}
		}
	})    

});