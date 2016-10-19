//url中数据的提取
$().ready(function() {
	var urltemp = document.location.href ;
	var uid  = getUrlParam('uid');
	$('#reg_html').attr("href",'../mobile_page/reg.html?uid='+uid);
	$("#code").qrcode({ 
		render: "table", //table方式 
		width: 200, //宽度 
		height:200, //高度 
		text: urltemp //任意内容
	});
	$("#carousel-generic").swipe({

	  swipeLeft: function() { $(this).carousel('next'); },
	
	  swipeRight: function() { $(this).carousel('prev'); },
	
	 });
});
 