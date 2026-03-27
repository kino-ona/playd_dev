<?php
include_once('./_common.php');

$res['post_no'] = $post_no;
$res['search_device_id'] = false;

$device_id = ($_POST['device_id']) ? trim($_POST['device_id']) : "test";

// 투표 여부
$sql = " select count(*) as cnt
           from {$y1['poll_result_table']}
          where post_no = '{$post_no}'
            and device_id = '{$device_id}' ";
$row = sql_fetch($sql);
if ($row['cnt'] > 0) {
    $res['search_device_id'] = true;
    
    // 투표 수
    $sql_cnt = " select count( if(poll_val = 1, poll_val, null) ) as like_cnt,
                        count( if(poll_val = 0, poll_val, null) ) as dislike_cnt
                   from {$y1['poll_result_table']}
                  where post_no = '{$post_no}' ";
    $row_cnt = sql_fetch($sql_cnt);
    $res['like_cnt']    = $row_cnt['like_cnt'];
    $res['dislike_cnt'] = $row_cnt['dislike_cnt'];
}

echo json_encode($res, true);
?>