<?php
/**
 * 热销产品
 * $Author: dingchaoyang $
 * 2014-11-12 $
 */
$ROOT_PATH_ = str_replace ( 'api/EBaoApp/responseData/hotProduct/hotProduct.class.php', '', str_replace ( '\\', '/', __FILE__ ) );
// include_once ($ROOT_PATH_ . 'includes/init.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/constant.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resUser.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resMessage.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/resStatus.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/baseResponse.php');
include_once ($ROOT_PATH_ . 'api/EBaoApp/responseData/iResponse.php');
class HotProductSuccess extends BaseResponse implements IResponse {
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_HOTPRODUCTSUCCESS_CONTENT;
		$this->status = new ResStatus ( '0', $message );
		$this->command = APP_COMMAND_HOTPRODUCT;
	}
	function setHotProduct($infos) {
	}
	function responseResult() {
		global $GLOBALS;
		
		// $sql = "
		// SELECT
		// *
		// FROM
		// (SELECT
		// g.goods_id,
		// g.goods_name,
		// g.tid,
		// g.shop_price,
		// COUNT(o.goods_id) AS total,
		// g.cat_id
		// FROM
		// bx_order_goods o
		// INNER JOIN bx_goods g
		// ON o.goods_id = g.goods_id
		// WHERE g.is_on_sale = 1
		// AND g.is_alone_sale = 1
		// GROUP BY o.goods_id
		// ORDER BY total DESC
		// LIMIT 0, 2) AS hotProduct,
		// (SELECT
		// pb.attribute_id,
		// pa.age_min,
		// pa.age_max,
		// pa.period
		// FROM
		// t_insurance_product_base AS pb
		// INNER JOIN t_insurance_product_additional AS pa
		// ON pa.product_id = pb.product_id) AS hotProductAddional
		// WHERE hotProduct.tid = hotProductAddional.attribute_id
		// ";
		$sql = "SELECT 
  * 
FROM
  (SELECT 
    * 
  FROM
    (SELECT 
      g.goods_id,
      g.goods_name,
      g.tid,
      g.shop_price,
      COUNT(o.goods_id) AS total,
      g.cat_id 
    FROM
      bx_order_goods o 
      INNER JOIN bx_goods g 
        ON o.goods_id = g.goods_id 
    WHERE g.is_on_sale = 1 
      AND g.is_alone_sale = 1 
    GROUP BY o.goods_id 
    ORDER BY total DESC) AS hotProduct,
    (SELECT 
      t_insurance_product_attribute.attribute_name,
      t_insurance_product_attribute.attribute_id,
		t_insurance_product_attribute.is_show_in_appwebview,
      t_insurance_product_additional.age_min,
      t_insurance_product_additional.age_max,
      t_insurance_product_additional.period 
    FROM
      t_insurance_product_base,
      t_insurance_product_additional,
      t_insurance_product_attribute 
    WHERE t_insurance_product_additional.product_id = t_insurance_product_base.product_id 
      AND t_insurance_product_base.attribute_id = t_insurance_product_attribute.attribute_id 
      AND t_insurance_product_attribute.is_show_in_app = 1) AS hotProductAddional 
  WHERE hotProduct.tid = hotProductAddional.attribute_id) AS temp 
LIMIT 0, 3 ";
		
		$resSet = $GLOBALS ['db']->getAll ( $sql );
		
		foreach ( $resSet as $key => $value ) {
			$cres [$key] ['name'] = $value ['attribute_name'];
			$cres [$key] ['ageRange'] = $value ['age_min'] . '-' . $value ['age_max'] . '周岁';
			$cres [$key] ['timeLimit'] = $value ['period'];
			$cres [$key] ['price'] = $value ['shop_price'];
			$cres [$key] ['id'] = $value ['goods_id']; // 险种id
			$cres [$key] ['catgoryID'] = $value ['cat_id']; // 险种大类id
			$cres [$key] ['isShowWebView'] = $value ['is_show_in_appwebview']; //此险种需要webview打开
			if ($value ['is_show_in_appwebview']){//webview的url
				$cres [$key] ['webUrl'] = "/mobile/goods.php?id=".$value ['goods_id']."&uid=".ResUser::getInstance()->encryptedUid.'&platformId='.PlatformEnvironment::getPlatformID();
			}
		}
		$base = parent::responseResult ();
		$curr = array (
				'hotProduct' => $cres 
		);
		
		return array_merge ( $base, $curr );
	}
}
class HotProductFail extends BaseResponse implements IResponse {
	function __construct() {
		parent::__construct ();
		$message = new ResMessage ();
		$message->title = APP_ALERT_TITLE;
		$message->content = APP_HOTPRODUCTFAIL_CONTENT;
		$this->status = new ResStatus ( '1', $message );
		$this->command = APP_COMMAND_HOTPRODUCT;
	}
	function responseResult() {
		return parent::responseResult ();
	}
}

?>