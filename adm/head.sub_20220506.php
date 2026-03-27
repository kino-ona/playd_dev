<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initail-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?=$p1['title']?></title>
<?php
echo '<link rel="stylesheet" href="'.P1_CSS_URL.'/admin.css?ver='.P1_CSS_VER.'">'.PHP_EOL;
?>
<link rel="stylesheet" href="<?php echo P1_CSS_URL ?>/bootstrap.css" rel="stylesheet"/>
<script>
var p1_url      = "<?php echo P1_URL ?>";
var p1_page_url = "<?php echo P1_PAGE_URL ?>";
</script>
<script src="<?php echo P1_JS_URL ?>/jquery-1.7.2.min.js"></script>
<script src="<?php echo P1_JS_URL ?>/common.js?ver=<?php echo P1_JS_VER ?>"></script>
<script src="<?php echo P1_JS_URL ?>/admin.js?ver=<?php echo P1_JS_VER ?>"></script>
<script src="<?php echo P1_JS_URL ?>/bootstrap-tab.js"></script>
</head>
<body>