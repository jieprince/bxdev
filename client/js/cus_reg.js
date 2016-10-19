//url中数据的提取
var phoneCode = null;	
var code = '';
function phone(value){
	if(!(/^1[1-9][0-9]\d{8}$/.test(value))){
	   phoneCode = false;
	   alert('请检查手机号码！');
	   return false;
	}else{
	   phoneCode = true;
	   return true;
	}
}


function verification_code(type){  //加参数是表示注册,还是重置密码的短信,1注册时的短信 2表示重置时的短信

   if(phoneCode){
		$.post('../user.php',{
		  'act':'phone_code',
		  'type':type,
		  'username':$.trim($("[name=username]").val())
		},
		function(datas){
			var obj = JSON.parse(datas);
			code = obj.num;
			//alert(code);
			if(obj.data == 0){
			   var step = 119;
			   $('#btn').val('重新发送120');
			   var flag = setInterval(function(){
					$("#btn").attr("disabled", true);//设置disabled属性
					$('#btn').val('重新发送'+step);
					step -= 1;
					if(step <= 0){
						$("#btn").removeAttr("disabled"); //移除disabled属性
						$('#btn').val('免费获取验证码');
						code = '';
						clearInterval(flag);//清除setInterval
					}
			   },1000);
			}else if(obj.code ==3){
			   alert(obj.msg);
			}else{
			   alert("发送失败!");
			}
		},'text');
   }	    
}
function checkNum(value){	
   //add yes123 2014-11-30 md5加密后再和服务器返回过来的比较
   value = $.md5(value);
	if(code != '' && value != ''){
	  if(code == value){
		 return true;
	   }else{
		 alert('验证码不正确!'); 
		 return false;
	  }	    
   }else{
	   alert('验证码不正确!'); 
	   return false;
   }
}

function check_password(val_password){
	if($.trim(val_password).length>=30||$.trim(val_password).length<5){
		alert('密码：6-30个字符，支持字母、数字、及_~@#$^ 。');	
		return false;
	}else{
		var conform_val = $('#conform_password').val()	;
		if($.trim(conform_val).length>0){
			if($.trim(conform_val)!=val_password){
				alert('密码和确认密码不一致,请核实后提交!');	
				return false;
			}else{
				return true;	
			}	
		}else{
			return true;	
		}
	}
}

function check_conform_password(conform_val){
	if($('#password1').val()!=conform_val){
		alert('密码和确认密码不一致,请核实后提交!');	
		return false;	
		
	}else{
		return true;		
	}
}


//注册验证
function register(){
	var username = $.trim($('#username').val());
	var checkCode = $.trim($('#checkCode').val());
	var password1 = $.trim($('#password1').val());
	var conform_password = $.trim($('#conform_password').val());
	var agreement = $('#agreement').prop("checked");
	if(!phone(username)){	
		return false;
	}else if(!checkNum(checkCode)){ 
		return false;
	}else if(!check_password(password1)){
		return false;	
	}else if(!check_conform_password(conform_password)){
		return false;	
	}else if(!agreement){
		alert('用户协议没有选中');
		return false;	
	}else{
		return true;	
	}
}

