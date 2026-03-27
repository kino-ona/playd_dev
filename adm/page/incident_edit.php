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
<input type="hidden" name="s_type" value="incident">
<?php if ($in['S_SEQ']) { ?>
<input type="hidden" name="seq" value="<?=$in['S_SEQ']?>">
<?php } ?>
    <fieldset>
    <legend class="hid1">입사지원관리 및 수정</legend>
        <table class="colTable">
            <tr>
                <th>제목 </th>
                <td colspan="3">
                    <label for="s_field"><input type="text" id="s_field" name="s_field" value="<?=$in['S_FIELD']?>" maxlength="100" style="width:600px" />
                </td>
            </tr>
            <tr>
                <th>모집기간</th>
                <td colspan="3">
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
                <th>직군</th>
                <td colspan="3">
                    <input type="text" id="s_job" name="s_job" value="<?=$in['S_JOB']?>" style="width:100%;" placeholder="기획,개발,마케팅 등 직군 입력">
                </td>
            </tr>

            <tr>
                <th>직무</th>
                <td colspan="3">
                    <input type="text" id="s_ext3" name="s_ext3" value="<?=$in['S_EXT3']?>" style="width:100%;" placeholder="온라인마케팅,AE,퍼포먼스마케팅 등 직무 입력">
                </td>
            </tr>
            
            <tr>
                <th>경력사항</th>
                <td colspan="3">
                    <?php
                    echo radio_selected("무관", $in['S_OBJ'], "무관", "s_obj");
                    echo radio_selected("신입", $in['S_OBJ'], "신입", "s_obj");
                    echo radio_selected("경력", $in['S_OBJ'], "경력", "s_obj");
                    ?>
                </td>
            </tr>
           
            <tr>
                <th>고용형태</th>
                <td colspan="3">
                    <?php
                    echo radio_selected("정규직", $in['S_EXT2'], "정규직", "s_ext2");
                    echo radio_selected("계약직", $in['S_EXT2'], "계약직", "s_ext2");
                    ?>
                </td>
            </tr>
           
            <!-- <tr>
                <th>상단고정</th>
                <td>
                    <?php
                    echo radio_selected("Y", $in['S_EXPS_YN'], "고정", "s_exps_yn");
                    echo radio_selected("N", $in['S_EXPS_YN'], "비고정", "s_exps_yn");
                    ?>
                </td>
            </tr> -->
            <tr>
                <th>노출여부</th>
                <td colspan="3">
                    <?php
                    echo radio_selected("Y", $in['S_NOTI_YN'], "노출", "s_noti_yn");
                    echo radio_selected("N", $in['S_NOTI_YN'], "비노출", "s_noti_yn");
                    ?>
                </td>
            </tr>

            <?if($in['S_SEQ']){?>
                <tr>
                <th>최초 등록일</th>
                <td >
                    <?=$in['S_DATE']?>
                </td>
                <th>최초 등록자</th>
                <td >
                <?=$in['S_WRITER']?>
                </td>
            </tr>
            <tr>
                <th>최종 수정일</th>
                <td >
                <?=$in['S_UDATE']?>
                </td>
                <th>최종 수정자</th>
                <td >
                <?=$in['S_UWRITER']?>
                </td>
            </tr>

            <?}?>

        </table>
        <!-- both button -->
        <div class="bothButton">
            <div class="fl">
                <button class="btn btn-inverse" type="button" onclick="fnGoView('<?php echo P1_PAGE_URL ?>/incident.php');">목록</button>
            </div>
            <div class="fr">
                <?php if ($m == "u") { ?>
                <input type="submit" class="btn btn-primary" value="수정">
                <button class="btn btn-inverse" type="button" onclick="deletes('<?php echo P1_PAGE_URL ?>/incident_del.php');">삭제</button>
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
      

        if($("#s_field").val() == null || $("#s_field").val() == ""){
            alert("모집분야를 입력해야 합니다.");
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

        if($("#s_ext1").val() == null || $("#s_ext1").val() == ""){
            alert("공고URL를 입력해 주세요.");
            return false;
        }

        if($("#s_job").val() == null || $("#s_job").val() == ""){
            alert("직군을 입력해 주세요.");
            return false;
        }

        if($(":radio[name='s_obj']:checked").length < 1) {
                alert("경력사항 선택해야 합니다.");
                return false;
            }

      
            if($(":radio[name='s_ext2']:checked").length < 1) {
                alert("고용형태를 선택해야 합니다.");
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