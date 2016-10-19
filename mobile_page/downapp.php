<!doctype html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1, user-scalable=no">
<title>app下载页面</title>
<link rel="stylesheet" href="dist/css/bootstrap.min.css">
<link rel="stylesheet" href="dist/css/style.css">
<script src="dist/js/jquery.min.js"></script>
<script src="dist/js/bootstrap.min.js"></script>
<script src="dist/js/html5shiv.min.js"></script>
<script src="dist/js/respond.min.js"></script>

<script src="dist/js/jquery.touchSwipe.min.js"></script>
<script src="dist/js/public.js"></script>
<script>
    var urltemp = document.location.href ;
    var type_num  = getUrlParam('type');
    // 对浏览器的UserAgent进行正则匹配，不含有微信独有标识的则为其他浏览器
    var useragent = navigator.userAgent;
    if (useragent.match(/MicroMessenger/i) != 'MicroMessenger'){
        if(type_num==1){
            window.location.href="http://www.ebaoins.cn/mobile/app_apk/eins_906.apk";
        }else if(type_num==2){
			window.location.href="https://itunes.apple.com/us/app/e-bao-xian/id967696755?l=zh&ls=1&mt=8";
        }
    }

</script>
</head>

<body>

<div class="container-fluid">
    <img src="images/app/downapp.png" class="img-responsive">
</div>

</body>
</html>
