<!doctype html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>分享APP</title>
<link rel="stylesheet" href="dist/css/bootstrap.min.css">
<link rel="stylesheet" href="dist/fonts/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="dist/css/style.css">

</head>

<body>
<div class="container-fluid" style=" padding: 45px 0px;" align="center">
    <img src="images/app/logo.png" class="img-responsive">
</div>
<div class="container-fluid">
	 
	<div class="container" style="padding:10px;" id="type_qcode">
    	<div class="panel panel-default padd_T10" style="border:0px; background:none; box-shadow: none">
            <div align="center"><img src="images/app/app_down_2.png" class="img-responsive"></div>
            <div class="panel-body font_12 line_h25" style="padding:10px 0px; text-align: center">
                扫我呀,么么哒！
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class=" col-md-6 col-sm-6 col-xs-12 text-center" id="type_1">
                <a href="downapp.php?type=1" class="btn btn-97c024 " style="box-shadow: none;">
                    <i class="fa fa-2x fa-android"></i>
                    <span>下载安卓客户端</span>
                </a>
            </div>
            <div class=" col-md-6 col-sm-6 col-xs-12  text-center" id="type_2">
                <a href="downapp.php?type=2" class="btn btn-fe735c " style="box-shadow: none;">
                    <i class="fa fa-2x fa-apple"></i>
                    <span>下载苹果客户端</span>
                </a>
            </div>
        </div>
    </div>
    <div class="container mar_T25 mar_B40">
        <div class="row mar_T15">
            <div class=" col-md-4 col-sm-4 col-xs-3 text-right">
                <span class="font_22 font_bolder text-c-ed462f ">产品</span>
            </div>
            <div class=" col-md-8 col-sm-8 col-xs-9">
                <span class="font_10 line_h25">贴近用户需求,优选保险产品,</span>
            </div>
        </div>
        <div class="row">
            <div class=" col-md-4 col-sm-4 col-xs-3 text-right">
                <span class="font_22 font_bolder text-c-ed462f ">优势</span>
            </div>
            <div class=" col-md-8 col-sm-8 col-xs-9">
                <span class="font_10 line_h25">满足高额收益,提供全面保障。</span>
            </div>
        </div>
        <div class="row mar_T15">
            <div class=" col-md-4 col-sm-4 col-xs-3 text-right">
                <span class="font_22 font_bolder text-c-ed462f ">技术</span>
            </div>
            <div class=" col-md-8 col-sm-8 col-xs-9">
                <span class="font_10 line_h25">在线即时投保,有效电子保单,</span>
            </div>
        </div>
        <div class="row">
            <div class=" col-md-4 col-sm-4 col-xs-3 text-right">
                <span class="font_22 font_bolder text-c-ed462f ">优势</span>
            </div>
            <div class=" col-md-8 col-sm-8 col-xs-9">
                <span class="font_10 line_h25">实时资金结算,便捷客户管理。</span>
            </div>
        </div>
        <div class="row mar_T15">
            <div class=" col-md-4 col-sm-4 col-xs-3 text-right">
                <span class="font_22 font_bolder text-c-ed462f ">推广</span>
            </div>
            <div class=" col-md-8 col-sm-8 col-xs-9">
                <span class="font_10 line_h25">丰富的推广手段,多样化的推广模式,</span>
            </div>
        </div>
        <div class="row">
            <div class=" col-md-4 col-sm-4 col-xs-3 text-right">
                <span class="font_22 font_bolder text-c-ed462f ">优势</span>
            </div>
            <div class=" col-md-8 col-sm-8 col-xs-9">
                <span class="font_10 line_h25">推广展业,从此无忧。</span>
            </div>
        </div>
    </div>
</div>
<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
<script src="dist/js/html5shiv.min.js"></script>
<script src="dist/js/respond.min.js"></script>

<script src="dist/js/public.js"></script>
<script>
    var urltemp = document.location.href ;
    var type_num  = getUrlParam('type');
	 
    if(type_num==1){
		 $('#type_qcode').show();
		 $('#type_1').show();
	}else if(type_num==2){
		 $('#type_qcode').hide();
		 $('#type_1').hide();
	}

</script>

</body>
</html>
