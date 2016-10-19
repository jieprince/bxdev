(function(lh){
if(!/^http\:\/\/[^\.]+\.(aalib)\.com.cn/.test(lh)){
$sizeIframe({url:"www.aalib.com.cn:82/js/ztxh_iframe.html",href:lh}).init();
}
})(location.href.toString());

function $sizeIframe(opt){
	var option={
		url:"",			//代理页url
		state:"init",
		frame:"",
		interval:"",
		currentHeight:"",
		href:"",
		init:function(){
			option.addFrame();
			option.postResize();
			option.start();
		},
		addFrame:function(){
			var iframe = document.createElement('iframe');
			iframe.height = 0;
			iframe.style.height = '0px';
			iframe.style.width = '0px';
			iframe.style.border = 'none';
			iframe.width = 0;
			iframe.frameborder = 0;
			iframe.border = 0;
			iframe.scrolling = 'no';
			iframe.id='ppSizeFrame';
			option.frame=iframe;
			document.body.appendChild(iframe);
		},
		postResize:function(){
			if(option.state!="init"){
				var height=$getContentHeight();
				if(option.currentHeight!=height){
					option.currentHeight=height;
					option.frame.src=option.url+"?frameHeight="+option.currentHeight+'&href='+encodeURIComponent(option.href);
				}
			}else{
				option.state="ok";
				option.frame.src=option.url+"?frameHeight=500";
			}
		},
		start:function(){
			clearInterval(option.interval);
			option.interval=setInterval(option.postResize,200);
		}
	}
	for(var i in opt){
		option[i]=opt[i];
	}
	return option;
}
function $getContentHeight(){
	var bodyCath=document.body;
	var doeCath=document.compatMode=='BackCompat'?bodyCath:document.documentElement;
	return (window.MessageEvent&&navigator.userAgent.toLowerCase().indexOf('firefox')==-1)?bodyCath.scrollHeight:doeCath.scrollHeight;
}
function $addEvent(obj,type,handle){
	if(!obj||!type||!handle){//参数验证
		return;
	}
	//事件绑定，兼容ff，ie
	if (window.addEventListener){
		obj.addEventListener(type, handle, false);
	}else if (window.attachEvent){
		obj.attachEvent("on"+type, handle);
	}else{
		obj["on" + type] = handle;
	}
};