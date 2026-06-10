<?php
include_once('./_common.php');

if ($is_guest) {
    echo "login";
    exit;
}

//---------------------
// 알림타입별
//---------------------
// noti_event : 이벤트알림
// noti_store : 업소알림
// noti_open  : 신규오픈알림
$kind = trim($_POST['kind']);    # 알림타입
$noti = trim($_POST['noti']);    # 알림설정 (1:on, 0:off)

// kind 검사
$kind_arr = array("noti_event", "noti_store", "noti_open");
if(isset($kind) && !in_array($kind, $kind_arr)) {
    echo "not kind";
    exit;
}

$sql = " update {$y1['user_master_table']}
            set {$kind} = '{$noti}'
          where user_no = '{$member['user_no']}' ";
sql_query($sql);
?>