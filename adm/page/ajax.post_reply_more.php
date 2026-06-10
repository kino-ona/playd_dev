<?php
include_once('./_common.php');

$post_no   = trim($_POST['post_no']);
$cmnt_no   = trim($_POST['cmnt_no']);
$user_no   = trim($_POST['user_no']);
$start     = trim($_POST['start']);
$list      = trim($_POST['list']);

$sql_search = " where (1)
                  and post_no = '{$post_no}'
                  and parent_no = '{$cmnt_no}'
                  and del_yn = '0' ";

$sql = " select * from {$y1['post_cmnt_table']} {$sql_search} order by reg_dttm limit {$start}, {$list} ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
    $user_re_re = get_user($user_no);    # 사용자 정보
?>
<li id="c_<?=$row['cmnt_no']?>">
    <div class="bbs_detail">
        <span class="user_id"><?=$user_re_re['nickname']?></span>
        <span class="date"><?=passing_time($row['reg_dttm'])?></span>
        <?php
        // 로그인중이고 자신의 글이라면 또는 관리자인 경우
        if ($member['user_no'] && $row['user_no'] === $member['user_no'] || $is_admin) {
        ?>
        <button class="span_modify" onclick="post_rereply_reg('<?=$row['cmnt_no']?>');">수정</button>
        <button class="span_delete" onclick="post_reply_del('<?=$row['cmnt_no']?>', '1');">삭제</button>
        <?php
        }
        ?>
    </div>
    <p class="reply_cont"><?=nl2br($row['cmnt_txt'])?></p>
</li>
<?php
}
?>