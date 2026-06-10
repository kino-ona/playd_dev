<?php
if (!defined('_YOLU_')) exit; // 개별 페이지 접근 불가
?>
<article class="detail">
    <section class="_top_">
        <a href="<?=$link?>" class="go_list">목록</a>
        <p class="title"><?=$board['board_nm']?></p>
    </section>
    <section class="_cont_">
        <p class="bbs_title"><?=$write['subj']?></p>
        <div class="bbs_detail">
            <span class="user_id"><?=$user['nickname']?></span>
            <span class="date"><?=passing_time($write['reg_dttm'])?></span>
            <span class="view">조회수 : <?=$write['read_cnt']?></span>
            <?php if ($update_href) { ?><a href="<?=$update_href?>" class="btn btn_edit">수정</a><?php } ?>
            <?php if ($delete_href) { ?><a href="<?=$delete_href?>" class="span_delete">삭제</a><?php } ?>
        </div>
        <?php
        // 파일 출력
        $img_count = count($write['img_url']);
        if($img_count) {
            setlocale(LC_ALL, 'ko_KR.UTF-8');
            echo '<div class="bbs_addfile">';
            echo '<p>첨부파일 :</p>';
            echo '<a href="#" onclick="go_popup(\''.$write['img_url'].'\');">'.$write['org_img_nm'].'</a>';
            echo '</div>';
        }
        ?>
        <!-- 레이어팝업 : start -->
        <div id="popup" class="Pstyle">
            <span class="b-close">X</span>
            <div class="content" style="height:auto; width:auto;">
            </div>
        </div>
        <script>
        function go_popup(img) {
            $("#popup").bPopup({
                content:'image', //'ajax', 'iframe' or 'image'
                contentContainer:'.content',
                loadUrl:img
            });
        }
        </script>
        <style type="text/css"> 
        .Pstyle{
            display: none;
            position: fixed;
            width: 40%;
            left: 50%;
            margin-left: -20%; /* half of width */
            height: 300px;
            top: 50%;
            margin-top: -150px; /* half of height */
            overflow: auto;

            /* decoration */
            border: 1px solid #000;
            background-color: #eee;
            padding: 1em;
            box-sizing: border-box;
        } 
        .b-close{ 
            position:absolute; 
            right:5px; 
            top:5px; 
            padding:5px; 
            display:inline-block; 
            cursor:pointer; 
        } 
        </style>
        <!-- 레이어팝업 : end -->
        <p class="bbs_cont"><?=nl2br($write['post_txt'])?></p>
    </section>
</article>