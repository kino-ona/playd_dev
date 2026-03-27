<?php
$board['BC_EDITOR'] = "smarteditor2";
include_once('./_common.php');
include_once(P1_EDITOR_LIB);

if ($m == "") {
    $html_title = "등록";
} else if ($m == "u") {
    $in = get_incident($seq);
    
    if (!$in['S_SEQ'])
        alert('존재하지 않는 정보입니다.');
    
    $html_title = "수정";
    
    $in['S_SEQ']     = get_text($in['S_SEQ']);                        # 게시판번호
    $in['S_FIELD']   = get_text($in['S_FIELD']);                      # 모집분야
    $in['S_OBJ']     = get_text($in['S_OBJ']);                        # 대상
    $in['S_JOB']     = get_text($in['S_JOB']);                        # 직군
    $in['S_ST_DATE'] = date("Y-m-d", strtotime($in['S_ST_DATE']));    # 모집기간 시작일자
    $in['S_ET_DATE'] = date("Y-m-d", strtotime($in['S_ET_DATE']));    # 모집기간 종료일자
    $in['S_EXPS_YN'] = get_text($in['S_EXPS_YN']);                    # 상단고정
    $in['S_NOTI_YN'] = get_text($in['S_NOTI_YN']);                    # 노출여부
} else {
    alert('제대로 된 값이 넘어오지 않았습니다.');
}

$content = get_text($in['S_CONT'], 0);

$editor_html = editor_html('s_cont', $content, true); # 내용
$editor_js = '';
$editor_js .= get_editor_js('s_cont', true);
$editor_js .= chk_editor_js('s_cont', true);

$p1['title']    = '입사지원관리(PLAYD)';
$p1['subtitle'] = '채용관리(PLAYD)';
include_once('./_head.php');

add_stylesheet(P1_DTPICKER_CSS, 0);     // DTPICKER css
add_javascript(P1_DTPICKER_JS, 0);      // DTPICKER js
?>
<script type="text/javascript">
$(function() {
    $('#to').change(function() {
        $('#from').appendDtpicker({
            //minDate: $('#to').val(), // when the end time changes, update the maxDate on the start field
            "autodateOnStart": false,	// 달력 초기값 안주기
            //maxDate: $('#to').val(),
            "closeOnSelected": true,
            "locale": "kr",
            "dateOnly": true,
        });
    });
    
    $('#from').change(function() {
        $('#to').appendDtpicker({
            //maxDate: $('#from').val(), // when the start time changes, update the minDate on the end field
            "autodateOnStart": false,
            //minDate: $('#from').val(),
            "closeOnSelected": true,
            "locale": "kr",
            "dateOnly": true,
        });
    });

    // trigger change event so datapickers get attached
    $('#from').trigger('change');
    $('#to').trigger('change');
});
</script>

<form name="frm" method="post" action="<?php echo P1_PAGE_URL ?>/incident_insert.php" onsubmit="return send();" enctype="multipart/form-data">
<input type="hidden" name="m" value="<?php echo $m ?>">
<input type="hidden" name="s_type" value="incident_sang">
<?php if ($in['S_SEQ']) { ?>
<input type="hidden" name="seq" value="<?=$in['S_SEQ']?>">
<?php } ?>
    <fieldset>
    <legend class="hid1">입사지원관리 및 수정</legend>
        <table class="colTable">
            <tr>
                <th>제목 </th>
                <td>
                    <label for="s_field"><input type="text" id="s_field" name="s_field" value="<?=$in['S_FIELD']?>" maxlength="100" style="width:600px" />
                </td>
            </tr>
           
            <tr>
                <th>게시기간</th>
                <td>
                    <input type="text" id="from" name="s_st_date" readonly="readonly" value="<?=$in['S_ST_DATE']?>">
                    <label for="from"><img src="<?php echo P1_IMAGES_URL ?>/manager/common/calendar.gif" alt="" /></label>
                    ~
                    <input type="text" id="to" name="s_et_date" readonly="readonly" value="<?=$in['S_ET_DATE']?>">
                    <label for="to"><img src="<?php echo P1_IMAGES_URL ?>/manager/common/calendar.gif" alt="" /></label>
                </td>
            </tr>
            <tr>
                <th>공고URL</th>
                <td colspan="3">
                    <input type="text" id="s_ext1" name="s_ext1" value="<?=$in['S_EXT1']?>" style="width:100%;" placeholder="URL를 넣어주세요">
                </td>
            </tr>

            <tr>
                <th>내용</th>
                <td>
                    <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                </td>
            </tr>
          
            <tr>
                <th>노출여부</th>
                <td>
                    <?php
                    echo radio_selected("Y", $in['S_NOTI_YN'], "노출", "s_noti_yn");
                    echo radio_selected("N", $in['S_NOTI_YN'], "비노출", "s_noti_yn");
                    ?>
                </td>
            </tr>
        </table>
        <!-- both button -->
        <div class="bothButton">
            <div class="fl">
                <button class="btn btn-inverse" type="button" onclick="fnGoView('<?php echo P1_PAGE_URL ?>/incident_sang.php');">목록</button>
            </div>
            <div class="fr">
                <?php if ($m == "u") { ?>
                <input type="submit" class="btn btn-primary" value="수정">
                <button class="btn btn-inverse" type="button" onclick="deletes('<?php echo P1_PAGE_URL ?>/incident_del.php?type=sang');">삭제</button>
                <?php } ?>
                <?php if ($m == "") { ?>
                <input type="submit" class="btn btn-inverse" value="등록">
                <?php } ?>
            </div>
        </div>
    </fieldset>
</form>
<!-- 목록으로 돌아가기 -->
<form name="golist" action="" method="post">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <input type="hidden" name="obj" value="<?=$obj?>">
    <input type="hidden" name="job" value="<?=$job?>">
</form>

<script type="text/javascript">
//등록 & 수정
function send() {
    var diff = diffDays();	// 시작 날짜, 끝 날짜 비교
    if (confirm("<?=$html_title?> 하시겠습니까?")) {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>
        
        if($("#s_field").val() == null || $("#s_field").val() == ""){
            alert("제목을 입력해야 합니다.");
            return false;
        }
      
        if($("#s_ext1").val() == null || $("#s_ext1").val() == ""){
            alert("공고URL를 입력해 주세요.");
            return false;
        }
        if($("#from").val() == null || $("#from").val() == "" || $("#to").val() == null || $("#to").val() == ""){
            alert("게시기간을 올바르게 입력하세요.");
            return false;
        }
        if(diff < 0) {
            alert("게시기간을 올바르게 입력하세요.");
            return false;
        }
        
        
        return;
    }
    
    return false;
}
</script>
<?php
include_once('./_tail.php');
?>