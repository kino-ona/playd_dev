<?php
if (!defined('_YOLU_')) exit; // 개별 페이지 접근 불가
?>
<article class="write">
    <section class="_top_">
        <a href="<?php echo Y1_PAGE_URL ?>/post?bo_no=<?=$bo_no?>&post_no=<?=$post_no?>" class="close">닫기</a>
        <p class="title"><?=$board['board_nm']?></p>
    </section>
    
    <form name="fwrite" id="fwrite" action="./post_regist_update" onsubmit="return fpost_submit(this);" method="post" enctype="multipart/form-data">
    <input type="hidden" name="m" id="m" value="<?=$m?>">
    <input type="hidden" name="bo_no" id="bo_no" value="<?=$bo_no?>">
    <input type="hidden" name="post_no" id="post_no" value="<?=$post_no?>">
    <input type="hidden" name="sst" id="sst" value="<?=$sst?>">
    
    <section class="_photo_">
        <button type="button" class="btn_photo<?=($write['img_url']) ? " edit" : " regist"?>">
            <span>사진<?=($write['img_url']) ? "수정" : "등록"?></span>
            <div class="img"><img src="<?=$write['img_url']?>" id="now_img"></div>
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
    <section class="_cont_">
        <input type="text" name="subj" id="subj" value="<?=$write['subj']?>" placeholder="제목을 입력해주세요." required/>
        <textarea name="post_txt" id="post_txt" placeholder="게시글을 작성해주세요. (10자 이상)" required><?=$content?></textarea>
        <p class="txt_limit"><span id="txt_count">0</span>/2000</p>
    </section>
    <div class="btn_wrap btn_bottom">
        <input type="submit" class="btn btn_submit" value="<?=$html_title?>">
    </div>
    
    </form>

    <script>
    // var char_min = 10;
    // var char_max = 2000;
    check_byte("post_txt", "txt_count");

    $(function() {
        $("#post_txt").on("keyup", function() {
            check_byte("post_txt", "txt_count");
        });
    });
    
    function fpost_submit(f) {
        // 글내용 확인
        if(f.post_txt.value == "") {
            alert("글 내용을 작성해주세요.");
            f.post_txt.focus();
            return false;
        }
        
        // 글내용 글자수 체크
        // var cnt = parseInt(check_byte("post_txt", "txt_count"));
        // if (char_min > 0 && char_min > cnt) {
            // alert("글 내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
            // f.post_txt.focus();
            // return false;
        // } else if (char_max > 0 && char_max < cnt) {
            // alert("글 내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
            // f.post_txt.focus();
            // return false;
        // }
        
        return true;
    }
    
    var fileTarget = $("input[name='img_file[]']");
    fileTarget.on('change', handleImgFile);
    </script>
</article>