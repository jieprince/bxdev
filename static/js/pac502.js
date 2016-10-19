$(document).ready( function(){
    //getInsurData();//加载主险和附属险的保额和保费
	
    getInsurset_init();
});

function getInsurset_init(){
	
	var career_list = [1,4,5];
	var datajson_All_list = {};
    $(career_list).each(function(i, n) {
       datajson_All_list[i] = getInsurAjax_Data(n);
    });	
	
	setData_One(datajson_All_list);
}
function getInsurAjax_Data(career){
	var datajson = {'attribute_id':$('#attribute_id').val(),'period_factor_code':$('#period').val(),'career_factor_code':career};
     var datajson_All = [];
	 $.ajax({
        type : "post",
        url : "oop/business/getPAC502Data.php",
        data : datajson,
		async:false,
        success : function(data){
           if(data!=null&&data!=""){
				var res = $.parseJSON(data); 
				datajson_All = res ;
		   }
            
           
        },
        error : function(data){
        
        }
    });
	return datajson_All;
	
	
}


function setData_One(data_json){
	var addtional_html ='<tr><th colspan="3" align="center">被保险人职业类别</th><th align="center">1~3类</th><th align="center">4类</th><th align="center" style="display:none">5~6类</th></tr><tr><th colspan="3"  align="center">被保险人个数(不能少于5人)</th><th  align="center"><input type="text" id="person_num_01" style="width:30px;padding:2px;border:1px #ccc solid;">人</th><th  align="center" ><input type="text" id="person_num_02" style="width:30px;padding:2px;border:1px #ccc solid;">人</th><th align="center" style="display:none"><input type="text" id="person_num_03" style="width:30px;padding:2px;border:1px #ccc solid; display:none;" >未销售</th></tr><tr><th align="center">险种</th><th align="center">责任</th><th align="center">保额(元)</th><th align="center">保费(元)</th><th align="center">保费(元)</th><th align="center" style="display:none">保费(元)</th></tr>'; 
	var value_text_11 = 0;
	var value_text_22 = 0;
	var value_text_33 = 0; 
	$.each(data_json[0],function(i,n){
		var product_name = '';
		var product_duty_name = ''; 
		var pr_duty_list = [];
		var product_id = 0;
		var main_product_name = '';
		var main_product_duty_name = '';
		var value_text_1 = 0;
		var value_text_2 = 0;
		var value_text_3 = 0; 
			 
		$.each(n.product_duty_list,function(i_p,n_p){
			var s_str = '';
			 
			if(n.product_duty_list.length==1){
				var input_checked = '<input type="checkbox" value="'+n_p.duty_code+'" name="addtional" />';
				
				addtional_html += '<tr><td style="padding-left:0px;">'+n.product_name+'</td><td>'+n_p.duty_name+s_str+'</td><td>&nbsp;<select id="'+n_p.duty_code+'"  name="addtional_'+n_p.duty_code+'">';
				if(n.product_type!='main'){addtional_html += '<option value="0">不投保</option>';	}
				$.each(n_p.duty_price_list,function(i_A,n_A){
					var value_1 = setData_text(data_json[1],n_A.amount,n_p.duty_code);
					var value_2 = setData_text(data_json[2],n_A.amount,n_p.duty_code);
					if(n_p.duty_code=='JD06' && n_A.amount==27000){
						
					}else{
					if(i_A==0){
						if(n.product_type=='main'){
							value_text_1 = n_A.premium;
							value_text_2 = value_1.premium;
							value_text_3 = value_2.premium;
							value_text_11 = n_A.premium;
							value_text_22 = value_1.premium;
							value_text_33 = value_2.premium;
						}
						addtional_html += '<option value="'+n_A.product_duty_price_id+'" data-value="'+n_A.premium+'" data-pduty_id_1="'+value_1.product_duty_price+'" data-pduty_id_2="'+value_2.product_duty_price+'"  data-value="'+n_A.premium+'" data-value_1="'+value_1.premium+'" data-value_2="'+value_2.premium+'">'+number2million(n_A.amount)+'</option>';	
					}else{
						addtional_html += '<option value="'+n_A.product_duty_price_id+'" data-value="'+n_A.premium+'" data-pduty_id_1="'+value_1.product_duty_price+'" data-pduty_id_2="'+value_2.product_duty_price+'"  data-value="'+n_A.premium+'" data-value_1="'+value_1.premium+'" data-value_2="'+value_2.premium+'">'+number2million(n_A.amount)+'</option>';	
					}
					}
					
				});
				addtional_html += '</select></td><td class="value_text_1" align="center">'+value_text_1+'</td><td class="value_text_2" align="center">'+value_text_2+'</td><td class="value_text_3" align="center" style="display:none">'+value_text_3+'</td></tr>';	
			}else{
				if(i_p==0){
					addtional_html += '<tr><td rowspan="'+n.product_duty_list.length+'" style="padding-left:0px;">'+n.product_name+'</td><td>'+n_p.duty_name+s_str+'</td><td><select id="'+n_p.duty_code+'"  name="addtional_'+n_p.duty_code+'">';
					addtional_html += '<option value="0">不投保</option>';
					$.each(n_p.duty_price_list,function(i_A,n_A){
						var value_1 = setData_text(data_json[1],n_A.amount,n_p.duty_code);
						var value_2 = setData_text(data_json[2],n_A.amount,n_p.duty_code);
						if(n_p.duty_code=='JD06' && n_A.amount==27000){
						
						}else{
						if(i_A==0){
							 
							addtional_html += '<option value="'+n_A.product_duty_price_id+'" data-value="'+n_A.premium+'" data-pduty_id_1="'+value_1.product_duty_price+'" data-pduty_id_2="'+value_2.product_duty_price+'"  data-value="'+n_A.premium+'" data-value_1="'+value_1.premium+'" data-value_2="'+value_2.premium+'">'+number2million(n_A.amount)+'</option>';	
						}else{
							addtional_html += '<option value="'+n_A.product_duty_price_id+'" data-value="'+n_A.premium+'" data-pduty_id_1="'+value_1.product_duty_price+'" data-pduty_id_2="'+value_2.product_duty_price+'"  data-value="'+n_A.premium+'" data-value_1="'+value_1.premium+'" data-value_2="'+value_2.premium+'">'+number2million(n_A.amount)+'</option>';	
						}
						}
						
					});
					addtional_html += '</select></td><td class="value_text_1"  align="center">'+value_text_1+'</td><td class="value_text_2" align="center">'+value_text_2+'</td><td class="value_text_3" align="center" style="display:none">'+value_text_3+'</td></tr>';		
				}else{
					addtional_html += '<tr><td>'+n_p.duty_name+'</td><td><select id="'+n_p.duty_code+'"  name="addtional_'+n_p.duty_code+'">';
					addtional_html += '<option value="0">不投保</option>';
					$.each(n_p.duty_price_list,function(i_A,n_A){
						var value_1 = setData_text(data_json[1],n_A.amount,n_p.duty_code);
						var value_2 = setData_text(data_json[2],n_A.amount,n_p.duty_code);
						if(n_p.duty_code=='JD06' && n_A.amount==27000){
						
						}else{
							if(i_A==0){
							 
								addtional_html += '<option value="'+n_A.product_duty_price_id+'" data-pduty_id_1="'+value_1.product_duty_price+'" data-pduty_id_2="'+value_2.product_duty_price+'"  data-value="'+n_A.premium+'" data-value_1="'+value_1.premium+'" data-value_2="'+value_2.premium+'">'+number2million(n_A.amount)+'</option>';	
							}else{
								addtional_html += '<option value="'+n_A.product_duty_price_id+'" data-value="'+n_A.premium+'" data-pduty_id_1="'+value_1.product_duty_price+'" data-pduty_id_2="'+value_2.product_duty_price+'"  data-value="'+n_A.premium+'" data-value_1="'+value_1.premium+'" data-value_2="'+value_2.premium+'">'+number2million(n_A.amount)+'</option>';	
							}	
						}
						
					});
					addtional_html += '</select></td><td class="value_text_1" align="center">'+value_text_1+'</td><td class="value_text_2"  align="center">'+value_text_2+'</td><td class="value_text_3"  align="center" style="display:none">'+value_text_3+'</td></tr>';		
				}
				
			}
			
		});
		
		//$('#addtional_list').html(addtional_html);
			
		 
	});	
	addtional_html +='<tr><th colspan="3">方案总保费/人：</th><td class="value_text_1" align="center">'+value_text_11+'</td><td class="value_text_2" align="center">'+value_text_22+'</td><td class="value_text_3" align="center" style="display:none">'+value_text_33+'</td></tr>';
	$('#addtional_list').html(addtional_html);
	
	//setprices(); //计算价格
	
	$('#addtional_list input[type="text"]').unbind("blur");
	$('#addtional_list input[type="text"]').bind("blur",function(){
		if(/^\d+$/.test($(this).val())&&$(this).val()>0){
			$('#addtional_list input[name="addtional"]').each(function(i, n) {
				if($(this).prop("checked")==false){
					$(this).parent().parent().children("td.value_text_1").text(0);
					$(this).parent().parent().children("td.value_text_2").text(0);
					$(this).parent().parent().children("td.value_text_3").text(0);
				}else{
					if(/^\d+$/.test($('#person_num_01').val())&&$('#person_num_01').val()>0){
						$(this).parent().parent().children("td.value_text_1").text($('#'+$(this).val()).children("option:selected").data("value"));
					}
					if(/^\d+$/.test($('#person_num_02').val())&&$('#person_num_02').val()>0){
						$(this).parent().parent().children("td.value_text_2").text($('#'+$(this).val()).children("option:selected").data("value_1"));
					}
					if(/^\d+$/.test($('#person_num_03').val())&&$('#person_num_03').val()>0){
						$(this).parent().parent().children("td.value_text_3").text($('#'+$(this).val()).children("option:selected").data("value_2"));
					}
					 
					
				} 
			});
			
			setprices(); //计算价格	
		}else{
			 
			setprices(); //计算价格	
			
			
		}
	});
	 
	$('#addtional_list select').unbind("change");
	$('#addtional_list select').bind("change",function(){
		if($(this).val()!=0){
			$(this).parent().parent().children("td.value_text_1").text($(this).children("option:selected").data("value"));
			$(this).parent().parent().children("td.value_text_2").text($(this).children("option:selected").data("value_1"));
			$(this).parent().parent().children("td.value_text_3").text($(this).children("option:selected").data("value_2"));
		}else{
			$(this).parent().parent().children("td.value_text_1").text(0);
			$(this).parent().parent().children("td.value_text_2").text(0);
			$(this).parent().parent().children("td.value_text_3").text(0);
		}
		setprices(); //计算价格
	});
}

function setData_text(data_json,amount_T,duty_code_T){
	var product_duty_price_id_premium_Temp = '';
	$.each(data_json,function(i,n){
		
		  
		$.each(n.product_duty_list,function(i_p,n_p){
			if(n_p.duty_code==duty_code_T){
				$.each(n_p.duty_price_list,function(i_A,n_A){
					if(n_A.amount==amount_T){
						 
						product_duty_price_id_premium_Temp = {'product_duty_price':n_A.product_duty_price_id,'premium':n_A.premium};
					}
					
				});
			}
			
		});
			 
	});	
	
	return product_duty_price_id_premium_Temp;
		 
}

function getInsurData(){
	var datajson = {'attribute_id':$('#attribute_id').val(),'period_factor_code':$('#period').val(),'career_factor_code':$('#career').val()};
     $.ajax({
        type : "post",
        url : "oop/business/getPAC502Data.php",
        data : datajson,
        success : function(data){
           if(data!=null&&data!=""){
				var res = $.parseJSON(data); 
				var addtional_html =''; 
				$.each(res,function(i,n){
					var product_name = '';
					var product_duty_name = ''; 
					var pr_duty_list = [];
					var product_id = 0;
                    var main_product_name = '';
                    var main_product_duty_name = '';
					if(n.product_type=='main'){
						product_id = n.product_id;
						var optionn = '';
						pr_duty_list = n.product_duty_list;
						main_product_name = n.product_name;
						main_product_duty_name = pr_duty_list[0].duty_name;
						
						$.each(pr_duty_list[0].duty_price_list,function(i_M,n_M){
							if(i_M==0){
								optionn += '<option value="'+n_M.product_duty_price_id+'" data-value="'+n_M.premium+'">'+number2million(n_M.amount)+'/'+n_M.premium+'</option>';
							}else{
								optionn += '<option value="'+n_M.product_duty_price_id+'" data-value="'+n_M.premium+'">'+number2million(n_M.amount)+'/'+n_M.premium+'</option>';	
							}
							
						});
						$('#product_id').val(product_id);
						$('#main_product_name').html(main_product_duty_name);
						$('#main_ins_premium').html(optionn);
					}else{
						 
						$.each(n.product_duty_list,function(i_p,n_p){
							if(n.product_duty_list==1){
								addtional_html += '<tr><td style="padding-left:0px;">'+n.product_name+'</td><td><input type="checkbox" value="'+n_p.duty_code+'" name="addtional" />&nbsp;&nbsp;'+n_p.duty_name+'</td><td>&nbsp;保额/保费(元)：<select id="'+n_p.duty_code+'"  name="addtional_'+n_p.duty_code+'">';
								$.each(n_p.duty_price_list,function(i_A,n_A){
									if(i_A==0){
										addtional_html += '<option value="'+n_A.product_duty_price_id+'" data-value="'+n_A.premium+'">'+number2million(n_A.amount)+'/'+n_A.premium+'</option>';	
									}else{
										addtional_html += '<option value="'+n_A.product_duty_price_id+'" data-value="'+n_A.premium+'">'+number2million(n_A.amount)+'/'+n_A.premium+'</option>';	
									}
									
								});
								addtional_html += '</select></td></tr>';	
							}else{
								if(i_p==0){
									addtional_html += '<tr><td rowspan="'+n.product_duty_list.length+'" style="padding-left:0px;">'+n.product_name+'</td><td><input type="checkbox" value="'+n_p.duty_code+'" name="addtional" />&nbsp;&nbsp;'+n_p.duty_name+'</td><td>&nbsp;保额/保费(元)：<select id="'+n_p.duty_code+'"  name="addtional_'+n_p.duty_code+'">';
									$.each(n_p.duty_price_list,function(i_A,n_A){
										if(i_A==0){
											addtional_html += '<option value="'+n_A.product_duty_price_id+'" data-value="'+n_A.premium+'">'+number2million(n_A.amount)+'/'+n_A.premium+'</option>';	
										}else{
											addtional_html += '<option value="'+n_A.product_duty_price_id+'" data-value="'+n_A.premium+'">'+number2million(n_A.amount)+'/'+n_A.premium+'</option>';	
										}
										
									});
									addtional_html += '</select></td></tr>';		
								}else{
									addtional_html += '<tr><td><input type="checkbox" value="'+n_p.duty_code+'" name="addtional" />&nbsp;&nbsp;'+n_p.duty_name+'</td><td>&nbsp;保额/保费(元)：<select id="'+n_p.duty_code+'"  name="addtional_'+n_p.duty_code+'">';
									$.each(n_p.duty_price_list,function(i_A,n_A){
										if(i_A==0){
											addtional_html += '<option value="'+n_A.product_duty_price_id+'" data-value="'+n_A.premium+'">'+number2million(n_A.amount)+'/'+n_A.premium+'</option>';	
										}else{
											addtional_html += '<option value="'+n_A.product_duty_price_id+'" data-value="'+n_A.premium+'">'+number2million(n_A.amount)+'/'+n_A.premium+'</option>';	
										}
										
									});
									addtional_html += '</select></td></tr>';		
								}
								
							}
							
						});
						
						
						$('#addtional_list').html(addtional_html);
						
					}
					 
					 
				});
				
				
		   }
            
           
        },
        error : function(data){
        
        }
    });
	
}

function setprices(){
	var pricesTemp_shu_1 = 0;
	var pricesTemp_shu_2 = 0;
	var pricesTemp_shu_3 = 0;
	var career = [1,4];
	var person_list = [];
	var addtionalIDs_1 = [];
	var addtionalIDs_2 = [];
	var addtionalIDs_3 = [];
	var pricesTemp = 0;
	$('#addtional_list select').each(function(i, n) {
        var value_sel_setprice_temp = $(this).val();
		
		if(value_sel_setprice_temp!=0){
			pricesTemp_shu_1 = accAdd(pricesTemp_shu_1,$(this).children("option:selected").data('value'));
			pricesTemp_shu_2 = accAdd(pricesTemp_shu_2,$(this).children("option:selected").data('value_1'));
			pricesTemp_shu_3 = accAdd(pricesTemp_shu_3,$(this).children("option:selected").data('value_2'));
			var value_addtionalIDs_01 = $(this).val();
			var value_addtionalIDs_02 = $(this).children("option:selected").data('pduty_id_1');
			var value_addtionalIDs_03 = $(this).children("option:selected").data('pduty_id_2');
			addtionalIDs_1.push(value_addtionalIDs_01);
			addtionalIDs_2.push(value_addtionalIDs_02);
			addtionalIDs_3.push(value_addtionalIDs_03);
			 
		}
    });
	
	$('#addtional_list tr:last').children("td.value_text_1").text(pricesTemp_shu_1);
	$('#addtional_list tr:last').children("td.value_text_2").text(pricesTemp_shu_2);
	$('#addtional_list tr:last').children("td.value_text_3").text(pricesTemp_shu_3);
	if(/^\d+$/.test($('#person_num_01').val())&&$('#person_num_01').val()>0){
		 
		person_list.push($('#person_num_01').val());
		pricesTemp = accAdd(pricesTemp,accMul($('#person_num_01').val(),pricesTemp_shu_1));
	}
	if(/^\d+$/.test($('#person_num_02').val())&&$('#person_num_02').val()>0){
 
		person_list.push($('#person_num_02').val());
		pricesTemp = accAdd(pricesTemp,accMul($('#person_num_02').val(),pricesTemp_shu_2)); 
	}
	if(/^\d+$/.test($('#person_num_03').val())&&$('#person_num_03').val()>0){
	  
		person_list.push($('#person_num_03').val());
		pricesTemp = accAdd(pricesTemp,accMul($('#person_num_03').val(),pricesTemp_shu_3));  
	}
	$('#productPrem_ strong').html(pricesTemp);
	$('#temp_price').val(pricesTemp);
	  
	$('#temp_career').val(career.join('_'));
 	$('#temp_person_list').val(person_list.join('_'));
	var temp_addtional_ids = '';
	if(addtionalIDs_1!=""){
		temp_addtional_ids = addtionalIDs_1.join(',')+'_';
	}
	if(addtionalIDs_2!=""){
		temp_addtional_ids = temp_addtional_ids+addtionalIDs_2.join(',')+'_';
	}
	if(addtionalIDs_3!=""){
		//temp_addtional_ids = temp_addtional_ids+addtionalIDs_3.join(',')+'_';
	}
	
	$('#temp_addtional_ids').val(temp_addtional_ids.substring(0,temp_addtional_ids.length-1));
	
}

 
function save_cp(){
	var periodSum = $('#period').val();
	$('#temp_period').val(periodSum);
	
	if((/^\d+$/.test($('#person_num_01').val())&&$('#person_num_01').val()>0) || (/^\d+$/.test($('#person_num_02').val())&&$('#person_num_02').val()>0)|| (/^\d+$/.test($('#person_num_03').val())&&$('#person_num_03').val()>0)){
		var person_num_temp_list = 0;
		if(/^\d+$/.test($('#person_num_01').val())&&$('#person_num_01').val()>0){
			person_num_temp_list = accAdd(person_num_temp_list,$('#person_num_01').val());
		}
		if(/^\d+$/.test($('#person_num_02').val())&&$('#person_num_02').val()>0){
			person_num_temp_list = accAdd(person_num_temp_list,$('#person_num_02').val());
		}
		if(/^\d+$/.test($('#person_num_03').val())&&$('#person_num_03').val()>0){
			person_num_temp_list = accAdd(person_num_temp_list,$('#person_num_03').val());
		}
		if(person_num_temp_list>=5){
			$('#temp_person_sum').val(person_num_temp_list);
			$('#save_form').submit();	
		}else{
			layer.alert('人员个数最少5个',9,'温馨提示');	
			return false;		
		}
		 
	}else{
		layer.alert('人员个数必须是大于0的正整数',9,'温馨提示');	
		return false;	
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

function number2million(number_str){
	var s1_length = number_str.toString().split(".")[0].length;
	var s2_length = 0;
	var mi_str = number_str;
	try{s2_length=number_str.toString().split(".")[1].length}catch(e){s2_length=0};
	 
	if(s2_length==0&&s1_length>=5){
		mi_str = ((number_str)/10000)+'万';
	}
	 
	return 	mi_str;
}

