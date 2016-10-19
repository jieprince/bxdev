/*华安商场商铺室内装修建工一切险*/
$().ready(function(){
	setApp_Cookie();
	showSelect_factor_list('factor_cost_select','factor_cost_select_P',true);
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

function showSelect_factor_list(domid,domPid,type){
	var str_data = $('#'+domPid).data('value');
	var initflag = true;
	if(type){
		initflag = false;
	}
	showSelect(initflag,domid,'factor_name',str_data,showSelect_factor_dates_callback);
}

function showSelect_factor_dates_callback(){
	var factor_code_peroid = $('#factor_cost_select').data('factor_id');
	var prodcutPeriod = $('#factor_cost_select').data('factor_name');
	var prodcutMarkPrice = $('#factor_cost_select').data('factor_price');
	$('#factor_code_peroid').val(factor_code_peroid);
	$('#prodcutPeriod').val(prodcutPeriod);
	$('#prodcutMarkPrice').val(prodcutMarkPrice);
	$('#productPrem_price').text(prodcutMarkPrice);
}
 






