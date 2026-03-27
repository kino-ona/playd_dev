<?php
if (!defined('_YOLU_')) exit; // 개별 페이지 접근 불가
?>
<article class="detail" id="vapp" v-cloak>
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
            <?php if ($update_href) { ?><a href="<?=$update_href?>" class="span_modify">수정</a><?php } ?>
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
    
    <section class="_vote_" v-for="res in result" v-if="res.search_device_id">
        <div class="vote_wrap">
            <div class="result like"><span>{{res.like_cnt}}</span>좋아요</div>
            <div class="result dislike"><span>{{res.dislike_cnt}}</span>싫어요</div>
        </div>
        <button class="btn btn_vote btn_vote_fin" disabled>투표완료</button>
    </section>

    <form name="fpoll" id="fpoll" action="./poll_update" onsubmit="return fpoll_submit(this);" method="post" v-else>
    <input type="hidden" name="device_id" id="device_id">
    <input type="hidden" name="post_no" id="post_no" value="<?=$write['post_no']?>">
    <input type="hidden" name="bo_no" id="bo_no" value="<?=$board['board_no']?>">
    <input type="hidden" name="poll_val" id="poll_val">

    <section class="_vote_">
        <div class="vote_wrap">
            <label for="like" class="btn like" onclick="poll_sel(this);">좋아요</label>
            <label for="dislike" class="btn dislike" onclick="poll_sel(this);">싫어요</label>
        </div>
        <button type="submit" class="btn btn_vote">투표하기</button>
    </section>
    
    </form>

    <section class="_reply_">
        <?php
        // 로그인 회원만 댓글쓰기
        // if ($is_member) {
        ?>
        <form name="fwrite" id="fwrite" action="./post_reply_regist_update" onsubmit="return freply_submit(this);" method="post">
        <input type="hidden" name="m" id="m" value="">
        <input type="hidden" name="cmnt_no" id="cmnt_no">
        <input type="hidden" name="post_no" id="post_no" value="<?=$write['post_no']?>">
        <input type="hidden" name="bo_no" id="bo_no" value="<?=$board['board_no']?>">
        
        <div class="reply_write">
            <div class="reply_num">댓글 <strong><?=$write['reply_cnt']?></strong></div>
            <div class="textarea">
                <textarea name="cmnt_txt" id="cmnt_txt" maxlength="200" placeholder="댓글을 작성해주세요."></textarea>
                <div class="ctrl_box">
                    <div class="_radio">
                        <p class="_good"><input type="radio" name="yesno" id="radio_good" value="1" checked><label for="radio_good">좋아요</label></p>
                        <p class="_bad"><input type="radio" name="yesno" id="radio_bad" value="0"><label for="radio_bad">싫어요</label></p>
                    </div>
                    <div class="_fin">
                        <div class="_limit"><span id="txt_count">0</span>/200</div>
                        <button class="_submit">등록</button>
                    </div>
                </div>
            </div>
        </div>
        
        </form>
        
        <script>
        // var char_min = 10;
        // var char_max = 200;
        check_byte("cmnt_txt", "txt_count");

        $(function() {
            $("#cmnt_txt").on("keyup", function() {
                check_byte("cmnt_txt", "txt_count");
            });
        });
        
        function freply_submit(f) {
            <?php if (!$is_member) { ?>
            alert("로그인 후 이용가능합니다.");
            location.href = './login';
            return false;
            <?php } ?>
            
            // 댓글내용 확인
            if(f.cmnt_txt.value == "") {
                alert("댓글 내용을 작성해주세요.");
                f.cmnt_txt.focus();
                return false;
            }
            
            // 리뷰내용 글자수 체크
            // var cnt = parseInt(check_byte("cmnt_txt", "txt_count"));
            // if (char_min > 0 && char_min > cnt) {
                // alert("댓글 내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                // f.cmnt_txt.focus();
                // return false;
            // } else if (char_max > 0 && char_max < cnt) {
                // alert("댓글 내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                // f.cmnt_txt.focus();
                // return false;
            // }
            
            return true;
        }
        </script>
        <?php
        // }
        ?>
        <div class="reply_list">
            <ul>
                <?php
                $sql_reply = " select *
                                 from {$y1['post_cmnt_table']}
                                where post_no = '{$write['post_no']}' 
                                  and del_yn = '0'
                                  and parent_no is null
                             order by reg_dttm desc ";
                $res_reply = sql_query($sql_reply);
                while($row_reply=sql_fetch_array($res_reply)) {
                    $user_re = get_user($row_reply['user_no']);    # 사용자 정보
                    
                    $rereply_show = ($p_no == $row_reply['cmnt_no']) ? ' style="display:block;"' : ' style="display:none;"';
                    $yesno_cls = ($row_reply['yesno'] == "1") ? " _good" : " _bad";
                    $yesno_txt = ($row_reply['yesno'] == "1") ? "좋아요" : "싫어요";
                ?>
                <li id="c_<?=$row_reply['cmnt_no']?>">
                    <div class="bbs_detail">
                        <span class="tag_goodnbad<?=$yesno_cls?>"><?=$yesno_txt?></span>
                        <span class="user_id"><?=$user_re['nickname']?></span>
                        <span class="date"><?=passing_time($row_reply['reg_dttm'])?></span>
                        <?php
                        // 로그인중이고 자신의 글이라면 또는 관리자인 경우
                        if ($member['user_no'] && $row_reply['user_no'] === $member['user_no'] || $is_admin) {
                        ?>
                        <button class="span_modify" onclick="post_reply_reg('<?=$row_reply['cmnt_no']?>');">수정</button>
                        <button class="span_delete" onclick="post_reply_del('<?=$row_reply['cmnt_no']?>');">삭제</button>
                        <?php
                        }
                        ?>
                    </div>
                    <p class="reply_cont"><?=nl2br($row_reply['cmnt_txt'])?></p>
                    <div class="rereply_wrap">
                        <button type="button" onclick="rereply_show(this);">대댓글 <strong><?=$row_reply['depth']?></strong></button>
                        <div class="rereply"<?=$rereply_show?>>
                            <?php
                            // 로그인 회원만 댓글쓰기
                            // if ($is_member) {
                            ?>
                            <form name="fwrite" action="./post_reply_regist_update" onsubmit="return frereply_submit(this);" method="post">
                            <input type="hidden" name="m" id="m_re_<?=$row_reply['cmnt_no']?>" value="r">
                            <input type="hidden" name="cmnt_no" id="cmnt_no_re_<?=$row_reply['cmnt_no']?>" value="<?=$row_reply['cmnt_no']?>">
                            <input type="hidden" name="post_no" id="post_no" value="<?=$write['post_no']?>">
                            <input type="hidden" name="bo_no" id="bo_no" value="<?=$board['board_no']?>">
                        
                            <div class="input_area">
                                <textarea name="cmnt_txt" id="cmnt_txt_re_<?=$row_reply['cmnt_no']?>" maxlength="200" placeholder="대댓글을 작성해주세요."></textarea>
                                <div class="btn_wrap">
                                    <div class="txt_limit"><span id="txt_count_re_<?=$row_reply['cmnt_no']?>">0</span>/200</div>
                                    <button class="btn_reply_submit" id="rereply_submit_<?=$row_reply['cmnt_no']?>">등록</button>
                                </div>
                            </div>
                            
                            </form>
                            
                            <script>
                            // var char_min = 10;
                            // var char_max = 200;
                            check_byte("cmnt_txt_re_<?=$row_reply['cmnt_no']?>", "txt_count_re_<?=$row_reply['cmnt_no']?>");

                            $(function() {
                                $("#cmnt_txt_re_<?=$row_reply['cmnt_no']?>").on("keyup", function() {
                                    check_byte("cmnt_txt_re_<?=$row_reply['cmnt_no']?>", "txt_count_re_<?=$row_reply['cmnt_no']?>");
                                });
                            });
                            
                            function frereply_submit(f) {
                                <?php if (!$is_member) { ?>
                                alert("로그인 후 이용가능합니다.");
                                location.href = './login';
                                return false;
                                <?php } ?>
                                
                                // 댓글내용 확인
                                if(f.cmnt_txt.value == "") {
                                    alert("댓글 내용을 작성해주세요.");
                                    f.cmnt_txt.focus();
                                    return false;
                                }
                                
                                // 리뷰내용 글자수 체크
                                // var cnt = parseInt(check_byte("cmnt_txt", "txt_count"));
                                // if (char_min > 0 && char_min > cnt) {
                                    // alert("댓글 내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                                    // f.cmnt_txt.focus();
                                    // return false;
                                // } else if (char_max > 0 && char_max < cnt) {
                                    // alert("댓글 내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                                    // f.cmnt_txt.focus();
                                    // return false;
                                // }
                                
                                return true;
                            }
                            </script>
                            <?php
                            // }
                            ?>
                            <div class="rereply_list">
                                <ul>
                                    <?php
                                    $sql_search = " where (1)
                                                      and post_no = '{$write['post_no']}'
                                                      and parent_no = '{$row_reply['cmnt_no']}'
                                                      and del_yn = '0' ";
                                    
                                    $sql_rereply = " select count(*) as cnt from {$y1['post_cmnt_table']} {$sql_search} ";
                                    $row_rereply = sql_fetch($sql_rereply);
                                    $total_count = $row_rereply['cnt'];

                                    $page_rows = 10;

                                    if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
                                    $from_record = ($page - 1) * $page_rows; // 시작 열을 구함

                                    $sql_rereply = " select *
                                                       from {$y1['post_cmnt_table']}
                                                            {$sql_search}
                                                   order by reg_dttm desc
                                                      limit {$from_record}, {$page_rows} ";
                                    $res_rereply = sql_query($sql_rereply);
                                    while($row_rereply=sql_fetch_array($res_rereply)) {
                                        $user_re_re = get_user($row_rereply['user_no']);    # 사용자 정보
                                    ?>
                                    <li id="c_<?=$row_rereply['cmnt_no']?>">
                                        <div class="bbs_detail">
                                            <span class="user_id"><?=$user_re_re['nickname']?></span>
                                            <span class="date"><?=passing_time($row_rereply['reg_dttm'])?></span>
                                            <?php
                                            // 로그인중이고 자신의 글이라면 또는 관리자인 경우
                                            if ($member['user_no'] && $row_rereply['user_no'] === $member['user_no'] || $is_admin) {
                                            ?>
                                            <button class="span_modify" onclick="post_rereply_reg('<?=$row_rereply['cmnt_no']?>');">수정</button>
                                            <button class="span_delete" onclick="post_reply_del('<?=$row_rereply['cmnt_no']?>', '1');">삭제</button>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <p class="reply_cont"><?=nl2br($row_rereply['cmnt_txt'])?></p>
                                    </li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="_more">
                                <button type="button" onclick="rereply_more('<?=$write['post_no']?>', '<?=$row_reply['cmnt_no']?>', '<?=$row_reply['user_no']?>');">더보기</button>
                            </div>
                        </div>
                    </div>
                </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </section>
</article>

<script>
// 투표 여부
poll_state('<?=$post_no?>');

function fpoll_submit(f)
{
    if (!f.poll_val.value) {
        alert("투표하실 항목을 선택하세요.");
        return false;
    }
    
    return true;
}
</script>