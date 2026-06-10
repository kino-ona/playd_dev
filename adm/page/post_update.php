<?php
include_once('./_common.php');

// 권한검사
// if (!auth_check_no("a", "1")) alert('권한이 없습니다.', '/');

if (!count($_POST['chk'])) {
    alert("항목을 하나 이상 체크하세요.");
}

if($_POST[sns_block]=="") {
	check_admin_token();
}

for ($i=0; $i<count($_POST['chk']); $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];
    $post = get_write($_POST['seq'][$k]);

    if (!$post['B_SEQ']) {
        alert("게시물이 존재하지 않습니다.");
    } else {
		if($_POST[sns_block]=="sns_block") {
			$sql_update = "UPDATE T_BOARD SET B_SNS_DEL_YN='N' WHERE B_SEQ='$post[B_SEQ]'";
			sql_query($sql_update);

		} else {
			// 게시물 삭제
			post_delete($post['B_SEQ']);
		}
    }
}


//로그 기록
if($bc_code == 'files'){
    insert_mgr_log('삭제','파일 업로드','');
} else {
    insert_mgr_log('삭제','게시판 관리 > '.get_code_str($bc_code),'');
}



alert("처리 되었습니다.", "./post.php?bc_code=".$bc_code.$qstr);
?>
