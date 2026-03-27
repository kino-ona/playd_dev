<?php
include_once('./_common.php');
include_once(P1_LIB_PATH."/register.lib.php");


// 권한검사
//if ($member['M_AUTH_TP'] != "1") alert('권한이 없습니다.', P1_URL);

$ip     = nvl($_POST['ip']);
$seq     = nvl($_POST['seq']);
$reg_name = nvl($_POST['reg_name']);
$cmd        = nvl($_POST['cmd']);

if ($cmd == 'insert')
{
    //중복 체크
    $sql = " select count(*) as cnt from T_IP where IP = '".$ip."' ";
    $row = sql_fetch($sql);
    $total_count = $row['cnt'];
    if($total_count > 0 ){
        alert("이미 추가한 IP입니다.", "./administration_ip.php");
        exit;
    }
    sql_query(" insert into T_IP (IP,REG_NAME,REG_DT) values ('".$ip."','".$reg_name."', now()) ");

    //로그 기록
    insert_mgr_log('등록','관리자 관리 > IP 관리','/adm/page/administration_ip.php');

    alert("등록되었습니다.", "./administration_ip.php");
}
else if ($cmd == 'delete')
{
    sql_query(" delete from T_IP where seq = '{$seq}' ");

    //로그 기록
    insert_mgr_log('삭제','관리자 관리 > IP 관리','/adm/page/administration_ip.php');

    alert("삭제되었습니다.", "./administration_ip.php");
}