<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가

$colspan = 5;
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
                        <option value="">확인여부</option>
                        <?php
                        echo option_selected("Y", $search_type, "확인");
                        echo option_selected("N", $search_type, "미확인");
                        ?>
                    </select>
                </div>
                <div class="fr">
                    <select name="search_type" id="search_type" class="fl">
                        <?php
                        echo option_selected("all", $search_type, "전체");
                        echo option_selected("cont", $search_type, "내용");
                        echo option_selected("name", $search_type, "성명");
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

<form name="multi_del_frm" id="multi_del_frm" action="<?php echo P1_PAGE_URL ?>/qna_update.php" onsubmit="return multi_del();" method="post">
<input type="hidden" name="bc_code" value="<?php echo $bc_code ?>">

<table class="rowTable">
    <thead>
        <tr>
            <th><input type="checkbox" id="allCheck" /></th>
            <th>번호</th>
            <th>성명</th>
            <th>확인여부</th>
            <th>문의일</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i=0; $i<count($list); $i++) {
            $re_yn_txt = ($list[$i]['A_RE_YN'] == "Y") ? "확인" : "미확인";
        ?>
        <tr>
            <td>
                <input type="hidden" name="seq[<?php echo $i ?>]" value="<?php echo $list[$i]['A_SEQ'] ?>" id="seq_<?php echo $i ?>">
                <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            </td>
            <td><?=$list[$i]['num']?></td>
            <td class="al w400"><a href="javascript:view('<?=$list[$i]['A_SEQ']?>','<?=$list[$i]['href']?>')"><?=$list[$i]['A_NAME']?></a></td>
            <td><?=$re_yn_txt?></td>
            <td><?=$list[$i]['A_DATE']?></td>
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
    <div class="fr">
        <input type="submit" class="btn btn-primary" value="선택삭제">
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