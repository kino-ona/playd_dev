<?php
if (!defined('_YOLU_')) exit; // 개별 페이지 접근 불가

add_stylesheet(Y1_VIDEO_CSS, 0);    // video css
add_javascript(Y1_VIDEO_JS, 0);     // video js
?>
<div class="tab_cont">
    <form name="fwrite" id="fwrite" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data">
    <input type="hidden" name="m" id="m" value="">
    <input type="hidden" name="store_no" id="store_no" value="<?=$store['store_no']?>">

    <button type="button" class="btn btn_apply">짤 영상 신청</button>
    <div class="photo_pop">
        <div class="inner">
            <div class="top">
                <button type="button" class="close">닫기</button>
                <p class="title">짤 영상 신청</p>
            </div>
            <div class="cont">
                <button type="button" class="btn_photo regist">
                    <span>짤 등록</span>
                    <video id="main-video">
                        <source type="video/mp4">
                    </video>
                </button>
                <div class="photo_option">
                    <label for="capture_inp" class="button">동영상찍기</label>
                    <input type="file" class="upload_hidden" name="video_file[]" id="capture_inp" accept="video/*" capture="camcorder">
                    <label for="gallery_inp" class="button">동영상선택</label>
                    <input type="file" class="upload_hidden" name="video_file[]" id="gallery_inp" accept="file_extension|audio/*|video/*|media_type">
                    <button type="button" class="button cancel">취소</button>
                </div>
                <div class="black_wall"></div>
                <div class="datepicker_wrap">
                    <div class="datepicker">
                        <input type="text" name="start_dt" id="start_dt" required>
                        <button type="button" class="btn_date" id="start_date"></button>
                    </div>
                    <span> ~ </span>
                    <div class="datepicker">
                        <input type="text" name="end_dt" id="end_dt" required>
                        <button type="button" class="btn_date" id="end_date"></button>
                    </div>
                    <span id="status"></span>
                </div>
                <input type="submit" class="btn btn_apply btn_confirm" value="신청">
            </div>
        </div>
    </div>
    
    </form>
    
    <script>
    var _CANVAS = document.querySelector("#video-canvas"),
        _VIDEO = document.querySelector("#main-video");

    var fileTarget = $("input[name='video_file[]']");
    fileTarget.on('change', function(e) {
        // Object Url as the video source
        $("#main-video source").attr("src", URL.createObjectURL(e.target.files[0]));
        
        // Load the video and show it
        _VIDEO.style.width = '100%';
        _VIDEO.style.display = 'inline';
        _VIDEO.load();
        
        handleImgFile(e, 1);
    });
    
    fileTarget.on('click', function() {
        $(this).val('');
    });
    
    // 운행기간 달력
    $("#start_dt").datepicker();
    $("#end_dt").datepicker({align:'right'});

    $('#start_date').click(function() {
        $('#start_dt').datepicker('show');
        return false;
    });

    $('#end_date').click(function() {
        $('#end_dt').datepicker('show');
        return false;
    });
    
    function fwrite_submit(f) {
        var file_chk = false;
        $("input[name='video_file[]']").each(function(index, item){
            if($(this)[0].files.length == 1) file_chk = true;
        });
        
        if(!file_chk) {
            alert("짤 동영상을 등록해주세요.");
            return false;
        }
        
        if(!($("#start_dt").val()) || !($("#end_dt").val())) {
            alert("유효기간을 선택해주세요.");
            return false;
        }
        
        // 날짜 역순 체크
        if ($("#start_dt").val() >= $("#end_dt").val()) {
            alert("유효기간이 역순입니다.");
            return false;
        }
        
        var formData = new FormData(document.getElementById('fwrite'));
        
        $.ajax({
            type: "post",
            url: y1_page_url + "/ajax.manager_store_intro_zzal_video_update.php",
            data: formData,
            cache: false,
            processData: false,  // file전송시 필수
            contentType: false,  // file전송시 필수
            success: function(html, textStatus, jqXHR) {
                alert(html);
                location.href = './manager_store?tab=intro&tab2=zzal_img';
            },
            xhr: function() {
                // get the native XmlHttpRequest object
                var xhr = $.ajaxSettings.xhr();
                
                // set the onprogress event handler
                xhr.upload.onprogress = function(evt) { 
                    var perc = Math.round(evt.loaded / evt.total * 100);
                    $("#status").text(perc + "% 업로드 중 입니다.");
                    
                };
                
                // set the onload event handler
                xhr.upload.onload = function() {
                    $("#status").text("업로드가 완료되었습니다.");
                };
                
                // return the customized object
                return xhr;
            } ,
            error: function(jqXHR, textStatus, errorThrown) {
                $("#status").text("업로드가 실패하였습니다.");
            }
        });
        
        return false;
    }
    </script>
</div>
<div class="tab_cont">
    <p class="txt">게시중인 짤</p>
    <div class="photo_box">
        <div class="img">
            <?=$zz_on_img_url?>
        </div>
        <div class="use_status">
            <p><span>사용기간</span><?=date("Y-m-d", strtotime($row_zz_on['start_dt']))?> - <?=date("Y-m-d", strtotime($row_zz_on['end_dt']))?></p>
            <!--<button class="btn_use_onoff">미사용</button>-->
        </div>
    </div>
</div>
<div class="tab_cont">
    <p class="txt">신청한 짤</p>
    <?php
    while($row_zz=sql_fetch_array($res_zz)) {
        $sql_co = " select cd_nm
                      from {$y1['code_master_table']}
                     where cd_cls = '220'
                       and cd = '{$row_zz['appl_state']}' ";
        $row_co = sql_fetch($sql_co);
    ?>
    <div class="photo_box">
        <?php
        if ($row_zz['zzal_url_orgin']) {
        ?>
        <div class="img">
            <a href="<?php echo Y1_URL.$row_zz['zzal_url_orgin'] ?>">동영상 보기</a>
        </div>
        <?php
        }
        ?>
        <div class="use_status in_btn">
            <p><span>사용기간</span><?=date("Y-m-d", strtotime($row_zz['start_dt']))?> ~ <?=date("Y-m-d", strtotime($row_zz['end_dt']))?></p>
            <p><span>신청상태</span><span class="txt_blue"><?=$row_co['cd_nm']?></span></p>
            <?php if($row_zz['appl_state'] == "1") { ?>
            <form name="fdel" id="fdel" action="./manager_store_intro_zzal_video_update" onsubmit="return fdel_submit(this);" method="post" enctype="multipart/form-data">
            <input type="hidden" name="m" id="m" value="u">
            <input type="hidden" name="appl_no" id="appl_no" value="<?=$row_zz['appl_no']?>">
            <input type="hidden" name="store_no" id="store_no" value="<?=$store['store_no']?>">
            <input type="submit" class="btn_cancel" value="신청취소">
            </form>
            
            <script>
            function fdel_submit(f) {
                if(!confirm("신청취소 하시겠습니까?")) {
                    return false;
                }
                
                return true;
            }
            </script>
            <?php } ?>
        </div>
    </div>
    <?php
    }
    ?>
</div>