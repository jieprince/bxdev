var  $j = jQuery.noConflict();

 $j(document).ready(function() {
//////////////////投保人的身份类型////////////////////////////////////
     var cp_name_temp = $('#cp_name').val(); //判断是那种类型的产品
     if (cp_name_temp!='huatai' && cp_name_temp!='huaan_xueping' && cp_name_temp!="epicc_vehicle" &&cp_name_temp!="taipingyang_tj_product" && cp_name_temp!="epicc_lvyou"&& cp_name_temp!="epicc_jingwailvyou") {//华泰的产品没有机构投保  不需要执行这部分代码
         $j("input[name=applicant_type]").change(function () {

             var $selectedvalue = $j("input[name=applicant_type]:checked").val();

             //alert($selectedvalue);
             if ($selectedvalue == 1) //single
             {
                 $j("input#businessType").val(1);
				 $j("div#applicant_info_group").hide();
                 $j("div#applicant_info_single").show();
                 
                 $j("select#relationshipWithInsured").val("1");
                 //$j("select#relationshipWithInsured").attr("readonly",false);
                 $j("#div_assured").hide();
             }
             else//group
             {
                 $j("input#businessType").val(2);
				 $j("div#applicant_info_single").hide();
                 $j("div#applicant_info_group").show();
                 
                 $j("select#relationshipWithInsured").val("6");//其他
                 //$j("select#relationshipWithInsured").attr("disable",true);
                 //$j("select#relationshipWithInsured").selectReadOnly();
                 $j("#div_assured").show();
             }
         });
     }
	 
 });
 
//验证其他产品excels返回数据  num=第几个人  隔行变色   ---华安学平险专用
function checkExcelsAll_huaanxueping(exldata,num){
	var cp_name = $('#cp_name').val(); //产品模板特定值  通过这个值来验证具体的批量上传
	var excels_flag = true;
	var n = exldata;
	var wrong_text_applicant = '';
    var wrong_text_assured = '';
    var bg_color = 'bgcolor="#faebd7"';
    if(num%2!=0){
        bg_color = 'bgcolor=""';
    }
	var insurer_code = $("#insurer_code").val();  //保险公司代码
	var str_type_code = '身份证'; //省份证类型编码
	var fMan_temp = '女'; //女
	var man_temp = '男';  //男
	var max_toubaoren = 65; //平安旅游特定变量  投保人最大年龄限制在80
	var pr_listhtml_applicant = '<tr calss="applicant" ><td rowspan="2" class="tac">'+(parseInt(num)+1)+'</td><td>投保人</td>';
    var pr_listhtml_assured = '<tr calss="assured" ><td class="tac">'+(parseInt(num)+1)+'</td>';
	//被保险人姓名
    if(strlen(n.assured_name)>24||strlen(n.assured_name)<3||n.assured_name==""||n.assured_name==null)
    {
        //投保人姓名不对
        excels_flag = false;
        wrong_text_assured += '被保险人姓名格式错误 ';
        pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_name" title="被保险人姓名格式错误">'+n.assured_name+'</td>';
    }
    else
    {
        pr_listhtml_assured += '<td class=" tac" data-value="assured_name">'+n.assured_name+'</td>';
    }
	if(cp_name=='epicc_jingwailvyou'){
		pr_listhtml_assured += '<td class=" tac" data-value="assured_name_english">'+n.assured_name_english+'</td>';
	//被保险人证件类型
	}
	if(cp_name=='huaan_xueping'){
		if(n.assured_certificates_type!='身份证' && n.assured_certificates_type!='其他' && n.assured_certificates_type!='出生日期'){
			//证件类型不对
			excels_flag = false;
			wrong_text_assured += '证件类型格式错误  ';
			pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_type" title="证件类型格式错误">'+n.assured_certificates_type+'</td>';
		}else{
			pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_type">'+n.assured_certificates_type+'</td>';
		}
		if(n.assured_certificates_type=='身份证'){
	
			//被保险人证件号码  需要被保险人的证件类型来验证 如果是身份证需要：身份证，生日，性别 三者一起验证
			if(n.assured_certificates_code == ""||n.assured_certificates_code==null){
				//省份证不对
				excels_flag = false;
				wrong_text_assured += '证件号码输入错误  ';
				pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码输入错误">'+n.assured_certificates_code+'</td>';
		
			}else{
				if(n.assured_certificates_type==str_type_code){ //动态获取身份类型验证
				
					if(isIdCardNo(n.assured_certificates_code)!=0){
						//省份证不对
						excels_flag = false;
						wrong_text_assured += '证件号码输入错误  ';
						pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码输入错误">'+n.assured_certificates_code+'</td>';
					}else{
						var br_Temp = '';
						var sex_tempint = '';
						var sex_temp = '';
						if(n.assured_certificates_code.length==15){
							sex_tempint = n.assured_certificates_code.substring(14,15);  //M F
							sex_temp = (((parseInt(sex_tempint))%2) ==0)?fMan_temp:man_temp;
							br_Temp = n.assured_certificates_code.substring(6,8)+'-'+n.assured_certificates_code.substring(8,10)+'-'+n.assured_certificates_code.substring(10,12);
							var bages = getAgeByBirthday(br_Temp);
							var sAge = parseInt($('#age_min').val());
							var eAge = parseInt($('#age_max').val());
							if(sex_temp!=n.assured_gender){
								//省份证  性别   不匹配
								excels_flag = false;
								wrong_text_assured += '证件号码和性别不匹配  ';
								pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码和性别不匹配">'+n.assured_certificates_code+'</td>';
							}else{
								if(bages < sAge || bages>eAge){
									excels_flag = false;
									wrong_text_assured += '证件年龄必须在'+sAge+'-'+eAge+'之间';
									pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件年龄必须在'+sAge+'-'+eAge+'之间">'+n.assured_certificates_code+'</td>';
								}else{
									pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
								}
							}
						}else if(n.assured_certificates_code.length==18){
							sex_tempint = n.assured_certificates_code.substring(16,17);
							sex_temp = (((parseInt(sex_tempint))%2) ==0) ?fMan_temp:man_temp;
							br_Temp = n.assured_certificates_code.substring(6,10)+'-'+n.assured_certificates_code.substring(10,12)+'-'+n.assured_certificates_code.substring(12,14);
							var bages = getAgeByBirthday(br_Temp);
							var sAge = parseInt($('#age_min').val());
							var eAge = parseInt($('#age_max').val());
							if(sex_temp!=n.assured_gender){
								//省份证  生日  性别 不匹配
								excels_flag = false;
								wrong_text_assured += '证件号码和性别不匹配';
								pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码和性别不匹配">'+n.assured_certificates_code+'</td>';
							}else{
								 
								if(bages < sAge || bages>eAge){
									excels_flag = false;
									wrong_text_assured += '证件年龄必须在'+sAge+'-'+eAge+'之间';
									pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件年龄必须在'+sAge+'-'+eAge+'之间">'+n.assured_certificates_code+'</td>';
								}else{
									pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
								}
							
								
							}
						}else{
							pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
						}
		
					}
				}else{
					pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
				}
		
			}
		}else if(n.assured_certificates_type=='出生日期'){
			var bages = getAgeByBirthday(n.assured_certificates_code);
			var sAge = parseInt($('#age_min').val());
			var eAge = parseInt($('#age_max').val());
			if(bages == -1){ 
				excels_flag = false;
				wrong_text_assured += '出生日期格式不正确2014-10-23';
				pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="出生日期格式不正确2014-10-23">'+n.assured_certificates_code+'</td>';
			}else{
				if(bages < sAge || bages>eAge){
					excels_flag = false;
					wrong_text_assured += '年龄必须在'+sAge+'-'+eAge+'之间';
					pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="年龄必须在'+sAge+'-'+eAge+'之间">'+n.assured_certificates_code+'</td>';
				}else{
					pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
				}
				
			}
		}else{
			wrong_text_assured += '证件号码不能为空';
			pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码不能为空">--</td>';
		}
		//被保险人性别
		if(n.assured_gender=="男" || n.assured_gender=="女"){
			pr_listhtml_assured += '<td class=" tac" data-value="assured_gender">'+n.assured_gender+'</td>'; 
			
		}else{
			 excels_flag = false;
			 wrong_text_assured += '性别不能为空  ';
			 pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_gender" title="性别不能为空">'+n.assured_gender+'</td>';
			//alert("before getAgeByBirthday");
	
		}
    }else if(cp_name=='epicc_vehicle' || cp_name=='taipingyang_tj_product' || cp_name=='epicc_lvyou'|| cp_name=='epicc_jingwailvyou'){
		if(n.assured_certificates_type!='身份证' && n.assured_certificates_type!='其他' && n.assured_certificates_type!='护照' && n.assured_certificates_type!='军官证' && n.assured_certificates_type!='户口薄'){
			//证件类型不对
			excels_flag = false;
			wrong_text_assured += '证件类型格式错误  ';
			pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_type" title="证件类型格式错误">'+n.assured_certificates_type+'</td>';
		}else{
			pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_type">'+n.assured_certificates_type+'</td>';
		}
		//被保险人证件号码  需要被保险人的证件类型来验证 如果是身份证需要：身份证，生日，性别 三者一起验证
		if(n.assured_certificates_code == ""||n.assured_certificates_code==null){
			//省份证不对
			excels_flag = false;
			wrong_text_assured += '证件号码输入错误  ';
			pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码输入错误">'+n.assured_certificates_code+'</td>';
	
		}else{
			if(n.assured_certificates_type==str_type_code){ //动态获取身份类型验证
			
				if(isIdCardNo(n.assured_certificates_code)!=0){
					//省份证不对
					excels_flag = false;
					wrong_text_assured += '证件号码输入错误  ';
					pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码输入错误">'+n.assured_certificates_code+'</td>';
				}else{
					var br_Temp = '';
					var sex_tempint = '';
					var sex_temp = '';
					if(n.assured_certificates_code.length==15){
						sex_tempint = n.assured_certificates_code.substring(14,15);  //M F
						sex_temp = (((parseInt(sex_tempint))%2) ==0)?fMan_temp:man_temp;
						br_Temp = n.assured_certificates_code.substring(6,8)+'-'+n.assured_certificates_code.substring(8,10)+'-'+n.assured_certificates_code.substring(10,12);
						if(n.assured_birthday.substring(2,9)!=br_Temp||sex_temp!=n.assured_gender){
							//省份证  生日  性别 不匹配
							excels_flag = false;
							wrong_text_assured += '证件号码和生日和性别不匹配  ';
							pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码和生日和性别不匹配">'+n.assured_certificates_code+'</td>';
						}else{
							pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
						}
					}else if(n.assured_certificates_code.length==18){
						sex_tempint = n.assured_certificates_code.substring(16,17);
						sex_temp = (((parseInt(sex_tempint))%2) ==0) ?fMan_temp:man_temp;
						br_Temp = n.assured_certificates_code.substring(6,10)+'-'+n.assured_certificates_code.substring(10,12)+'-'+n.assured_certificates_code.substring(12,14);
						if(br_Temp!=n.assured_birthday||sex_temp!=n.assured_gender){
							//省份证  生日  性别 不匹配
							excels_flag = false;
							wrong_text_assured += '证件号码和生日和性别不匹配  ';
							pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码和生日和性别不匹配">'+n.assured_certificates_code+'</td>';
						}else{
							pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
						}
					}else{
						pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
					}
	
				}
			}else{
				pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
			}
	
		}
	
	
		
		//被保险人生日
		if(n.assured_birthday.length==""){
			//生日不正确
			excels_flag = false;
			wrong_text_assured += '生日格式错误不能为空  ';
			pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_birthday" title="生日格式错误不能为空">'+n.assured_birthday+'</td>';
		}else{
			var bage = getAgeByBirthday(n.assured_birthday);
	
			//alert("after getAgeByBirthday: "+bage);
			if(bage == -1)
			{
	
				excels_flag = false;
				wrong_text_assured += '生日格式错误2014-10-12  ';
				pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_birthday" title="生日格式错误2014-10-12">'+n.assured_birthday+'</td>';
	
			}else{
				var sAge = parseInt($('#age_min').val());
				var eAge = parseInt($('#age_max').val());
	
				if(bage < sAge || bage>eAge)
				{
					excels_flag = false;
					wrong_text_assured += '年龄必须在'+sAge+'-'+eAge+'之间';
					pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_birthday" title="年龄必须在'+sAge+'-'+eAge+'之间">'+n.assured_birthday+'</td>';
	
				}else{
					pr_listhtml_assured += '<td class=" tac" data-value="assured_birthday">'+n.assured_birthday+'</td>';
				}
			}
	
			//alert("before getAgeByBirthday");
	
		}
		//被保险人性别
		if(n.assured_gender==man_temp || n.assured_gender==fMan_temp){
			pr_listhtml_assured += '<td class=" tac" data-value="assured_gender">'+n.assured_gender+'</td>';
		}else{
			//性别不正确
			excels_flag = false;
			wrong_text_assured += '性别代码格式错误 '+man_temp+'(男) '+fMan_temp+'(女) ';
			pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_gender" title="性别代码格式错误 '+man_temp+'(男) '+fMan_temp+'(女) ">'+n.assured_gender+'</td>';
		}
		
		//被保险人电话
		
		if(!isMobile(n.assured_mobilephone,true)||isNull(n.assured_mobilephone)){
			//手机号不正确
			excels_flag = false;
			wrong_text_assured += '手机号错误 13912345678';
			pr_listhtml_assured += '<td class=" tac wrong_bg"  data-value="assured_mobilephone" title="手机号错误 13912345678">'+n.assured_mobilephone+'</td>';
		}else{
			pr_listhtml_assured += '<td class=" tac" data-value="assured_mobilephone">'+n.assured_mobilephone+'</td>';
		}	 
		 
		if(!isEmail(n.assured_email, true) || n.assured_email==null || n.assured_email==""){
			//邮箱不正确
			excels_flag = false;
			wrong_text_assured += '邮箱格式错误 XXX@163.com ';
			pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_email" title="邮箱格式错误 XXX@163.com">'+n.assured_email+'</td>';
		}else{
			pr_listhtml_assured += '<td class=" tac"  data-value="assured_email">'+n.assured_email+'</td>';
		}
		
		 
	}
 
    
	pr_listhtml_assured += '<td class="tac" title="'+wrong_text_assured+'"><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; width:95px;color:red">'+ wrong_text_assured+'</div></td>';
	pr_listhtml_assured += '</tr>';
     

	var datas = {'flag':excels_flag,'pr_listhtml':pr_listhtml_assured};
	return  datas;
	
}

///////////////////////////////////////////////////////////////////////////
//验证其他产品excels返回数据  num=第几个人  隔行变色
function checkExcelsAll(exldata,num)
{
    var cp_name = $('#cp_name').val(); //产品模板特定值  通过这个值来验证具体的批量上传
	var excels_flag = true;
	var n = exldata;
	var wrong_text_applicant = '';
    var wrong_text_assured = '';
    var bg_color = 'bgcolor="#faebd7"';
    if(num%2!=0){
        bg_color = 'bgcolor=""';
    }
	var pr_listhtml_applicant = '<tr calss="applicant" '+bg_color+'><td rowspan="2" class="tac">'+(parseInt(num)+1)+'</td><td>投保人</td>';
    var pr_listhtml_assured = '<tr calss="assured" '+bg_color+'><td>被险保人</td><td class="tac">--</td>';
	if(cp_name=='huaan_xueping' || cp_name=='epicc_vehicle' || cp_name=='taipingyang_tj_product' || cp_name=='epicc_lvyou'|| cp_name=='epicc_jingwailvyou'){
		pr_listhtml_assured = '<tr calss="assured" '+bg_color+'><td>被险保人</td><td class="tac">个人</td>';
	}
	var insurer_code = $("#insurer_code").val();  //保险公司代码
	var str_type_code = '身份证'; //省份证类型编码
	var fMan_temp = '女'; //女
	var man_temp = '男';  //男
	var max_toubaoren = 65; //平安旅游特定变量  投保人最大年龄限制在80
	if(cp_name=='pingan_lvyou' || cp_name=='pingan_lvyoujingwai'){
		max_toubaoren = 80; 
	}

	//////////////////////////////////////////////////////////////
    //投保人类型
    if(n.applicant_type==""){
        excels_flag = false;
        wrong_text_applicant += '投保人类型有误 ';
        pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_type" title="投保人类型有误">'+n.applicant_type+'</td>';
    }else{
        pr_listhtml_applicant += '<td class="tac" data-value="applicant_type">'+n.applicant_type+'</td>';
    }
/*
 *投保人信息的验证
 *
*/
    if(n.applicant_type=="个人"){
        //投保人姓名
        if(strlen(n.applicant_name)>24||strlen(n.applicant_name)<3||n.applicant_name==""||n.applicant_name==null)
        {
            //投保人姓名不对
            excels_flag = false;
            wrong_text_applicant += '姓名格式错误 ';
            pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_name" title="姓名3-24个字符">'+n.applicant_name+'</td>';
        }
        else
        {
            pr_listhtml_applicant += '<td class=" tac" data-value="applicant_name">'+n.applicant_name+'</td>';
        }
        if(cp_name=='huatai') {
            //投保人英文名
            if ($.trim(n.applicant_englishname) == "" || n.applicant_englishname == null) {
                //投保人姓名不对
                excels_flag = false;
                wrong_text_applicant += '英文姓名格式错误 ';
                pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_englishname" title="英文姓名格式错误">' + n.applicant_englishname + '</td>';
            }
            else {
                pr_listhtml_applicant += '<td class=" tac" data-value="applicant_englishname">' + n.applicant_englishname + '</td>';
            }
        }
		if(cp_name=='epicc_jingwailvyou') {
            //投保人英文名
            pr_listhtml_applicant += '<td class=" tac" data-value="applicant_englishname">' + n.applicant_englishname + '</td>';
        }
		if(cp_name=='pingan_lvyoujingwai'){
			pr_listhtml_applicant += '<td class=" tac" >--</td>';
		}

        //投保人证件类型
        if(n.applicant_certificates_type==0||n.applicant_certificates_type==""||n.applicant_certificates_type==null)
        {
            //证件类型不对
            excels_flag = false;
            wrong_text_applicant += '证件类型格式错误  ';
            pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_certificates_type" title="证件类型格式错误">'+n.applicant_certificates_type+'</td>';
        }
        else
        {
            pr_listhtml_applicant += '<td class=" tac" data-value="applicant_certificates_type">'+n.applicant_certificates_type+'</td>';
        }

        //投保人证件号码  需要投保人的证件类型来验证 如果是身份证需要：身份证，生日，性别 三者一起验证
        if(n.applicant_certificates_code == ""||n.applicant_certificates_code==null){
            //省份证不对
            excels_flag = false;
            wrong_text_applicant += '证件号码输入错误  ';
            pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_certificates_code" title="证件号码输入错误">'+n.applicant_certificates_code+'</td>';

        }
        else
        {
            if(n.applicant_certificates_type==str_type_code) //动态获取身份类型验证
            {
                if(isIdCardNo(n.applicant_certificates_code)!=0)
                {
                    //省份证不对
                    excels_flag = false;
                    wrong_text_applicant += '证件号码输入错误';
                    pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_certificates_code" title="证件号码输入错误">'+n.applicant_certificates_code+'</td>';
                }else{
                    var br_Temp = '';
                    var sex_tempint = '';
                    var sex_temp = '';
                    if(n.applicant_certificates_code.length==15){
                        sex_tempint = n.applicant_certificates_code.substring(14,15);  //M F
                        sex_temp = (((parseInt(sex_tempint))%2) ==0)?fMan_temp:man_temp;
                        br_Temp = n.applicant_certificates_code.substring(6,8)+'-'+n.applicant_certificates_code.substring(8,10)+'-'+n.applicant_certificates_code.substring(10,12);
                        if(n.applicant_birthday.substring(2,9)!=br_Temp||sex_temp!=n.applicant_gender){
                            //省份证  生日  性别 不匹配
                            excels_flag = false;
                            wrong_text_applicant += '证件号码和生日和性别不匹配';
                            pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_certificates_code" title="证件号码和生日和性别不匹配">'+n.applicant_certificates_code+'</td>';
                        }else{
                            pr_listhtml_applicant += '<td class=" tac" data-value="applicant_certificates_code">'+n.applicant_certificates_code+'</td>';
                        }
                    }else if(n.applicant_certificates_code.length==18){
                        sex_tempint = n.applicant_certificates_code.substring(16,17);
                        sex_temp = (((parseInt(sex_tempint))%2) ==0) ?fMan_temp:man_temp;
                        br_Temp = n.applicant_certificates_code.substring(6,10)+'-'+n.applicant_certificates_code.substring(10,12)+'-'+n.applicant_certificates_code.substring(12,14);
                        if(br_Temp!=n.applicant_birthday||sex_temp!=n.applicant_gender){
                            //省份证  生日  性别 不匹配
                            excels_flag = false;
                            wrong_text_applicant += '证件号码和生日和性别不匹配';
                            pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_certificates_code" title="证件号码和生日和性别不匹配">'+n.applicant_certificates_code+'</td>';
                        }else{
                            pr_listhtml_applicant += '<td class=" tac" data-value="applicant_certificates_code">'+n.applicant_certificates_code+'</td>';
                        }
                    }else{
                        pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_certificates_code">'+n.applicant_certificates_code+'</td>';
                    }

                }
            }else{
                pr_listhtml_applicant += '<td class=" tac" data-value="applicant_certificates_code">'+n.applicant_certificates_code+'</td>';
            }

        }



        //投保人性别
        if(n.applicant_gender==man_temp || n.applicant_gender==fMan_temp){
            pr_listhtml_applicant += '<td class=" tac" data-value="applicant_gender">'+n.applicant_gender+'</td>';
        }else{
            //性别不正确
            excels_flag = false;
            wrong_text_applicant += '性别代码格式错误 '+man_temp+'(男) '+fMan_temp+'(女) ';
            pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_gender" title="性别代码格式错误 '+man_temp+'(男) '+fMan_temp+'(女) ">'+n.applicant_gender+'</td>';
        }
        //投保人生日
        if(n.applicant_birthday.length==""){
            //生日不正确
            excels_flag = false;
            wrong_text_applicant += '生日格式错误不能为空';
            pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_birthday" title="生日格式错误不能为空">'+n.applicant_birthday+'</td>';
        }else{
            var bage = getAgeByBirthday(n.applicant_birthday);
            //alert("after getAgeByBirthday: "+bage);
            if(bage == -1)
            {

                excels_flag = false;
                wrong_text_applicant += '生日格式错误2014-10-12';
                pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_birthday" title="生日格式错误2014-10-12">'+n.applicant_birthday+'</td>';

            }else{
                var sAge = 18;
                var eAge = 65;
				if(eAge<max_toubaoren){
					eAge = max_toubaoren;
				}
                if(bage < sAge || bage>eAge)
                {
                    excels_flag = false;
                    wrong_text_applicant += '年龄必须在'+sAge+'-'+eAge+'之间';
                    pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_birthday" title="年龄必须在'+sAge+'-'+eAge+'之间">'+n.applicant_birthday+'</td>';

                }else{
                    pr_listhtml_applicant += '<td class=" tac" data-value="applicant_birthday">'+n.applicant_birthday+'</td>';
                }
            }

            //alert("before getAgeByBirthday");

        }

    }
    else if(n.applicant_type=="机构"){
        //机构名称
        if(n.applicant_name=="" || n.applicant_name==null)
        {
            //机构名称
            excels_flag = false;
            wrong_text_applicant += '机构名称不能为空 ';
            pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_name" title="机构名称不能为空">'+n.applicant_name+'</td>';
        }
        else
        {
            pr_listhtml_applicant += '<td class=" tac" data-value="applicant_name">'+n.applicant_name+'</td>';
        }
		
		if(cp_name=='epicc_jingwailvyou'){
			pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_englishname">'+n.applicant_englishname+'</td>';
		}
        if(cp_name=='huatai'){
            //投保人机构英文名
            if($.trim(n.applicant_englishname)=="" || n.applicant_englishname==null)
            {
                //投保人姓名不对
                excels_flag = false;
                wrong_text_applicant += '英文姓名格式错误 ';
                pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_englishname" title="英文姓名格式错误">'+n.applicant_englishname+'</td>';
            }
            else
            {
                pr_listhtml_applicant += '<td class=" tac" data-value="applicant_englishname">'+n.applicant_englishname+'</td>';
            }
        }
		if(cp_name=='pingan_lvyoujingwai'){
			pr_listhtml_applicant += '<td class=" tac" >--</td>';
		}
        //机构证件类型
        if(n.applicant_certificates_type==0||n.applicant_certificates_type==""||n.applicant_certificates_type==null)
        {
            //证件类型不对
            excels_flag = false;
            wrong_text_applicant += '机构类型格式错误  ';
            pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_certificates_type" title="机构类型格式错误">'+n.applicant_certificates_type+'</td>';
        }
        else
        {
            pr_listhtml_applicant += '<td class=" tac" data-value="applicant_certificates_type">'+n.applicant_certificates_type+'</td>';
        }

        //机构证件号码
        if(n.applicant_certificates_code == ""||n.applicant_certificates_code==null){
            //省份证不对
            excels_flag = false;
            wrong_text_applicant += '机构证件号码不能为空 ';
            pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_certificates_code" title="机构证件号码不能为空">'+n.applicant_certificates_code+'</td>';

        }
        else
        {
            pr_listhtml_applicant += '<td class=" tac " data-value="applicant_certificates_code">'+n.applicant_certificates_code+'</td>';

        }
        pr_listhtml_applicant += '<td class=" tac ">--</td>';
        pr_listhtml_applicant += '<td class=" tac ">--</td>';
    }

    //投保人邮箱
	if(!isEmail(n.applicant_email, true)||n.applicant_email==null||n.applicant_email==""){
		//邮箱不正确
 		excels_flag = false;
        wrong_text_applicant += '邮箱格式错误 XXX@163.com';
        pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="applicant_email" title="邮箱格式错误 XXX@163.com">'+n.applicant_email+'</td>';
	}else{
        pr_listhtml_applicant += '<td class=" tac"  data-value="applicant_email">'+n.applicant_email+'</td>';
	}

    //投保人电话
	if(!isMobile(n.applicant_mobilephone,true)||isNull(n.applicant_mobilephone)){
		//手机号不正确
		excels_flag = false;
        wrong_text_applicant += '手机号错误 13912345678';
        pr_listhtml_applicant += '<td class=" tac wrong_bg"  data-value="applicant_mobilephone" title="手机号错误 13912345678">'+n.applicant_mobilephone+'</td>';
	}else{
        pr_listhtml_applicant += '<td class=" tac" data-value="applicant_mobilephone">'+n.applicant_mobilephone+'</td>';
	}
    //不同产品不同的处理方式  通过cp_name
    if(cp_name=='pingan_lvyou' || cp_name=='huaan_xueping' || cp_name=='epicc_vehicle'  || cp_name=='epicc_lvyou'|| cp_name=='epicc_jingwailvyou' || cp_name=='pingan_lvyoujingwai' || cp_name=='pingan_yiwai' || cp_name=='chinalife_xueping' ){
        //与被保险人关系
        if(n.relationship==""){
            excels_flag = false;
            wrong_text_applicant += '与被保险人关系有误';
            pr_listhtml_applicant += '<td class=" tac wrong_bg" data-value="relationship" title="与被保险人关系有误">'+n.relationship+'</td>';
        }else{
            pr_listhtml_applicant += '<td class=" tac" data-value="relationship">'+n.relationship+'</td>';
        }
    }else if(cp_name=='taipingyang' || cp_name=='picclife_product' ||cp_name=='taipingyang_tj_product'){
        pr_listhtml_applicant += '<td class=" tac" >--</td>';
    }else if(cp_name=='huatai'){
        pr_listhtml_applicant += '<td class=" tac" >--</td><td class="tac">--</td><td class="tac">--</td>';
    }
	if(cp_name=='epicc_jingwailvyou'){
		pr_listhtml_applicant += '<td class=" tac" >--</td>';
	}
	if(cp_name=='huaan_xueping'){
		pr_listhtml_applicant += '<td class=" tac" >--</td>';
	}
    if(cp_name=='huaan_xueping' || cp_name=='epicc_vehicle'||cp_name=='taipingyang_tj_product' || cp_name=='epicc_lvyou'|| cp_name=='epicc_jingwailvyou'){
		pr_listhtml_applicant += '<td class=" tac" ><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; width:95px;color:red">'+wrong_text_applicant+'</div></td><td class="tac" rowspan="2"><a href="javascript:void(0)" onclick="deleteRow(this,1)" class="btn_link002">删</a></td></tr>';
	}else if(cp_name=='picclife_product'){
		pr_listhtml_applicant += '<td class=" tac" ><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; width:95px;color:red">'+wrong_text_applicant+'</div></td><td class="tac" rowspan="2"><a href="javascript:void(0)" onclick="deleteRow(this,1)" class="btn_link002">删</a></td></tr>';
	}else{
		pr_listhtml_applicant += '<td class="tac">--</td><td class=" tac" ><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; width:95px;color:red">'+wrong_text_applicant+'</div></td><td class="tac" rowspan="2"><a href="javascript:void(0)" onclick="deleteRow(this,1)" class="btn_link002">删</a></td></tr>';
	}
    
 /*   pr_listhtml_applicant += '<td class="tac" data-value="applicant_name">'+n.applicant_name+'</td>';
    pr_listhtml_applicant += '<td class="tac" data-value="applicant_certificates_type">'+n.applicant_certificates_type+'</td>';
    pr_listhtml_applicant += '<td class="tac" data-value="applicant_certificates_code">'+n.applicant_certificates_code+'</td>';
    pr_listhtml_applicant += '<td class="tac" data-value="applicant_gender">'+n.applicant_gender+'</td>';
    pr_listhtml_applicant += '<td class="tac" data-value="applicant_birthday">'+n.applicant_birthday+'</td>';
    pr_listhtml_applicant += '<td class="tac" data-value="applicant_email">'+n.applicant_email+'</td>';
    pr_listhtml_applicant += '<td class="tac" data-value="applicant_mobilephone">'+n.applicant_mobilephone+'</td>';
    pr_listhtml_applicant += '<td class="tac" data-value="relationship">'+n.relationship+'</td>';
    pr_listhtml_applicant += '<td class="tac">--</td>';*/

    /*
     *被保险人信息的验证
     *
     */

    //被保险人姓名
    if(strlen(n.assured_name)>24||strlen(n.assured_name)<3||n.assured_name==""||n.assured_name==null)
    {
        //投保人姓名不对
        excels_flag = false;
        wrong_text_assured += '被保险人姓名格式错误';
        pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_name" title="被保险人姓名有误">'+n.assured_name+'</td>';
    }
    else
    {
        pr_listhtml_assured += '<td class=" tac" data-value="assured_name">'+n.assured_name+'</td>';
    }
    if(cp_name=='huatai'){
        //被保险人英文名
        if($.trim(n.assured_englishname)=="" || n.assured_englishname==null)
        {
            //被保险人英文名不对
            excels_flag = false;
            wrong_text_assured += '英文姓名格式错误';
            pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_englishname" title="英文姓名格式错误">'+n.assured_englishname+'</td>';
        }
        else
        {
            pr_listhtml_assured += '<td class=" tac" data-value="assured_englishname">'+n.assured_englishname+'</td>';
        }
    }
	if(cp_name=='pingan_lvyoujingwai'){
        pr_listhtml_assured += '<td class=" tac" data-value="assured_englishname">'+n.assured_englishname+'</td>';
    }
    //被保险人证件类型
    if(n.assured_certificates_type==0||n.assured_certificates_type==""||n.assured_certificates_type==null)
    {
        //证件类型不对
        excels_flag = false;
        wrong_text_assured += '证件类型格式错误';
        pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_type" title="证件类型格式错误">'+n.assured_certificates_type+'</td>';
    }
    else
    {
        pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_type">'+n.assured_certificates_type+'</td>';
    }

    //被保险人证件号码  需要被保险人的证件类型来验证 如果是身份证需要：身份证，生日，性别 三者一起验证
    if(n.assured_certificates_code == ""||n.assured_certificates_code==null){
        //省份证不对
        excels_flag = false;
        wrong_text_assured += '证件号码输入错误';
        pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码输入错误">'+n.assured_certificates_code+'</td>';

    }
    else
    {
        if(n.assured_certificates_type==str_type_code) //动态获取身份类型验证
        {
            if(isIdCardNo(n.assured_certificates_code)!=0)
            {
                //省份证不对
                excels_flag = false;
                wrong_text_assured += '证件号码输入错误  ';
                pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码输入错误">'+n.assured_certificates_code+'</td>';
            }else{
                var br_Temp = '';
                var sex_tempint = '';
                var sex_temp = '';
                if(n.assured_certificates_code.length==15){
                    sex_tempint = n.assured_certificates_code.substring(14,15);  //M F
                    sex_temp = (((parseInt(sex_tempint))%2) ==0)?fMan_temp:man_temp;
                    br_Temp = n.assured_certificates_code.substring(6,8)+'-'+n.assured_certificates_code.substring(8,10)+'-'+n.assured_certificates_code.substring(10,12);
                    if(n.assured_birthday.substring(2,9)!=br_Temp||sex_temp!=n.assured_gender){
                        //省份证  生日  性别 不匹配
                        excels_flag = false;
                        wrong_text_assured += '证件号码和生日和性别不匹配  ';
                        pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码和生日和性别不匹配">'+n.assured_certificates_code+'</td>';
                    }else{
                        pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
                    }
                }else if(n.assured_certificates_code.length==18){
                    sex_tempint = n.assured_certificates_code.substring(16,17);
                    sex_temp = (((parseInt(sex_tempint))%2) ==0) ?fMan_temp:man_temp;
                    br_Temp = n.assured_certificates_code.substring(6,10)+'-'+n.assured_certificates_code.substring(10,12)+'-'+n.assured_certificates_code.substring(12,14);
                    if(br_Temp!=n.assured_birthday||sex_temp!=n.assured_gender){
                        //省份证  生日  性别 不匹配
                        excels_flag = false;
                        wrong_text_assured += '证件号码和生日和性别不匹配  ';
                        pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code" title="证件号码和生日和性别不匹配">'+n.assured_certificates_code+'</td>';
                    }else{
                        pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
                    }
                }else{
                    pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
                }

            }
        }else{
            pr_listhtml_assured += '<td class=" tac" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
        }

    }


    //被保险人性别
    if(n.assured_gender==man_temp || n.assured_gender==fMan_temp){
        pr_listhtml_assured += '<td class=" tac" data-value="assured_gender">'+n.assured_gender+'</td>';
    }else{
        //性别不正确
        excels_flag = false;
        wrong_text_assured += '性别代码格式错误 '+man_temp+'(男) '+fMan_temp+'(女) ';
        pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_gender" title="性别代码格式错误 '+man_temp+'(男) '+fMan_temp+'(女)">'+n.assured_gender+'</td>';
    }
    //被保险人生日
    if(n.assured_birthday.length==""){
        //生日不正确
        excels_flag = false;
        wrong_text_assured += '生日格式错误不能为空  ';
        pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_birthday" title="生日格式错误不能为空">'+n.assured_birthday+'</td>';
    }else{
        var bage = getAgeByBirthday(n.assured_birthday);
		if(cp_name=="huaan_xueping" && $('#xuepingxian').val()=='y'){
			bage = getAgeByBirthday(n.assured_birthday,'assured_birthday');
		}
        //alert("after getAgeByBirthday: "+bage);
        if(bage == -1)
        {

            excels_flag = false;
            wrong_text_assured += '生日格式错误2014-10-12  ';
            pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_birthday" title="生日格式错误2014-10-12">'+n.assured_birthday+'</td>';

        }else{
            var sAge = parseInt($('#age_min').val());
            var eAge = parseInt($('#age_max').val());
			if(cp_name=="huaan_xueping"){
				eAge = eAge-1;
			}
            if(bage < sAge || bage>eAge)
            {
                excels_flag = false;
				if(cp_name=="huaan_xueping"){
					wrong_text_assured += '年龄必须在'+sAge+'-'+(eAge+1)+'之间';
                	pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_birthday" title="年龄必须在'+sAge+'-'+(eAge+1)+'之间">'+n.assured_birthday+'</td>';
				}else{
					wrong_text_assured += '年龄必须在'+sAge+'-'+eAge+'之间';
                	pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_birthday" title="年龄必须在'+sAge+'-'+eAge+'之间">'+n.assured_birthday+'</td>';	
				}
                

            }else{
                pr_listhtml_assured += '<td class=" tac" data-value="assured_birthday">'+n.assured_birthday+'</td>';
            }
        }

        //alert("before getAgeByBirthday");

    }
	if(cp_name=="huaan_xueping" ){
    //被保险人邮箱
		
    	pr_listhtml_assured += '<td class=" tac" >--</td>';
		pr_listhtml_assured += '<td class=" tac" >--</td>';
		
	}else{
		if(!isEmail(n.assured_email, true) || n.assured_email==null || n.assured_email==""){
			//邮箱不正确
			excels_flag = false;
			wrong_text_assured += '邮箱格式错误 XXX@163.com';
			pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_email" title="邮箱格式错误 XXX@163.com">'+n.assured_email+'</td>';
		}else{
			pr_listhtml_assured += '<td class=" tac"  data-value="assured_email">'+n.assured_email+'</td>';
		}
		//被保险人电话
		
		 
		if(!isMobile(n.assured_mobilephone,true)||isNull(n.assured_mobilephone)){
			//手机号不正确
			excels_flag = false;
			wrong_text_assured += '手机号错误 13912345678';
			pr_listhtml_assured += '<td class=" tac wrong_bg"  data-value="assured_mobilephone" title="手机号错误 13912345678">'+n.assured_mobilephone+'</td>';
		}else{
			pr_listhtml_assured += '<td class=" tac" data-value="assured_mobilephone">'+n.assured_mobilephone+'</td>';
		}	
		 
	}

    
    //特殊处理
    if(cp_name=='taipingyang' ||cp_name=='picclife_product' || cp_name=="huatai" ||cp_name=='taipingyang_tj_product' ){

        if(n.relationship==""){
            excels_flag = false;
            wrong_text_assured += '与投保人关系有误';
            pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="relationship" title="与投保人关系有误">'+n.relationship+'</td>';
        }else{
            pr_listhtml_assured += '<td class=" tac" data-value="relationship">'+n.relationship+'</td>';
        }
    }else{
        pr_listhtml_assured += '<td class="tac">--</td>';
		if(cp_name=="huaan_xueping" ){
			if(n.assured_school_name=="" || n.assured_school_name==null){
				excels_flag = false;
				wrong_text_assured += '被保险人学校不能为空!';
				pr_listhtml_assured += '<td class=" tac wrong_bg" data-value="assured_school_name" title="被保险人学校不能为空!">'+n.assured_school_name+'</td>';
			}else{
				pr_listhtml_assured += '<td class=" tac"  data-value="assured_school_name">北京市'+n.assured_school_name+'</td>';
			}	
		}
    }
	
    if(cp_name=='pingan_lvyou' || cp_name=='pingan_lvyoujingwai' || cp_name=='epicc_jingwailvyou'){
		
			//被保险人目的地
			if($.trim(n.destination)=="" && cp_name=='pingan_lvyoujingwai'){
				//被保险人目的地不能为空
				excels_flag = false;
				wrong_text_assured += '被保险人目的地不能为空';
				pr_listhtml_assured += '<td class=" tac wrong_bg"  data-value="destination" title="被保险人目的地不能为空">&nbsp;</td>';
			}else if($.trim(n.destination)=="" && cp_name=='epicc_jingwailvyou'){
				//被保险人目的地不能为空
				excels_flag = false;
				wrong_text_assured += '被保险人目的地不能为空';
				pr_listhtml_assured += '<td class=" tac wrong_bg"  data-value="destination" title="被保险人目的地不能为空">&nbsp;</td>';
			}else{
				pr_listhtml_assured += '<td class="tac" data-value="destination">'+ n.destination+'</td>';
			}
		 
    }else if(cp_name=='taipingyang' || cp_name=='pingan_yiwai' || cp_name=='chinalife_xueping' ){
        //职业代码
		 
		if($.trim(n.occupation)==""){
			//被保险人目的地不能为空
		   //excels_flag = false;
			wrong_text_assured += '被保险人职业类别';
			pr_listhtml_assured += '<td class="tac"  data-value="occupation" title="被保险人职业类别">&nbsp;</td>';
		}else{
			pr_listhtml_assured += '<td class="tac" data-value="occupation">'+ n.occupation+'</td>';
		}
		 
    }else if(cp_name=='huatai'){
        //被保险人目的地
        if($.trim(n.destination)==""){
            //被保险人目的地不能为空
            excels_flag = false;
            wrong_text_assured += '被保险人目的地不能为空';
            pr_listhtml_assured += '<td class=" tac wrong_bg"  data-value="destination" title="被保险人目的地不能为空">&nbsp;</td>';
        }else{
            pr_listhtml_assured += '<td class="tac" data-value="destination">'+ n.destination+'</td>';
        }
        //被保险人出行目的
        if($.trim(n.purpose)==""){
            //被保险人出行目的
            excels_flag = false;
            wrong_text_assured += '被保险人出行目的不能为空';
            pr_listhtml_assured += '<td class=" tac wrong_bg"  data-value="purpose" title="被保险人出行目的不能为空">&nbsp;</td>';
        }else{
            pr_listhtml_assured += '<td class="tac" data-value="purpose">'+ n.purpose+'</td>';
        }
        //被保险人签证城市
        if($.trim(n.visacity)==""){
            //被保险人签证城市
            excels_flag = false;
            wrong_text_assured += '被保险人签证城市不能为空';
            pr_listhtml_assured += '<td class=" tac wrong_bg"  data-value="visacity" title="被保险人签证城市不能为空">&nbsp;</td>';
        }else{
            pr_listhtml_assured += '<td class="tac" data-value="visacity">'+ n.visacity+'</td>';
        }

    }
	pr_listhtml_assured += '<td class="tac" title="'+wrong_text_assured+'"><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; width:95px;color:red">'+ wrong_text_assured+'</div></td>';
    pr_listhtml_assured += '</tr>';
    /*pr_listhtml_assured += '<td class="tac">--</td>';
    pr_listhtml_assured += '<td class="tac" data-value="assured_name">'+n.assured_name+'</td>';
    pr_listhtml_assured += '<td class="tac" data-value="assured_certificates_type">'+n.assured_certificates_type+'</td>';
    pr_listhtml_assured += '<td class="tac" data-value="assured_certificates_code">'+n.assured_certificates_code+'</td>';
    pr_listhtml_assured += '<td class="tac" data-value="assured_gender">'+n.assured_gender+'</td>';
    pr_listhtml_assured += '<td class="tac" data-value="assured_birthday">'+n.assured_birthday+'</td>';
    pr_listhtml_assured += '<td class="tac" data-value="assured_email">'+n.assured_email+'</td>';
    pr_listhtml_assured += '<td class="tac" data-value="assured_mobilephone">'+n.assured_mobilephone+'</td>';
    pr_listhtml_assured += '<td class="tac">--</td>';
    pr_listhtml_assured += '<td class="tac" data-value="destination">'+ n.destination+'</td>';
    pr_listhtml_assured += '</tr>';*/

	var datas = {'flag':excels_flag,'pr_listhtml':pr_listhtml_applicant+pr_listhtml_assured};
	return  datas;

}

//验证 Y502 产品excels返回数据
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
        pr_listhtml += '<td class=" tac wrong_bg" data-value="fullname" title="姓名格式错误">'+n.fullname+'</td>';
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
        pr_listhtml += '<td class=" tac wrong_bg" data-value="certificates_type" title="证件类型格式错误">'+n.certificates_type+'</td>';
    }else{
        pr_listhtml += '<td class=" tac" data-value="certificates_type">'+n.certificates_type+'</td>';
    }
    if(n.certificates_code == ""||n.certificates_code==null){
        //省份证不对
        excels_flag = false;
        wrong_text += '证件号码输入错误  ';
        pr_listhtml += '<td class=" tac wrong_bg" data-value="certificates_code" title="证件号码输入错误">'+n.certificates_code+'</td>';

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
                pr_listhtml += '<td class=" tac wrong_bg" data-value="certificates_code" title="证件号码输入错误">'+n.certificates_code+'</td>';
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
                        wrong_text += '证件号码和生日和性别不匹配  ';
                        pr_listhtml += '<td class=" tac wrong_bg" data-value="certificates_code" title="证件号码和生日和性别不匹配">'+n.certificates_code+'</td>';
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
                        wrong_text += '证件号码和生日和性别不匹配  ';
                        pr_listhtml += '<td class=" tac wrong_bg" data-value="certificates_code" title="证件号码和生日和性别不匹配">'+n.certificates_code+'</td>';
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
        pr_listhtml += '<td class=" tac wrong_bg" data-value="gender" title="性别代码格式错误 '+man_temp+'(男) '+fMan_temp+'(女) ">'+n.gender+'</td>';
    }

    if(n.birthday.length==""){
        //生日不正确
        excels_flag = false;
        wrong_text += '生日格式错误2014-10-12  ';
        pr_listhtml += '<td class=" tac wrong_bg" data-value="birthday" title="生日格式错误2014-10-12 ">'+n.birthday+'</td>';
    }else{
        var bage = getAgeByBirthday(n.birthday);
        //alert("after getAgeByBirthday: "+bage);
        if(bage == -1)
        {

            excels_flag = false;
            wrong_text += '生日格式错误2014-10-12  ';
            pr_listhtml += '<td class=" tac wrong_bg" data-value="birthday" title="生日格式错误2014-10-12">'+n.birthday+'</td>';

        }else{
            var sAge = parseInt($('#age_min').val());
            var eAge = parseInt($('#age_max').val());

            if(bage < sAge || bage>eAge)
            {
                excels_flag = false;
                wrong_text += '年龄必须在'+sAge+'-'+eAge+'之间';
                pr_listhtml += '<td class=" tac wrong_bg" data-value="birthday" title="年龄必须在'+sAge+'-'+eAge+'之间">'+n.birthday+'</td>';

            }else{
                pr_listhtml += '<td class=" tac" data-value="birthday">'+n.birthday+'</td>';
            }



        }

        //alert("before getAgeByBirthday");



    }

    
	if($('#attribute_type').val()=='Y502'){
		if(n.email==null||n.email==""){
			pr_listhtml += '<td class=" tac"  data-value="email">'+n.email+'</td>';
		}else{
			if(!isEmail(n.email, true)){
				//邮箱不正确
				excels_flag = false;
				wrong_text += '邮箱格式错误 XXX@163.com';
				pr_listhtml += '<td class=" tac wrong_bg" data-value="email" title="邮箱格式错误 XXX@163.com">'+n.email+'</td>';
			}else{
				pr_listhtml += '<td class=" tac"  data-value="email">'+n.email+'</td>';
			}
		}
		
		if(isNull(n.mobiletelephone)){
			pr_listhtml += '<td class=" tac" data-value="mobiletelephone">'+n.mobiletelephone+'</td>'; 
		}else{
			if(!isMobile(n.mobiletelephone,true)){
				excels_flag = false;
				wrong_text += '手机号错误 139123456789  ';
				pr_listhtml += '<td class=" tac wrong_bg"  data-value="mobiletelephone" title="手机号错误 139123456789">'+n.mobiletelephone+'</td>';
			}else{
				pr_listhtml += '<td class=" tac" data-value="mobiletelephone">'+n.mobiletelephone+'</td>'; 
			}
		}
	}else{
		if(!isEmail(n.email, true)||n.email==null||n.email==""){
			//邮箱不正确
			excels_flag = false;
			wrong_text += '邮箱格式错误 XXX@163.com';
			pr_listhtml += '<td class=" tac wrong_bg" data-value="email" title="邮箱格式错误 XXX@163.com">'+n.email+'</td>';
		}else{
			pr_listhtml += '<td class=" tac"  data-value="email">'+n.email+'</td>';
		}
		if(!isMobile(n.mobiletelephone,true)||isNull(n.mobiletelephone)){
			//手机号不正确
			excels_flag = false;
			wrong_text += '手机号错误 139123456789  ';
			pr_listhtml += '<td class=" tac wrong_bg"  data-value="mobiletelephone" title="手机号错误 139123456789">'+n.mobiletelephone+'</td>';
		}else{
		    pr_listhtml += '<td class=" tac" data-value="mobiletelephone">'+n.mobiletelephone+'</td>'; 
		}	
	}
    
    if($('#career').val()==1 || $('#career').val()==4 || $('#career').val()==5 || $('#career').val()=='1_4' || $('#career').val()=='1_5' || $('#career').val()=='4_5' || $('#career').val()=='1_4_5' ){//add by wangcya , 20141224, 增加这个限制为了什么呢？  这是职业类型  产品详情页面传递过来的值  可以判定出是哪一个职业类型
	  
	  if($('#career').val()==1){
		  if(n.level_num==1||n.level_num==2||n.level_num==3){
				pr_listhtml += '<td class=" tac" data-value="level_num">'+n.level_num+'</td>';
			}else{
	
				//	职业类型不正确或者和职业代码不相匹配
				excels_flag = false;
				wrong_text += '职业类型代码错误 1,2,3';
				pr_listhtml += '<td class=" tac wrong_bg" data-value="level_num" title="职业类型代码错误">'+n.level_num+'</td>';
			}
	  }
	  if($('#career').val()==4){
		  if(n.level_num==4){
				pr_listhtml += '<td class=" tac" data-value="level_num">'+n.level_num+'</td>';
			}else{
	
				//	职业类型不正确或者和职业代码不相匹配
				excels_flag = false;
				wrong_text += '职业类型代码错误 4';
				pr_listhtml += '<td class=" tac wrong_bg" data-value="level_num" title="职业类型代码错误">'+n.level_num+'</td>';
			}
	  }
	  
	  if($('#career').val()==5){
		  if(n.level_num==5 || n.level_num==6){
				pr_listhtml += '<td class=" tac" data-value="level_num">'+n.level_num+'</td>';
			}else{
	
				//	职业类型不正确或者和职业代码不相匹配
				excels_flag = false;
				wrong_text += '职业类型代码错误 5,6';
				pr_listhtml += '<td class=" tac wrong_bg" data-value="level_num" title="职业类型代码错误">'+n.level_num+'</td>';
			}
	  }
	  
	  if($('#career').val()=='1_4'){
		  if(n.level_num==1||n.level_num==2||n.level_num==3 || n.level_num==4){
				pr_listhtml += '<td class=" tac" data-value="level_num">'+n.level_num+'</td>';
			}else{
	
				//	职业类型不正确或者和职业代码不相匹配
				excels_flag = false;
				wrong_text += '职业类型代码错误 1,2,3,4';
				pr_listhtml += '<td class=" tac wrong_bg" data-value="level_num" title="职业类型代码错误">'+n.level_num+'</td>';
			}
	  }
	  if($('#career').val()=='1_5'){
		  if(n.level_num==1||n.level_num==2||n.level_num==3 || n.level_num==5 || n.level_num==6){
				pr_listhtml += '<td class=" tac" data-value="level_num">'+n.level_num+'</td>';
			}else{
	
				//	职业类型不正确或者和职业代码不相匹配
				excels_flag = false;
				wrong_text += '职业类型代码错误 1,2,3,5,6';
				pr_listhtml += '<td class=" tac wrong_bg" data-value="level_num" title="职业类型代码错误">'+n.level_num+'</td>';
			}
	  }
	  if($('#career').val()=='4_5'){
		  if(n.level_num==4 || n.level_num==5 || n.level_num==6){
				pr_listhtml += '<td class=" tac" data-value="level_num">'+n.level_num+'</td>';
			}else{
	
				//	职业类型不正确或者和职业代码不相匹配
				excels_flag = false;
				wrong_text += '职业类型代码错误 4,5,6';
				pr_listhtml += '<td class=" tac wrong_bg" data-value="level_num" title="职业类型代码错误">'+n.level_num+'</td>';
			}
	  }
	  
	  if($('#career').val()=='1_4_5'){
		  if(n.level_num==1 || n.level_num==2 || n.level_num==3 || n.level_num==4 || n.level_num==5 || n.level_num==6){
				pr_listhtml += '<td class=" tac" data-value="level_num">'+n.level_num+'</td>';
			}else{
	
				//	职业类型不正确或者和职业代码不相匹配
				excels_flag = false;
				wrong_text += '职业类型代码错误 1,2,3,4,5,6';
				pr_listhtml += '<td class=" tac wrong_bg" data-value="level_num" title="职业类型代码错误">'+n.level_num+'</td>';
			}
	  }
        
    }else{
        pr_listhtml += '<td class="tac"></td>';
    }
    pr_listhtml += '<td class="tac" title="'+wrong_text+'"><div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; width:95px;color:red">'+ wrong_text+'</div></td>';
    pr_listhtml += '<td class="tac"><a href="javascript:void(0)" onclick="deleteRow(this)" class="btn_link002">删</a></td></tr>';

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

//验证每一行(目前没有使用 这个验证只适用于 Y502 )
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
	var length_pro = 5;
	var cp_name_temp = $('#cp_name').val();
	if(cp_name_temp=='huaan_xueping'){
		length_pro = 1;
	}else if(cp_name_temp=='epicc_vehicle'){
		length_pro = 5;
	}else if(cp_name_temp=='epicc_lvyou'){
		length_pro = 5;
	}else if(cp_name_temp=='epicc_jingwailvyou'){
		length_pro = 5;
	}
	if($('#ps_list table tr').length>length_pro){ 
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
				var checkedRow_datas = '';
				if(cp_name_temp=='huaan_xueping' || cp_name_temp=='epicc_vehicle' || cp_name_temp=='taipingyang_tj_product' || cp_name_temp=='epicc_lvyou'|| cp_name_temp=='epicc_jingwailvyou'){  //华安  学平险
					length_pro = 1;
					if(cp_name_temp=='epicc_vehicle' || cp_name_temp=='taipingyang_tj_product'  || cp_name_temp=='epicc_lvyou'|| cp_name_temp=='epicc_jingwailvyou'){
						length_pro = 5;
					}
					checkedRow_datas = checkExcelsAll_huaanxueping(temp_obj_Row,i_A);
				}else{
					checkedRow_datas = checkExcels(temp_obj_Row);	 //平安 Y502
					
				}
				
				if(checkedRow_datas.flag==false){
					flag_t = checkedRow_datas.flag;
                    return false;   //退出循环
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

//新的批量验证 测试阶段  除Y502产品以外的产品批量处理
function checkedAll_New(){
    //alert("checkedAll");
    var excel_content = [];
    var temp_obj = '';
    var flag_t = true;
    if($('#ps_list table tr').length>2){
        var code_list_Temps = [];
        var email_list_Temps = [];
        var mobiletelephone_list_Temps = [];
        var length_temp = $('#ps_list table tr').length-1;
        $('#ps_list table tr').each(function(i_A,n_A ) {

            if(i_A%2==0 && length_temp>i_A){
                var temp_obj_Row = {};

                $('#ps_list table tr').eq(i_A+1).children('td').each(function(i_ck, n_ck) {

                    if($(this).data("value")){
                        temp_obj_Row[$(this).data("value")] = $.trim($(this).text());
                    }
                });
                $('#ps_list table tr').eq(i_A+2).children('td').each(function(i_ck, n_ck) {

                    if($(this).data("value")){
                        temp_obj_Row[$(this).data("value")] = $.trim($(this).text());
                        if($(this).data("value")=="assured_code"){
                         code_list_Temps.push($.trim($(this).text()));
                         }
                         if($(this).data("value")=="assured_email"){
                         email_list_Temps.push($.trim($(this).text()));
                         }
                         if($(this).data("value")=="assured_mobiletelephone"){
                         mobiletelephone_list_Temps.push($.trim($(this).text()));
                         }
                    }
                });

                excel_content.push(temp_obj_Row);
                var checkedRow_datas = checkExcelsAll(temp_obj_Row,(i_A+1));
                if(checkedRow_datas.flag==false){
                    flag_t = checkedRow_datas.flag;
                    return false;  //退出循环
                }
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
function deleteRow(d_ef,sequence_num){
    if(!sequence_num){
        if($('#ps_list table tr').length-1>5){
            $(d_ef).parent().parent().remove();
        }else{
            layer.alert('你的操作失败:最少人员数5人',9,'温馨提示');
        }
    }else{
        $(d_ef).parent().parent().next().remove();
        $(d_ef).parent().parent().remove();
    }
    sum_price(); //计算价格
}

//修改一行updateRow
//<a href="javascript:void(0)" onclick="updateRow(this,1)" class="btn_link002">改</a>&nbsp;  批量
//<a href="javascript:void(0)" onclick="updateRow(this)" class="btn_link002">改</a>&nbsp;  团单
function updateRow(d_ef,sequence_num){
	if(!sequence_num){
        
    }else{
        
    }	
}

//增加一行
function addRow(){
	//$(ef).parent().parent();
}


//上传文件并且对返回值做验证处理（Y502）
function importXLs(file_id,proid,min_persion_num,max_persion_num,career){
	var career_temp = '';
	var cp_name = $('#cp_name').val();
	if(career){ //对于Y502产品
		career_temp = '&career='+career;
	}
	if(cp_name=='huaan_xueping' || cp_name=='epicc_vehicle' || cp_name=='taipingyang_tj_product' || cp_name=='epicc_lvyou'|| cp_name=='epicc_jingwailvyou'){
		
		min_persion_num = parseInt($('#tuandan_text').text());
	}
	//$('#commit_type_id').val() 是华安产品的批量上传和机构的批量上传
	var data_post = {};
	if($('#commit_type_id').val()=='tuandan' || $('#commit_type_id').val()=='piliang'||$('#commit_type_id').val()=='jingwai_tuandan' || $('#commit_type_id').val()=='jingwai_piliang'){
		data_post = {'commit_type':$('#commit_type_id').val()};
	}
	//alert("sfdfsdfssss");
    var loadi = layer.load('文件正在上传中…');
	jQuery.ajaxFileUpload({
		url : './cp.php?ac=product_upload&op=uploadfile_insured_xls&product_id='+proid+career_temp, //用于文件上传的服务器端请求地址
		secureuri : false, //是否需要安全协议，一般设置为false
		fileElementId : file_id, //文件上传域的ID
		type:'POST',
		data:data_post,
		dataType : 'json', //返回值类型 一般设置为json
		success : function(res, status){//服务器成功响应处理函数
            layer.close(loadi);
			var bat_post_policy_temp = $('#bat_post_policy').val();//0,1,2  0-单个  1 批量  2 Y502批量
			
			if(res.result_msg=='success'){
				if(res.data.length>=min_persion_num && res.data.length<=max_persion_num){
					var lever_numList = [];
					var wrong_html = '';
					var code_list_Temp =[];
					var email_list_Temp =[];
					var phone_list_Temp =[];
					
                    var temp_flag_upload = true;
					$(res.data).each(function(i, n) {
						 
						lever_numList.push(n.level_num);
                        var check_res = '';
                        if(cp_name=='huaan_xueping' || cp_name=='epicc_vehicle' || cp_name=='taipingyang_tj_product' || cp_name=='epicc_lvyou'|| cp_name=='epicc_jingwailvyou'){ //华安学平险
							if(bat_post_policy_temp==0){ //机构
								code_list_Temp.push(n.assured_certificates_code);
								check_res = checkExcelsAll_huaanxueping(n,i);
							}else if(bat_post_policy_temp==1){  //批量
								code_list_Temp.push(n.assured_certificates_code);
								phone_list_Temp.push(n.assured_email);
								email_list_Temp.push(n.assured_email);
								check_res = checkExcelsAll(n,i);
							}	
						}else{
							if(bat_post_policy_temp==1){
								code_list_Temp.push(n.assured_certificates_code);
								phone_list_Temp.push(n.assured_email);
								email_list_Temp.push(n.assured_email);
								check_res = checkExcelsAll(n,i);
							}else if(bat_post_policy_temp==2){
								code_list_Temp.push(n.certificates_code);
								phone_list_Temp.push(n.mobiletelephone);
								email_list_Temp.push(n.email);
								check_res = checkExcels(n);
							}	
						}
						
                        wrong_html+=check_res.pr_listhtml;
                        if(check_res.flag==false){
                            temp_flag_upload = check_res.flag;
                        }
					});
					var pr_listhtml = '';
                    /*cp_name是区分产品字段的显示与否、字段名称的改变*/
                    
                    var titlenameTemp = '出行目的地';
                    var relationship_text = '被保险人';
                    var temp_th = '';
                    var layer_text_add = '';
                    var english_name_text = '';
					var max_toubaoren = 65;
					 
                    if(cp_name=='pingan_lvyou' || cp_name=='pingan_lvyoujingwai'){
                        titlenameTemp = '出行目的地';
                        relationship_text = '被保险人';
                        layer_text_add = '出行目的地';
						max_toubaoren = 80; //平安旅游特定投保人年龄 最大年龄80岁 
						if(cp_name=='pingan_lvyoujingwai'){
							english_name_text = '<th class="fwb tac">英文名</th>';	
						}
                    }else if(cp_name=='taipingyang'){
                        titlenameTemp = '职业类别';
                        relationship_text = '投保人';
                        layer_text_add = '职业类别';
                    }else if(cp_name=='pingan_yiwai'){
                        titlenameTemp = '职业类别';
                        relationship_text = '被保险人';
                        layer_text_add = '职业类别';
                    }else if(cp_name=='chinalife_xueping'){
                        titlenameTemp = '职业类别';
                        relationship_text = '被保险人';
                        layer_text_add = '职业类别';
                    }else if(cp_name=='huatai'){
                        titlenameTemp = '目的地国家';
                        relationship_text = '投保人';
                        layer_text_add = '英文名、目的地国家、出行目的、签证城市';
                        english_name_text = '<th class="fwb tac">英文名</th>';
                        temp_th = '<th class="fwb tac">出行目的</th><th class="fwb tac">签证城市</th>';
                    }else if(cp_name=='huaan_xueping'){
						relationship_text = '被保险人';	
						titlenameTemp = '职业类别';
						layer_text_add = '职业类别';
					}else if(cp_name=='epicc_vehicle'){
						relationship_text = '被保险人';	
						 
					}else if(cp_name=='taipingyang_tj_product'){
						 
                        relationship_text = '投保人';
                         
					}else if(cp_name=='picclife_product'){
                        relationship_text = '投保人'; 
                    }
					if(cp_name=='epicc_vehicle' || cp_name=='taipingyang_tj_product' || cp_name=='picclife_product' || cp_name=='epicc_lvyou' || cp_name=='epicc_jingwailvyou' ){
						if(bat_post_policy_temp==0){ //其他
							pr_listhtml = '<div class="tac p10">备注：<i class="wrong_block_bg"></i> 是内容(或相关联的内容)不符合规范部分</div>' +
							'<table cellpadding="0" cellspacing="0" border="1" width="98%" class="border_table_05"  >' +
							'<tr><th class="fwb tac">序号</th>' +
							'<th class="fwb tac">姓名</th>';
							if(cp_name=='epicc_jingwailvyou'){
								pr_listhtml += '<th class="fwb tac">英文姓名</th>';
							}
							pr_listhtml += '<th class="fwb tac">证件类型</th>' +
							'<th class="fwb tac">证件号码</th>' +
							'<th class="fwb tac">出生日期</th>' +
							'<th class="fwb tac">性别</th>' +
							'<th class="fwb tac">手机号码</th>' +
							'<th class="fwb tac">邮箱</th>' +
							'<th class="fwb tac w100">提示信息</th>' +
							'</tr>';
						
						}else{
							pr_listhtml = '<div class="tac p10">备注：<i class="wrong_block_bg"></i> 是内容(或相关联的内容)不符合规范部分</div>' +
							'<table cellpadding="0" cellspacing="0" border="1" width="98%" class="border_table_05"  >' +
							'<tr><th class="fwb tac">序号</th>' +
							'<th class="fwb tac">类型</th>'+
							'<th class="fwb tac">投保人类型</th>'+
							'<th class="fwb tac">姓名</th>';
							if(cp_name=='epicc_jingwailvyou'){
								pr_listhtml += '<th class="fwb tac">英文姓名</th>';
							}
							pr_listhtml += '<th class="fwb tac">证件类型</th>' +
							'<th class="fwb tac">证件号码</th>' +
							'<th class="fwb tac">性别</th>' +
							'<th class="fwb tac">生日</th>' +
							'<th class="fwb tac">邮箱</th>' +
							'<th class="fwb tac">手机号</th>' +
							'<th class="fwb tac">与'+relationship_text+'关系</th>' ;
							if(cp_name=='epicc_jingwailvyou'){
								pr_listhtml += '<th class="fwb tac">出行目的地</th>';
							} 
							pr_listhtml += '<th class="fwb tac w100">提示信息</th><th class="fwb tac">操作</th>' +
							 
							'</tr>';
								
						}
							
					}else if(cp_name=='huaan_xueping'){
						if(bat_post_policy_temp==0){ //其他
							pr_listhtml = '<div class="tac p10">备注：<i class="wrong_block_bg"></i> 是内容(或相关联的内容)不符合规范部分<br><span class="red">被保险人年龄计算公式 ：年龄 = "保险起期" - "生日日期" </span></div>' +
							'<table cellpadding="0" cellspacing="0" border="1" width="98%" class="border_table_05"  >' +
							'<tr><th class="fwb tac">序号</th>' +
							'<th class="fwb tac">姓名</th>' +
							'<th class="fwb tac">证件类型</th>' +
							'<th class="fwb tac">证件号码</th>' +
							'<th class="fwb tac">性别</th>' +
							'<th class="fwb tac w100">提示信息</th>' +
							'</tr>';
						
						}else{
							pr_listhtml = '<div class="tac p10">备注：<i class="wrong_block_bg"></i> 是内容(或相关联的内容)不符合规范部分<br><span class="red">投保人年龄计算公式：年龄 = "投保日期" - "生日日期" ；被保险人年龄计算公式 ：年龄 = "保险起期" - "生日日期"</span> </div>' +
							'<table cellpadding="0" cellspacing="0" border="1" width="98%" class="border_table_05"  >' +
							'<tr><th class="fwb tac">序号</th>' +
							'<th class="fwb tac">类型</th>'+
							'<th class="fwb tac">投保人类型</th>'+
							'<th class="fwb tac">姓名</th>'+
							'<th class="fwb tac">证件类型</th>' +
							'<th class="fwb tac">证件号码</th>' +
							'<th class="fwb tac">性别</th>' +
							'<th class="fwb tac">生日</th>' +
							'<th class="fwb tac">邮箱</th>' +
							'<th class="fwb tac">手机号</th>' +
							
							'<th class="fwb tac">与'+relationship_text+'关系</th>' +
							'<th class="fwb tac">学校名称</th>' +
							 
							'<th class="fwb tac w100">提示信息</th><th class="fwb tac">操作</th>' +
							 
							'</tr>';
								
						}
							
					}else{
						if(bat_post_policy_temp==1){ //其他
	
							pr_listhtml = '<div class="tac p10">备注：<i class="wrong_block_bg"></i> 是内容(或相关联的内容)不符合规范部分</div>' +
							'<table cellpadding="0" cellspacing="0" border="1" width="98%" class="border_table_05"  >' +
							'<tr><th class="fwb tac">序号</th>' +
							'<th class="fwb tac">类型</th>'+
							'<th class="fwb tac">投保人类型</th>'+
							'<th class="fwb tac">姓名</th>' +english_name_text+
							'<th class="fwb tac">证件类型</th>' +
							'<th class="fwb tac">证件号码</th>' +
							'<th class="fwb tac">性别</th>' +
							'<th class="fwb tac">生日</th>' +
							'<th class="fwb tac">邮箱</th>' +
							'<th class="fwb tac">手机号</th>' +
							'<th class="fwb tac">与'+relationship_text+'关系</th>' +
							'<th class="fwb tac">'+titlenameTemp+'</th>' +temp_th+
							'<th class="fwb tac w100">提示信息</th><th class="fwb tac">操作</th>' +
							 
							'</tr>';
	
						}else if(bat_post_policy_temp==2){ //Y502
							pr_listhtml = '<div class="tac p10">备注：<i class="wrong_block_bg"></i> 是内容(或相关联的内容)不符合规范部分</div><table cellpadding="0" cellspacing="0" border="1" width="98%" class="border_table_05"  ><tr><th class="fwb tac">姓名</th><th class="fwb tac">证件类型</th><th class="fwb tac">证件号码</th><th class="fwb tac">职业代码</th><th class="fwb tac">性别</th><th class="fwb tac">生日</th><th class="fwb tac">邮箱</th><th class="fwb tac">手机号</th><th class="fwb tac">职业类别</th><th class="fwb tac w100">提示信息</th><th class="fwb w100 tac">操作</th></tr>';
						}
					}
					pr_listhtml+=wrong_html;
					pr_listhtml += '</table>';
					if($('#product_code').val()=='Y502'){
						$('#lever_numList').val(lever_numList.join(',')); 
					}
					$('#ps_list').html(pr_listhtml);
                    sum_price();//计算价格
					
                    if(temp_flag_upload==false){
                        var layer_html_temp = '验证失败可能原因如下：<br>1.被保险人员数小于'+min_persion_num+'人或者大于'+max_persion_num+'人<br>2.被保险人名单中重复的证件号码、手机号、邮箱<br>4.被保险人身份证件号码无效<br>5.被保险人年龄不在投保范围之内(18 至 65岁)<br><br>请核实后再次提交!';
                        var sAge = parseInt($('#age_min').val());//被保险人最小年龄
						var eAge = parseInt($('#age_max').val());//被保险人最大年龄
						if(cp_name=='huaan_xueping'){
							if(bat_post_policy_temp==0){
								layer_html_temp = '验证失败可能原因如下：<br>1.被保险人姓名、证件类型、身份证件号码无效,<br>2.被保险人身份证号码和生日不匹配,<br>3.被保险人员数小于'+min_persion_num+'人或者大于'+max_persion_num+'人<br>4.被保险人年龄不在投保范围之内('+sAge+' 至 '+eAge+'岁)<br>请核实后再次提交!';
							}
							if(bat_post_policy_temp==1){ 
								layer_html_temp = '验证失败可能原因如下：<br>1.投保人类型、身份类型、身份证件号码无效,<br>2.投保人年龄不在投保范围之内(18 至 '+max_toubaoren+'岁)<br>3.被保险人员数小于'+min_persion_num+'人或者大于'+max_persion_num+'人<br>4.被保险人名单中重复的证件号码、手机号、邮箱<br>5.被保险人身份证件号码无效<br>6.被保险人年龄不在投保范围之内('+sAge+' 至 '+eAge+'岁)<br>7.被保险人'+layer_text_add+'不能为空<br>8.被保险人学校名称没有填写<br>请核实后再次提交!';
							}
						}else if(cp_name=='epicc_vehicle' || cp_name=='taipingyang_tj_product' || cp_name=='epicc_lvyou' || cp_name=='epicc_jingwailvyou'){
							if(bat_post_policy_temp==0){
								layer_html_temp = '验证失败可能原因如下：<br>1.被保险人姓名、证件类型、身份证件号码、生日、性别无效,<br>2.被保险人身份证号码和生日不匹配,<br>3.被保险人员数小于'+min_persion_num+'人或者大于'+max_persion_num+'人<br>4.被保险人年龄不在投保范围之内('+sAge+' 至 '+eAge+'岁)<br>请核实后再次提交!';
							}
							if(bat_post_policy_temp==1){ 
								layer_html_temp = '验证失败可能原因如下：<br>1.投保人类型、身份类型、身份证件号码无效,<br>2.投保人年龄不在投保范围之内(18 至 '+max_toubaoren+'岁)<br>3.被保险人员数小于'+min_persion_num+'人或者大于'+max_persion_num+'人<br>4.被保险人名单中重复的证件号码、手机号、邮箱<br>5.被保险人身份证件号码无效<br>6.被保险人年龄不在投保范围之内('+sAge+' 至 '+eAge+'岁)<br>7.被保险人'+layer_text_add+'不能为空<br>请核实后再次提交!';
							}
						}else{
							if(bat_post_policy_temp==1){ //除Y502以外的判定(批量)
								
								layer_html_temp = '验证失败可能原因如下：<br>1.投保人类型、身份类型、身份证件号码无效,<br>2.投保人年龄不在投保范围之内(18 至 '+max_toubaoren+'岁)<br>3.被保险人员数小于'+min_persion_num+'人或者大于'+max_persion_num+'人<br>4.被保险人名单中重复的证件号码、手机号、邮箱<br>5.被保险人身份证件号码无效<br>6.被保险人年龄不在投保范围之内('+sAge+' 至 '+eAge+'岁)<br>7.被保险人'+layer_text_add+'不能为空<br>请核实后再次提交!';
	
							}
						}
                        layer.alert(layer_html_temp,9,'温馨提示');
                    }
					 //临时隐藏
					/*if(code_isRepeat(code_list_Temp)||code_isRepeat(email_list_Temp)||code_isRepeat(phone_list_Temp)){
						console.log(4); 
						layer.alert('上传文件中有重复的人员、手机号、邮箱,请处理后再次上传!',9,'温馨提示');	
						console.log(5); 
					}*/
					
					
				}else{
					layer.alert(max_persion_num+'人>=excel人员表大于>='+min_persion_num+'人',9,'温馨提示');
					
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
            layer.close(loadi);
			//alert(e);
            alert('上传出错！');
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



//点击切换(个单和批量)

function change_pro2more(num_val){
	$("input[name=assured_input_type][value="+num_val+"]").attr("checked",true);
	//alert($selectedvalue);
    $j('.pre_more_on').attr("class","pre_more_out");
    $j('.pro_more_div span').eq(parseInt(num_val)-1).attr("class","pre_more_on");
	var cp_name_temps_pro = $('#cp_name').val();
	if (num_val == 1) //manual,手工单个输入
	{
		//$j("input#businessType").val(1);

		$j("input#bat_post_policy").val(0);//add by wangcya, 20150106,是否批量投保，单独一个字段
		$j("#more_upload_list").hide(); 
		$j("#table_input_type_manual").show();
		$j('#str_relationshipWithInsured_T').show();
		 
		if(cp_name_temps_pro=='huaan_xueping' || cp_name_temps_pro=='epicc_vehicle' || cp_name_temps_pro=='taipingyang_tj_product' || cp_name_temps_pro=='epicc_lvyou' || cp_name_temps_pro=='epicc_jingwailvyou'){ //华安学平险 需要执行这段代码
			
			if(cp_name_temps_pro=='epicc_vehicle' || cp_name_temps_pro=='taipingyang_tj_product' || cp_name_temps_pro=='epicc_lvyou'|| cp_name_temps_pro=='epicc_jingwailvyou'){
				$j("#as_other").show();
				if(cp_name_temps_pro=='taipingyang_tj_product'){
					$('#down_wordxml').attr('href','ziliao/taipingyang/taipingyang_tj_property/taipingyang_tj_property_tuanti.xls');
				}else if(cp_name_temps_pro=='epicc_jingwailvyou'){
					$('#down_wordxml').attr('href','ziliao/EPICC/epicc_jingwai_tuanti.xlsx');
				}else{
					$('#down_wordxml').attr('href','ziliao/EPICC/epicc_tuanti.xlsx');	
				}
				$('#tuandan_text').text(5);
			}else if(cp_name_temps_pro=='huaan_xueping'){
				$('#down_wordxml').attr('href','ziliao/huaan/xuepingxian/huaan_xuepingxian_tuandan.xls');
				$('#tuandan_text').text(2);
			}
			$('#tuandan_wordxml').show();
			
			 
			var str_applicant_type_checked = $('#applicant_type_s_g input[name="applicant_type"]:checked').val();
			 
			if(str_applicant_type_checked==2){
				if(cp_name_temps_pro=='epicc_jingwailvyou' || cp_name_temps_pro=='taipingyang_tj_product'){
					$('#commit_type_id').val('tuandan');
				}else{
					$('#commit_type_id').val('jingwai_tuandan');
				}
				$j('#more_upload_list_title').hide(); 
				$j("#more_upload_list").show();
			}else{
				$('#commit_type_id').val('');
			}
			
		}
	} 
	else if(num_val == 2)//xls，批量投保的方式
	{
		if(cp_name_temps_pro=='huaan_xueping' || cp_name_temps_pro=='epicc_vehicle' || cp_name_temps_pro=='taipingyang_tj_product' || cp_name_temps_pro=='epicc_lvyou' || cp_name_temps_pro=='epicc_jingwailvyou'){ //华安学平险 需要执行这段代码
			if(cp_name_temps_pro=='epicc_vehicle' || cp_name_temps_pro=='taipingyang_tj_product' || cp_name_temps_pro=='epicc_lvyou' || cp_name_temps_pro=='epicc_jingwailvyou'){
				$j("#as_other").hide();
				if(cp_name_temps_pro=='taipingyang_tj_product'){
					$('#down_wordxml').attr('href','ziliao/taipingyang/taipingyang_tj_property/taipingyang_tj_property_piliang.xls');
				}else if(cp_name_temps_pro=='epicc_jingwailvyou'){
					$('#down_wordxml').attr('href','ziliao/EPICC/epicc_jingwai_piliang.xls');
				}else{
					$('#down_wordxml').attr('href','ziliao/EPICC/epicc_piliang.xls');
				}
				
				$('#tuandan_text').text(1);
			}else if(cp_name_temps_pro=='huaan_xueping'){
				
				$('#down_wordxml').attr('href','ziliao/huaan/xuepingxian/huaan_xuepingxian_piliang.xls');
				$('#tuandan_text').text(1);
			}
			
			$('#tuandan_wordxml').hide();
			
			if(cp_name_temps_pro=='epicc_jingwailvyou'){
				$('#commit_type_id').val('jingwai_piliang');
			}else{
				$('#commit_type_id').val('piliang');
			} 
			
			
		}
		//$j("input#businessType").val(3);
		$j("input#bat_post_policy").val(1);//add by wangcya, 20150106,是否批量投保，单独一个字段
		$j("#table_input_type_manual").hide();
		$j('#str_relationshipWithInsured_T').hide();
		$j("#more_upload_list").show();
		$j('#more_upload_list_title').show();
        $('#ps_list').html("");
		
	}
    sum_price();//计算价格
}
//计算总价  所有的计算价格都是在这里执行
function sum_price(){
	var sum_prices = 0;
	if($('#product_code').val()!='Y502'){
		var sum_pre = 1; //单人投保
		var bat_post_policy_temp = $('#bat_post_policy').val();
		if(bat_post_policy_temp==0 && $('#cp_name').val()=='huaan_xueping'){
			sum_pre = ($('#ps_list table tr').length-1);	
		}else if(bat_post_policy_temp==0 && $('#cp_name').val()=='epicc_vehicle'){
			sum_pre = ($('#ps_list table tr').length-1);	
		}else if(bat_post_policy_temp==0 && $('#cp_name').val()=='taipingyang_tj_product'){
			sum_pre = ($('#ps_list table tr').length-1);	
		}else if(bat_post_policy_temp==0 && $('#cp_name').val()=='epicc_lvyou'){
			sum_pre = ($('#ps_list table tr').length-1);	
		}else if(bat_post_policy_temp==0 && $('#cp_name').val()=='epicc_jingwailvyou'){
			sum_pre = ($('#ps_list table tr').length-1);	
		}else if(bat_post_policy_temp==1){ //批量
			sum_pre = ($('#ps_list table tr').length-1)/2;
		}else if(bat_post_policy_temp==2){ //Y502
			sum_pre = ($('#ps_list table tr').length-1);
		}
		if(sum_pre<=1){
			sum_pre = 1;
		}
		var pre_price = $('#posion_price').val();
		sum_prices = accMul(sum_pre,pre_price);
		var applyNum_temp = $('#applyNum').val();
		if(!isNaN(applyNum_temp)){
			if(parseInt(applyNum_temp)>1){
				sum_prices = accMul(applyNum_temp,sum_prices);
			}
		}
	}else{
		
		var lever_numListTemp = $('#lever_numList').val();
		 
		if(lever_numListTemp!=""){
			var s_lever_numListTemp = array_qies(lever_numListTemp.split(','));	
			var s_lever =$('#career').val().split('_'); 
			var s_duty_price_idTempsQ = $('#additional_ids').val().split('_');
			var s_leverTQ_02 = [];
			var s_duty_priceTQ_02 = [];
			for(var  i = 0 ;i<s_lever_numListTemp.length;i++){
				var f_lever_Temp = s_lever_numListTemp[i][0]; //类型
				var num_lever_Temp = s_lever_numListTemp[i][1]; //人次
				
				if(1<=f_lever_Temp && f_lever_Temp<=3){
					sum_prices = accAdd(sum_prices,accMul(num_lever_Temp,$('#price_temp_mine_01').val()));
					s_leverTQ_02.push(s_lever[0]);
					s_duty_priceTQ_02.push(s_duty_price_idTempsQ[0]);
				}
				if(f_lever_Temp==4){
					sum_prices = accAdd(sum_prices,accMul(num_lever_Temp,$('#price_temp_mine_02').val()));
					s_leverTQ_02.push(s_lever[1]);
					s_duty_priceTQ_02.push(s_duty_price_idTempsQ[1]);
				}
				if(5<=f_lever_Temp && f_lever_Temp<=6){
					//sum_prices = accAdd(sum_prices,accMul(num_lever_Temp,$('#price_temp_mine_03').val()));
					//s_leverTQ_02.push(s_lever[2]);
					//s_duty_priceTQ_02.push(s_duty_price_idTempsQ[2]);
				}
				
			}
			
			$('#careerSQ_02').val(unique4(s_leverTQ_02).join('_'));
			$('#additional_idsSQ_02').val(unique4(s_duty_priceTQ_02).join('_'));
		}
	}
	
    $('#showprice').text(sum_prices);
    $('#totalModalPremium').val(sum_prices);
	if($('#cp_name').val()=='taipingyang_tj_product'){
		$('#span_totalpremium').text(sum_prices);	
	}

}

//遍历重复的次数的内容
function array_qies(array_list){
	var ary =array_list;  
	var res = [];  
	ary.sort();  
	for(var i = 0;i<ary.length;){   
	var count = 0;  
	for(var j=i;j<ary.length;j++){     
		if(ary[i] == ary[j]){  
			count++;  
		}  
	
	}  
	 res.push([ary[i],count]);  
	 i+=count;  
	   
	}  
	return res;	
}

function unique4(regs){
	regs.sort();
	var re=[regs[0]];
	for(var i = 1; i < regs.length; i++)
	{
		if( regs[i] !== re[re.length-1])
		{
			re.push(regs[i]);
		}
	}
	return re;
}