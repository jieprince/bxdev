<!doctype html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>邀请加入</title>
<link rel="stylesheet" href="dist/css/bootstrap.min.css">
<link rel="stylesheet" href="dist/css/style.css">
</head>

<body>

<div class="container-fluid" style=" padding: 45px 0px;" align="center">
    <img src="images/app/logo.png" class="img-responsive">
</div>
<div class="container-fluid">

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

    <div class="container  mar_B40">
        <div class="text-center font_12"><span>现在我们都移动互联网了，赶紧注册吧!</span></div>
        <div class="text-center mar_T25">
            <a href="reg.html" id="reg_btn" class="btn btn-danger btn-block">我要注册</a>
        </div>
    </div>

</div>
<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
<script src="dist/js/html5shiv.min.js"></script>
<script src="dist/js/respond.min.js"></script>
<script src="dist/js/public.js"></script>
<script>
    $().ready(function(){
        var uidtemp =  getUrlParam('agentid');
		if(uidtemp){
        	$('#reg_btn').attr('href','reg.html?uid='+uidtemp);
		}
    });
</script>
</body>
</html>
