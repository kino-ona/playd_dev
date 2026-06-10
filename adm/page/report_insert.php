<?php
include_once('./_common.php');

check_admin_token();

$m_id     = trim($member['M_ID']);       # 관리자아이디
$a_seq    = trim($_POST['seq']);         # 게시물번호
$a_re_yn  = trim($_POST['a_re_yn']);     # 확인여부
$a_fail_yn  = trim($_POST['a_fail_yn']);     # 확인여부
$a_mail_check = trim($_POST['a_mail_check']);
$a_answer = trim(addslashes($_POST['a_answer']));    # 답변내용

if ($a_re_yn == "Y") $sql_re .= " a_rdate = '".P1_TIME_YMDHIS."', ";

$sql_re .= " a_re_yn = '{$a_re_yn}',
             a_answer = '{$a_answer}',
             a_rwriter = '{$m_id}',
             a_uwriter = '{$m_id}',
             a_uregdate = '".P1_TIME_YMDHIS."' ";

if ($m == 'u')
{
    $re = get_report($a_seq);
    if (!$re['A_SEQ'])
        alert('존재하지 않는 정보입니다.');
    
    // 글 update
    sql_query(" update {$p1['t_report_table']}
                   set {$sql_re}
                 where a_seq = '{$a_seq}' ");


    //로그 기록
    insert_mgr_log('수정','문의 관리 > 윤리경영제보', "./report_edit.php?seq=".$a_seq.$qstr."&amp;m=u");

    // 비번 해제 
    if($a_fail_yn == 'N') {
        sql_query(" update {$p1['t_report_table']}
                   set a_passwd_fail = 0 
                 where a_mail = '{$a_mail_check}' ");
    } else if($a_fail_yn == 'Y') {
        sql_query(" update {$p1['t_report_table']}
                   set a_passwd_fail = 5 
                 where a_mail = '{$a_mail_check}' ");
    }

    alert("수정되었습니다.", "./report_edit.php?seq=".$a_seq.$qstr."&amp;m=u");
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');
?>