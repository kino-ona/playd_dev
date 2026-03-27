<?php
include_once('./_common.php');
include_once(P1_LIB_PATH."/register.lib.php");

check_admin_token();

// 권한검사
//if ($member['M_AUTH_TP'] != "1") alert('권한이 없습니다.', P1_URL);

$m_id        = trim($_POST['m_id']);         # 사번
$m_pw        = trim($_POST['m_pw']);         # 비밀번호
$m_mail      = trim($_POST['m_mail']);       # 이메일
$m_use_yn    = trim($_POST['m_use_yn']);     # 상태
// $m_auth_tp   = trim($_POST['m_auth_tp']);    # 관리자권한설정

$m_name     = trim($_POST['m_name']);        # roles 권한
$m_phone    = trim($_POST['m_phone']);    # 일반 권한 대분류
$g_seq     = trim($_POST['g_seq']);    # 일반 권한 소분류


if ($m == '' || $m == 'u') {
    if ($msg = empty_m_id($m_id))                alert($msg, "", true, true);
    if ($msg = valid_m_id($m_id))                alert($msg, "", true, true);
    if ($msg = count_m_id($m_id))                alert($msg, "", true, true);

    if ($msg = empty_m_email($m_mail))           alert($msg, "", true, true);
    if ($msg = valid_m_email($m_mail))           alert($msg, "", true, true);
    if ($msg = exist_m_email($m_mail, $m_id))    alert($msg, "", true, true);

    if ($m == '') {
        if ($msg = exist_m_id($m_id))    alert($msg, "", true, true);
    }

}

$sql_user = " m_mail = '{$m_mail}',
              m_name = '{$m_name}',
              m_phone = '{$m_phone}' ";

if ($m == 'u')
{
    $m_seq = trim($_POST['seq']);    # 유저번호

    $user = get_user($m_seq);
    if (!$user['M_SEQ'])
        alert('존재하지 않는 정보입니다.');

    $sql_password = "";
    if ($m_pw){

         //이전 비번 체크
         $sql = " select count(*) as cnt FROM T_MGR where m_seq = '{$m_seq}' and  passwd_old = '".get_encrypt_string($m_pw)."' ";
         $row2 = sql_fetch($sql);
         $total_count = $row2['cnt'];
         if($total_count > 0){
             alert('최근 사용한 비밀번호입니다. 다른 비밀번호를 선택해 주세요.','administration_edit.php?seq='.$m_seq.'&m=u');
             exit;
         }

        $sql_password = " m_pw = '".get_encrypt_string($m_pw)."', passwd_old = '".get_encrypt_string($m_pw)."' , ";
    }

    $sql_fail_pw = '';
    if($user['M_USE_YN'] == 'N' && $m_use_yn == 'Y') {
        $sql_fail_pw = " passwd_fail_cnt = 0, m_use_yn = 'Y' , ";
    }

    sql_query(" update {$p1['t_mgr_table']}
                   set m_uregdate = '".P1_TIME_YMDHIS."',
                       {$sql_fail_pw}
                       {$sql_password}
                       {$sql_user}
                 where m_seq = '{$m_seq}' ");
    //로그 기록
    insert_mgr_log('수정','관리자 관리 > 관리자 계정 관리', "./administration_u_edit.php?seq=".$m_seq.$qstr."&amp;m=u");

    alert("수정되었습니다.", "./administration_u_edit.php?seq=".$m_seq.$qstr."&amp;m=u");
}
else
    alert('제대로 된 값이 넘어오지 않았습니다.');