var  $j = jQuery.noConflict();

 $j(document).ready(function(){
	 
//////////////////投保人的身份类型////////////////////////////////////
		$j("input[name=applicant_type]").change(function () {

			var $selectedvalue = $j("input[name=applicant_type]:checked").val();
			
			//alert($selectedvalue);
			if ($selectedvalue == 1) //single
			{
				$j("input#businessType").val(1);
				
				$j("div#applicant_info_single").show();
				$j("div#applicant_info_group").hide();
				
				$j("select#relationshipWithInsured").val("1");
				//$j("select#relationshipWithInsured").attr("readonly",false);
				$j("#div_assured").hide();
				
				
			} 
			else//group
			{
				$j("input#businessType").val(2);
				
				$j("div#applicant_info_group").show();
				$j("div#applicant_info_single").hide();
						
				$j("select#relationshipWithInsured").val("6");//其他
				//$j("select#relationshipWithInsured").attr("disable",true);
				//$j("select#relationshipWithInsured").selectReadOnly();
				$j("#div_assured").show();
			}
	    });

		/////////////////////被保险人输入方式/////////////////////////////////////////
		$j("input[name=assured_input_type]").change(function () {

			var $selectedvalue = $j("input[name=assured_input_type]:checked").val();
			//alert($selectedvalue);
			if ($selectedvalue == 1) //manual,手工单个输入
			{
				//$j("input#businessType").val(1);
				$j("input#bat_post_policy").val(0);//add by wangcya, 20150106,是否批量投保，单独一个字段
				
				$j("table#table_input_type_xls").hide();
				$j("table#table_input_type_manual").show();
			} 
			else if($selectedvalue == 2)//xls，批量投保的方式
			{
				//$j("input#businessType").val(3);
				$j("input#bat_post_policy").val(1);//add by wangcya, 20150106,是否批量投保，单独一个字段
				
				$j("table#table_input_type_xls").show();
				$j("table#table_input_type_manual").hide();
			}
	    });

	 
 });
 
 
///////////////////////////////////////////////////////////////////////////
//验证excels返回数据
function checkExcels(exldata)
{
	var excels_flag = true;
	var n = exldata;
	var wrong_text = '';
	var pr_listhtml = '<tr>';
	
	var insurer_code = $("#insurer_code").val();  //保险公司代码
	var str_type_code = '01'; //省份证类型编码
	var fMan_temp = 'F'; //女
	var man_temp = 'M';  //男
	
	//////////////////////////////////////////////////////////////
	if(insurer_code=='PAC' || insurer_code=='PAC01' ||insurer_code=='PAC02' || insurer_code=='PAC03')
	{  //通过保险公司而处理  性别  身份证类型编码
		str_type_code = '01';
		fMan_temp = "F";
		man_temp = "M";
	}
	else if(insurer_code=='TBC01')
	{  //通过保险公司而处理  性别  身份证类型编码
		str_type_code = '1';
		fMan_temp = "2";
		man_temp = "1";
	}
	else if(insurer_code=='HTS')
	{  //通过保险公司而处理  性别  身份证类型编码
		str_type_code = '1';
		fMan_temp = 1;
		man_temp = 2;
	}
	////////////////////////////////////////////////////////////////////////////////////////
	if(strlen(n.fullname)>24||strlen(n.fullname)<3||n.fullname==""||n.fullname==null)
	{
		//姓名不对
		excels_flag = false;
		wrong_text += '姓名格式错误 ';
		pr_listhtml += '<td class=" tac wrong_bg" data-value="fullname">'+n.fullname+'</td>';
	}
	else
	{
		pr_listhtml += '<td class=" tac" data-value="fullname">'+n.fullname+'</td>';	
	}
	
	if(n.certificates_type==0||n.certificates_type==""||n.certificates_type==null)
	{
		//证件类型不对
		excels_flag = false;
		wrong_text += '证件类型格式错误  ';
		pr_listhtml += '<td class=" tac wrong_bg" data-value="certificates_type">'+n.certificates_type+'</td>';
	}else{
		pr_listhtml += '<td class=" tac" data-value="certificates_type">'+n.certificates_type+'</td>'; 	
	}
	if(n.certificates_code == ""||n.certificates_code==null){
		//省份证不对
		excels_flag = false;
		wrong_text += '证件号码输入错误  ';
		pr_listhtml += '<td class=" tac wrong_bg" data-value="certificates_code">'+n.certificates_code+'</td>';
		
	}
	else
	{
		if(n.certificates_type==str_type_code)
		{
			if(isIdCardNo(n.certificates_code)!=0)
			{
				 //省份证不对
				excels_flag = false;
				wrong_text += '证件号码输入错误  ';
				pr_listhtml += '<td class=" tac wrong_bg" data-value="certificates_code">'+n.certificates_code+'</td>';
			}else{
				var br_Temp = '';
				var sex_tempint = '';
				var sex_temp = '';
				if(n.certificates_code.length==15){
					 sex_tempint = n.certificates_code.substring(14,15);  //M F
					 sex_temp = (((parseInt(sex_tempint))%2) ==0)?fMan_temp:man_temp;
					br_Temp = n.certificates_code.substring(6,8)+'-'+n.certificates_code.substring(8,10)+'-'+n.certificates_code.substring(10,12);
					if(n.birthday.substring(2,9)!=br_Temp||sex_temp!=n.gender){
						//省份证  生日  性别 不匹配
						excels_flag = false;
						wrong_text += '证件号码和生日和性不匹配  ';
						pr_listhtml += '<td class=" tac wrong_bg" data-value="certificates_code">'+n.certificates_code+'</td>';
					}else{
						pr_listhtml += '<td class=" tac" data-value="certificates_code">'+n.certificates_code+'</td>';	
					}
				}else if(n.certificates_code.length==18){
					sex_tempint = n.certificates_code.substring(16,17);
					sex_temp = (((parseInt(sex_tempint))%2) ==0) ?fMan_temp:man_temp;
					br_Temp = n.certificates_code.substring(6,10)+'-'+n.certificates_code.substring(10,12)+'-'+n.certificates_code.substring(12,14);
					if(br_Temp!=n.birthday||sex_temp!=n.gender){
						//省份证  生日  性别 不匹配
						excels_flag = false;
						wrong_text += '证件号码和生日和性不匹配  ';
						pr_listhtml += '<td class=" tac wrong_bg" data-value="certificates_code">'+n.certificates_code+'</td>';
					}else{
						pr_listhtml += '<td class=" tac" data-value="certificates_code">'+n.certificates_code+'</td>';	
					}
				}else{
					pr_listhtml += '<td class=" tac wrong_bg" data-value="certificates_code">'+n.certificates_code+'</td>';	
				}	
				
			}	
		}else{
			pr_listhtml += '<td class=" tac" data-value="certificates_code">'+n.certificates_code+'</td>';
		}
		
	}
	if(n.career_type!=""){
		pr_listhtml += '<td class=" tac" data-value="career_type">'+n.career_type+'</td>';	
	}else{
		pr_listhtml += '<td class=" tac wrong_bg" data-value="career_type">'+n.career_type+'</td>';
	}
	 
	if(n.gender==man_temp||n.gender==fMan_temp){
		pr_listhtml += '<td class=" tac" data-value="gender">'+n.gender+'</td>';
	}else{
		//性别不正确
		excels_flag = false;
		wrong_text += '性别代码格式错误 '+man_temp+'(男) '+fMan_temp+'(女) ';
		pr_listhtml += '<td class=" tac wrong_bg" data-value="gender">'+n.gender+'</td>';
	}
	
	if(n.birthday.length==""){
		//生日不正确
		excels_flag = false;
		wrong_text += '生日格式错误2014-10-12  ';
		pr_listhtml += '<td class=" tac wrong_bg" data-value="birthday">'+n.birthday+'</td>';
	}else{
		var bage = getAgeByBirthday(n.birthday);
		//alert("after getAgeByBirthday: "+bage);
		if(bage == -1)
		{
		 
			excels_flag = false;
			wrong_text += '生日格式错误2014-10-12  ';
			pr_listhtml += '<td class=" tac wrong_bg" data-value="birthday">'+n.birthday+'</td>';
			
		}else{
			var sAge = parseInt($('#age_min').val());
			var eAge = parseInt($('#age_max').val());
		     
			if(bage < sAge || bage>eAge)
    		{
    			excels_flag = false;
				wrong_text += '年龄必须在'+sAge+'-'+eAge+'之间';
				pr_listhtml += '<td class=" tac wrong_bg" data-value="birthday">'+n.birthday+'</td>';
				 
			}else{
				pr_listhtml += '<td class=" tac" data-value="birthday">'+n.birthday+'</td>';		
			}
			
			
			
		}
		
		//alert("before getAgeByBirthday");
		
		
		
	}
	 
	if(!isEmail(n.email, true)||n.email==null||n.email==""){
		//邮箱不正确
 		excels_flag = false;
		wrong_text += '邮箱格式错误 XXX@163.com,XXX@126.com  ';
		pr_listhtml += '<td class=" tac wrong_bg" data-value="email">'+n.email+'</td>';
	}else{
		pr_listhtml += '<td class=" tac"  data-value="email">'+n.email+'</td>';
	}
	 
	if(!isMobile(n.mobiletelephone,true)||isNull(n.mobiletelephone)){
		//手机号不正确
		excels_flag = false;
		wrong_text += '手机号错误 139123456789  ';	
		pr_listhtml += '<td class=" tac wrong_bg"  data-value="mobiletelephone">'+n.mobiletelephone+'</td>';
	}else{
		pr_listhtml += '<td class=" tac" data-value="mobiletelephone">'+n.mobiletelephone+'</td>';
	}
	
	if($('#career').val()==1){//add by wangcya , 20141224, 增加这个限制为了什么呢？  这是职业类型  产品详情页面传递过来的值  可以判定出是哪一个职业类型
		if(n.level_num==1||n.level_num==2||n.level_num==3){
			pr_listhtml += '<td class=" tac" data-value="level_num">'+n.level_num+'</td>';
		}else{
			
			//	职业类型不正确或者和职业代码不相匹配
			excels_flag = false;
			wrong_text += '职业类型代码错误 1,2,3';	
			pr_listhtml += '<td class=" tac wrong_bg" data-value="level_num">'+n.level_num+'</td>';
		}
	}else{
		pr_listhtml += '<td class="tac"></td>';	
	}
 
	pr_listhtml += '<td class="tac"><a href="javascript:void(0)" onclick="deleteRow(this)" class="btn_link002">删 除</a></td></tr>'; 
	 
	var datas = {'flag':excels_flag,'pr_listhtml':pr_listhtml};
	return  datas;
					/*n.fullname   姓名
					n.certificates_type  证件类型
					n.certificates_code  证件号码
					career_type  职业代码
					gender  性别
					birthday 生日
					email 邮箱
					mobiletelephone  手机号
					level_num  职业类别*/
	
}

//验证每一行
function checkedRow(c_ef){
	var temp_obj = {};
	var stf = $(c_ef).parent().index();
	$(c_ef).parent().children().each(function(i_ck, n_ck) {
		 
		if($(this).data("value")){
        	temp_obj[$(this).data("value")] = $.trim($(this).text());
		}
	});
	 
	var checkedRow_data = checkExcels(temp_obj);
	$(c_ef).parent().replaceWith(checkedRow_data.pr_listhtml);
	$('#ps_list table tr').eq(stf).children("td").unbind("click");
	$('#ps_list table tr').eq(stf).children("td").bind("click",function(){
		var this_temp = $(this);
		var ef = this;
		if(this_temp.data("value")){
			var ila = layer.prompt({type:0,title:'内容修改'}, function(val){
				if(val !== ''){
					this_temp.text(val);
					layer.close(ila);
					checkedRow(ef);
				}
			});
		}
	});
}


//Y502页面修改以后提交之前验证全部
function checkedAll(){
	//alert("checkedAll");
	
	var excel_content = [];
	var temp_obj = '';
	var flag_t = true;
	if($('#ps_list table tr').length>5){ 
		var code_list_Temps = [];
		var email_list_Temps = [];
		var mobiletelephone_list_Temps = [];
		$('#ps_list table tr').each(function(i_A,n_A ) {
			var temp_obj_Row = {};
			
			if(i_A!=0){
				$(this).children('td').each(function(i_ck, n_ck) {
					
					if($(this).data("value")){
						temp_obj_Row[$(this).data("value")] = $.trim($(this).text());
						if($(this).data("value")=="certificates_code"){
							code_list_Temps.push($.trim($(this).text()));	
						}
						if($(this).data("value")=="email"){
							email_list_Temps.push($.trim($(this).text()));		
						}
						if($(this).data("value")=="mobiletelephone"){
							mobiletelephone_list_Temps.push($.trim($(this).text()));		
						}
					}
				});
				excel_content.push(temp_obj_Row);
				var checkedRow_datas = checkExcels(temp_obj_Row);
				if(checkedRow_datas.flag==false){
					flag_t = checkedRow_datas.flag;	
				}
				temp_obj+=checkedRow_datas.pr_listhtml;
			}
		});
		//临时隐藏 
		/*if(code_isRepeat(code_list_Temps)||code_isRepeat(email_list_Temps)||code_isRepeat(mobiletelephone_list_Temps)){
			 	
			flag_t = false;	
		}*/
		 
		if(flag_t){
			 
			$('#excel_content').val(JSON.stringify(excel_content)); 
		}
	
	}else{
		
		 
		flag_t = false;	
	}
	/*$('#ps_list table tr').children("td").unbind("click");
	$('#ps_list table tr').children("td").bind("click",function(){
		var this_temp = $(this);
		var ef = this;
		if(this_temp.data("value")){
			var ila = layer.prompt({type:0,title:'内容修改'}, function(val){
				if(val !== ''){
					this_temp.text(val);
					layer.close(ila);
					checkedRow(ef);
				}
			});
		}
	});*/
	
	return flag_t;
}


//批量投保检查
function checkedAll_bat(){
	//alert("checkedAll");
	
	var excel_content = [];
	var temp_obj = '';
	var flag_t = true;
	if($('#ps_list table tr').length>5){ 
		var code_list_Temps = [];
		var email_list_Temps = [];
		var mobiletelephone_list_Temps = [];
		$('#ps_list table tr').each(function(i_A,n_A ) {
			var temp_obj_Row = {};
			if(i_A!=0){
				$(this).children('td').each(function(i_ck, n_ck) {
					
					if($(this).data("value")){
						temp_obj_Row[$(this).data("value")] = $.trim($(this).text());
						if($(this).data("value")=="certificates_code"){
							code_list_Temps.push($.trim($(this).text()));	
						}
						if($(this).data("value")=="email"){
							email_list_Temps.push($.trim($(this).text()));		
						}
						if($(this).data("value")=="mobiletelephone"){
							mobiletelephone_list_Temps.push($.trim($(this).text()));		
						}
					}
				});
				excel_content.push(temp_obj_Row);
				var checkedRow_datas = checkExcels(temp_obj_Row);
				if(checkedRow_datas.flag==false){
					flag_t = checkedRow_datas.flag;	
				}
				temp_obj+=checkedRow_datas.pr_listhtml;
			}
		});
		
		if(code_isRepeat(code_list_Temps)||code_isRepeat(email_list_Temps)||code_isRepeat(mobiletelephone_list_Temps)){
			 	
			flag_t = false;	
		}
		
		flag_t = true;//add by wangcya, 20141224
		
		if(flag_t)
		{
			$('#excel_content').val(JSON.stringify(excel_content)); 
		}
	
	}else{
		
		 
		flag_t = false;	
	}
	/*$('#ps_list table tr').children("td").unbind("click");
	$('#ps_list table tr').children("td").bind("click",function(){
		var this_temp = $(this);
		var ef = this;
		if(this_temp.data("value")){
			var ila = layer.prompt({type:0,title:'内容修改'}, function(val){
				if(val !== ''){
					this_temp.text(val);
					layer.close(ila);
					checkedRow(ef);
				}
			});
		}
	});*/
	return flag_t;
}
//删除一行
function deleteRow(d_ef){
	if($('#ps_list table tr').length-1>5){ 
		$(d_ef).parent().parent().remove();	
		var sum_pre = ($('#ps_list table tr').length-1);
		var pre_price = $('#posion_price').val();
		var sum_prices = accMul(sum_pre,pre_price);
		var applyNum_temp = $('#applyNum').val();
		if(!isNaN(applyNum_temp)){
			if(parseInt(applyNum_temp)>1){
				sum_prices = accMul(applyNum_temp,sum_prices);
			}
		}
		$('#showprice').text(sum_prices);
		$('#totalModalPremium').val(sum_prices); 
	}else{
		layer.alert('你的操作失败:最少人员数5人',9,'温馨提示');		
	}
	 
}
//增加一行
function addRow(){
	//$(ef).parent().parent();
}


//上传文件并且对返回值做验证处理（Y502）
function importXLs(file_id,proid,career){
		
	//alert("sfdfsdfssss");
	jQuery.ajaxFileUpload({
		url : './cp.php?ac=product_upload&op=uploadfile_insured_xls&product_id='+proid+'&career='+career, //用于文件上传的服务器端请求地址
		secureuri : false, //是否需要安全协议，一般设置为false
		fileElementId : file_id, //文件上传域的ID
		dataType : 'json', //返回值类型 一般设置为json
		success : function(res, status){//服务器成功响应处理函数
			
			if(res.result_msg=='success'){
				if(res.data.length>=5&&res.data.length<=3000){
					var wrong_html = '';
					var code_list_Temp =[];
					var email_list_Temp =[];
					var phone_list_Temp =[];
                    var temp_flag_upload = true;
					$(res.data).each(function(i, n) {
						 code_list_Temp.push(n.certificates_code);
						 phone_list_Temp.push(n.mobiletelephone);
						 email_list_Temp.push(n.email);
						 
						  var check_res = checkExcels(n);
						  wrong_html+=check_res.pr_listhtml;
                        if(check_res.flag==false){
                            temp_flag_upload = check_res.flag;
                        }
					});
					var pr_listhtml = '<div class="tac p10">备注：<i style="display:inline-block;width:20px;height:10px;background:#ccc;"></i> 是内容(或相关联的内容)不符合规范部分</div><table cellpadding="0" cellspacing="0" border="1" width="90%" class="border_table_05"  ><tr><th class="fwb tac">姓名</th><th class="fwb tac">证件类型</th><th class="fwb tac">证件号码</th><th class="fwb tac">职业代码</th><th class="fwb tac">性别</th><th class="fwb tac">生日</th><th class="fwb tac">邮箱</th><th class="fwb tac">手机号</th><th class="fwb tac">职业类别</th><th class="fwb w100 tac">操作</th></tr>';
					pr_listhtml+=wrong_html;
					pr_listhtml += '</table>';
					
					$('#ps_list').html(pr_listhtml);

					var sum_pre = ($('#ps_list table tr').length-1);
					 
					var pre_price = $('#posion_price').val();
					  
					var sum_prices = accMul(sum_pre,pre_price);
					var applyNum_temp = $('#applyNum').val();
					if(!isNaN(applyNum_temp)){
						if(parseInt(applyNum_temp)>1){
							sum_prices = accMul(applyNum_temp,sum_prices);
						}
					} 
					$('#showprice').text(sum_prices);
					
					$('#totalModalPremium').val(sum_prices); 
                    if(temp_flag_upload==false){
                        layer.alert('验证失败可能原因如下：<br>1.上传人员数小于1人<br>2.被保险人名单中重复的证件号码、手机号、邮箱<br>4.被保险人身份证件号码无效<br>5.被保险人年龄不在投保范围之内(18 至 65岁)<br><br>请核实后再次提交!',9,'温馨提示');
                    }

					 //临时隐藏
					/*if(code_isRepeat(code_list_Temp)||code_isRepeat(email_list_Temp)||code_isRepeat(phone_list_Temp)){
						console.log(4); 
						layer.alert('上传文件中有重复的人员、手机号、邮箱,请处理后再次上传!',9,'温馨提示');	
						console.log(5); 
					}*/
					 
					 
					
				}else{
					layer.alert('3000人>=excel人员表大于>=5人',9,'温馨提示');	
					
				}
				//$('#excel_content').val(JSON.stringify(res.data));
				/*$('#ps_list table td').unbind("click");
				$('#ps_list table td').bind("click",function(){
					var this_temp = $(this);
					var ef = this;
					if(this_temp.data("value")){
						var ila = layer.prompt({type:0,title:'内容修改'}, function(val){
							if(val !== ''){
								this_temp.text(val);
								layer.close(ila);
								checkedRow(ef);
							}
						});
					}
				});*/
				 
			}
		},
		error : function(data, status, e){//服务器响应失败处理函数
		
			alert(e);
		}
	})
	 
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


 
//true表示重复，false表示不重复  验证有没有重复的
function code_isRepeat(arr){
	 var hash = {};
	 for(var i in arr) {
		 if(hash[arr[i]])
			  return true;
		 hash[arr[i]] = true;
	 }
	 return false;
}

