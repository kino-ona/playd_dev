<?php
include_once('./_common.php');

$board_no = trim($_POST['bo_no']);
$start    = trim($_POST['start']);
$list     = trim($_POST['list']);
$sst      = trim($_POST['sst']);

$sql_search = " where (1)
                  and board_no = '{$board_no}'
                  and post_state = '2' ";

$sql_order = " order by reg_dttm desc ";
if ($sst) {
    $sql_order = " order by (case when (TIMESTAMPDIFF(HOUR, reg_dttm, now())) > 24 then 1 else 0 end), read_cnt desc ";
}

$sql = " select * from {$y1['post_table']} {$sql_search} {$sql_order} limit {$start}, {$list} ";
$res = sql_query($sql);
while ($row=sql_fetch_array($res)) {
    $user = get_user($row['user_no']);    # 사용자 정보
    $href = Y1_PAGE_URL.'/post?bo_no='.$board_no.'&amp;post_no='.$row['post_no'].$qstr;
?>
<li>
    <a href="<?=$href?>" class="bbs_title"><?=$row['subj']?></a>
    <p class="bbs_cont"><?=$row['post_txt']?></p>
    <div class="bbs_detail">
        <span class="user_id"><?=$user['nickname']?></span>
        <span class="date"><?=passing_time($row['reg_dttm'])?></span>
        <span class="view">조회수 : <?=$row['read_cnt']?></span>
        <span class="reply"><?=$row['reply_cnt']?></span>
    </div>
</li>
<?php
}
?>