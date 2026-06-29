<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가

$colspan = 6;
?>
<form name="f" method="post">
<input type="hidden" name="bc_code" value="<?php echo $bc_code ?>">
    <!-- search -->
    <div class="pageCount">
        <fieldset>
            <legend class="hid1">검색 테이블</legend>
            <div class="oh">
                <div class="fl">
                    <select name="re_yn" id="re_yn">
                        <option value="">전체 현황</option>
                        <?php
                        echo option_selected("Y", $re_yn, "확인");
                        echo option_selected("N", $re_yn, "미확인");
                        ?>
                    </select>
                </div>
                <div class="fr">
                    <select name="search_type" id="search_type" class="fl">
                        <?php
                        echo option_selected("all", $search_type, "전체");
                        echo option_selected("title", $search_type, "제목");
                        echo option_selected("title_cont", $search_type, "제목+내용");
                        echo option_selected("name", $search_type, "성명");
                        ?>
                    </select>
                    <label for="search_txt" class="fl ML10">
                        <input type="text" id="search_txt" name="search_txt" value="<?=$search_txt?>">
                    </label>
                    <div class="acButton dib fl ML10">
                        <button class="btn btn-success" type="button" onClick="search2()">검색</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</form>

<form name="multi_del_frm" id="multi_del_frm" action="<?php echo P1_PAGE_URL ?>/qna_update.php" onsubmit="return multi_del();" method="post">
<input type="hidden" name="bc_code" value="<?php echo $bc_code ?>">

<table class="rowTable">
    <thead>
        <tr>
            <th width="5%"><input type="checkbox" id="allCheck" /></th>
            <th width="10%">번호</th>
            <th>제목</th>
            <th width="13%">등록일자</th>
            <?if($bc_code=='nsmad'){?>
                <th width="15%">작성자</th>
                <th width="10%">광고성 정보<br/>수집 동의 여부</th>
            <?}else{?>
                <th width="15%">작성자</th>
            <?}?>
            <th width="13%">현황</th>

        </tr>
    </thead>
    <tbody>
        <?php
        for ($i=0; $i<count($list); $i++) {
            $re_yn_txt = ($list[$i]['A_RE_YN'] == "Y") ? "확인완료" : "<span style='color:#ff0000;'>미확인</span>";

            $new_str = '';
            if($list[$i]['A_ADMIN_READ'] == 0 && $i < 3){
                $new_str = ' <span class="badge badge-danger">N</span>';
            }
        ?>
        <tr>
            <td>
                <input type="hidden" name="seq[<?php echo $i ?>]" value="<?php echo $list[$i]['A_SEQ'] ?>" id="seq_<?php echo $i ?>">
                <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            </td>
            <td><?=$list[$i]['num']?></td>
            <td class="al w400"><a href="javascript:view('<?=$list[$i]['A_SEQ']?>','<?=$list[$i]['href']?>')"><?=($list[$i]['A_TITLE']) ? $list[$i]['A_TITLE'] : $p1['title']?> <?=$new_str?></a></td>
            <td><?=$list[$i]['A_DATE']?></td>
            <?if($bc_code=='nsmad'){?>
            <td><?=Decrypt($list[$i]['A_NAME'])?></td>
            <td><?=$list[$i]['A_MARKET2_YN']=='Y'?'O':'X'?></td>
            <?}else{?>
            <td><?=$list[$i]['A_NAME']?></td>
            <?}?>
            <td><?=$re_yn_txt?></td>

        </tr>
        <?php
        }
        if ($i == 0)
            echo "<tr><td colspan=\"".$colspan."\">자료가 없습니다.</td></tr>";
        ?>
    </tbody>
</table>

<?php if ($write_href) { ?>
<!-- both button -->
<div class="bothButton">
<div class="fl">
        전체 <span style="font-size:14px;color:#aa0000; padding-left:5px;"><?=number_format($total_count)?></span>
    </div>

    <div class="fr">
        <?if($auth['read']=='Y'){?>
            <input type="button" class="btn btn-primary" value="엑셀파일로 다운" onclick="javascript:excel_download();">
        <?}?>
        <?if($auth['del']=='Y'){?>
        <input type="submit" class="btn btn-primary" value="선택삭제">
        <?}?>
        <!--<button class="btn btn-primary" type="button" onclick="location.href='<?=$write_href?>'">등록</button>-->
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
    <input type="hidden" name="re_yn" value="<?=$re_yn?>">
    <input type="hidden" name="seq" value="">
    <input type="hidden" name="bc_code" value="<?=$bc_code?>">
</form>

<script>

//검색기능
function search2(){

    var f = document.f;
    // if($('#re_yn').val() == ''){
    //     if(f.search_txt.value ==''){
    //         alert('검색어를 입력해 주세요.');
    //         return;
    //     }
    // }

    //f.pageNo.value = 1;

    f.submit();
}

function excel_download() {
    var f = document.f;
    f.action = '<?php echo P1_PAGE_URL ?>/ad_excel_download.php';
    f.submit();
}


</script>