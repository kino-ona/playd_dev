<?php
if (!defined('_YOLU_')) exit; // 개별 페이지 접근 불가

$sql = " select *
           from {$y1['store_info_table']}
          where user_no = '{$member['user_no']}'
            and service_state = '3' ";
$store = sql_fetch($sql);

$store['store_no']      = get_text($store['store_no']);         # 번호
$store['store_disp_nm'] = get_text($store['store_disp_nm']);    # 매장명
$store['tel']           = get_text($store['tel']);              # 대표전화
$store['parking']       = get_text($store['parking']);          # 주차가능여부
$store['bg_img_url']    = ($store['bg_img_url'])   ? get_text($store['bg_img_url'])   : Y1_NOIMG_VISUAL;    # 배경이미지경로
$store['icon_img_url']  = ($store['icon_img_url']) ? get_text($store['icon_img_url']) : Y1_NOIMG_STORE;     # 아이콘이미지경로

add_stylesheet(Y1_SWIPER_CSS, 0);    // swiper css
add_javascript(Y1_SWIPER_JS, 0);     // swiper js
?>
<section class="_intro_">
    <div class="visual">
        <img src="<?=$store['bg_img_url']?>" id="now_bg_img">
    </div>
    <div class="profile _photo_">
        <button class="btn_photo_bg regist_bg" onclick="javascript:setImagebordType('background')"></button>
        <button class="btn_photo regist" onclick="javascript:setImagebordType('profile')">
            <span></span>
            <div class="img"><img src="<?=$store['icon_img_url']?>" id="now_img"></div>
        </button>
        <div class="photo_option">
            <label for="capture_inp" class="button">사진찍기</label>
            <input type="file" class="upload_hidden" name="img_file[]" id="capture_inp" accept="image/*" capture="camera">
            <label for="gallery_inp" class="button">사진선택</label>
            <input type="file" class="upload_hidden" name="img_file[]" id="gallery_inp" accept="file_extension|audio/*|video/*|image/*|media_type">
            <button class="button cancel">취소</button>
        </div>
        <div class="bg_photo_option">
            <label for="bg_capture_inp" class="button">배경사진찍기</label>
            <input type="file" class="upload_hidden" name="img_file_bg[]" id="bg_capture_inp" accept="image/*" capture="camera">
            <label for="bg_gallery_inp" class="button">배경사진선택</label>
            <input type="file" class="upload_hidden" name="img_file_bg[]" id="bg_gallery_inp" accept="file_extension|audio/*|video/*|image/*|media_type">
            <button class="button cancel">취소</button>
        </div>
        <div class="black_wall"></div>
        <p class="name"><?=$store['store_disp_nm']?></p>
        <div class="tag">
            <?php
            // 해시태그 정보
            $sql_hash = " select *
                            from {$y1['store_recom_table']}
                           where store_no = '{$store['store_no']}'
                        order by reg_dttm ";
            $res_hash = sql_query($sql_hash);
            while($row_hash=sql_fetch_array($res_hash)) {
                echo '<p>'.$row_hash['rec_word'].'</p>';
            }
            ?>
        </div>
    </div>
</section>
<section class="_info_">
    <dl class="addr">
        <dt>소개</dt>
        <dd><?=nl2br($store['store_intro'])?></dd>
    </dl>
    <dl class="addr">
        <dt>주소</dt>
        <dd><?=$store['addr1']?> <?=$store['addr2']?></dd>
    </dl>
    <dl class="call">
        <dt>전화번호</dt>
        <dd><?=$store['tel']?></dd>
    </dl>
    <dl class="time">
        <dt>영업시간</dt>
        <dd>
            <?php
            // 요일 코드
            $yoil = date("w", strtotime(Y1_TIME_YMD));
            $sql_time = " select case
                                     when day_off = '1' then '휴무'
                                     else concat(open_time, ' ~ ', end_time)
                                 end as 'time'
                            from {$y1['store_weekly_time_table']}
                           where store_no = '{$store['store_no']}'
                             and day_code = '{$yoil}' ";
            $row_time = sql_fetch($sql_time);
            echo $row_time['time'];
            ?>
        </dd>
    </dl>
    <dl class="parking">
        <dt>주차</dt>
        <dd><?=($store['parking'] == "1") ? "주차가능" : "상관없음"?></dd>
    </dl>
    <a href="<?php echo Y1_PAGE_URL ?>/manager_store?tab=info" class="btn btn_edit">기본정보수정</a>
</section>
<section class="_coupon_">
    <div class="overwrap">
        <?php
        $sql_coupon = " select *
                          from {$y1['cupon_appl_table']}
                         where store_no = '{$store['store_no']}'
                           and appl_state = '2'
                           and '".Y1_TIME_YMDHIS."' between start_dt and end_dt
                      order by start_dt, end_dt
                         limit 1 ";
        $res_coupon = sql_query($sql_coupon);
        while($row_coupon=sql_fetch_array($res_coupon)) {
            $cupon_img_url = ($row_coupon['img_url']) ? $row_coupon['img_url'] : Y1_NOIMG_STORE;
        ?>
        <article onclick="alert('리뷰 등록시 자동 발급됩니다.');">
            <div class="img">
                <img src="<?=$cupon_img_url?>">
            </div>
            <div class="txt">
                <p class="tag">COUPON</p>
                <p class="inner"><?=$row_coupon['title']?></p>
            </div>
        </article>
        <?php
        }
        ?>
    </div>
</section>
<section class="_menu_">
    <div class="btn_wrap">
        <a href="<?php echo Y1_PAGE_URL ?>/manager_store_menu_regist?store_no=<?=$store['store_no']?>" class="btn btn_add_menu">메뉴 추가</a>
    </div>
    <?php
    $idx = 0;
    $sql_menu = " select *
                    from {$y1['menu_table']}
                   where store_no = '{$store['store_no']}'
                     and use_yn = '1'
                order by disp_ord ";
    $res_menu = sql_query($sql_menu);
    while($row_menu=sql_fetch_array($res_menu)) {
        $menu_img_url = ($row_menu['img_url']) ? $row_menu['img_url'] : Y1_NOIMG_MENU;
        
        $idx += 1;
    ?>
    <article>
        <div class="base">
            <div class="img">
                <img src="<?=$menu_img_url?>">
            </div>
            <div class="txt">
                <p class="name"><?=$row_menu['menu_nm']?><span><?=number_format($row_menu['price'])?></span></p>
                <div class="tag">
                    <?php
                    // 리뷰 해시태그 정보
                    // 리뷰 등록시 해시태그 TOP 3
                    $sql_hash_re = " select distinct(review_word),
                                            count(review_word) as cnt
                                       from {$y1['review_recom_table']}
                                      where menu_no = '{$row_menu['menu_no']}'
                                   group by review_word
                                   order by cnt desc, reg_dttm desc
                                      limit 3 ";
                    $res_hash_re = sql_query($sql_hash_re);
                    while($row_hash_re=sql_fetch_array($res_hash_re)) {
                        echo '<p>'.$row_hash_re['review_word'].'</p>';
                    }
                    ?>
                </div>
            </div>
            <div class="btn_wrap">
                <a href="<?php echo Y1_PAGE_URL ?>/manager_store_menu_regist?m=u&menu_no=<?=$row_menu['menu_no']?>" class="btn_menu">메뉴수정</a>
                <a href="<?php echo Y1_PAGE_URL ?>/review_regist?menu_no=<?=$row_menu['menu_no']?>" class="btn_menu btn_review">리뷰등록</a>
            </div>
        </div>
        <div class="review swiper-container_<?=$idx?>">
            <div class="overwrap swiper-wrapper">
                <?php
                $show_review = 6;
                $sql_review = " select *
                                  from {$y1['menu_review_table']}
                                 where review_state = '2'
                                   and menu_no = '{$row_menu['menu_no']}'
                              order by reg_dttm desc
                                 limit {$show_review} ";
                $res_review = sql_query($sql_review);
                while($row_review=sql_fetch_array($res_review)) {
                    $thumb_url = ($row_review['thumb_url']) ? $row_review['thumb_url'] : Y1_NOIMG_GALLERY;
                ?>
                <a href="./review_view?review_no=<?=$row_review['review_no']?>" class="swiper-slide"><img src="<?=$thumb_url?>"></a>
                <?php
                }
                ?>
            </div>
        </div>
        
        <form name="ajax_reivew_frm_<?=$idx?>" id="ajax_reivew_frm_<?=$idx?>">
            <input type="hidden" name="menu_no" value="<?=$row_menu['menu_no']?>">
            <input type="hidden" name="show_review" value="<?=$show_review?>">
        </form>
        
        <script>
        $(function() {
            var swiper_<?=$idx?> = new Swiper('.swiper-container_<?=$idx?>', {
                slidesPerView: <?=($show_review / 2)?>,
                slidesPerGroup: <?=($show_review / 2)?>,
            });
            swiper_<?=$idx?>.on('reachEnd', function () {
                var last_idx = swiper_<?=$idx?>.activeIndex + <?=$show_review?>;
                review_more(last_idx, $('#ajax_reivew_frm_<?=$idx?>').serialize(), swiper_<?=$idx?>);
            });
        });
        </script>
    </article>
    <?php
    }
    ?>
</section>

<script>
var fileTarget = $("input[name='img_file[]']");
fileTarget.on('change', function(e) {
    handleImgFile(e, 1);
    imgUpload(e.target.files[0], '<?=$store['store_no']?>');
});

var BGfileTarget = $("input[name='img_file_bg[]']");
BGfileTarget.on('change', function(e) {
    handleBGImgFile(e, 1);
    BGimgUpload(e.target.files[0], '<?=$store['store_no']?>');
});
</script>