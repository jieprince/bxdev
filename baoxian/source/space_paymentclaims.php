<?php

//////////////////////////////////////////////////////
$peer_order_id = rand(1,1000);
//////////////////////////////////////////////////////
$en_str = "fei93-tgie13jwi.6s78wwmerti";

$flag = empty($_GET['onlywatch'])? 1:0;

if($flag)
{
	
	$data = array(
		"user_id"=>"12",
		"product_id"=>"6",
		"peer_order_id"=>$peer_order_id,
		"relationshipwithInsured"=>"3",
		"totalpremium"=>"190.00",
		"hardwareprice"=>"509.00",
		"startdate"=>"2014-10-29 00:00:00",
		"enddate"=>"2015-09-28 00:00:00",
		"sig"=>"dsfsdfsdfsdffffffffffffffffrdf",
		"parent"=>
		array(
			"assured_certificates_type"=>"01",
			"assured_certificates_code"=>"610102197611113031",
			"assured_birthday"=>"1976-10-11",
			"assured_fullname"=>"老王",
			"assured_sex"=>"M",
			"assured_mobilephone"=>"13677777777",
			"assured_email"=>"laowang@.126.com"
		),
		"children"=>
		array(
			"assured_certificates_type"=>"01",
			"assured_certificates_code"=>"610102200606023031",
			"assured_birthday"=>"2006-06-01",
			"assured_fullname"=>"小王",
			"assured_sex"=>"M",
			"assured_mobilephone"=>"13677567777",
			"assured_email"=>"xiaowang@.126.com"
		),
	  );


	$md_str_sourece = $data[peer_order_id].$data[user_id].$data[product_id].$data[parent][assured_certificates_code].$data[children][assured_certificates_code].$data[totalpremium].$data[hardwareprice].$en_str;
}
else
{
	$data = array(
			"user_id"=>"12",
			"peer_order_id"=>$peer_order_id,
			"totalpremium"=>0,
			"hardwareprice"=>"599",
			"sig"=>"dsfsdfsdfsdffffffffffffffffrdf"
			);
	
	$md_str_sourece = $data[peer_order_id].$data[user_id].$data[hardwareprice].$en_str;
}

///////////////////////////////////////////////////////////////////////////
$data['sig'] = md5($md_str_sourece);


$jsondata = json_encode($data);

$_TPL['css'] = 'client_product';
include_once template("space_paymentclaims");