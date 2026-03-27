<?php
if (!defined('_YOLU_')) exit; // 개별 페이지 접근 불가

// 가맹점 정보
$sql = " select *
           from {$y1['store_info_table']}
          where user_no = '{$member['user_no']}'
            and service_state = '3' ";
$store = sql_fetch($sql);

$store['store_no']        = get_text($store['store_no']);           # 번호
$store['store_nm']        = get_text($store['store_nm']);           # 사업자명
$store['store_disp_nm']   = get_text($store['store_disp_nm']);      # 매장명
$store['ceo_nm']          = get_text($store['ceo_nm']);             # 대표자성명
$store['tel']             = get_text($store['tel']);                # 대표전화
$store['store_cate']      = get_text($store['store_cate']);         # 요식업분류[900]
$store['parking']         = get_text($store['parking']);            # 주차가능여부
$store['zipcode']         = get_text($store['zipcode']);            # 우편번호
$store['addr1']           = get_text($store['addr1']);              # 주소
$store['addr2']           = get_text($store['addr2']);              # 상세주소
$store['license_img_url'] = get_text($store['license_img_url']);    # 사업자등록증 첨부파일
$store['link_url']        = get_text($store['link_url']);           # 홈페이지 주소
$store['store_intro']     = get_text($store['store_intro']);        # 가맹점 소개

add_stylesheet(Y1_CLOCKPICKER_CSS, 0);    // clockpicker css
add_javascript(Y1_CLOCKPICKER_JS, 0);     // clockpicker js
add_javascript(Y1_POSTCODE_JS, 0);        // 다음 주소 js
?>
<form name="fwrite" id="fwrite" action="./manager_store_info_update" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="store_no" id="store_no" value="<?=$store['store_no']?>">

<section class="manager">
    <div class="input_box mg0">
        <ul>
            <li>
                <label for="store_nm">사업자명</label>
                <input type="text" name="store_nm" id="store_nm" value="<?=$store['store_nm']?>" placeholder="사업자등록 기준 사업자명" required>
            </li>
            <li>
                <label for="store_id">사업자등록번호</label>
                <input type="text" name="store_id" id="store_id" value="<?=$store['store_id']?>" placeholder="사업자등록 기준 사업자등록번호" required>
            </li>
            <li>
                <label for="store_disp_nm">매장명</label>	
                <input type="text" name="store_disp_nm" id="store_disp_nm" value="<?=$store['store_disp_nm']?>" placeholder="앱에 표시되는 매장명" required>
            </li>
            <li>
                <label for="ceo_nm">대표자 성명</label>
                <input type="text" name="ceo_nm" id="ceo_nm" value="<?=$store['ceo_nm']?>" required>
            </li>
            <li>
                <label for="tel">가맹점 대표전화</label>
                <input type="text" name="tel" id="tel" value="<?=$store['tel']?>" required>
            </li>
            <li>
                <label for="link_url">가맹점 홈페이지</label>
                <input type="text" name="link_url" id="link_url" value="<?=$store['link_url']?>" required>
            </li>
            <li>
                <label for="store_intro">가맹점 소개</label>
                <textarea name="store_intro" id="store_intro" required><?php echo $store['store_intro'] ?></textarea>
            </li>
            <li>
                <label for="category">요식업 분류</label>
                <?=get_store_cate($store['store_cate'], "1", "store_cate")?>
            </li>
            <li>
                <label for="parking">주차가능여부</label>
                <select name="parking" id="parking" required>
                    <option value="1" <?php echo get_selected($store['parking'], "1"); ?>>주차가능</option>
                    <option value="0" <?php echo get_selected($store['parking'], "0"); ?>>상관없음</option>
                </select>
            </li>
			<li class="_regist_">
				<label for="now_img">사업자등록증</label>
				<div class="input_wrap">
					<input type="text" id="now_img" name="now_img" class="regi_card in_btn" value="<?=$store['license_img_url']?>" placeholder="사업자등록증을 등록하세요." />
					<button type="button" class="btn find_regi_card">업로드</button>
				</div>
                <div class="photo_option">
                    <label for="capture_inp" class="button">사진찍기</label>
                    <input type="file" class="upload_hidden" name="img_file[]" id="capture_inp" accept="image/*" capture="camera">
                    <label for="gallery_inp" class="button">사진선택</label>
                    <input type="file" class="upload_hidden" name="img_file[]" id="gallery_inp" accept="file_extension|audio/*|video/*|image/*|media_type">
                    <button type="button" class="button cancel">취소</button>
                </div>
				<div class="black_wall"></div>
			</li>
            <li>
                <label for="postcode">가맹점 주소</label>
                <div class="input_wrap">
                    <input type="text" class="postcode in_btn" name="zipcode" id="zipcode" value="<?=$store['zipcode']?>" placeholder="우편번호" />
                    <button type="button" class="btn find_address" onclick="execDaumPostcode()">찾기</button>
                    
                    <div id="wrap" style="display:none;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
                        <img src="//t1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
                    </div>
                    
                    <input type="text" class="address" name="addr1" id="addr1" value="<?=$store['addr1']?>" placeholder="주소" />
                    <input type="text" class="address" name="addr2" id="addr2" value="<?=$store['addr2']?>" placeholder="상세주소" />
                    
                    <script>
                        // 우편번호 찾기 찾기 화면을 넣을 element
                        var element_wrap = document.getElementById('wrap');

                        function foldDaumPostcode() {
                            // iframe을 넣은 element를 안보이게 한다.
                            element_wrap.style.display = 'none';
                        }

                        function execDaumPostcode() {
                            // 현재 scroll 위치를 저장해놓는다.
                            var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
                            new daum.Postcode({
                                oncomplete: function(data) {
                                    // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                                    // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                                    // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                                    var fullAddr = data.address; // 최종 주소 변수
                                    var extraAddr = ''; // 조합형 주소 변수

                                    // 기본 주소가 도로명 타입일때 조합한다.
                                    if(data.addressType === 'R'){
                                        // 법정동명이 있을 경우 추가한다.
                                        if(data.bname !== ''){
                                            extraAddr += data.bname;
                                        }
                                        // 건물명이 있을 경우 추가한다.
                                        if(data.buildingName !== ''){
                                            extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                                        }
                                        // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                                        fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                                    }

                                    // 우편번호와 주소 정보를 해당 필드에 넣는다.
                                    document.getElementById('zipcode').value = data.zonecode; // 5자리 새우편번호 사용
                                    document.getElementById('addr1').value = fullAddr;

                                    // iframe을 넣은 element를 안보이게 한다.
                                    // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                                    element_wrap.style.display = 'none';

                                    // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                                    document.body.scrollTop = currentScroll;
                                },
                                // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
                                onresize : function(size) {
                                    element_wrap.style.height = size.height+'px';
                                },
                                width : '100%',
                                height : '100%'
                            }).embed(element_wrap);

                            // iframe을 넣은 element를 보이게 한다.
                            element_wrap.style.display = 'block';
                        }
                    </script>
                </div>
            </li>
            <li>
                <label for="keyword">검색 키워드</label>
                <div class="input_wrap keyword_wrap">
                    <input type="text" id="keyword" name="keyword" class="keyword in_btn" maxlength="15" placeholder="키워드 입력 후 엔터">
                    <button type="button" class="btn btn_regist" onclick="return keyword_push();">등록</button>
                </div>
                <div class="keyword_list">
                    <?php
                    $sql_rec = " select *
                                   from {$y1['store_recom_table']}
                                  where store_no = '{$store['store_no']}' ";
                    $res_rec = sql_query($sql_rec);
                    while($row_rec=sql_fetch_array($res_rec)) {
                    ?>
                    <p><?=$row_rec['rec_word']?><input type="hidden" name="store_recom[]" value="<?=$row_rec['rec_word']?>"><span class="btn_delete" onclick="recom_del(this);">삭제</span></p>
                    <?php
                    }
                    ?>
                </div>
                <p class="info_txt">※ 검색 키워드는 최대 5개까지 등록 가능합니다.</p>
                <script>
                function keyword_push() {
                    if ($("#keyword").val().length < 2) {
                        alert("2글자 이상 입력해주세요.");
                    } else if ($("#keyword").val().length > 10) {
                        alert("최대 15글자 까지 입력가능합니다.");
                    } else if ($(".keyword_list > p").length >= 5) {
                        alert("검색 키워드는 최대 5개까지 등록 가능합니다.");
                    } else {                            
                        $(".keyword_list").append('<p>' + $("#keyword").val() + '<input type="hidden" name="store_recom[]" value="' + $("#keyword").val() + '"><span class="btn_delete" onclick="recom_del(this);">삭제</span></p>');
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
                        } else if ($(".keyword_list > p").length >= 5) {
                            alert("검색 키워드는 최대 5개까지 등록 가능합니다.");
                        } else {                            
                            $(".keyword_list").append('<p>' + $(this).val() + '<input type="hidden" name="store_recom[]" value="' + $(this).val() + '"><span class="btn_delete" onclick="recom_del(this);">삭제</span></p>');
                            $(this).val("");
                        }
                        return false;
                    }
                });
                </script>
            </li>
            <li>
                <span class="label">요일별 영업시간</span>
                <ul>
                    <?php
                    $sql_week = " select *
                                    from {$y1['store_weekly_time_table']}
                                   where store_no = '{$store['store_no']}'
                                order by day_code ";
                    $res_week = sql_query($sql_week);
                    while($row_week=sql_fetch_array($res_week)) {
                        $sql_co = " select cd_nm
                                      from {$y1['code_master_table']}
                                     where cd_cls = '900'
                                       and cd = '{$row_week['day_code']}' ";
                        $row_co = sql_fetch($sql_co);
                        
                        $day_off_cls = ($row_week['day_off'] == "1") ? " on" : "";
                    ?>
                    <li>
                        <div class="timepicker_wrap">
                            <span class="day"><?=$row_co['cd_nm']?></span>
                            <div class="timepicker">
                                <div class="input-group clockpicker" id="week_open<?=$row_week['day_code']?>" data-placement="left" data-align="top" data-autoclose="true">
                                    <button type="button" class="btn_time"></button>
                                    <input type="text" class="form-control" name="open_time<?=$row_week['day_code']?>" id="open_time<?=$row_week['day_code']?>" value="<?=$row_week['open_time']?>" readonly>
                                </div>
                                <script>
                                $('#week_open<?=$row_week['day_code']?>').clockpicker({
                                    align: 'center',
                                    placement: 'bottom',
                                    vibrate: true
                                });
                                </script>
                            </div>
                            <span>~</span>
                            <div class="timepicker">
                                <div class="input-group clockpicker" id="week_end<?=$row_week['day_code']?>" data-placement="left" data-align="top" data-autoclose="true">
                                    <button type="button" class="btn_time"></button>
                                    <input type="text" class="form-control" name="end_time<?=$row_week['day_code']?>" id="end_time<?=$row_week['day_code']?>" value="<?=$row_week['end_time']?>" readonly>
                                </div>
                                <script>
                                $('#week_end<?=$row_week['day_code']?>').clockpicker({
                                    align: 'center',
                                    placement: 'bottom',
                                    vibrate: true
                                });
                                </script>
                            </div>
                        </div>
                        <div class="onoff_wrap">
                            <span>휴무</span>
                            <input type="hidden" name="day_off<?=$row_week['day_code']?>" id="day_off<?=$row_week['day_code']?>" value="<?=$row_week['day_off']?>">
                            <button type="button" class="btn_onoff<?=$day_off_cls?>" onclick="store_day_off(this, '<?=$row_week['day_code']?>');"><?=($row_week['day_off'] == "1") ? "ON" : "OFF"?></button>
                        </div>
                    </li>
                    <?php
                    }
                    ?>
                </ul>
            </li>
        </ul>
    </div>
    <div class="btn_wrap">
        <button type="submit" class="btn btn_save">저장</button>
    </div>
</section>

</form>

<script>
function fwrite_submit(f) {
    if ($(".keyword_list > p").length < 1) {
        alert("검색 키워드를 1개이상 입력해주세요.");
        return false;
    }
    
    return true;
}

var fileTarget = $("input[name='img_file[]']");
fileTarget.on('change', function(e) {
    handleImgFile(e, 1);
    $("#now_img").val($(this).val());
});
</script>