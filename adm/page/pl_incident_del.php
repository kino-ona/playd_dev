<?php
include_once('./_common.php');

$in = get_pl_incident($_POST['seq']);

if (!$in['S_SEQ']) {
    $msg .= '정보가 존재하지 않습니다.';
} else {
    pl_incident_delete($in['S_SEQ']);
    
    alert("삭제되었습니다.", "./pl_incident.php?".$qstr);
}

if ($msg)
    alert($msg);
?>