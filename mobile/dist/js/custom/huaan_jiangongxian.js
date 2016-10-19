/*华安商场商铺室内装修建工一切险*/
$().ready(function() {
    showSelect_project_cost('project_cost_select','project_cost_select_P',true);
	setApp_Cookie();
});

//遍历显示建工险工程造价内容
function showSelect_project_cost(domid,domPid,type){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(type){
		initflag = false;
	}
	showSelect(initflag,domid,'factor_name',str_data,showSelect_project_dates_callback);
}

//更新工期数据
function showSelect_project_dates_callback(){
	var str_data = $('#project_dates_select_P').data('sumvalue');
	var product_influencingfactor_id = $('#project_cost_select').data("product_influencingfactor_id");
	var option_child = [];
		$(str_data).each(function(i_p, n_p) {
			
			if(product_influencingfactor_id==n_p.id){
				$(n_p.period).each(function(i_c, n_c) {
					option_child.push({'c_id':n_c.c_id,'c_code':n_c.c_code,'c_name':n_c.c_name}); 
				});	
			}
		});
		$('#project_dates_select_P').data('value',JSON.stringify(option_child));
		
		showSelect(false,'project_dates_select','c_name',option_child,showSelect_project_baofei_callback);
		//加载下一项的下拉列表
	//showSelect('project_dates_select','c_name',str_data);
}



//遍历显示建工险工程工期数据
function showSelect_project_dates(domid,domPid){
	var str_data = $.parseJSON($('#'+domPid).data('value'));
	showSelect(true,domid,'c_name',str_data,showSelect_project_baofei_callback);
}

//第三方责任保费列表  ajax
function showSelect_project_baofei_callback(){
	var datajson = {'cost_id':$('#project_cost_select').data('product_influencingfactor_id'),'period_id':$('#project_dates_select').data('c_id'),'product_id':$('#product_id').val(),'type':'mobile'};
	var ajax_res = Ajax_option("../oop/business/get_SINO_Data.php",datajson,'POST',false);
	if(ajax_res!=null&&ajax_res!=""){
		var data_json = ajax_res[0];
		if(data_json.duty_price_list.length>0){
			var duty_json = [];
			$(data_json.duty_price_list).each(function(i_d, n_d) {
				duty_json.push({'product_duty_price_id':n_d.product_duty_price_id,'amount':n_d.amount,'premium':n_d.premium,'amount_premium':n_d.amount+'/'+n_d.premium});
  
			});
			$('#project_baofei_select_P').data('value',JSON.stringify(duty_json));
			showSelect(false,'project_baofei_select','amount_premium',duty_json,setprices);
		}
	} 
	
}


//遍历显示建工险工程第三方责任保费
function showSelect_project_baofei(domid,domPid){
	var str_data = $.parseJSON($('#'+domPid).data('value'));
	showSelect(true,domid,'amount_premium',str_data,setprices);
}


//计算价格
function setprices(){
	var price_tempsT = $('#project_baofei_select').data('premium');
	if(price_tempsT!=""&&price_tempsT!=null){
		$('#productPrem_price').text(price_tempsT);	
	}else{
		$('#productPrem_price').text('0.00');
	}
	
	var temp_cost_code = $('#project_cost_select').data("factor_code");
	var temp_period_fator_code = $('#project_dates_select').data("c_code");
	
	$('#cost_name').val($('#project_cost_select').text());
	$('#cost_factor_code').val(temp_cost_code);
	$('#period_name').val($('#project_dates_select').text());
	$('#period_fator_code').val(temp_period_fator_code);
	$('#price_temp').val(price_tempsT);
	var duty_price_ids = $('#project_baofei_select').data('product_duty_price_id');
	$('#duty_price_ids').val(duty_price_ids);
	var duty_beizhu = $('#project_baofei_select').data('amount').split(',');
	var duty_beizhu_html = '&nbsp;备注：<br>&nbsp;&nbsp;每次事故赔偿限额：'+duty_beizhu[1]+'万元<br>&nbsp;&nbsp;每次事故每人赔偿限额：'+duty_beizhu[2]+'万元<br>&nbsp;&nbsp;累计赔偿限额：'+duty_beizhu[0]+'万元';
	$('#duty_beizhu').html(duty_beizhu_html);
}






