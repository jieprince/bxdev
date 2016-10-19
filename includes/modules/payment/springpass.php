<?php

/**
 *  绿翼积分支付
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/springpass.php';
include_once(ROOT_PATH . 'baoxian/source/function_debug.php');//add by wangcya, 20141124

if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'springpass_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.springpass.com.cn';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'springpass_account',  'type' => 'text',   'value' => ''),
        array('name' => 'springpass_password',  'type' => 'text',   'value' => ''),
        array('name' => 'springpass_gateway_url',      'type' => 'text',   'value' => ''),
        array('name' => 'springpass_merUrl',      'type' => 'text',   'value' => ''),
        //array('name' => 'springpass_back_url',      'type' => 'text',   'value' => ''),
        //array('name' => 'springpass_applied_range', 'type' => 'select','value' => 'private'),
    );

    return;
}

class springpass
{
   /**
    * 构造函数
    *
    * @access  public
    * @param
    *
    * @return void
    */
    function springpass()
    {
		
    }

    function __construct()
    {
        $this->springpass();
    }

    /**
    * 生成支付代码
    * @param   array   $order  订单信息
    * @param   array   $payment    支付方式信息
    */

    function get_code($order, $payment)
    {
    	$def_url  = '<br /><div style="text-align:center;">';
        $def_url.=  '<script>
					function go_pay_new(order_id){
					    var input_amount = $("#input_amount").val();
						$.ajax({type: "get",
					    url: "user.php",
					    dataType:"JSON",
				   		data: "act=go_pay_form&order_id="+order_id+"&input_amount="+input_amount,
				   		success: function(res){
				   			if(res.code==0){
						   		$("#pay_code_span").html(res.pay);
						   	    $("#pay_form").submit();
				   			}
				   			else{
				   				alert(res.msg);
				   			}
				   			
					   	}
				   	 });
						
					}
    			</script>';
        
        $def_url .= "<input type='hidden' name='order_id'  value='" . $order['order_id'] . "'>\n";
        $def_url .= "<span  style='display: none' id='pay_code_span'></span>\n";
        $def_url .= "请输入本次支付金额 ：";
        $def_url .= "<input type='text' name='amount' id='input_amount' size='5' value='".$order['order_amount']."' class='f-pA10 n-invalid'>";
        $def_url .= "<input class='btn-ec6941-4 f-ml20' style='display:inline-block' target='_blank' onclick='go_pay_new(".$order['order_id'].");' type='button' value='" . $GLOBALS['_LANG']['pay_button'] . "'>";
        $def_url .= "</div>";

        return $def_url;
    }
	
	
	
/*	function go_pay($order, $payment)
	{
		
		ss_log(__FUNCTION__.",payment:".print_r($payment,true));
		$input_amount = floatval($_REQUEST['amount']);
		
		
		if($input_amount<=0)
		{
			showmessage ( "请输入正确金额" );
			
		}
		
		$post_data = array();
		        //商户号
        $post_data['MerCode'] = $mer_code   = $payment['springpass_account'];
        
        
        //交易金额
         $post_data['Amount'] = $amount     = sprintf("%0.02f", $order['order_amount']);
        
         
         if($input_amount>$amount)
         {
         	$input_amount = $amount;
         }
        
        
        //商户日期
         $post_data['MerDate'] = $MerDate    = date('Ymd', time());
        
        //PayType
         $post_data['PayType'] = $PayType = '01';
        
        //回调Url
        $post_data['backStageUrl'] =  $backStageUrl   = return_url(basename(__FILE__, '.php'));

        //SignMD5  加密串
         $post_data['SignMD5'] = $SignMD5 =1;
		
		//返回URL 
		 $post_data['MerUrl'] = $MerUrl = $payment['springpass_merUrl']."&order_id=".$order['order_id'];
		
		
		//外部订单号
		$retrievalReferenceNumber = $order['log_id'];
		
		//
		$txnCurrencyCode='';
		
		
		$pay_log_serial = array(
				'pay_log_id'=>$order['log_id'],
				'order_id'=>$order['order_id'],
				'order_sn'=>$order['order_sn'],
				'amount'=>$amount,
				'add_tiem'=>time()
		
		);
		
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('pay_log_serial'), $pay_log_serial, 'INSERT');	
		$post_data['BillNo'] = $billno = $GLOBALS['db']->insert_id(); //发票ID

        $strcontent = $mer_code . $billno . $amount . $MerDate.$PayType.$txnCurrencyCode.$payment['springpass_password']; // 签名验证串 //
        
        $post_data['SignMD5'] = $signmd5    = MD5($strcontent);
		
		$result = curl_post($payment['springpass_gateway_url'],$post_data);
		
        print_r($result,true);
		
	}*/
	
	
	function get_pay_form($order,$payment)
	{
        
        $amount = isset($_REQUEST['input_amount'])?floatval($_REQUEST['input_amount']):0;

	
		//商户号
        $mer_code   = $payment['springpass_account'];
        
        
        //交易金额
        $order_amount     = sprintf("%0.02f", $order['order_amount']);
                 
         
	    if($amount>$order_amount)
	    {
	    	$amount = $order_amount;
	    }
        
        if($amount<=0)
        {
        	$amount = $order_amount;
        }
        
        //商户日期
        $MerDate    = date('Ymd', time());
        
        //PayType
        $PayType = '01';
        
        //回调Url
        $backStageUrl   = return_url(basename(__FILE__, '.php'));

        //SignMD5  加密串
        $SignMD5 =1;
		
		//返回URL 
		$MerUrl = $payment['springpass_merUrl'];
		//
		$txnCurrencyCode = '';

		
		//外部订单号
		$retrievalReferenceNumber = $order['log_id'];
		
		
		$billno = insert_pay_log ( $order['order_id'], $amount, PAY_ORDER );
		
		//MD5(MerCode+BillNo+Amount+MerDate +PayType+MerUrl+txnCurrencyCode+商户密码)
        $strcontent = $mer_code . $billno . $amount . $MerDate.$PayType.$MerUrl.$txnCurrencyCode.$payment['springpass_password']; // 签名验证串 //
        $signmd5    = MD5($strcontent);
		
        $def_url  = '<br /><form style="text-align:center;" action="'.$payment['springpass_gateway_url'].'" method="post"  id="pay_form" >';
        $def_url .= "<input type='hidden' name='MerCode' value='" . $mer_code . "'>\n";
        $def_url .= "<input type='hidden' name='BillNo' value='" . $billno . "'>\n";
        $def_url .= "<input type='hidden' name='Amount'  value='" . $amount . "'>\n";
        $def_url .= "<input type='hidden' name='MerDate' value='" . $MerDate . "'>\n";
        $def_url .= "<input type='hidden' name='PayType'  value='" . $PayType . "'>\n";
        $def_url .= "<input type='hidden' name='backStageUrl' value='" . $backStageUrl . "'>\n";
        $def_url .= "<input type='hidden' name='MerUrl' value='" . $MerUrl . "'>\n";
       // $def_url .= "<input type='hidden' name='txnCurrencyCode' value='" . $txnCurrencyCode . "'>\n";
        $def_url .= "<input type='hidden' name='SignMD5' value='" . $signmd5 . "'>\n";
        $def_url .= "<input class='btn-ec6941-3' type='submit' value='" . $GLOBALS['_LANG']['pay_button'] . "'>";
        $def_url .= "</form><br />";
		
        return $def_url;
		
	}
	
    function respond()
    {
    	ss_log(__FUNCTION__.",into ");
    	
    	ss_log(__FUNCTION__.",post :".print_r($_POST,true));
    	
    	
    	$succ = $_POST['SUCC'];

        if ($succ == 'Y')
        {
        	$billno        = $_POST['BillNo'];
	        $sql = "SELECT * FROM " .$GLOBALS['ecs']->table('pay_log'). " WHERE log_id='$billno'";
	        ss_log(__FUNCTION__.",get pay_log ".$sql);
			$pay_log = $GLOBALS['db']->getRow($sql);
	        $order = order_info($pay_log['order_id']);
	        
	        $payment       = get_payment_order($order);
	        ss_log(__FUNCTION__.",payment1 :".print_r($payment,true));
	        $payment = unserialize_config($payment['pay_config']);
	        ss_log(__FUNCTION__.",payment2 :".print_r($payment,true));
	   
	        $amount        = $_POST['BillAmount'];
	        $mydate        = $_POST['date'];
	        
	        $SignMD5          = $_POST['SignMD5'];
        	
        	
            $content = $billno . $amount . $succ.$payment['springpass_password'];
      		ss_log(__FUNCTION__.",content:$content ");
            $signature_1ocal = md5($content);

            if ($signature_1ocal == $SignMD5)
            {
                if (!check_money($billno, $amount))
                {
                   return false;
                }
       			
                order_paid($billno);
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
        	ss_log(__FUNCTION__.", Payment Failed ");
            return false;
        }
    }
    

}

?>