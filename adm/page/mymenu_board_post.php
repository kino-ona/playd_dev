<?php
if (!defined('_YOLU_')) exit; // 개별 페이지 접근 불가

while($row=sql_fetch_array($res)) {
    // 사용자 정보
    $user = get_user($row['user_no']);
?>
<li>
    <a href="./post?bo_no=<?=$row['board_no']?>&post_no=<?=$row['post_no']?>&url=<?php echo Y1_PAGE_URL ?>/mymenu_board?v=post" class="bbs_title">
        <?=$row['subj']?>
        <p class="bbs_cont"><?=$row['post_txt']?></p>
        <div class="bbs_detail">
            <span class="user_id"><?=$user['nickname']?></span>
            <span class="date"><?=passing_time($row['reg_dttm'])?></span>
            <span class="view">조회수 : <?=$row['read_cnt']?></span>
            <span class="reply"><?=$row['reply_cnt']?></span>
        </div>
    </a>
</li>
<?php
}
?>