<?php
define('_NAV_', true);

include_once('./_common.php');

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

// 받은 쿠폰 리스트
$sql = " select *
           from {$y1['user_cupon_table']}
          where user_no = '{$member['user_no']}'
       order by reg_dttm desc ";
$res = sql_query($sql);

$y1['title'] = '마이메뉴';
$y1['div_cls'] = 'nav_wrap';
$y1['div_id'] = 'navMymenu';
include_once('./_head.php');
?>
<article class="mymenu list review">
    <section class="_top_">
        <a href="<?php echo Y1_PAGE_URL ?>/mymenu" class="go_list">마이메뉴</a>
        <p class="title">받은 쿠폰</p>
    </section>
    <section class="list_coupon">
        <ul>
            <?php
            for ($i=0; $row=sql_fetch_array($res); $i++) {
                // 리뷰 정보
                $review = get_review($row['review_no']);
                
                $review_img_url = ($review['image_url']) ? $review['image_url'] : Y1_URL.Y1_NOIMG_GALLERY;
                
                // 사용자 정보
                $user = get_user($review['user_no']);
                
                // 메뉴 정보
                $menu = get_menu($review['menu_no']);
                
                // 쿠폰 정보
                $coupon = get_coupon($row['appl_no']);
                
                $coupon_img_url = ($coupon['img_url']) ? $coupon['img_url'] : Y1_URL.Y1_NOIMG_STORE;
                
                // 매장 정보
                $store = get_store($coupon['store_no']);
                
                // 만료시간 구하기..
                $now_time = strtotime(Y1_TIME_YMDHIS);
                $exp_time = strtotime($row['exp_dttm']);

                $passing_time_state = ($now_time >= $exp_time) ? true : false;
            ?>
            <li>
                <div class="coupon_wrap" onclick="location.href='<?php echo Y1_PAGE_URL ?>/store_view?store_no=<?=$store['store_no']?>&url=<?php echo Y1_PAGE_URL ?>/mymenu_coupon'" style="cursor:pointer;">
                    <div class="img">
                        <img src="<?=$coupon_img_url?>">
                    </div>
                    <div class="txt">
                        <div class="coupon on">coupon</div>
                        <p class="name"><?=$store['store_disp_nm']?></p>
                        <p class="info"><?=$coupon['title']?></p>
                    </div>
                </div>
                <div class="status _cont_">
                    <!-- 미사용된 경우 -->
                    <p><span>받은시간</span><?=$row['reg_dttm']?></p>
                    <?php if($passing_time_state && $row['use_state'] == "1") { ?>
                    <p><span>만료시간</span><?=$row['exp_dttm']?></p>
                    <button class="btn expire" id="state">기간만료</button>
                    <?php } else if($row['use_state'] == "2") {?>
                    <p><span>사용시간</span><?=$row['use_dttm']?></p>
                    <button class="btn complete" id="state">사용완료</button>
                     <?php } else if(!$passing_time_state && $row['use_state'] == "1") { ?>
                    <p><span>사용기한</span><?=$row['exp_dttm']?></p>
                    <button class="btn available" id="state">사용하기</button>
                    
                    <div class="coupon_pop">
                        <p class="store_name"><?=$store['store_disp_nm']?></p>
                        <p class="name"><?=$menu['menu_nm']?></p>
                        <div class="coupon_box">
                            <div class="img">
                                <img src="<?=$review_img_url?>">
                            </div>
                            <div class="txt">
                                <div class="user_info">
                                    <span class="user_id"><?=$user['nickname']?></span>
                                    <span class="date"><?=passing_time($review['reg_dttm'])?></span>
                                </div>
                                <div class="review_txt">
                                    <p><?=nl2br($review['review_txt'])?></p>
                                </div>
                            </div>
                        </div>
                        <div class="btn_wrap btn_bottom btn_col2">
                            <button type="button" class="btn btn_confirm" onclick="mymenu_use_coupon(this, '<?=$row['user_cupon_no']?>');">직원확인</button>
                            <button type="button" class="btn btn_cancel">취소</button>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </li>
            <?php
            }
            if ($i == 0)
                echo "<li>자료가 없습니다.</li>";
            ?>
        </ul>
    </section>
</article>
<?php
include_once('./_tail.php');
?>