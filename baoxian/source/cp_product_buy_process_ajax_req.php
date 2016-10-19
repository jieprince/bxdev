<?php

$op = empty($_GET['op'])?'':$_GET['op'];

if($op=="huatai_destination")//华泰获得目的地国家处理
{
	
	//////////////////////////////////////////////////////////////////
	$area = urldecode($_GET["area"]);

	//和前面的前后顺序不要颠倒了。
	ss_log("area: ".$area);
	////////////////////////////////////////////////////////////////
	$huatai_destination = array('Asia'=>array(
			"孟加拉共和国",
			"不丹王国",
			"文莱",
			"缅甸",
			"柬埔寨",
			"中国",
			"印度",
			"印度尼西亚",
			"日本",
			"朝鲜",
			"韩国",
			"老挝",
			"马来西亚",
			"马尔代夫群岛",
			"蒙古",
			"尼泊尔",
			"巴基斯坦",
			"菲律宾",
			"新加坡",
			"斯里兰卡",
			"泰国",
			"越南",
			"中国香港",
			"中国澳门",
	),
			"M-East"=>array(
					"巴林",
					"塞浦路斯",
					"伊朗",
					"以色列",
					"约旦",
					"科威特",
					"黎巴嫩",
					"阿曼",
					"卡塔尔",
					"沙特阿拉伯",
					"阿拉伯联合酋长国",
					"也门",
					"巴勒斯坦",
					"土耳其",
					"阿尔及利亚",
					"埃及",
					"摩洛哥",
					"突尼斯",
			),
			"E-EuropeAsia"=>array(
					"亚美尼亚",
					"哈萨克斯坦",
					"吉尔吉斯共和国吉尔吉斯斯坦",
					"塔吉克斯坦",
					"乌兹别克斯坦",
					"格鲁吉亚",
					"土库曼斯坦"
			),
			"W-Europe"=>array(
					"安道尔共和国",
					"奥地利",
					"比利时",
					"丹麦",
					"西班牙",
					"芬兰",
					"法国",
					"德国",
					"英国",
					"希腊",
					"冰岛",
					"爱尔兰",
					"意大利",
					"列支敦士登",
					"卢森堡",
					"马耳他",
					"摩纳哥",
					"荷兰",
					"挪威",
					"葡萄牙",
					"圣马利诺",
					"瑞典",
					"瑞士",
					"梵蒂冈城国"
					),
					"E-Europe"=>array(
					"阿尔巴尼亚",
					"白俄罗斯",
					"波斯尼亚和黑塞哥维那波黑",
					"保加利亚",
					"阿塞拜疆",
					"克罗地亚共和国",
					"捷克共和国",
					"爱沙尼亚",
					"马其顿共和国",
					"匈牙利",
					"拉脱维亚",
					"立陶宛",
					"黑山",
					"波兰",
					"摩尔多瓦",
					"罗马尼亚",
					"俄罗斯",
					"塞尔维亚塞黑",
					"斯洛伐克",
					"斯洛文尼亚",
					"乌克兰"
					),
					"N-America"=>array(
					"加拿大",
					"墨西哥",
					"美国"
					),
					"S-America"=>array(
					"阿根廷",
					"巴哈马",
					"巴巴多斯",
					"伯利兹",
					"玻利维亚 ",
					"巴西",
					"智利",
					"哥伦比亚",
					"哥斯达黎加",
					"古巴",
					"多米尼加共和国",
					"多米尼克",
					"厄瓜多尔",
					"萨尔瓦多",
					"格林纳达",
					"危地马拉",
					"圭亚那",
					"海地",
					"洪都拉斯",
					"牙买加",
					"圣卢西亚",
					"尼加拉瓜",
					"巴拿马",
					"巴拉圭",
					"秘鲁",
					"圣基茨和尼维斯",
					"苏里南",
					"特立尼达和多巴哥",
					"乌拉圭",
					"委内瑞拉",
					"圣文森特和格林纳丁斯",
					"安的列斯",
					"阿鲁巴",
					"百慕大",
					"英属维尔京群岛",
					"开曼群岛",
					"维尔京群岛",
					"波多黎各",
					"萨摩亚"
					),
					"Oceania"=>array(
					"澳大利亚",
					"库克群岛",
					"斐济",
					"新西兰",
					"巴布亚新几内亚"
					),
					"Africa"=>array(
					"安哥拉",
					"贝宁",
					"博茨瓦那",
					"布基纳法索国",
					"喀麦隆",
					"佛得角",
					"科摩罗",
					"刚果（布）",
					"民主刚果",
					"吉布提",
					"赤道几内亚",
					"埃塞俄比亚",
					"加蓬",
					"冈比亚",
					"加纳",
					"几内亚比绍 ",
					"肯尼亚",
					"莱索托",
					"马达加斯加 ",
					"马拉维",
					"马里",
					"毛里塔尼亚",
					"毛里求斯非洲岛国",
					"莫桑比克",
					"纳米比亚",
					"尼日尔 ",
					"尼日利亚",
					"圣多美与普林西比共和国",
					"塞内加尔",
					"塞舌尔群岛",
					"塞拉利昂",
					"南非",
					"斯威士兰",
					"多哥",
					"乌干达",
					"坦桑尼亚",
					"赞比亚",
					"津巴布韦"
					),
					);

					//////////////////////////////////////////////////////////////
					$country_list = $huatai_destination[$area];

					$arr_disease = array();

					//print_r($country_list);
					if(empty($country_list))
					{
						$arr_disease[0]["BD_ID"]  = 0;
						$arr_disease[0]["BD_Name"] = "";

						$jarr=json_encode($arr_disease);
						echo $jarr;
						return;
					}


					foreach($country_list as $key => $value)
					{
						$arr_disease[$key]["BD_ID"]  = 1;
						$arr_disease[$key]["BD_Name"] = $value;
						//$arr_disease[$key]["BD_Code"] = $value[duty_code];
					}

					$jarr=json_encode($arr_disease);
					echo $jarr;

					return;
}
elseif($op=="huatai_city")//华泰获得地址的市区信息
{
	/*
	 $huatai_provine = array(
	 		"860100"=>"北京",
	 		"860200"=>"上海",
	 		"860500"=>"广东",
	 		"860600"=>"江苏",
	 		"860700"=>"浙江",
	 		"860800"=>"山东",
	 		"860900"=>"福建",
	 		"861200"=>"四川",
	 		"861500"=>"湖南",
	 		"861600"=>"湖北",
	 		"861700"=>"江西",
	 		"862100"=>"河南",
	 		"862300"=>"内蒙古",
	 		"861100"=>"河北",
	 );

	 
	$huatai_city = array(
			"860100"=>array("860199"=>"北京"),//北京
			"860200"=>array("860299"=>"上海"),//"上海"
			"860500"=>array("860501"=>"广州",
					"860502"=>"潮州",
					"860503"=>"东莞",
					"860504"=>"佛山",
					"860505"=>"河源",
					"860506"=>"惠州",
					"860507"=>"江门",
					"860508"=>"揭阳",
					"860509"=>"茂名",
					"860510"=>"梅州",
					"860511"=>"清远",
					"860512"=>"汕头",
					"860513"=>"汕尾",
					"860514"=>"韶关",
					"860515"=>"深圳",
					"860516"=>"阳江",
					"860517"=>"云浮",
					"860518"=>"湛江",
					"860519"=>"肇庆",
					"860520"=>"中山",
					"860521"=>"珠海"
			),//"广东",
			"860600"=>array("860601"=>"南京",
					"860602"=>"常州",
					"860603"=>"淮安",
					"860604"=>"连云港",
					"860605"=>"南通",
					"860606"=>"苏州",
					"860607"=>"宿迁",
					"860608"=>"泰州",
					"860609"=>"无锡",
					"860610"=>"徐州",
					"860611"=>"盐城",
					"860612"=>"扬州",
					"860613"=>"镇江"
			),//"江苏",
			"860700"=>array("860701"=>"杭州",
					"860702"=>"湖州",
					"860703"=>"嘉兴",
					"860704"=>"金华",
					"860705"=>"丽水",
					"860706"=>"宁波",
					"860707"=>"衢州",
					"860708"=>"绍兴",
					"860709"=>"台州",
					"860710"=>"温州",
					"860711"=>"舟山"
			),//"浙江",
			"860800"=>array(	"860801"=>"济南",
					"860802"=>"滨州",
					"860803"=>"德州",
					"860804"=>"东营",
					"860805"=>"菏泽",
					"860806"=>"济宁",
					"860807"=>"莱芜",
					"860808"=>"聊城",
					"860809"=>"临沂",
					"860810"=>"青岛",
					"860811"=>"日照",
					"860812"=>"泰安",
					"860813"=>"威海",
					"860814"=>"潍坊",
					"860815"=>"烟台",
					"860816"=>"枣庄",
					"860817"=>"淄博"
			),//"山东",
			"860900"=>array(	"860901"=>"福州",
					"860902"=>"龙岩",
					"860903"=>"南平",
					"860904"=>"宁德",
					"860905"=>"莆田",
					"860906"=>"泉州",
					"860907"=>"三明",
					"860908"=>"厦门",
					"860909"=>"漳州"
			),//"福建",
			"861200"=>array("861201"=>"成都",
					"861202"=>"阿坝",
					"861203"=>"巴中",
					"861204"=>"达州",
					"861205"=>"德阳",
					"861206"=>"甘孜",
					"861207"=>"广安",
					"861208"=>"广元",
					"861209"=>"乐山",
					"861210"=>"凉山",
					"861211"=>"泸州",
					"861212"=>"眉山",
					"861213"=>"绵阳",
					"861214"=>"内江",
					"861215"=>"南充",
					"861216"=>"攀枝花",
					"861217"=>"遂宁",
					"861218"=>"雅安",
					"861219"=>"宜宾",
					"861220"=>"资阳",
					"861221"=>"自贡"
			),//"四川",
			"861500"=>array("861501"=>"长沙",
					"861502"=>"常德",
					"861503"=>"郴州",
					"861504"=>"衡阳",
					"861505"=>"怀化",
					"861506"=>"娄底",
					"861507"=>"邵阳",
					"861508"=>"湘潭",
					"861509"=>"湘西",
					"861510"=>"永州",
					"861511"=>"岳阳",
					"861512"=>"张家界",
					"861513"=>"株洲"
			),//"湖南",
			"861600"=>array("861601"=>"武汉",
					"861602"=>"鄂州",
					"861603"=>"恩施",
					"861604"=>"黄冈",
					"861605"=>"黄石",
					"861606"=>"荆门",
					"861607"=>"荆州",
					"861608"=>"十堰",
					"861609"=>"随州",
					"861610"=>"咸宁",
					"861611"=>"襄樊",
					"861612"=>"孝感",
					"861613"=>"宜昌"
			),//"湖北",
			"861700"=>array("861701"=>"南昌",
					"861702"=>"抚州",
					"861703"=>"赣州",
					"861704"=>"吉安",
					"861705"=>"景德镇",
					"861706"=>"九江",
					"861707"=>"萍乡",
					"861708"=>"上饶",
					"861709"=>"新余",
					"861710"=>"宜春",
					"861711"=>"鹰潭"
			),//"江西",
			"862100"=>array("862101"=>"郑州",
					"862102"=>"安阳",
					"862103"=>"鹤壁",
					"862104"=>"焦作",
					"862105"=>"开封",
					"862106"=>"洛阳",
					"862107"=>"漯河",
					"862108"=>"南阳",
					"862109"=>"平顶山",
					"862110"=>"濮阳",
					"862111"=>"三门峡",
					"862112"=>"商丘",
					"862113"=>"新乡",
					"862114"=>"信阳",
					"862115"=>"许昌",
					"862116"=>"周口",
					"862117"=>"驻马店"
			),//"河南",
			"862300"=>array("862301"=>"呼和浩特",
					"862302"=>"阿拉善",
					"862303"=>"巴彦淖尔",
					"862304"=>"包头",
					"862305"=>"赤峰",
					"862306"=>"鄂尔多斯",
					"862307"=>"呼伦贝尔",
					"862308"=>"通辽",
					"862309"=>"乌海",
					"862310"=>"乌兰察布",
					"862311"=>"锡林郭勒",
					"862312"=>"兴安"
			),//"内蒙古",
			"861100"=>array("861101"=>"石家庄",
					"861102"=>"保定",
					"861103"=>"沧州",
					"861104"=>"承德",
					"861105"=>"邯郸",
					"861106"=>"衡水",
					"861107"=>"廊坊",
					"861108"=>"秦皇岛",
					"861109"=>"唐山",
					"861110"=>"邢台",
					"861111"=>"张家口"
			),//"河北"
	);
	*/

	$huatai_city = array(
			"101"=>array("860199"=>"北京"),//北京
			"105"=>array("860299"=>"上海"),//"上海"
			"110"=>array("860501"=>"广州",
					"860502"=>"潮州",
					"860503"=>"东莞",
					"860504"=>"佛山",
					"860505"=>"河源",
					"860506"=>"惠州",
					"860507"=>"江门",
					"860508"=>"揭阳",
					"860509"=>"茂名",
					"860510"=>"梅州",
					"860511"=>"清远",
					"860512"=>"汕头",
					"860513"=>"汕尾",
					"860514"=>"韶关",
					"860515"=>"深圳",
					"860516"=>"阳江",
					"860517"=>"云浮",
					"860518"=>"湛江",
					"860519"=>"肇庆",
					"860520"=>"中山",
					"860521"=>"珠海"
	),//"广东",
			"104"=>array("860601"=>"南京",
					"860602"=>"常州",
					"860603"=>"淮安",
					"860604"=>"连云港",
					"860605"=>"南通",
					"860606"=>"苏州",
					"860607"=>"宿迁",
					"860608"=>"泰州",
					"860609"=>"无锡",
					"860610"=>"徐州",
					"860611"=>"盐城",
					"860612"=>"扬州",
					"860613"=>"镇江"
	),//"江苏",
			"102"=>array("860701"=>"杭州",
					"860702"=>"湖州",
					"860703"=>"嘉兴",
					"860704"=>"金华",
					"860705"=>"丽水",
					"860706"=>"宁波",
					"860707"=>"衢州",
					"860708"=>"绍兴",
					"860709"=>"台州",
					"860710"=>"温州",
					"860711"=>"舟山"
	),//"浙江",
			"106"=>array(	"860801"=>"济南",
					"860802"=>"滨州",
					"860803"=>"德州",
					"860804"=>"东营",
					"860805"=>"菏泽",
					"860806"=>"济宁",
					"860807"=>"莱芜",
					"860808"=>"聊城",
					"860809"=>"临沂",
					"860810"=>"青岛",
					"860811"=>"日照",
					"860812"=>"泰安",
					"860813"=>"威海",
					"860814"=>"潍坊",
					"860815"=>"烟台",
					"860816"=>"枣庄",
					"860817"=>"淄博"
	),//"山东",
			"108"=>array(	"860901"=>"福州",
					"860902"=>"龙岩",
					"860903"=>"南平",
					"860904"=>"宁德",
					"860905"=>"莆田",
					"860906"=>"泉州",
					"860907"=>"三明",
					"860908"=>"厦门",
					"860909"=>"漳州"
	),//"福建",
			"103"=>array("861201"=>"成都",
					"861202"=>"阿坝",
					"861203"=>"巴中",
					"861204"=>"达州",
					"861205"=>"德阳",
					"861206"=>"甘孜",
					"861207"=>"广安",
					"861208"=>"广元",
					"861209"=>"乐山",
					"861210"=>"凉山",
					"861211"=>"泸州",
					"861212"=>"眉山",
					"861213"=>"绵阳",
					"861214"=>"内江",
					"861215"=>"南充",
					"861216"=>"攀枝花",
					"861217"=>"遂宁",
					"861218"=>"雅安",
					"861219"=>"宜宾",
					"861220"=>"资阳",
					"861221"=>"自贡"
	),//"四川",
			"109"=>array("861501"=>"长沙",
					"861502"=>"常德",
					"861503"=>"郴州",
					"861504"=>"衡阳",
					"861505"=>"怀化",
					"861506"=>"娄底",
					"861507"=>"邵阳",
					"861508"=>"湘潭",
					"861509"=>"湘西",
					"861510"=>"永州",
					"861511"=>"岳阳",
					"861512"=>"张家界",
					"861513"=>"株洲"
	),//"湖南",
			"113"=>array("861601"=>"武汉",
					"861602"=>"鄂州",
					"861603"=>"恩施",
					"861604"=>"黄冈",
					"861605"=>"黄石",
					"861606"=>"荆门",
					"861607"=>"荆州",
					"861608"=>"十堰",
					"861609"=>"随州",
					"861610"=>"咸宁",
					"861611"=>"襄樊",
					"861612"=>"孝感",
					"861613"=>"宜昌"
	),//"湖北",
			"111"=>array("861701"=>"南昌",
					"861702"=>"抚州",
					"861703"=>"赣州",
					"861704"=>"吉安",
					"861705"=>"景德镇",
					"861706"=>"九江",
					"861707"=>"萍乡",
					"861708"=>"上饶",
					"861709"=>"新余",
					"861710"=>"宜春",
					"861711"=>"鹰潭"
	),//"江西",
			"107"=>array("862101"=>"郑州",
					"862102"=>"安阳",
					"862103"=>"鹤壁",
					"862104"=>"焦作",
					"862105"=>"开封",
					"862106"=>"洛阳",
					"862107"=>"漯河",
					"862108"=>"南阳",
					"862109"=>"平顶山",
					"862110"=>"濮阳",
					"862111"=>"三门峡",
					"862112"=>"商丘",
					"862113"=>"新乡",
					"862114"=>"信阳",
					"862115"=>"许昌",
					"862116"=>"周口",
					"862117"=>"驻马店"
	),//"河南",
			"112"=>array("862301"=>"呼和浩特",
					"862302"=>"阿拉善",
					"862303"=>"巴彦淖尔",
					"862304"=>"包头",
					"862305"=>"赤峰",
					"862306"=>"鄂尔多斯",
					"862307"=>"呼伦贝尔",
					"862308"=>"通辽",
					"862309"=>"乌海",
					"862310"=>"乌兰察布",
					"862311"=>"锡林郭勒",
					"862312"=>"兴安"
	),//"内蒙古",
			"114"=>array("861101"=>"石家庄",
					"861102"=>"保定",
					"861103"=>"沧州",
					"861104"=>"承德",
					"861105"=>"邯郸",
					"861106"=>"衡水",
					"861107"=>"廊坊",
					"861108"=>"秦皇岛",
					"861109"=>"唐山",
					"861110"=>"邢台",
					"861111"=>"张家口"
	),//"河北"
	);
	
// 	foreach ($huatai_city as $key=>$value){
// 		$name;

// 		if ($key ==101) {$name ='北京';}
// 		if ($key ==102) {$name ='浙江';}
// 		if ($key ==103) {$name ='四川';}
// 		if ($key ==104) {$name ='江苏';}
// 		if ($key ==105) {$name ='上海';}
// 		if ($key ==106) {$name ='山东';}
// 		if ($key ==107) {$name ='河南';}
// 		if ($key ==108) {$name ='福建';}
// 		if ($key ==109) {$name ='湖南';}
// 		if ($key ==110) {$name ='广东';}
// 		if ($key ==111) {$name ='江西';}
// 		if ($key ==112) {$name ='内蒙古';}
// 		if ($key ==113) {$name ='湖北';}
// 		if ($key ==114) {$name ='河北';}
		
// 		echo "insert into province_city_huatai_tour (region_id,parent_id,region_name,region_type) values (".$key.",0,'".$name."',1);";
// 		foreach ($value as $subkey=>$subValue){
// 			echo "insert into province_city_huatai_tour (region_id,parent_id,region_name,region_type) values (".$subkey.",".$key.",'".$subValue."',2);";
// 		}
		
// 	}
	
	//////////////////////////////////////////////////////////
	$provine_code = urldecode($_GET["provine_code"]);

	//和前面的前后顺序不要颠倒了。
	ss_log("provine_code: ".$provine_code);

	//////////////////////////////////////////////////////////////
	$country_list = $huatai_city[$provine_code];

	$arr_disease = array();

	//print_r($country_list);
	if(empty($country_list))
	{
		$arr_disease[0]["BD_ID"]  = 0;
		$arr_disease[0]["BD_Name"] = "";

		$jarr=json_encode($arr_disease);
		echo $jarr;
		return;
	}


	foreach($country_list as $key => $value)
	{
		/*
		 $arr_disease[$key]["BD_ID"]  = 1;
		$arr_disease[$key]["BD_Code"] = $key;
		$arr_disease[$key]["BD_Name"] = $value;
		*/
		$node = array();
		$node["BD_ID"]  = 1;
		$node["BD_Code"] = $key;
		$node["BD_Name"] = $value;

		$arr_disease[] = $node;
	}

	$jarr=json_encode($arr_disease);
	echo $jarr;

	return;
		
}