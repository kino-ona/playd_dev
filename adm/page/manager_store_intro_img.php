<?php
if (!defined('_YOLU_')) exit; // 개별 페이지 접근 불가
?>
<div class="tab_cont">
    <button type="button" class="btn_photo regist">
        <span>사진등록</span>
        <div class="img">
            <img src="<?=$store['pr_img_url']?>" id="now_img">
        </div>
    </button>
    <div class="photo_option">
        <label for="capture_inp" class="button">사진찍기</label>
        <input type="file" class="upload_hidden" name="img_file[]" id="capture_inp" accept="image/*" capture="camera">
        <label for="gallery_inp" class="button">사진선택</label>
        <input type="file" class="upload_hidden" name="img_file[]" id="gallery_inp" accept="file_extension|audio/*|video/*|image/*|media_type">
        <button type="button" class="button cancel">취소</button>
    </div>
    <div class="black_wall"></div>
    <p class="regist_info">매장소개콘텐츠는 이미지를 올리시면 바로 YOLU 메인화면에 반영됩니다.</p>
</div>

<script>
var fileTarget = $("input[name='img_file[]']");
fileTarget.on('change', function(e) {
    console.log('change:' + e.target);
    handleImgFile(e, 1);
    PRimgUpload(e.target.files[0], '<?=$store['store_no']?>');
});

fileTarget.on('click', function() {
    $(this).val('');
});
</script>