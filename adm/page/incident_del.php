<?php
include_once('./_common.php');

$in = get_incident($_POST['seq']);

if (!$in['S_SEQ']) {
    $msg .= '정보가 존재하지 않습니다.';
} else {
    incident_delete($in['S_SEQ']);
    
    if($type=='sang'){

        insert_mgr_log('삭제','채용공고 관리 > 상시채용', "");

        alert("삭제되었습니다.", "./incident_sang.php?".$qstr);
    } else {

        insert_mgr_log('삭제','채용공고 관리 > 채용공고', "");

        alert("삭제되었습니다.", "./incident.php?".$qstr);
    }
    
}

if ($msg)
    alert($msg);
?>