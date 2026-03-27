<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가
?>


<script>
        var resizefunc = [];
        <?if($waitCount>0){?>
            $('#consulting-menu').show();
        <?} else {?>
            $('#consulting-menu').hide();
        <?}?>
    </script>
        <!-- jQuery  -->
        <script src="/static/admin_assets/js/bootstrap.min.js"></script>
        <script src="/static/admin_assets/js/detect.js"></script>
        <script src="/static/admin_assets/js/fastclick.js"></script>
        <script src="/static/admin_assets/js/jquery.slimscroll.js"></script>
        <script src="/static/admin_assets/js/jquery.blockUI.js"></script>
        <script src="/static/admin_assets/js/waves.js"></script>
        <script src="/static/admin_assets/js/wow.min.js"></script>
        <script src="/static/admin_assets/js/jquery.nicescroll.js"></script>
        <script src="/static/admin_assets/js/jquery.scrollTo.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.form.min.js"></script>
        <script type="text/javascript" src="/static/js/spin.min.js"></script>
        <script type="text/javascript" src="/static/js/jquery.toast.js"></script>
        <script src="/static/admin_assets/js/modernizr.min.js"></script>

        <script src="/static/js/sweetalert2.min.js"></script>

        <link href="/static/css/daterangepicker.css" rel="stylesheet" type="text/css" />
        <script src="/static/js/moment.js"></script>
        <script src="/static/js/daterangepicker.js"></script>


        <script src="/static/admin_assets/js/jquery.common.js"></script>

    </body>
</html>


<?php echo html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다. ?>