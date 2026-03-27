<?php
include_once('./_common.php');

// 권한검사
if ($member['M_AUTH_TP'] != "1") alert('권한이 없습니다.', P1_URL);

if( $_POST['m'] == 'all') {

   foreach (explode(",", $_POST['seq']) as $val) {
        user_delete($val);
   }

   //로그 기록
   insert_mgr_log('삭제','관리자 관리 > 관리자 계정 관리','');

   alert("삭제되었습니다.", "./administration.php");
   
} else {
    $user = get_user($_POST['seq']);

    if (!$user['M_SEQ']) {
        $msg .= '정보가 존재하지 않습니다.';
    } else {
        user_delete($user['M_SEQ']);

        //로그 기록
        insert_mgr_log('삭제','관리자 관리 > 관리자 계정 관리','');

        
        alert("삭제되었습니다.", "./administration.php?".$qstr);
    }

    if ($msg)
        alert($msg);


}
?>
