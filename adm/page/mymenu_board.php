<?php
define('_NAV_', true);

include_once('./_common.php');

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

$v = trim($_GET['v']);
$v = ($v) ? $v : "review";

$start = 0;
$rows  = 10;

if ($v == "review") {
    $sql_common = " from {$y1['menu_review_table']} ";
} else if ($v == "post") {
    $sql_common = " from {$y1['post_table']} ";
    $sql_search = " and post_state = '2' ";
    $sql_union  = " union
                   select b.*
                     from {$y1['post_cmnt_table']} a,
                          {$y1['post_table']} b
                    where a.post_no = b.post_no
                      and a.user_no = '{$member['user_no']}'
                      and a.del_yn = '0'
                      and b.post_state = '2' ";
}

// 내가 쓴 리뷰&글 리스트
$sql = " select *
                {$sql_common}
          where user_no = '{$member['user_no']}'
                {$sql_search}
                {$sql_union}
       order by reg_dttm desc
          limit {$start}, {$rows} ";
$res = sql_query($sql, Y1_DISPLAY_SQL_ERROR, null, 1);

$y1['title'] = '마이메뉴';
$y1['div_cls'] = 'nav_wrap _bg';
$y1['div_id'] = 'navMymenu';
include_once('./_head.php');
?>
<article class="mymenu detail">
    <section class="_top_">
        <a href="<?php echo Y1_PAGE_URL ?>/mymenu" class="go_list">마이메뉴</a>
        <p class="title">내가 쓴 글</p>
    </section>
    <section class="tab">
        <div class="tab_btn col2">
            <a href="<?php echo Y1_PAGE_URL ?>/mymenu_board?v=review" class="btn<?=($v == "review") ? " on" : ""?>">내가 쓴 리뷰</a>
            <a href="<?php echo Y1_PAGE_URL ?>/mymenu_board?v=post" class="btn<?=($v == "post") ? " on" : ""?>">내가 쓴 글</a>
        </div>
    </section>
    <section class="bbs_list">
        <ul>
        <?php include_once(Y1_PAGE_PATH.'/mymenu_board_'.$v.'.php'); ?>
        </ul>
    </section>
</article>
<script>
$(window).scroll(function() {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
        mymenu_board_more('<?=$v?>');
    }
});
</script>
<?php
include_once('./_tail.php');
?>