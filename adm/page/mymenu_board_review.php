<?php
if (!defined('_YOLU_')) exit; // 개별 페이지 접근 불가

while($row=sql_fetch_array($res)) {
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
    
    // 사용자 정보
    $user = get_user($row['user_no']);
    
    // 리뷰 댓글 수
    $sql_cmnt = " select count(*) as cnt
                    from {$y1['review_cmnt_table']}
                   where review_no = '{$row['review_no']}'
                     and del_yn = '0' ";
    $row_cmnt = sql_fetch($sql_cmnt);
    $row['reply_cnt'] = $row_cmnt['cnt'];
?>
<li>
    <a href="./review_view?review_no=<?=$row['review_no']?>&url=<?php echo Y1_PAGE_URL ?>/mymenu_board?v=review" class="name">
        <div class="bbs_img_wrap">
            <div class="img">
                <img src="<?=$review_thumb_url?>">
            </div>
            <div class="txt">
                <?=$store['store_disp_nm']?>
                <p class="menu"><?=$menu['menu_nm']?></p>
            </div>
        </div>
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