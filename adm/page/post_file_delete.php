<?php
include_once('./_common.php');

$post = get_write($_GET['seq']);
$num = $_GET['num'];
if(!$num){
    $num = '2';
}

if (!$post['B_SEQ']) {
    $msg .= "게시물이 존재하지 않습니다..";
} else if (!file_exists(P1_PATH2.$post['B_FILE'.$num])) {
    $msg .= '첨부파일이 존재하지 않습니다.';
} else {
    // 파일삭제
    @unlink(P1_PATH2.$post['B_FILE'.$num]);
    
    $sql = " update {$p1['t_board_table']}
                set b_file".$num." = null,
                    b_sysfile".$num." = null
              where b_seq = '{$post['B_SEQ']}' ";
    sql_query($sql);
    
    $msg .= "삭제되었습니다.";
}

if ($msg)
    alert($msg, "./post_write.php?bc_code=".$post['B_CODE']."&b_seq=".$post['B_SEQ'].$qstr."&amp;m=u");
?>