<?php

$op = empty($_GET['op'])?'':$_GET['op'];

if($_GET['op'] == 'delete')
{
	//删除
	if(submitcheck('deletesubmit'))
	{
		$ids = $_POST['ids'];
		
		include_once(S_ROOT.'./source/function_delete.php');
		//if(insurance_order_delete($ids))
		if(1)
		{
			showmessage('do_success', "space.php?do=admin_insurance_order");
		}
		else
		{
			showmessage('failed_to_delete_operation');
		}
	}

}

$_TPL['css'] = 'admin_product';

include_once template("cp_admin_insurance_order");
