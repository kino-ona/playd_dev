<?php
include_once('./_common.php');

check_admin_token();

$m_id    = trim($member['M_ID']);      # 관리자아이디
$a_seq   = trim($_POST['seq']);        # 게시물번호
$a_code  = trim($_POST['a_code']);     # 게시판코드
$a_rcont = trim(addslashes($_POST['a_rcont']));    # 상담내용
$a_re_yn = trim($_POST['a_re_yn']);    # 확인여부

if ($a_rcont)        $sql_qna .= " a_rcont = '{$a_rcont}', ";
if ($a_re_yn == "Y") $sql_qna .= " a_rdate = '".P1_TIME_YMDHIS."', ";

$sql_qna .= " a_re_yn = '{$a_re_yn}',
              a_rwriter = '{$m_id}',
              a_uwriter = '{$m_id}',
              a_uregdate = '".P1_TIME_YMDHIS."' ";

if ($m == 'u')
{
    $qna = get_qna($a_seq);
    if (!$qna['A_SEQ'])
        alert("글이 존재하지 않습니다.");
    
    if (get_session('ss_bc_code') != $a_code || get_session('ss_a_seq') != $a_seq) {
        alert('올바른 방법으로 수정하여 주십시오.', P1_PAGE_URL.'/qna.php?bc_code='.$a_code);
    }
    
    // 글 update
    sql_query(" update {$p1['t_ask_table']}
                   set {$sql_qna}
                 where a_seq = '{$a_seq}' ");

    
    //로그 기록
    insert_mgr_log('수정','문의 관리 > '.get_code_str($a_code), "/adm/page/qna.php?bc_code=".$a_code."&a_seq=".$a_seq.$qstr."&amp;m=u");

    alert("수정되었습니다.", "./qna.php?bc_code=".$a_code."&a_seq=".$a_seq.$qstr."&amp;m=u");
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');
?>