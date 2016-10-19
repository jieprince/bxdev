//公用js文件
var l_i = 0;

//url中数据的提取
function getUrlParam(name){
		var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if (r!=null) return unescape(r[2]); return null;	
}

/*弹出选择列表
 *layer_type true 有弹出框   false 没有弹出框 false是初始化使用
 *domid string   赋值dom节点
 *selkey string  显示内容的赋值字段名称  
 *datajsontemp object  数组
*/
function showSelect(layer_type,domid,selkey,datajsontemp,callback){
	//var datajsontemp = $.parseJSON($(ef).data('value'));
	//datajsontemp = [{'name':'华安商场商铺室内装修建工一切险'}];
	if(typeof(datajsontemp)!='object' && typeof(datajsontemp)!='OBJECT'){
		datajsontemp = $.parseJSON(datajsontemp);
	}
	if(layer_type){
		var html_temp = '<ul class="list-group f-mb0" style="height:260px; overflow:auto;">';
		
		//onClick="Selected(\''+selkey+'\',\''+domid+'\',\''+JSON.stringify(n)+'\')"
		$.each(datajsontemp,function(i,n){
			html_temp += '<a class="list-group-item f-ml0 f-mr0 onclick_a" href="javascript:void(0)" data-value="'+i+'" ><span class="">'+n[selkey]+'</span></a>';
		});
		html_temp += '</ul>';
		l_i = layer.open({
			type:1,
			content: html_temp,
			style: 'width:92%;border:1px;background:none',
			success: function(elem){
				 $('.onclick_a').unbind("click");
				 $('.onclick_a').bind("click",function(){
					 Selected(selkey,domid,datajsontemp[$(this).data('value')],callback);
					 
				 });
			} 
		});
	}else{
		Selected(selkey,domid,datajsontemp[0],callback,true);
	}
}

/*选中替换并且赋值data-* 为以后查询使用
 *v_cid string   赋值dom节点
 *selkey string  显示内容的赋值字段名称  
 *setData object  数组  选中的一列数组
 *inittype  为初始化使用  true初始化  false
 *callback  回调函数   为联动操作使用
*/
function Selected(selkey,v_cid,setData,callback,inittype){
	if(!inittype){
		layer.close(l_i);
	}
	var json_data = setData;
	$.each(json_data,function(i,n){
		$('#'+v_cid).data(i,n);
	});
	
	$('#'+v_cid).text(json_data[selkey]);
	if(callback){
		callback();	
	}
}


//模块显示隐藏 type存在的话 是投保三步页面的调用
function show_hide(ef,type){
	var this_ul = $(ef).next("ul");
	 
	var dis_flag = this_ul.css("display");
	var close_img = 'dist/images/drop_down_card_btn_close.png';
	var open_img = 'dist/images/drop_down_card_btn_open.png';
	if(type){
		close_img = '../mobile/dist/images/drop_down_card_btn_close.png';
		open_img = '../mobile/dist/images/drop_down_card_btn_open.png';
	}
	if(dis_flag=='block'){
		this_ul.hide();
		$(ef).children().children("img").attr("src",close_img);
	}else{
		this_ul.show();
		$(ef).children().children("img").attr("src",open_img);
	}
}



//ajax数据加载 
function Ajax_option(url_type,Qjson,type,async_type){
	var dataajax = "";
	 
	$.ajax({
		url: url_type,
		type: type,
		async:async_type,
		dataType: "json",
		data:Qjson,
		success: function (result) {
			if(result!=null){
				dataajax = result;
			}else{
				dataajax = [];
			}
		},
		error: function (result) {
			
		}
	});
	return dataajax;
}


//页面跳转
function redirect(ef){
	document.location.href=$(ef).data('value');
}

//设置Cookie  uid   platformId
function setApp_Cookie(){
	var app_userid = $('#userid_app').val();
	var app_platformId = $('#platformId').val();
	 
	if(app_userid && app_platformId){
		 
		$.cookie("app_userid", app_userid, { path: '/', expires: 7 });  
		$.cookie("app_platformId", app_platformId, { path: '/', expires: 7 }); 
		 
	}else{
		 
		$.cookie("app_userid", "", { path: '/', expires: 7 });  
		$.cookie("app_platformId", "", { path: '/', expires: 7 }); 
	} 
}


//浮点数乘法
function accMul(arg1,arg2){
	var m=0,ms=0,mz=0,s1=arg1.toString(),s2=arg2.toString();
	try{m=s1.split(".")[1].length}catch(e){};
	try{ms=s2.split(".")[1].length}catch(e){};
	if(m>ms){
		mz = m;
	}else{
		mz = ms;
	}
	return parseFloat(parseInt(Number(s1.replace(".",""))*Number(s2.replace(".","")))/Math.pow(10,(m+ms))).toFixed(mz);
}

//浮点数减法
function accsubtract(arg1,arg2){
	var r1,r2,m,mz=0;
	 
	try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0};
	try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0};
	m=Math.pow(10,Math.max(r1,r2));
	if(r1>r2){
		mz = r1;
	}else{
		mz = r2;
	}
	return parseFloat(parseInt((arg1*m-arg2*m))/m).toFixed(mz);
} 

//浮点数相加
function accAdd(arg1,arg2){
	var r1,r2,m,mz=0;
	 
	try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0};
	try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0};
	m=Math.pow(10,Math.max(r1,r2));
	if(r1>r2){
		mz = r1;
	}else{
		mz = r2;
	}
	return parseFloat(parseInt((arg1*m+arg2*m))/m).toFixed(mz);
} 

//除法
function accDiv(arg1,arg2){
	var t1=0,t2=0,r1,r2;
	try{t1=arg1.toString().split(".")[1].length}catch(e){}
	try{t2=arg2.toString().split(".")[1].length}catch(e){}
	with(Math){
		r1=Number(arg1.toString().replace(".",""))
		r2=Number(arg2.toString().replace(".",""))
		return (r1/r2)*pow(10,t2-t1);
	}
} 


 