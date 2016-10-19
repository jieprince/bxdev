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