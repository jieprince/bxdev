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
    /*	$('#que_titleList li a').off("click").on("click",function(){
     var var_index = $(this).parent().index();
     $('#que_titleList li a.pactive').removeClass("pactive");
     $(this).addClass("pactive");
     $('#que_content_list div.itemchild').hide();
     $('#que_content_list div.itemchild').eq(var_index).show();
     });*/

});


/*合作伙伴图片滚动*/
$(function(){
    var page = 1;
    var i = 1; //每版放4个图片
    var aaa=$(".v_content ul li").length
    var bbb=aaa*(1140)
    $("._content_list").width(bbb)
    //向后 按钮
    $("span.next").click(function(){    //绑定click事件
        var $parent = $(this).parents("div.v_show");//根据当前点击元素获取到父元素
        var $v_show = $parent.find("div.v_content_list"); //寻找到“视频内容展示区域”
        var $v_content = $parent.find("div.v_content"); //寻找到“视频内容展示区域”外围的DIV元素
        var v_width = $v_content.width() ;
        var len = $v_show.find("li").length;
        var page_count = Math.ceil(len / i) ;   //只要不是整数，就往大的方向取最小的整数
        if( !$v_show.is(":animated") ){    //判断“视频内容展示区域”是否正在处于动画
            if( page == page_count ){  //已经到最后一个版面了,如果再向后，必须跳转到第一个版面。
                $v_show.animate({ left : '0px'}, "slow"); //通过改变left值，跳转到第一个版面
                page = 1;
            }else{
                $v_show.animate({ left : '-='+v_width }, "slow");  //通过改变left值，达到每次换一个版面
                page++;
            }
            $parent.find("span").eq((page-1)).addClass("current").siblings().removeClass("current");
        }
    });
    //往前 按钮
    $("span.prev").click(function(){
        var $parent = $(this).parents("div.v_show");//根据当前点击元素获取到父元素
        var $v_show = $parent.find("div.v_content_list"); //寻找到“视频内容展示区域”
        var $v_content = $parent.find("div.v_content"); //寻找到“视频内容展示区域”外围的DIV元素
        var v_width = $v_content.width();
        var len = $v_show.find("li").length;
        var page_count = Math.ceil(len / i) ;   //只要不是整数，就往大的方向取最小的整数
        if( !$v_show.is(":animated") ){    //判断“视频内容展示区域”是否正在处于动画
            if( page == 1 ){  //已经到第一个版面了,如果再向前，必须跳转到最后一个版面。
                $v_show.animate({ left : '-='+v_width*(page_count-1) }, "slow");
                page = page_count;
            }else{
                $v_show.animate({ left : '+='+v_width }, "slow");
                page--;
            }
            $parent.find("span").eq((page-1)).addClass("current").siblings().removeClass("current");
        }
    });
});
/*合作伙伴图片滚动*/
/*首页经过出现*/
$(function(){
    $(".t-m-list-tt").hover(function(){
            $(this).children(".t-m-list-t-s").hide()
            $(this).children(".t-m-list-t-h").show()
        },function(){
            $(this).children(".t-m-list-t-s").show()
            $(this).children(".t-m-list-t-h").hide()
        }
    )

})
