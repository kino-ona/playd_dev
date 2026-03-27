<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가
?>
<form name="f" method="post">
<input type="hidden" name="bc_code" value="<?php echo $bc_code ?>">
    <!-- search -->
    <div class="pageCount">
        <fieldset>
            <legend class="hid1">검색 테이블</legend>
            <div class="oh">
                <div class="fl">
                    <?php if ($board['BC_SITE_USE_YN'] == 1) { ?>
                    <select name="site" id="site">
                        <option value="">사이트 선택</option>
                        <?php
                        echo option_selected("플레이디", $site, "플레이디");
                        echo option_selected("메이블", $site, "메이블");
                        ?>
                    </select>
                    <?php } ?>
                    <?php if ($board['BC_EXPS_USE_YN'] == 1) { ?>
                    <select name="exps_yn" id="exps_yn">
                        <option value="">상단고정 선택</option>
                        <?php
                        echo option_selected("Y", $exps_yn, "고정");
                        echo option_selected("N", $exps_yn, "비고정");
                        ?>
                    </select>
                    <?php } ?>
                    <?php if ($board['BC_TYPE_USE_YN'] == 1) { ?>
                    <select name="type" id="type">
                        <option value="">구분</option>
                        <?php
                        switch($bc_code) {
                            case "nsmexp":
                                $type_arr = array("광고 트렌드", "광고전략", "광고운영TIP", "시스템 활용", "Trend Research");
                                break;
                            default:
                                $type_arr = array("업데이트", "공지", "안내");
                                break;
                        }
                        
                        foreach($type_arr as $k=>$v) {
                            echo option_selected($v, $type, $v);
                        }
                        ?>
                    </select>
                    <?php } ?>
                    <?php if ($board['BC_NOTI_USE_YN'] == 1) { ?>
                    <select name="noti_yn" id="noti_yn">
                        <option value="">노출여부</option>
                        <?php
                        echo option_selected("Y", $noti_yn, "노출");
                        echo option_selected("N", $noti_yn, "비노출");
                        ?>
                    </select>
                    <?php } ?>
                </div>
                <div class="fr">
                    <select name="search_type" id="search_type" class="fl">
                        <?php
                        echo option_selected("title", $search_type, "제목");
                        echo option_selected("all", $search_type, "제목+내용");
                        ?>
                    </select>
                    <label for="search_txt" class="fl ML10"> 
                        <input type="text" id="search_txt" name="search_txt" value="<?=$search_txt?>">
                    </label>
                    <div class="acButton dib fl ML10">
                        <button class="btn btn-success" type="button" onClick="search()">검색</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</form>

<form name="multi_del_frm" id="multi_del_frm" action="<?php echo P1_PAGE_URL ?>/post_update.php" onsubmit="return multi_del();" method="post">
<input type="hidden" name="bc_code" value="<?php echo $bc_code ?>">

<div id="gall_allchk">
    <label for="allCheck" class="sound_only">현재 페이지 게시물 전체</label>
    <input type="checkbox" id="allCheck" />
</div>
    
<ul class="gallery">
    <?php
    for ($i=0; $i<count($list); $i++) {
        $list[$i]['B_SITE']    = ($list[$i]['B_SITE']) ? $list[$i]['B_SITE'] : "전체";
        $list[$i]['B_EXPS_YN'] = ($list[$i]['B_EXPS_YN'] == "Y") ? "고정" : "비고정";
        $list[$i]['B_NOTI_YN'] = ($list[$i]['B_NOTI_YN'] == "Y") ? "노출" : "비노출";
    ?>
    <li>
        <div class="gall_chk">
            <input type="hidden" name="seq[<?php echo $i ?>]" value="<?php echo $list[$i]['B_SEQ'] ?>" id="seq_<?php echo $i ?>">
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </div>
        <a href="javascript:view('<?=$list[$i]['B_SEQ']?>','<?=$list[$i]['href']?>')">
            <?php if ($list[$i]['B_SYSFILE2']) { ?>
            <img src="<?=$list[$i]['B_SYSFILE2']?>" style="width:150px; height:150px" alt="<?=$list[$i]['B_TITLE']?>" />
            <?php } else { ?>
            <img src="<?php echo P1_IMAGES_URL ?>/manager/common/no_image.jpg" style="width:150px; height:150px" alt="<?=$list[$i]['B_TITLE']?>" />
            <?php } ?>
        </a>
        <div><?=$list[$i]['B_CORP_NAME']?></div>
    </li>
    <?php
    }
    if ($i == 0)
        echo "<li>자료가 없습니다.</li>";
    ?>
</ul>

<?php if ($write_href) { ?>
<!-- both button -->
<div class="bothButton">
    <div class="fr">
        <input type="submit" class="btn btn-primary" value="선택삭제">
        <button class="btn btn-primary" type="button" onclick="location.href='<?=$write_href?>'">등록</button>
    </div>
</div>
<?php } ?>

</form>

<!-- pagination -->
<?=$pages?>

<!-- 뷰 페이지 파라미터 넘겨주기 -->
<form name="view" action="" method="post">
    <input type="hidden" name="m" value="u">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <?php if ($board['BC_SITE_USE_YN'] == 1) { ?>
    <input type="hidden" name="site" value="<?=$site?>">
    <?php } ?>
    <?php if ($board['BC_EXPS_USE_YN'] == 1) { ?>
    <input type="hidden" name="exps_yn" value="<?=$exps_yn?>">
    <?php } ?>
    <?php if ($board['BC_TYPE_USE_YN'] == 1) { ?>
    <input type="hidden" name="type" value="<?=$type?>">
    <?php } ?>
    <?php if ($board['BC_NOTI_USE_YN'] == 1) { ?>
    <input type="hidden" name="noti_yn" value="<?=$noti_yn?>">
    <?php } ?>
    <input type="hidden" name="seq" value="">
    <input type="hidden" name="bc_code" value="<?=$bc_code?>">
</form>