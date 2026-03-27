<?php
define('_NAV_', true);

include_once('./_common.php');

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

// 선호음식 정보
$sql_food = " select *
                from {$y1['user_food_table']}
               where user_no = '{$member['user_no']}' ";
$res_food = sql_query($sql_food);
for($i=0; $row=sql_fetch_array($res_food); $i++) {
    if ($row['food_cd'] == "99") {
        $food[]    = $row['food_cd'];
        $food_name = $row['food_nm'];
    } else {
        $food[]    = $row['food_cd'];
    }
}
$food = implode(",", $food);

// 자주가는지역 정보
$sql_region = " select *
                  from {$y1['user_region_table']}
                 where user_no = '{$member['user_no']}' ";
$res_region = sql_query($sql_region);
for($i=0; $row=sql_fetch_array($res_region); $i++) {
    if ($row['region_cd'] == "99") {
        $region[]    = $row['region_cd'];
        $region_name = $row['region_nm'];
    } else {
        $region[]    = $row['region_cd'];
    }
}
$region = implode(",", $region);

$y1['title'] = '마이메뉴';
$y1['div_cls'] = 'nav_wrap';
$y1['div_id'] = 'navMymenu';
include_once('./_head.php');
?>
<article class="mymenu detail write">
    <section class="_top_">
        <a href="<?php echo Y1_PAGE_URL ?>/mymenu" class="go_list">마이메뉴</a>
        <p class="title">내 정보 수정</p>
    </section>
    
    <script src="<?php echo Y1_JS_URL; ?>/jquery.register_form.js"></script>
    
    <form name="fwrite" id="fwrite" action="./mymenu_regist_update" onsubmit="return fmymenu_submit(this);" method="post" enctype="multipart/form-data">
    <input type="hidden" name="login_key" id="login_key" value="<?=$member['login_key']?>">
    <input type="hidden" name="age_group" id="age_group" value="<?=$member['age_group']?>">
    <input type="hidden" name="job" id="job" value="<?=$member['job']?>">
    <input type="hidden" name="job_name" id="job_name" value="<?=$member['job_name']?>">
    <input type="hidden" name="food" id="food" value="<?=$food?>">
    <input type="hidden" name="food_name" id="food_name" value="<?=$food_name?>">
    <input type="hidden" name="area" id="area" value="<?=$region?>">
    <input type="hidden" name="area_name" id="area_name" value="<?=$region_name?>">
    
    <section class="profile">
        <div class="_photo_">
            <button type="button" class="btn_photo regist">
                <span><?=($member['thumbnail_url']) ? "수정" : "등록"?></span>
                <div class="img"><img src="<?=$member['thumbnail_url']?>" id="now_img"></div>
            </button>
            <div class="photo_option">
                <label for="capture_inp" class="button capture">사진찍기</label>
                <input type="file" class="upload_hidden" name="img_file[]" id="capture_inp" accept="image/*" capture="camera">
                <label for="gallery_inp" class="button capture">사진선택</label>
                <input type="file" class="upload_hidden" name="img_file[]" id="gallery_inp" accept="file_extension|audio/*|video/*|image/*|media_type">
                <button class="button cancel">취소</button>
            </div>
            <div class="black_wall"></div>
        </div>
        <div class="input_box">
            <ul>
                <li>
                    <label for="nickname">닉네임</label>
                    <input type="text" name="nickname" id="nickname" value="<?=$member['nickname']?>">
                </li>
                <li>
                    <label for="email">이메일</label>
                    <input type="text" name="login_accnt" id="login_accnt" value="<?=$member['login_accnt']?>" readonly>
                </li>
            </ul>
        </div>
    </section>
    <section class="list_slide">
        <ul>
            <li>
                <a href="#" class="age">연령대</a>
                <div class="_inner">
                    <div class="sel_list sel_single">
                        <?=get_reg_step_code_list("110", "age_group", "sel_btn", "1", $member['age_group'])?>
                    </div>
                </div>
            </li>
            <li>
                <a href="#" class="job">직업</a>
                <div class="_inner">
                    <div class="sel_list sel_single">
                        <?=get_reg_step_code_list("120", "job", "sel_btn", "1", $member['job'])?>
                    </div>
                </div>
            </li>
            <li>
                <a href="#" class="food">선호음식</a>
                <div class="_inner">
                    <div class="sel_list btn_col2 sel_multi">
                        <?=get_reg_step_code_list("130", "food", "sel_btn", "1", $food)?>
                    </div>
                </div>
            </li>
            <li>
                <a href="#" class="place">자주가는 지역</a>
                <div class="_inner">
                    <div class="sel_list sel_multi">
                        <?=get_reg_step_code_list("140", "area", "sel_btn", "1", $region)?>
                    </div>
                </div>
            </li>
        </ul>
        <div class="btn_wrap">
            <input type="submit" class="btn btn_submit" value="수정">
        </div>
    </section>
    
    </form>
    
    <script>
    function fmymenu_submit(f)
    {
        // 닉네임 검사
        var msg = reg_nickname_check();
        if (msg) {
            alert(msg);
            f.nickname.select();
            return false;
        }

        return true;
    }
    
    var fileTarget = $("input[name='img_file[]']");
    fileTarget.on('change', handleImgFile);
    </script>
</article>
<?php
include_once('./_tail.php');
?>