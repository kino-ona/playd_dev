<?php
include_once('./_common.php');

// 권한검사
if ($member['M_AUTH_TP'] != "1") alert('권한이 없습니다.', P1_URL);

$bo = get_board($_POST['seq']);

if (!$bo['BC_SEQ']) {
    $msg .= '정보가 존재하지 않습니다.';
} else {
    board_delete($bo['BC_SEQ']);
    
    alert("삭제되었습니다.", "./board.php?".$qstr);
}

if ($msg)
    alert($msg);
?>
