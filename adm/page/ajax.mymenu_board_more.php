<?php
include_once('./_common.php');

$v     = trim($_POST['v']);
$start = trim($_POST['start']);
$list  = trim($_POST['list']);

// 리뷰 & 글
switch($v) {
    case "review":
        $sql_common = " from {$y1['menu_review_table']} ";
        $sql_search = " where user_no = '{$member['user_no']}' ";
        $sql_order  = " order by reg_dttm desc ";
        break;
    case "post":
        $sql_common = " from {$y1['post_table']} ";
        $sql_search = " where user_no = '{$member['user_no']}'
                          and post_state = '2' ";
        $sql_union  = " union
                       select b.*
                         from {$y1['post_cmnt_table']} a,
                              {$y1['post_table']} b
                        where a.post_no = b.post_no
                          and a.user_no = '{$member['user_no']}'
                          and a.del_yn = '0'
                          and b.post_state = '2' ";
        $sql_order  = " order by reg_dttm desc ";
        break;
}

$sql = " select * {$sql_common} {$sql_search} {$sql_union} {$sql_order} limit {$start}, {$list} ";
// 정규식에 걸러지지 않도록 sql_query(쿼리, 에러출력여부, 링크, 정규식 사용여부(1:사용안함))
$res = sql_query($sql, Y1_DISPLAY_SQL_ERROR, null, 1);
while($row=sql_fetch_array($res)) {
    if($v == "review") {
        // 리뷰 썸네일 이미지
        $review_thumb_url = ($row['thumb_url']) ? $row['thumb_url'] : Y1_NOIMG_GALLERY;
        
        // 메뉴 정보
        $menu = get_menu($row['menu_no']);
        if (!$menu['menu_no'])
            continue;
        
        // 매장 정보
        $store = get_store($menu['store_no']);
        if (!$store['store_no'])
            continue;
        
        // 리뷰 댓글 수
        $sql_cmnt = " select count(*) as cnt
                        from {$y1['review_cmnt_table']}
                       where review_no = '{$row['review_no']}'
                         and del_yn = '0' ";
        $row_cmnt = sql_fetch($sql_cmnt);
        $row['reply_cnt'] = $row_cmnt['cnt'];
    }
    
    // 사용자 정보
    $user = get_user($row['user_no']);
?>
<li>
    <?php if($v == "review") { ?>
    <div class="bbs_img_wrap">
        <div class="img">
            <img src="<?=$review_thumb_url?>">
        </div>
        <div class="txt">
            <a href="./review_view?review_no=<?=$row['review_no']?>" class="name"><?=$store['store_disp_nm']?></a>
            <p class="menu"><?=$menu['menu_nm']?></p>
        </div>
    </div>
    <?php } else if($v == "post") { ?>
    <a href="./post?bo_no=<?=$row['board_no']?>&post_no=<?=$row['post_no']?>" class="bbs_title"><?=$row['subj']?></a>
    <p class="bbs_cont"><?=nl2br($row['post_txt'])?></p>
    <?php } ?>
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