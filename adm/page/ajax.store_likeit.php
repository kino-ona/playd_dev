<?php
include_once('./_common.php');

if ($is_guest) {
    echo "login";
    exit;
}

$mode     = trim($_POST['mode']);        # false: 좋아요 true: 취소
$store_no = trim($_POST['store_no']);    # 매장번호

switch($mode) {
    case "false":    # 좋아요
        $sql = " insert into {$y1['user_pick_store_table']}
                         set user_no = '{$member['user_no']}',
                             store_no = '{$store_no}',
                             reg_dttm = '".Y1_TIME_YMDHIS."' ";
        break;
    case "true":    # 좋아요 취소
        $sql = " delete
                   from {$y1['user_pick_store_table']}
                  where user_no = '{$member['user_no']}'
                    and store_no = '{$store_no}' ";
        break;
}

sql_query($sql);
?>