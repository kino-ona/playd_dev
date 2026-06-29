<?php
include_once('./_common.php');

$re = get_report($_POST['seq']);

if (!$re['A_SEQ']) {
    $msg .= '정보가 존재하지 않습니다.';
} else {
    report_delete($re['A_SEQ']);

    //로그 기록
    insert_mgr_log('삭제','문의 관리 > 윤리경영제보', "");
    
    alert("삭제되었습니다.", "./report.php?".$qstr);
}

if ($msg)
    alert($msg);
?>