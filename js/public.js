/**
 * 校验金额是否合法
 * @param money  金额
 * @returns {Boolean}
 */
function ismoney(money) {
			console.info("ismoney money:"+money);
        	if(!money){
        		alert("请输入金额");return false;
        	}
            var v = money;
            if (v.indexOf(",") > -1) {
                //如果出现在最后一位和第一位不正确
                if (v.indexOf(",") == 0) {
                    alert("第一位不能出现,号")
                    return false;
                }
                if (v.lastIndexOf(",") == (v.length - 1)) {
                    alert("最后一位不能出现,号");
                    return false;
                }
                
                var tmp = v.split(",");
                for (var i = 1; i < tmp.length; i++) {
                    if (i == (tmp.length - 1)) {//最后一位
                        if (tmp[tmp.length - 1].indexOf(".") > -1) {
                            var la = tmp[tmp.length - 1].split(".")[0];
                            if (la == "" || la.length != 3) {
                                alert("小数点前的数字格式不正确,位数不为3个不能用逗号分隔");
                                return false;
                            }
                        } else if (tmp[i] == "" || tmp[i].length != 3) {
                            alert("位数不为3个不能用逗号分隔");
                            return false;
                        }
                    } else if (tmp[i] == "" || tmp[i].length != 3) {
                        alert("数字位数不正确");
                        return false;
                    }
                }
            }
            var a = /^[0-9]*(\.[0-9]{1,2})?$/;
            if (!a.test(v)) {
                alert("金额格式不正确");
                return false;
            } else {
                return true;
            }
}

//日期加减
function dateOperator(date,days,operator)
{
 
    date = date.replace(/-/g,"/"); //更改日期格式
    var nd = new Date(date);
    nd = nd.valueOf();
    if(operator=="+"){
     nd = nd + days * 24 * 60 * 60 * 1000;
    }else if(operator=="-"){
        nd = nd - days * 24 * 60 * 60 * 1000;
    }else{
        return false;
    }
    nd = new Date(nd);
 
    var y = nd.getFullYear();
    var m = nd.getMonth()+1;
    var d = nd.getDate();
    if(m <= 9) m = "0"+m;
    if(d <= 9) d = "0"+d;
    var cdate = y+"-"+m+"-"+d;
    return cdate;
}




function get_check_code(arrayObj){

	if(arrayObj.is_captcha){
		var captcha = $.trim($("#input_captcha").val());
	
		if(captcha.length<4){
			alert("请输入图形验证码");
			return;
		}
	
		var parm = "";
		if(arrayObj.is_captcha==1){
			parm = "act=check_captcha&captcha="+captcha;
		}
	
	   //去验证
		$.ajax({
			   type: "POST",
			   url: "user.php",
			   data: parm,
			   success: function(res){
			  		if(res!=200){
			  			alert(res);
			  		}else{
			  			
			  			verification_code(arrayObj);
			  		}
			  			
			   }
	
			});
	}
	else{
		
		verification_code(arrayObj);
	}
}

var code = '';
function verification_code(arrayObj){  //加参数是表示注册,还是重置密码的短信,1注册时的短信 2表示重置时的短信
	
	var url = 'user.php';
	if(arrayObj.is_mobile==1){
		
		url = '../mobile/user.php';
		
	}
	
	$.post(url,{
	  'act':'phone_code',
	  'type':arrayObj.type,
	  'msg_type':arrayObj.msg_type,
	  'username':arrayObj.username
	},
	function(datas){
		var obj = JSON.parse(datas);
		code = obj.num;
		if(arrayObj.msg_type=='common_msg'){
			if(obj.code == 0){
			   var step = 119;
			   $('#get_code_btn').val('重新发送120');
			   var flag = setInterval(function(){
					$("#get_code_btn").attr("disabled", true);//设置disabled属性
					$('#get_code_btn').val('重新发送'+step);
					$("#get_code").show();
					step -= 1;
					if(step <= 0){
						$("#get_code_btn").removeAttr("disabled"); //移除disabled属性
						$('#get_code_btn').val('免费获取验证码');
						code = '';
						clearInterval(flag);//清除setInterval
					}
			   },1000);
			}
			else if(obj.code =='500' || obj.code ==500)
			{
			   alert("您获取验证码的次数过多，请明天再试！");
			}
			else if(obj.code!=0 )
			{
			   alert(obj.msg);
			}
			else{
				 alert(obj.msg);
			}
		}else{
			alert("已发送请求，请稍后！");
		}
		
	},'text');
	    
}    

function checkNum(value){  
    value = $.md5(value);
    if(code != '' && value != ''){
	   if(code == value){
		    document.getElementById("div").innerHTML='<img src="./images/yes.gif"/>';
		    //del yes123 2014-11-30 这个没必要了,不能这样验证
		    //$("#checkCode").val(1);
		    $("#tr").hide();
	     }else{
		    //del yes123 2014-11-30 这个没必要了,不能这样验证
		       //$("#checkCode").val(0);
		    document.getElementById("div").innerHTML='<img src="./images/no.gif"/>';
	     }
     }
} 


function is_exist_by_value(field_name,field_value){
	 var flag = false;
	 $.ajax({
		   url: "user.php",
		   type: "GET",
		   async: false,
		   data: "act=is_exist_by_value&field_name="+field_name+"&field_value="+field_value,
		   success: function(res){
			   if(res=='true'){
				   flag=true; 
			   }else if(res=='false'){
				   flag=false; 
			   }
			   
		   }
		 });
	  return flag;
}