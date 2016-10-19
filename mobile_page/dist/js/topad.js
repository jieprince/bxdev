// JScript 文件
function TopAd()
{
    var strTopAd="";
	var str_flash = '<a href="mobile_page/active_pingan_ranqi.html"  target="_blank"  ><img src="mobile_page/images/huodong/ranqi/pc_small.png"></a>';
	//定义小图片内容
    var topSmallBanner="<div style=\"margin: 0 auto; background:url(mobile_page/images/smail_ranqi.png) bottom\">"+str_flash+"</div>";
	
	//判断在那些页面上显示大图变小图效果，非这些地址只显示小图（或FLASH）
    if (location == "http://www.ebaoins.cn" || true){
		//定义大图内容
        strTopAd="<div id=adimage style=\"margin: 0 auto; background:url(mobile_page/images/huodong/ranqi/banner_bg.jpg)\" align=center>"+
                    "<div id=adBig><a href=\"mobile_page/active_pingan_ranqi.html\" " + 
                    "target=_blank><img title=E保险网春节大礼包 "+
                    "src=\"mobile_page/images/huodong/ranqi/banner.jpg\" " +
                    "border=0></A></div>"+
                    "<div id=adSmall style=\"display: none\">";
        //strTopAd+=  topFlash;     
		strTopAd+=  topSmallBanner;  
        strTopAd+=  "</div></div>";
    }
    else
    {
        //strTopAd+=topFlash;
		strTopAd+=  topSmallBanner;  
    }
    strTopAd+="<div style=\"height:0px; clear:both;overflow:hidden\"></div>";
    return strTopAd;
}
document.write(TopAd());
$(function(){
	//过两秒显示 showImage(); 内容
    setTimeout("showImage();",2000);
    //alert(location);
});
function showImage()
{
    $("#adBig").slideUp(1000,function(){$("#adSmall").slideDown(1000);});
}
 
