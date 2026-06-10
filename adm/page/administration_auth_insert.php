<?php
include_once('./_common.php');
include_once(P1_LIB_PATH."/register.lib.php");

check_admin_token();

// 권한검사
//if ($member['M_AUTH_TP'] != "1") alert('권한이 없습니다.', P1_URL);

$g_name     = nvl($_POST['g_name']);         # 사번
$g_seq     = nvl($_POST['seq']);
$auth_array        = nvl($_POST['auth_array']);

$cmd        = nvl($_POST['cmd']);         # 비밀번호

if ($cmd == 'insert')
{   
    //중복 체크
    $sql = " select count(*) as cnt from T_GROUP where G_NAME = '".$g_name."' ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];
    if($total_count > 0 ){
        alert("이미 등록된 그룹명입니다.", "./administration_auth.php");
        exit;
    }

    sql_query(" insert into T_GROUP (G_NAME,G_REGDATE) values ('".$g_name."', now()) ");

    //로그 기록
    insert_mgr_log('등록','관리자 관리 > 권한 관리','/adm/page/administration_auth.php');

    alert("등록되었습니다.", "./administration_auth.php");
}
else if ($cmd == 'delete')
{

    //중복 체크
    $sql = " select count(*) as cnt from T_MGR where G_SEQ = '".$g_seq."' ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];
    if($total_count > 0 ){
        alert("삭제 될 그룹에 권한을 갖고 있는 계정이 있습니다. 권한 변경 후 다시 삭제해주시기 바랍니다.", "./administration_auth.php");
        exit;
    }
    sql_query(" delete from T_GROUP where G_SEQ = '{$g_seq}' ");

    //로그 기록
    insert_mgr_log('삭제','관리자 관리 > 권한 관리','/adm/page/administration_auth.php');

    alert("삭제되었습니다.", "./administration_auth.php");
}else if ($cmd == 'auth')
{
   $auth_array = json_decode($auth_array, true);
    
   sql_query(" delete from T_GROUP_AUTH where G_SEQ = ".$g_seq);

    foreach ($auth_array as $row) {
        sql_query(" insert into T_GROUP_AUTH (G_SEQ,G_MENU,G_AUTH_READ, G_AUTH_WRITE, G_AUTH_DEL,G_REGDATE) values ('".$g_seq."', '".$row['menu']."', '".$row['read']."', '".$row['write']."', '".$row['del']."',  now()) ");
    }

    //로그 기록
    insert_mgr_log('수정','관리자 관리 > 권한 관리','/adm/page/administration_auth.php');

    alert("저장되었습니다.", "./administration_auth.php?seq=".$g_seq);

}
