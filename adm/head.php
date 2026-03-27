<?php
if (!defined('_ADM_')) {
    exit();
} // 개별 페이지 접근 불가

include_once P1_PATH . '/head.sub.php';

$my_menus = '';
if($member['M_AUTH_TP']!=1){
    $sql = " select G_MENU from T_GROUP_AUTH where G_SEQ=".$member['G_SEQ']." AND G_AUTH_READ = 'Y' order by G_MENU ASC ";
    $result10 = sql_query($sql);
    for ($i=0; $row10=sql_fetch_array($result10); $i++) {
        $my_menus.= $row10['G_MENU'].",";
    }
}

?>

<div id="wrapper" >


<!-- Top Bar Start -->
<div class="topbar">

<!-- LOGO -->
<div class="topbar-left">
    <a href="" class="logo"><span><img src="/static/images/playd-logo.png" width="110"/></span><i class="zmdi zmdi-layers"></i></a>
</div>

<!-- Button mobile view to collapse sidebar menu -->
<div class="navbar navbar-default" role="navigation">
    <div class="container">
        <!-- Page title -->
        <ul class="nav navbar-nav navbar-left">
            <li>
                <button class="button-menu-mobile open-left">
                    <i class="zmdi zmdi-menu"></i>
                </button>
            </li>
            <li>
                <h4 class="page-title"> <?= $p1['title']
                    ? $p1['title']
                    : $p1['subtitle'] ?></h4>
            </li>
        </ul>
        <div class="text-right m-t-20">
            <span class="btn btn-inverse btn-trans btn-sm btn-rounded" onclick="javascript:location.href='/adm/page/administration_u_edit.php';"><?= $member[
                'M_ID'
            ] ?> 님 로그인 되었습니다.</span> <button class="btn btn-danger btn-sm btn-trans btn-rounded" onclick="javascript:logout();">  로그아웃</button>
        </div>
    </div><!-- end container -->
</div><!-- end navbar -->
</div>
<!-- Top Bar End -->

<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
<div class="sidebar-inner slimscrollleft">

    <!--- Sidemenu -->
    <div id="sidebar-menu">
        <ul>

        <li class="has_sub" <?=get_admin_menu_active('a', $my_menus)?> >
                    <a href="" class="waves-effect <?= get_admin_page_active(
                        'administration'
                    ) ?>"><span>
                    관리자 관리 </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled" style="display: block;">
                        <li <?=get_admin_menu_active('a1', $my_menus)?> class="<?= get_admin_page_active(
                            'administration.php|administration_edit.php'
                        ) ?>"><a href="/adm/page/administration.php">관리자 계정 관리</a></li>
                        <li <?=get_admin_menu_active('a2', $my_menus)?> class="<?= get_admin_page_active(
                            'administration_auth.php'
                        ) ?>"><a href="/adm/page/administration_auth.php">권한 관리</a></li>
                        <li <?=get_admin_menu_active('a3', $my_menus)?> class="<?= get_admin_page_active(
                            'administration_ip.php'
                        ) ?>"><a href="/adm/page/administration_ip.php">IP 관리</a></li>
                    </li>
                </ul>
            </li>

            <li class="has_sub" <?=get_admin_menu_active('h', $my_menus)?> >
                    <a href="" class="waves-effect <?if(strpos(basename($_SERVER["PHP_SELF"]), 'post_category_') === 0) {?> active <?}?> <?= get_admin_page_active(
                        'post','playdportfolio'
                    ) ?>"><span>
                    포트폴리오 </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled" style="display: block;">
                        <li <?=get_admin_menu_active('h1', $my_menus)?> class="<?= get_admin_page_active(
                            'post_category_'
                        ) ?>"><a href="/adm/page/post_category_list.php">카테고리 관리</a></li>
                        <li <?=get_admin_menu_active('h2', $my_menus)?> class="<?= get_admin_page_active(
                            'post','playdportfolio'
                        ) ?>"><a href="/adm/page/post.php?bc_code=playdportfolio"> 포트폴리오 게시판 관리</a></li>
                       
                    </li>
                </ul>
            </li>


            <!-- 일단주석 <li <?=get_admin_menu_active('b', $my_menus)?> >
               <a href="/adm/page/board.php" class="waves-effect <?= get_admin_page_active(
                   'board'
               ) ?>"><span> 게시판 설정 관리 </span> </a>
            </li>  -->
            <li class="has_sub" <?=get_admin_menu_active('c', $my_menus)?> >
                    <a href="" class="waves-effect <?= get_admin_page_active(
                        'post',
                        'nsmnw|report|nsmexp|playdprivate|ir_notice|pr_notice'
                    ) ?>"><span>
                    게시판 관리 </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled" style="display: block;">
                    <li <?=get_admin_menu_active('c1', $my_menus)?>  class="<?= get_admin_page_active(
                        'post',
                        'nsmnw'
                    ) ?>"><a href="/adm/page/post.php?bc_code=nsmnw">뉴스레터</a></li>
                    <li <?=get_admin_menu_active('c2', $my_menus)?> class="<?= get_admin_page_active(
                        'post',
                        'report'
                    ) ?>"><a href="/adm/page/post.php?bc_code=report">리포트</a></li>
                    <li <?=get_admin_menu_active('c3', $my_menus)?> class="<?= get_admin_page_active(
                        'post',
                        'nsmexp'
                    ) ?>"><a href="/adm/page/post.php?bc_code=nsmexp">광고컬럼</a></li>
                    <!-- <li <?=get_admin_menu_active('c4', $my_menus)?> class="<?= get_admin_page_active(
                        'post',
                        'playdportfolio'
                    ) ?>"><a href="/adm/page/post.php?bc_code=playdportfolio">포트폴리오</a></li> -->
                    <li <?=get_admin_menu_active('c5', $my_menus)?> class="<?= get_admin_page_active(
                        'post',
                        'playdprivate'
                    ) ?>"><a href="/adm/page/post.php?bc_code=playdprivate">개인정보처리방침</a></li>
                    <li <?=get_admin_menu_active('c6', $my_menus)?> class="<?= get_admin_page_active(
                        'post',
                        'ir_notice'
                    ) ?>"><a href="/adm/page/post.php?bc_code=ir_notice">전자공고</a></li>
                    <li <?=get_admin_menu_active('c7', $my_menus)?> class="<?= get_admin_page_active(
                        'post',
                        'pr_notice'
                    ) ?>"><a href="/adm/page/post.php?bc_code=pr_notice">공시정보</a></li>

                    </li>
                </ul>
            </li>

            <li class="has_sub"  <?=get_admin_menu_active('d', $my_menus)?>>
                    <a href="" class="waves-effect <?= get_admin_page_active(
                        'qna|report'
                    ) ?>"><span>
                    문의 관리 </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled" style="display: block;">
                        <li <?=get_admin_menu_active('d1', $my_menus)?> class="<?= get_admin_page_active(
                            'qna',
                            'nsmad'
                        ) ?>"><a href="/adm/page/qna.php?bc_code=nsmad">광고문의</a></li>
                        <!-- <li class="<?= get_admin_page_active(
                            'qna'
                        ) ?>"><a href="/adm/page/qna.php?bc_code=nsmasso">제휴문의</a></li> -->
                        <li <?if($_SESSION['ss_m_id'] != 'admin_et'){?> style="display:none" <?}?> <?=get_admin_menu_active('d2', $my_menus)?> class="<?= get_admin_page_active(
                            'report'
                        ) ?>"><a href="/adm/page/report.php">윤리경영제보</a></li>
                        <!-- <li class="<?= get_admin_page_active(
                            'qna'
                        ) ?>"><a href="/adm/page/qna.php?bc_code=nsmcommerce">글로벌커머스</a></li> -->
                    </li>
                </ul>
            </li>

            <li class="has_sub" <?=get_admin_menu_active('e', $my_menus)?>>
                    <a href="" class="waves-effect <?= get_admin_page_active(
                        'incident|incident_sang'
                    ) ?>"><span>
                    채용공고 관리 </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled" style="display: block;">
                        <li <?=get_admin_menu_active('e1', $my_menus)?> class="<?= get_admin_page_active(
                            'incident.php|incident_edit.php'
                        ) ?>"><a href="/adm/page/incident.php">채용공고</a></li>
                        <li <?=get_admin_menu_active('e2', $my_menus)?> class="<?= get_admin_page_active(
                            'incident_sang.php|incident_sang_edit.php'
                        ) ?>"><a href="/adm/page/incident_sang.php">상시채용</a></li>
                    </li>
                </ul>
            </li>


            <li class="has_sub" <?=get_admin_menu_active('f', $my_menus)?>>
                    <a href="" class="waves-effect <?= get_admin_page_active(
                        'newsletter|newsreport'
                    ) ?>"><span>
                    신청자 관리 </span> <span class="menu-arrow"></span></a>
                    <ul class="list-unstyled" style="display: block;">
                        <li <?=get_admin_menu_active('f1', $my_menus)?> class="<?= get_admin_page_active(
                            'newsletter'
                        ) ?>"><a href="/adm/page/newsletter.php">뉴스레터</a></li>
                        <li <?=get_admin_menu_active('f2', $my_menus)?> class="<?= get_admin_page_active(
                            'newsreport'
                        ) ?>"><a href="/adm/page/newsreport.php">리포트</a></li>
                    </li>
                </ul>
            </li>

            <li  <?=get_admin_menu_active('g', $my_menus)?>>
               <a href="/adm/page/post.php?bc_code=files" class="waves-effect <?= get_admin_page_active(
                   'post',
                   'files'
               ) ?>"><span> 파일 업로드 </span> </a>
            </li>


        </ul>
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -->
    <div class="clearfix"></div>

</div>

</div>
<!-- Left Sidebar End -->

<script>
function logout(){
    Swal.fire({
                title: '로그아웃하시겠습니까?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '확인',
                cancelButtonText: '취소'
        }).then((result) => {
                if (result.value) {
                    javascript:location.href='/adm/page/logout.php';
                } else {

                }
        });
}
</script>

      <div class="content-page" >
                <!-- Start content -->

                <div class="content" >
                    <div class="container">
                        <div class="row"  >

                        <div class="col-md-12">
                            <section class="card-box">
