<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="format-detection" content="telephone=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="/static/admin_assets/images/favicon.ico">

        <!-- App title -->
        <title>PLAYD<?=$p1['title']?' | '.$p1['title']:''?></title>


        <?php
        echo '<link rel="stylesheet" href="'.P1_CSS_URL.'/admin.css?ver='.P1_CSS_VER.'">'.PHP_EOL;
        echo '<link rel="stylesheet" href="'.P1_CSS_URL.'/bootstrap.css?ver='.P1_CSS_VER.'">'.PHP_EOL;
        ?>

        <!-- App CSS -->
        <link href="/static/admin_assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/static/admin_assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="/static/admin_assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="/static/admin_assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="/static/admin_assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="/static/admin_assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="/static/admin_assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <link rel='stylesheet' href='//cdn.jsdelivr.net/npm/font-kopub@1.0/kopubdotum.min.css'>

        <link rel="stylesheet" href="/static/admin_assets/plugins/magnific-popup/dist/magnific-popup.css"/>


        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="/static/css/jquery-ui.css">

        <script src="/static/js/jquery-1.12.4.min.js"></script>

        <!--jquery-3.4.1.min.js-->

        <script src="/static/js/jquery-ui.min.js"></script>


        <!-- Magnific popup -->
        <script type="text/javascript" src="/static/admin_assets/plugins/magnific-popup/dist/jquery.magnific-popup.min.js"></script>

        <link rel="stylesheet" type="text/css" href="/static/css/sweetalert2.min.css"/>
        <link rel="stylesheet" type="text/css" href="/static/css/jquery.toast.css"/>

        <link href="/static/css/MonthPicker.css" rel="stylesheet" type="text/css" />
        <script src="/static/js/jquery.mtz.monthpicker.js"></script>
        <!-- <script src="/lib/js/utils.js"></script> -->

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Gowun+Batang&family=Nanum+Gothic&family=Nanum+Myeongjo&display=swap');

            .swal2-title  { font-size:15px; line-height:22px;}

            .byte_box { position:absolute;top:5px;right:30px; }

            .fr-quick-insert { display: none; }
        </style>

        <!-- froala -->
        <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />


<script src="<?php echo P1_JS_URL ?>/common.js?ver=<?php echo P1_JS_VER ?>"></script>
<script src="<?php echo P1_JS_URL ?>/admin.js?ver=<?php echo P1_JS_VER ?>"></script>

</head>
<body class="fixed" >