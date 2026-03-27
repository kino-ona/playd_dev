<?php
include_once('./_common.php');

if (!count($_POST['chk'])) {
    alert("선택삭제 하실 항목을 하나 이상 체크하세요.");
}

check_admin_token();

for ($i=0; $i<count($_POST['chk']); $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];

    $in = get_incident($_POST['seq'][$k]);

    if (!$in['S_SEQ']) {
        alert("자료가 존재하지 않습니다.");
    } else {
        // 입사지원글 삭제
        incident_delete($in['S_SEQ']);
    }
}


    
    

if($type=='sang'){
    //로그 기록
    insert_mgr_log('삭제','채용공고 관리 > 상시채용', "");

    alert("삭제되었습니다.", "./incident_sang.php?".$qstr);
} else {

    //로그 기록
    insert_mgr_log('삭제','채용공고 관리 > 채용공고', "");

    alert("삭제되었습니다.", "./incident.php?".$qstr);
}

?>
