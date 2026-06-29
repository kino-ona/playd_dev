<?php
define('_LOGIN_', true);
include_once './_common.php';


$m_pw = trim($_POST['m_pw']);
$m_pw2 = trim($_POST['m_pw2']);

if (!$m_pw || !$m_pw2) {
    alert('비밀번호는 8~16자 이내의 영문 대/소문자,숫자,특수문자를 사용해 주세요.');
}
$passwd_id = get_session('passwd_id');
if(!$passwd_id){
    alert('접근이 불가합니다.', 'login.php');
    exit;
}


//이전 비번 체크
$sql = " select count(*) as cnt FROM T_MGR where m_id = '{$passwd_id}' and  passwd_old = '".get_encrypt_string($m_pw)."' ";
$row2 = sql_fetch($sql);
$total_count = $row2['cnt'];
if($total_count > 0){
    alert('최근 사용한 비밀번호입니다. 다른 비밀번호를 선택해 주세요.');
    exit;
}

$user = get_member($passwd_id);

//비번 틀리면 FAIL_COUNT 업데이트
if($user['M_ID']) {

    
    set_session('passwd_id', '');
    session_unset(); // 모든 세션변수를 언레지스터 시켜줌
    session_destroy(); // 세션해제함
    sql_query(" UPDATE T_MGR SET PASSWD_FAIL_CNT = 0, PASSWD_CHG_DT = DATE_ADD(NOW(), INTERVAL 90 DAY), PASSWD_OLD = '".get_encrypt_string($m_pw)."', M_PW =  '".get_encrypt_string($m_pw)."' WHERE M_ID = '".$passwd_id."' ");
    alert('비밀번호가 변경되었습니다.','login.php');
} else {
    alert('비밀번호는 8~16자 이내의 영문 대/소문자,숫자,특수문자를 사용해 주세요.');
}

?>
