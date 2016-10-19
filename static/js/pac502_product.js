$(document).ready( function(){
    //getInsurData();//加载主险和附属险的保额和保费
	
    getInsurset_init();
});

function getInsurset_init(){
	
	var career_list = [1,4,5];
	if($('#attribute_code_Temp').val()=='Y502_A'){
		career_list = [1];
	}else if($('#attribute_code_Temp').val()=='Y502_B'){
		career_list = [4];
	}
	var datajson_All_list = {};
    $(career_list).each(function(i, n) {
       datajson_All_list[i] = getInsurAjax_Data(n);
    });	
	
	setData_One(datajson_All_list);
}
function getInsurAjax_Data(career){
	var datajson = {'attribute_id':$('#attribute_id_y502temp').val(),'period_factor_code':12,'career_factor_code':career};
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
	var Aplan_temp = $('#product_plan_typeTemp').val(); 
	var pricesTemp = 0;
	var temp_addtional_idsJSON = []; 
	var duty_code_Json = ['YA01','JA17'];
	var duty_list_Json = [{'code':'YA01','amount':100000},{'code':'JA17','amount':10000}];
	if(Aplan_temp=='Aplan1'){
		duty_code_Json = ['YA01','JA17'];
		duty_list_Json = [{'code':'YA01','amount':100000},{'code':'JA17','amount':10000}];
	}else if(Aplan_temp=='Aplan2'){
		                            
		duty_code_Json = ['YA01','JA17','JD06','YA08','YA09','YA10','YA11'];
		duty_list_Json = [{'code':'YA01','amount':100000},{'code':'JA17','amount':10000},{'code':'JD06','amount':5400},{'code':'YA08','amount':2000000},{'code':'YA09','amount':200000},{'code':'YA10','amount':200000},{'code':'YA11','amount':100000}];
	}else if(Aplan_temp=='Aplan3'){
		 
		duty_code_Json = ['YA01','JA17','JD06'];
		duty_list_Json = [{'code':'YA01','amount':200000},{'code':'JA17','amount':20000},{'code':'JD06','amount':9000}];
	}else if(Aplan_temp=='Aplan4'){
		 
		duty_code_Json = ['YA01','JA17','JD06'];
		duty_list_Json = [{'code':'YA01','amount':300000},{'code':'JA17','amount':20000},{'code':'JD06','amount':18000}];
	}else if(Aplan_temp=='Aplan5'){
		 
		duty_code_Json = ['YA01','JA17','JD06'];
		duty_list_Json = [{'code':'YA01','amount':500000},{'code':'JA17','amount':20000},{'code':'JD06','amount':27000}];

	}else if(Aplan_temp=='Bplan1'){
		 
		duty_code_Json = ['YA01','JA17','JD06'];
		duty_list_Json = [{'code':'YA01','amount':100000},{'code':'JA17','amount':10000},{'code':'JD06','amount':5400}];

	}else if(Aplan_temp=='Bplan2'){
		duty_code_Json = ['YA01','JA17','JD06'];
		duty_list_Json = [{'code':'YA01','amount':200000},{'code':'JA17','amount':20000},{'code':'JD06','amount':5400}];
	}else if(Aplan_temp=='Bplan3'){
		duty_code_Json = ['YA01','JA17','JD06'];
		duty_list_Json = [{'code':'YA01','amount':300000},{'code':'JA17','amount':20000},{'code':'JD06','amount':18000}];
	}
	$.each(data_json[0],function(i,n){	 
		
		$.each(n.product_duty_list,function(i_p,n_p){
			 var q_i = $.inArray(n_p.duty_code,duty_code_Json);
			 if(q_i!=-1){
				 $.each(n_p.duty_price_list,function(i_S,n_S){
				  	 if(n_S.amount==duty_list_Json[q_i].amount){
						temp_addtional_idsJSON.push(n_S.product_duty_price_id);	 
						pricesTemp = accAdd(pricesTemp,n_S.premium);
					 }
					 
				 });
			 }
			 
			
		});
		
		 
	});	
	 
	$('#productPrem_ strong').html(pricesTemp);
	$('#temp_price').val(pricesTemp);
	$('#temp_addtional_ids').val(temp_addtional_idsJSON.join(','));
	 
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

