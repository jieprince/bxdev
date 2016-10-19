	//add yes123 2015-01-07 绑定推荐人
	function add_parent(user_id){
		var parent_id = $("#text_parent_id").val();
		var patrn=/^(?:[1-9]\d*|0)$/;
		if(patrn.test(parent_id)){
			$.post('users.php',{
				'act':'add_parent',
				'parent_id':parent_id,
				'user_id':user_id
			},function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.code==0){
					alert(obj.msg);
					location.reload();
				}else if(obj.code==1){
					alert(obj.msg);
				}
			},'text');
			
		}else
		{
			alert('请输入正确用户ID');
		}
		
	}
	
	
	function remove_parent(id)
	{
		$.ajax({
			   type: "GET",
			   url: "users.php",
			   data: "act=remove_parent&id="+id,
			   success: function(data){
				 var obj = $.parseJSON(data);
			     if(obj.code==0){
			    	 alert(obj.msg);
			    	 location.reload();
			     }else{
			    	alert(obj.msg); 
			     }
			   }
		}); 
		
	}
	
	//add yes123 2015-04-28 绑定推荐人
	function add_institution(user_id){
		var institution_id = $("#text_institution_id").val();
		var patrn=/^(?:[1-9]\d*|0)$/;
		if(patrn.test(institution_id)){
			$.post('users.php',{
				'act':'add_institution',
				'institution_id':institution_id,
				'user_id':user_id
			},function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.code==0){
					alert(obj.msg);
					location.reload();
				}else if(obj.code==1){
					alert(obj.msg);
				}
			},'text');
			
		}else
		{
			alert('请输入正确用户ID');
		}
		
	}
	
	function remove_institution(id)
	{
		$.ajax({
			   type: "GET",
			   url: "users.php",
			   data: "act=remove_institution&id="+id,
			   success: function(data){
				 var obj = $.parseJSON(data);
			     if(obj.code==0){
			    	 alert(obj.msg);
			    	 location.reload();
			     }else{
			    	alert(obj.msg); 
			    }
			   }
		}); 
		
	}