<?php
include_once('./_common.php');

// 권한검사
if ($member['M_AUTH_TP'] != "1") alert('권한이 없습니다.', P1_URL);

if (!count($_POST['chk'])) {
    alert("선택삭제 하실 항목을 하나 이상 체크하세요.");
}

check_admin_token();

for ($i=0; $i<count($_POST['chk']); $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];

    $bo = get_board($_POST['seq'][$k]);

    if (!$bo['BC_SEQ']) {
        alert("게시판이 존재하지 않습니다.");
    } else {
        // 게시판 삭제
        board_delete($bo['BC_SEQ']);
    }
}

alert("삭제되었습니다.", "./board.php?".$qstr);
?>
