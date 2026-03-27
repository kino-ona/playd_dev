<?php
include_once('./_common.php');

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

$sql = " select *
           from {$y1['store_info_table']}
          where user_no = '{$member['user_no']}'
            and service_state = '3' ";
$store = sql_fetch($sql);

// 로그인중이고 자신의 매장이라면 패스
if (!($member['user_no'] && $store['user_no'] === $member['user_no']))
    alert('권한이 없습니다.');

$y1['title'] = '마이메뉴';
$y1['div_cls'] = '_bg';
$y1['div_id'] = 'navMymenu';
include_once('./_head.php');
?>
<article class="mymenu write">
    <section class="_top_">
        <a href="<?php echo Y1_PAGE_URL ?>/manager_coupon" class="close">닫기</a>
        <p class="title">쿠폰등록</p>
    </section>
    
    <form name="fwrite" id="fwrite" action="./manager_coupon_regist_update" method="post" enctype="multipart/form-data">
    <input type="hidden" name="store_no" id="store_no" value="<?=$store['store_no']?>">
    
    <section class="_photo_">
        <button type="button" class="btn_photo regist">
            <span>사진등록</span>
            <div class="img"><img src="" id="now_img"></div>
        </button>
        <div class="photo_option">
            <label for="capture_inp" class="button capture">사진찍기</label>
            <input type="file" class="upload_hidden" name="img_file[]" id="capture_inp" accept="image/*" capture="camera">
            <label for="gallery_inp" class="button capture">사진선택</label>
            <input type="file" class="upload_hidden" name="img_file[]" id="gallery_inp" accept="file_extension|audio/*|video/*|image/*|media_type">
            <button type="button" class="button cancel">취소</button>
        </div>
        <div class="black_wall"></div>
    </section>
    <section class="manager">
        <div class="input_box">
            <ul>
                <li>
                    <label for="title">쿠폰내용</label>
                    <input type="text" name="title" id="title" placeholder="쿠폰내용을 입력하세요.">
                </li>
                <li>
                    <p class="label">적용기간</p>
                    <div class="datepicker_wrap">
                        <div class="datepicker">
                            <input type="text" name="start_dt" id="start_dt" readonly>
                            <button type="button" class="btn_date" id="start_date"></button>
                        </div>
                        <span> ~ </span>
                        <div class="datepicker">
                            <input type="text" name="end_dt" id="end_dt" readonly>
                            <button type="button" class="btn_date" id="end_date"></button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="btn_wrap btn_bottom">
            <input type="submit" class="btn btn_submit" value="등록">
        </div>
    </section>
    
    </form>
    
    <script>
    // 운행기간 달력
    $("#start_dt").datepicker({
        type : 'datetime',
        align : 'left'
    });
    
    $("#end_dt").datepicker({
        type : 'datetime',
        align : 'right'
    });

    $('#start_dt, #start_date').click(function() {
        $('#start_dt').datepicker('show');
        return false;
    });

    $('#end_dt, #end_date').click(function() {
        $('#end_dt').datepicker('show');
        return false;
    });
    
    var fileTarget = $("input[name='img_file[]']");
    fileTarget.on('change', handleImgFile);
    </script>
</article>
<?php
include_once('./_tail.php');
?>