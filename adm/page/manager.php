<?php
include_once('./_common.php');

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

$sql = " select count(*) as cnt
           from {$y1['store_info_table']}
          where user_no = '{$member['user_no']}'
            and service_state in (1, 2) ";
$row = sql_fetch($sql);
if ($row['cnt'] > 0) {
    alert("가맹점회원 심의 중입니다.");
}

$sql = " select *
           from {$y1['store_info_table']}
          where user_no = '{$member['user_no']}'
            and service_state = '3' ";
$store = sql_fetch($sql);

// 매장공지
$sql_noti = " select *
                from {$y1['store_notice_table']}
               where use_yn = '1' ";
$res_noti = sql_query($sql_noti);
$noti_cnt = sql_num_rows($res_noti);
$noti     = sql_fetch($sql_noti);

$y1['title'] = '마이메뉴';
$y1['div_cls'] = 'nav_wrap _bg';
$y1['div_id'] = 'navMymenu';
include_once('./_head.php');
?>
<article class="mymenu detail">
    <section class="_top_">
        <a href="<?php echo Y1_PAGE_URL ?>/mymenu" class="go_list">마이메뉴</a>
        <p class="title"><?=$store['store_disp_nm']?></p>
    </section>
    <section class="list_mymenu">
        <ul>
            <li><a href="<?php echo Y1_PAGE_URL ?>/manager_store" class="store">매장정보</a></li>
            <!--<li><a href="#" class="commu">커뮤니티</a></li>-->
            <li><a href="<?php echo Y1_PAGE_URL ?>/push_message" class="messege">메시지 보내기</a></li>
            <li><a href="<?php echo Y1_PAGE_URL ?>/manager_coupon" class="coupon">쿠폰 등록</a></li>
            <!--<li><a href="#" class="ad">AD 신청</a></li>-->
        </ul>
    </section>
    <?php if ($noti_cnt > 0) { ?>
    <section class="noti_pop">
        <p class="_head"><?=$noti['title']?></p>
        <p class="_inner"><?=$noti['notice']?></p>
    </section>
    <?php } ?>
    <section>
        <div class="name_card">
            <p>비즈무리사업단</p>
            <span class="address">충청남도 천안시 ㅇㅇㅇ길 40</span>
            <span>문의 : 041-850-8114</span>
        </div>
    </section>
</article>
<?php
include_once('./_tail.php');
?>