<?php
include_once('./_common.php');

check_admin_token();

$m_id    = trim($member['M_ID']);      # 관리자아이디
$a_seq   = trim($_POST['seq']);        # 게시물번호
$comment = trim($_POST['comment']);    # 답변내용
$cmd = trim($_POST['cmd']);    # 답변내용
$no = trim($_POST['no']);    # 답변내용


$sql_co .= " report_no = '{$a_seq}',
             writer = '관리자',
             comment = '{$comment}',
             wdate = now(),
             ip = '{$_SERVER['REMOTE_ADDR']}' ";

if($cmd == 'update'){
    sql_query(" update T_COMMENT set comment = '{$comment}' where no = '{$no}' ");

    //로그 기록
    insert_mgr_log('수정','문의 관리 > 윤리경영제보', "./report_edit.php?seq=".$a_seq.$qstr."&amp;m=u");
    

    alert("수정되었습니다.", "./report_edit.php?seq=".$a_seq.$qstr."&amp;m=u");

} else {
    sql_query(" insert into {$p1['t_comment_table']}
    set {$sql_co} ");

    //로그 기록
    insert_mgr_log('등록','문의 관리 > 윤리경영제보', "./report_edit.php?seq=".$a_seq.$qstr."&amp;m=u");
    


alert("등록되었습니다.", "./report_edit.php?seq=".$a_seq.$qstr."&amp;m=u");

}

?>