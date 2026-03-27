<?php
$board['BC_EDITOR'] = "smarteditor2";
include_once('./_common.php');
include_once(P1_EDITOR_LIB);

//권한체크
$auth = get_check_admin_auth($member); //alert('권한이 없습니다.', P1_URL);
if($auth['read'] != 'Y') {
    alert('권한이 없습니다.', '/adm/page/login.php');
    exit;
}

if ($m == "") {
    $html_title = "등록";
} else if ($m == "u") {
    $in = get_m_incident($seq);
    
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

$p1['title']    = '입사지원관리(MABLE)';
$p1['subtitle'] = '채용관리(MABLE)';
include_once('./_head.php');

add_stylesheet(P1_DTPICKER_CSS, 0);     // DTPICKER css
add_javascript(P1_DTPICKER_JS, 0);      // DTPICKER js
add_javascript(P1_SMART_EDIT_JS, 0);    // SMART_EDIT js
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

<form name="frm" method="post" action="<?php echo P1_PAGE_URL ?>/m_incident_insert.php" onsubmit="return send();" enctype="multipart/form-data">
<input type="hidden" name="m" value="<?php echo $m ?>">
<?php if ($in['S_SEQ']) { ?>
<input type="hidden" name="seq" value="<?=$in['S_SEQ']?>">
<?php } ?>
    <fieldset>
    <legend class="hid1">입사지원관리 및 수정</legend>
        <table class="colTable">
            <tr>
                <th>모집분야</th>
                <td>
                    <label for="s_field"><input type="text" id="s_field" name="s_field" value="<?=$in['S_FIELD']?>" maxlength="100" style="width:600px" />
                </td>
            </tr>
            <tr>
                <th>대상</th>
                <td>
                    <?php
                    echo radio_selected("무관", $in['S_OBJ'], "무관", "s_obj");
                    echo radio_selected("신입", $in['S_OBJ'], "신입", "s_obj");
                    echo radio_selected("경력", $in['S_OBJ'], "경력", "s_obj");
                    ?>
                </td>
            </tr>
            <tr>
                <th>직군</th>
                <td>
                    <select name="s_job" id="s_job">
                        <option value="">직군선택</option>
                        <?php
                        echo option_selected("전체", $in['S_JOB'], "전체");
                        echo option_selected("시스템운영", $in['S_JOB'], "시스템운영");
                        echo option_selected("마케팅,컨설팅", $in['S_JOB'], "마케팅,컨설팅");
                        echo option_selected("개발", $in['S_JOB'], "개발");
                        echo option_selected("경영지원", $in['S_JOB'], "경영지원");
                        echo option_selected("고객상담", $in['S_JOB'], "고객상담");
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>내용</th>
                <td>
                    <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                </td>
            </tr>
            <tr>
                <th>모집기간</th>
                <td>
                    <input type="text" id="from" name="s_st_date" readonly="readonly" value="<?=$in['S_ST_DATE']?>">
                    <label for="from"><img src="<?php echo P1_IMAGES_URL ?>/manager/common/calendar.gif" alt="" /></label>
                    ~
                    <input type="text" id="to" name="s_et_date" readonly="readonly" value="<?=$in['S_ET_DATE']?>">
                    <label for="to"><img src="<?php echo P1_IMAGES_URL ?>/manager/common/calendar.gif" alt="" /></label>
                </td>
            </tr>
            <tr>
                <th>상단고정</th>
                <td>
                    <?php
                    echo radio_selected("Y", $in['S_EXPS_YN'], "고정", "s_exps_yn");
                    echo radio_selected("N", $in['S_EXPS_YN'], "비고정", "s_exps_yn");
                    ?>
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
                <button class="btn btn-inverse" type="button" onclick="fnGoView('<?php echo P1_PAGE_URL ?>/m_incident.php');">목록</button>
            </div>
            <div class="fr">
                <?php if ($m == "u") { ?>
                <?if($auth['write'] == 'Y') {?>
                <input type="submit" class="btn btn-primary" value="수정">
                <?}?>
                <?if($auth['del'] == 'Y') {?>
                <button class="btn btn-inverse" type="button" onclick="deletes('<?php echo P1_PAGE_URL ?>/m_incident_del.php');">삭제</button>
                <?}?>
                <?php } ?>
                <?php if ($m == "") { ?>
                    <?if($auth['write'] == 'Y') {?>
                <input type="submit" class="btn btn-inverse" value="등록">
                <?}?>
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
            alert("모집분야를 입력해야 합니다.");
            return false;
        }
        if(f.obj.length > 0) {
            if($(":radio[name='s_obj']:checked").length < 1) {
                alert("대상을 선택해야 합니다.");
                return false;
            }
        }
        if($("#s_job").val() == null || $("#s_job").val() == ""){
            alert("직군을 선택해야 합니다.");
            return false;
        }

        if($("#from").val() == null || $("#from").val() == "" || $("#to").val() == null || $("#to").val() == ""){
            alert("모집기간을 올바르게 입력하세요.");
            return false;
        }
        if(diff < 0) {
            alert("모집기간을 올바르게 입력하세요.");
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