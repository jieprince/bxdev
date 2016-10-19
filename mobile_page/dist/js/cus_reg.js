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
		return true;
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




//注册验证
function register_meeting(){
	
	//return true;//add by wangcya, 20150130, for test
	
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

