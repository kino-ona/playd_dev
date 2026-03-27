<?php
include_once('./_common.php');

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

if ($m == "") {
    $html_title = "등록";
    
    $menu['store_no'] = get_text($_GET['store_no']);    # 가맹점번호
} else if ($m == "u") {
    $menu_no = get_text($_GET['menu_no']);    # 메뉴번호
    
    $menu = get_menu($menu_no);
    if (!$menu['menu_no'])
        alert("존재하지 않는 메뉴입니다.");
    
    $html_title = "수정";
    
    $menu['menu_no']  = get_text($menu['menu_no']);     # 메뉴번호
    $menu['menu_nm']  = get_text($menu['menu_nm']);     # 메뉴이름
    $menu['store_no'] = get_text($menu['store_no']);    # 가맹점번호
    $menu['price']    = get_text($menu['price']);       # 가격
    $menu['img_url']  = get_text($menu['img_url']);     # 메뉴이미지경로
    $menu['disp_ord'] = get_text($menu['disp_ord']);    # 표시순서
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

$y1['title'] = '마이메뉴';
$y1['div_cls'] = 'nav_wrap _bg';
$y1['div_id'] = 'navMymenu';
include_once('./_head.php');
?>
<article class="mymenu write">
    <section class="_top_">
        <a href="javascript:history.back();" class="close">닫기</a>
        <p class="title">메뉴 등록</p>
    </section>
    
    <form name="fwrite" id="fwrite" action="./manager_store_menu_regist_update" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data">
    <input type="hidden" name="m" id="m" value="<?=$m?>">
    <input type="hidden" name="menu_no" id="menu_no" value="<?=$menu['menu_no']?>">
    <input type="hidden" name="store_no" id="store_no" value="<?=$menu['store_no']?>">

    <section class="_photo_">
        <button type="button" class="btn_photo<?=($menu['img_url']) ? " edit" : " regist"?>">
            <span>사진<?=($menu['img_url']) ? "수정" : "등록"?></span>
            <div class="img"><img src="<?=$menu['img_url']?>" id="now_img"></div>
        </button>
        <div class="photo_option">
            <label for="capture_inp" class="button">사진찍기</label>
            <input type="file" class="upload_hidden" name="img_file[]" id="capture_inp" accept="image/*" capture="camera">
            <label for="gallery_inp" class="button">사진선택</label>
            <input type="file" class="upload_hidden" name="img_file[]" id="gallery_inp" accept="file_extension|audio/*|video/*|image/*|media_type">
            <button type="button" class="button cancel">취소</button>
        </div>
        <div class="black_wall"></div>
    </section>
    <section class="manager">
        <div class="input_box">
            <ul>
                <li>
                    <label for="menu_nm">메뉴명</label>
                    <input type="text" name="menu_nm" id="menu_nm" value="<?=$menu['menu_nm']?>" placeholder="메뉴명을 입력하세요.">
                </li>
                <li>
                    <label for="price">가격(￦)</label>
                    <input type="number" name="price" id="price" value="<?=$menu['price']?>" placeholder="가격을 입력하세요.">
                </li>
                <li>
                    <label for="disp_ord">표시순서</label>
                    <input type="number" name="disp_ord" id="disp_ord" value="<?=$menu['disp_ord']?>" placeholder="표시순서를 입력하세요.">
                </li>
                <li>
                    <label for="keyword">추천단어</label>
                    <div class="input_wrap keyword_wrap">
                        <input type="text" id="keyword" name="keyword" class="keyword in_btn" placeholder="추천단어 입력 후 엔터">
                        <button type="button" class="btn btn_regist" onclick="return keyword_push();">등록</button>
                    </div>
                    <div class="keyword_list">
                        <?php
                        if ($m == 'u') {
                            $sql_rec = " select *
                                           from {$y1['review_recom_table']}
                                          where menu_no = '{$menu['menu_no']}'
                                            and store_yn = '1'
                                       order by reg_dttm ";
                            $res_rec = sql_query($sql_rec);
                            while($row_rec=sql_fetch_array($res_rec)) {
                            ?>
                            <p><?=$row_rec['review_word']?><input type="hidden" name="menu_recom[]" value="<?=$row_rec['review_word']?>"><span class="btn_delete" onclick="recom_del(this);">삭제</span></p>
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <p class="info_txt">※ 추천단어는 최대 3개까지 등록 가능합니다.</p>
                    <script>
                    function keyword_push() {
                        if ($("#keyword").val().length < 2) {
                            alert("2글자 이상 입력해주세요.");
                        } else if ($("#keyword").val().length > 10) {
                            alert("최대 15글자 까지 입력가능합니다.");
                        } else if ($(".keyword_list > p").length >= 5) {
                            alert("검색 키워드는 최대 5개까지 등록 가능합니다.");
                        } else {                            
                            $(".keyword_list").append('<p>' + $("#keyword").val() + '<input type="hidden" name="menu_recom[]" value="' + $("#keyword").val() + '"><span class="btn_delete" onclick="recom_del(this);">삭제</span></p>');
                            $("#keyword").val("");
                        }
                        return false;
                    }

                    
                    $("#keyword").keydown(function(key) {
                        if (key.keyCode == 13) {
                            if ($(this).val().length < 2) {
                                alert("2글자 이상 입력해주세요.");
                            } else if ($(this).val().length > 10) {
                                alert("최대 15글자 까지 입력가능합니다.");
                            } else if ($(".keyword_list > p").length >= 3) {
                                alert("추천단어는 최대 3개까지 등록 가능합니다.");
                            } else {                            
                                $(".keyword_list").append('<p>' + $(this).val() + '<input type="hidden" name="menu_recom[]" value="' + $(this).val() + '"><span class="btn_delete" onclick="recom_del(this);">삭제</span></p>');
                                $(this).val("");
                            }
                            return false;
                        }
                    });
                    </script>
                </li>
            </ul>
        </div>
    </section>
    <section class="_cont_">
        <p class="alert" style="display:none;">* 사진을 등록해주세요</p>
    </section>
    <div class="btn_wrap btn_bottom btn_col2">
        <?php if ($m == "u") { ?>
        <a href="<?php echo Y1_PAGE_URL ?>/manager_store_menu_delete?menu_no=<?=$menu['menu_no']?>" class="btn btn_delete">삭제</a>
        <?php } ?>
        <input type="submit" class="btn btn_submit" value="<?=$html_title?>">
    </div>
    
    </form>

    <script>
    function fwrite_submit(f) {
        <?php if ($m == "") { ?>
        // 첨부파일 확인
        if(f.capture_inp.value == "" && f.gallery_inp.value == "") {
            $(".alert").show();
            return false;
        }
        <?php } ?>
        
        if ($(".keyword_list > p").length < 1) {
            alert("추천단어를 1개이상 입력해주세요.");
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