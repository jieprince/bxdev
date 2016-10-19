/*华安商场商铺室内装修建工一切险*/
$().ready(function(){
	setApp_Cookie();
});

//显示产品类型下拉列表
function showSelect_product_list(domid,domPid,type){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(type){
		initflag = false;
	}
	showSelect(initflag,domid,'product_name',str_data,showSelect_product_dates_callback);
}

//选择产品类型(不联动)
function showSelect_product_dates_callback(){
	var str_data = $('#project_dates_select_P').data('sumvalue');
	var product_id = $('#project_cost_select').data("product_id");
	window.location.href = "goods.php?id="+$('#goods_id_temp').val()+"&ins_product_id="+product_id; 
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