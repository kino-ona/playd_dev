<?php
if (!defined('_YOLU_')) exit; // 개별 페이지 접근 불가
?>
<article class="board">
    <?php
    include_once(Y1_PAGE_PATH.'/post_tab.php');
    ?>
    <section class="bbs_list">
        <ul>
            <?php
            for ($i=0; $i<count($list); $i++) {
                $user = get_user($list[$i]['user_no']);    # 사용자 정보
            ?>
            <li>
                <a href="<?=$list[$i]['href']?>" class="bbs_title">
                    <?=$list[$i]['subj']?>
                    <p class="bbs_cont"><?=$list[$i]['post_txt']?></p>
                    <div class="bbs_detail">
                        <span class="user_id"><?=$user['nickname']?></span>
                        <span class="date"><?=passing_time($list[$i]['reg_dttm'])?></span>
                        <span class="view">조회수 : <?=$list[$i]['read_cnt']?></span>
                        <span class="reply"><?=$list[$i]['reply_cnt']?></span>
                    </div>
                </a>
            </li>
            <?php
            }
            if (count($list) == 0) { echo '<li>게시물이 없습니다.</li>'; }
            ?>
        </ul>
        <?php if ($write_href) { ?>
        <a href="<?=$write_href?>" class="btn btn_write">글쓰기</a>
        <?php } ?>
    </section>
</article>
<script>
var chk_top = "";
$(window).scroll(function() {
    if(event.stopPropagation){event.stopPropagation();}else{event.returnValue = false;}
    if(event.preventDefault){event.preventDefault();}else{event.stop();}
    
    event.cancelBubble = true;
    event.bubbles = false;
    event.cancelable = false;
    
    if (chk_top != $(window).scrollTop()) {
        chk_top = $(window).scrollTop();
        // console.log(Math.floor($(window).scrollTop()));
        // console.log($(document).height());
        // console.log($(window).height());
        if (Math.floor($(window).scrollTop()) == $(document).height() - $(window).height()) {
            post_more('<?=$board['board_no']?>', '<?=$sst?>');
        }
    }
});
</script>