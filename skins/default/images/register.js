$(document).ready(function(e) {
    $('.textboxEx span').on('click', function() {
		$(this).prev().trigger('focus');
	});
	$('.textboxEx input').on('focusin', function() {
		$(this).parent().animate({borderColor:'#999'},200).children('span').animate({fontSize:'12px',top:'-11px',color:'#286FBF',paddingLeft:'5px',paddingRight:'5px'}, 200);
	}).on('focusout', function() {
		$(this).val($.trim($(this).val()));
		if($(this).val()=='') $(this).parent().animate({borderColor:'#D5D5D5'},200).children('span').animate({fontSize:'14px',top:'7px',color:'#D5D5D5',paddingLeft:'0px',paddingRight:'0px'}, 200);
	});
	$('#regBtn').on('click', function() {
		if(!$('#usernameTxt').is(':disabled')) {
			$(this).css({'opacity':'.5'});
			$('#usernameTxt,#passwordTxt,#emailTxt,#verTxt').prop('disabled', true);
			$.ajax({
				type: 'POST',
				cache: false,
				url: 'ajax.php?ac=register',
				data: {username:$('#usernameTxt').val(),password:$('#passwordTxt').val(),email:$('#emailTxt').val(),ver:$('#verTxt').val()},
				success: function(j) {
					j = $.parseJSON(j);
					if(j.result > 0) {
						if(j.verify) {
							alert('已经有一封验证邮件发送到您的邮箱，请在1小时内验证。');
						} else {
							alert('注册成功');
							window.location.href = './';
						}
					} else {
						alert(j.msg);
						$('#usernameTxt,#passwordTxt,#emailTxt,#verTxt').prop('disabled', false);
						$('#regBtn').css({'opacity':'1'});
						$('#verifyImg').attr('src', 'exts/php_verify/verify.php?'+Math.random());
					}
				},
				error: function() {
					alert('网络连接失败');
					$('#usernameTxt,#passwordTxt,#emailTxt,#verTxt').prop('disabled', false);
					$('#regBtn').css({'opacity':'1'});
				}
			});
		}
	});
});