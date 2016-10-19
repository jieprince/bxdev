/*（手机端）华安学平险*/
var temp_html = {}; 
var age_min_temp_date = 0;
var age_max_temp_date = 0;
var opt={};
$(document).ready( function(){
	  
	  //加载被保险人年龄范围
	  age_min_temp_date = parseInt($('#age_min').val());
	  age_max_temp_date = parseInt($('#age_max').val());
	  
	  var app_useridTemp = $.cookie("app_userid");
	  var app_platformIdTemp = $.cookie("app_platformId");
	  if(app_useridTemp!="" && app_platformIdTemp!="" ){ 
	  	$('#head_top').hide();
		$('#userid_app').val(app_useridTemp);
		$('#platformId').val(app_platformIdTemp); 
	  }
	  if($('#step').val()==1){
		  temp_html = {'jigoucontent':$('#applicant_jigou').html(),'gerencontent':$('#applicant_geren').html()};
		  $('#applicant_jigou').html('');
		  initSet();
	  }
});


//初始化
function initSet(){
 	 $('#group_name').bind("blur",function(){
			
			var ischecked_f = $('#checkbox_bbxr input').prop('checked'); 
			if(ischecked_f){
				$('#assured_fullname').val($(this).val());
			}
			$('#title_fp').val($(this).val());
		 
	 });
	 $('#amount').bind("blur",function(){
		if(!$('#amount').prop('readonly')){
			setPrices();
		}
	 });
	 
	 $('#checkbox_bbxr input').iCheck({
		checkboxClass: 'icheckbox_square-red',
    	radioClass: 'iradio_square-red',
		increaseArea: '20%' ,// optional
		 
	});
	$('#checkbox_bbxr input').on('ifChecked', function(event){
		$('#assured_fullname').val($('#group_name').val());
	});
	$('#checkbox_bbxr input').on('ifUnchecked', function(event){
		$('#assured_fullname').val('');
	});
	
	
	
	//加载投保日期
	var currdate = serverDate();
	var currdateNow = serverNowDate();
	 
	var start_date_01 = currdateNow.getFullYear()+'-'+(currdateNow.getMonth()+1)+'-'+(currdateNow.getDate());
	var start_date_00 = timeStamp2String(start_date_01,2,'day'); 
	 
	
	
	opt.date = {preset : 'date'};
	opt.default = {
		theme: 'android-ics light', //皮肤样式
		display: 'modal', //显示方式 
		mode: 'scroller', //日期选择模式
		lang:'zh',
		startYear:currdateNow.getFullYear(),//开始年份
		startMonth:(currdateNow.getMonth()),
		startDay:currdateNow.getDate(),
		endYear:currdateNow.getFullYear()+20, //结束年份
		endMonth:(currdateNow.getMonth()),
		endDay:currdateNow.getDate(),
		onSelect: function(dateText, inst) { 
		   $('#effectdate').val(dateText) ;
	   } 
	};
	
	 
	
	$("#startDate").val(start_date_00.substring(0,10)+' 00:00:00');
	 
	$("#saildate").val('').scroller('destroy').scroller($.extend(opt['date'], opt['default']));
	 
	
	showSelect_classtype('text_select_classtype','text_select_classtype_P',true);
	
	getitemcode('text_select_big_itemcode_P','small');
	getmain_clause(); 
}



function getmain_clause(){ 
    $.ajax({
        type : "post",
        url : "../oop/business/get_CPIC_CARGO_Data.php",
        data : {'op':'get_cargo_internal_main_clause'},
        success : function(html){
            var html_option = []; 
			  
			if($('#product_attribute_code').val()=='ZH'){
				var tempitemcontent = '';
				$($.parseJSON(html)).each(function(i, n){
					if($('#product_attribute_code').val()==n.code){
						 
						html_option.push({'mainitemcode_id':n.code,'mainitemcode_name':n.name,'mainitemcode_content':n.content});
						tempitemcontent = n.content;
					}
				});
				
				$('#itemcontent').val(tempitemcontent);
				if($('#product_attribute_code').val()=='C090026M'){
					$('#c90_gonglu').hide();
				}else{
					$('#c90_gonglu').hide();
				}
				
			
			}else{
				$($.parseJSON(html)).each(function(i, n){
					html_option.push({'mainitemcode_id':n.code,'mainitemcode_name':n.name,'mainitemcode_content':n.content});
				});
			}
            $('#text_select_mainitemcode_P').data('value',JSON.stringify(html_option));
			 
        },
        error : function(data){
        // alert("网络传输异常，无法获取地区信息");
        }
    });
}





//获取服务器时间并且加上间隔时间
function serverDate(){
	var start_day = $("#start_day").val();
	if(start_day==0){
		start_day = 5;
	}
	var date = $("#server_time").val();
	date = parseInt(date);
	var bombay = date + (3600 * 24)*start_day;
	var time_s = new Date(bombay*1000);
	 
	return time_s;
}


//获取服务器时间不加间隔时间
function serverNowDate(){
	 
	var date = $("#server_time").val();
	date = parseInt(date);
	 
	var time_s = new Date(date*1000);
	 
	return time_s;
}

//遍历险种
function showSelect_classtype(domid,domPid,layertype){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	showSelect(initflag,domid,'classtype_name',str_data);
}

function showSelect_big_itemcode(domid,domPid,layertype){
	
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	showSelect(initflag,domid,'itemcode_name',str_data,showSelect_big_itemcodecallback);
	
}

function showSelect_big_itemcodecallback(){
	$('#text_select_small_itemcode').text('');
	$('#text_select_small_itemcode').data('itemcode_id','');
	$('#text_select_small_itemcode').data('itemcode_name','');
	getitemcode('text_select_small_itemcode_P',$('#text_select_big_itemcode').data('itemcode_id'));	
}

function showSelect_mainitemcode (domid,domPid,layertype){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(layertype){ //判定 存在 不弹出选择框
		initflag = false;
	}
	showSelect(initflag,domid,'mainitemcode_name',str_data,showSelect_mainitemcodecallback);
}

function showSelect_mainitemcodecallback(){
	$('#itemcontent').html($('#text_select_mainitemcode').data('mainitemcode_content'));
	$('#itemcontent_input').val($('#text_select_mainitemcode').data('mainitemcode_content'));
}

//货物类型
function getitemcode(dom_id,str_num){
	var name_main = '-1';
    if(str_num!="small"){
		name_main = str_num;
	}
    $.ajax({
        type : "post",
        url : "../oop/business/get_CPIC_CARGO_Data.php",
        data : {'op':'get_cargo_type','name_main':name_main},
        success : function(html){
            var html_option = []; 
			 
			$($.parseJSON(html)).each(function(i, n) {
				
				if(str_num=="small"){
					
					html_option.push({'itemcode_id':n.name_one,'itemcode_name':n.name_one});
				}else{
					html_option.push({'itemcode_id':n.code,'itemcode_name':n.name_two});
					 
				}
            });
			
            $('#'+dom_id).data('value',JSON.stringify(html_option));
			 
        },
        error : function(data){
        // alert("网络传输异常，无法获取地区信息");
        }
    });
}

 

//计算价格
function setPrices(){
	var amount_T = $('#amount').val();	
	var rate_T = $('#rate').val();	
	 
	var pr_zong = 0;
	if(!isNaN(amount_T) && !isNaN(rate_T)){
		 
		pr_zong = accMul(amount_T,rate_T);
		pr_zong = parseFloat(pr_zong).toFixed(2);
		 
	}else{
		pr_zong = 0;
	} 
	//计算价格
	
	$('#premium').val(pr_zong);
	$('#test_price_Temp').text(pr_zong);
	$('#totalModalPremium').val(pr_zong);
 
}
 

//投保填写页面验证
function submit_checked(){
	 var flag_submit = true;
	 
	 if(!checked_str_length('group_name',100)){		 
		showMessages('投保人名称100字符之内','group_name'); 
		flag_submit = false;
		return false;
	 }else if(!checked_str_length('assured_fullname',100)){		 
		showMessages('被保险人名称100字符之内','assured_fullname'); 
		flag_submit = false;
		return false;
	 }
	if(!$('#text_select_classtype').data('classtype_id') || $('#text_select_classtype').data('classtype_id')==""){
		 
		showMessages('险种没有选择！','text_select_classtype_P'); 
		flag_submit = false;
		return false;
	}
	$('#classtype').val($('#text_select_classtype').data('classtype_id'));
	 
	if(!checked_str_length('mark',500)){
		 
		showMessages('标记/发票号码/运单号500字符之内！','mark'); 
		flag_submit = false;
		return false;
	}else if(!checked_str_length('item',500)){
		showMessages('货物名称500字符之内！','item'); 
		flag_submit = false; 
		return false;
	}
	if(!$('#text_select_big_itemcode').data('itemcode_id') || $('#text_select_big_itemcode').data('itemcode_id')==""){
		 
		showMessages('货物类型(大类)没有选择！','text_select_big_itemcode_P'); 
		flag_submit = false;
		return false;
	}
	$('#big_itemcode').val($('#text_select_big_itemcode').data('itemcode_id'));
	
	if(!$('#text_select_small_itemcode').data('itemcode_id') || $('#text_select_small_itemcode').data('itemcode_id')==""){
		 
		showMessages('货物类型(小类)没有选择！','text_select_small_itemcode_P'); 
		flag_submit = false;
		return false;
	}
	$('#itemcode').val($('#text_select_small_itemcode').data('itemcode_id'));
	
	if(!checked_str_length('quantity',500)){
		showMessages('包装及数量500字符之内！','quantity'); 
		flag_submit = false;
		return false;
	}
	if(!$('#text_select_packcode').data('packcode_id') || $('#text_select_packcode').data('packcode_id')==""){
		 
		showMessages('货物包装没有选择！','text_select_packcode_P'); 
		flag_submit = false;
		return false;
	}
	$('#packcode').val($('#text_select_packcode').data('packcode_id'));
	if(!$('#text_select_kind').data('kind_id') || $('#text_select_kind').data('kind_id')==""){
		 
		showMessages('运输方式没有选择！','text_select_kind_P'); 
		flag_submit = false;
		return false;
	}
	$('#kind').val($('#text_select_kind').data('kind_id'));
	if(!checked_str_length('kindname',30)){
		 
		showMessages('运输工具30字符之内！','kindname'); 
		flag_submit = false;
		return false;
	}else if(!checked_str_length('voyno',30)){
		 
		showMessages('航次/车牌号30字符之内！','voyno'); 
		flag_submit = false;
		return false;
	}else if(!checked_str_length('startport',80)){
		 
		showMessages('运输路线开始地80字符之内！','startport'); 
		flag_submit = false;
		return false;
	}else if(!checked_str_length('endport',80)){
		 
		showMessages('运输路线目的地80字符之内！','endport'); 
		flag_submit = false;
		return false;	
	
	}else if(!$('#saildate').val()){
		 
		showMessages('起运日期不能为空！','saildate'); 
		flag_submit = false;
		return false;
	}
	if(!$('#text_select_mainitemcode').data('mainitemcode_id') || $('#text_select_mainitemcode').data('mainitemcode_id')==""){
		 
		showMessages('主要条款没有选择！','text_select_mainitemcode_P'); 
		flag_submit = false;
		return false;
	}
	$('#mainitemcode').val($('#text_select_mainitemcode').data('mainitemcode_id'));
	 
	if(!isCheckedNull('amount')){
		if($('#product_attribute_code').val()=='ZH'){
			if($('#amount').val()>1000000){
				 
				showMessages('保险金额小于等于1000000元！','amount'); 
				flag_submit = false;
				return false;	
			}	
		}else{
			 
			showMessages('保险金额只能为数字！','amount'); 
			flag_submit = false;
			return false;	
		}
			
	}else if(!isCheckedNull('rate')){
		showMessages('费率有误！','rate'); 
		flag_submit = false; 
		return false;	
	}else if(!$('#premium').val() || $('#premium').val()<=0){
		showMessages('保费必须大于0！','premium'); 
		flag_submit = false;
		return false;	
	}
	 
	if(flag_submit){
		$('#submit_link').click();	 
	}
	 
	 
}

//投保确认页面验证提交
function steptwo_queck(){
	if(!ydtychecked()){
	   showMessages('保险条款、投保须知、投保声明 三者有没有选中的项!','bxtk_01');
	   return false;
    }
	layer.open({
		content: '你是想确认提交订单吗？',
		btn: ['确认', '取消'],
		shadeClose: false,
		yes: function(index){
			layer.close(index);
			return true;
		}, no: function(index){
			layer.close(index);
			return false;
		}
	});
}


function checked_codebirsex_test(code_str,br_str,str_sex,sex_dominput,type,showflag){
	var res_str = checked_codebirsex(code_str,br_str,str_sex,type,showflag);
	if(res_str){
		$('#'+sex_dominput).val($('#'+str_sex).data('sex_id'));
	}else{
		showMessages('身份证号码不正确!',code_str);	
		$('#'+sex_dominput).val('');
		$('#'+str_sex).text('');
		$('#'+br_str).val('');
	}
}