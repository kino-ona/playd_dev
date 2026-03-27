<?php
include_once('./_common.php');

$sql_common = " from  T_POST_CATEGORY  ";

$sql_search = " where (1) ";

// 검색어
if ($search_txt) {    
    // LIKE 보다 INSTR 속도가 빠름
    if (preg_match("/[a-zA-Z]/", $search_txt))
        $sql_instr = " and INSTR(LOWER(s_{$search_type}), LOWER('{$search_txt}')) ";
    else
        $sql_instr = " and INSTR(s_{$search_type}, '{$search_txt}') ";
    
    switch($search_type) {
        case "all": # 전체
            $sql_search .= " and C_NAME like '%{$search_txt}%' ";
            break;
        default:
            $sql_search .= $sql_instr;
            break;
    }
}

$sql_order = " order by c_sort asc ";
if ($sst) {
    $sql_order = " order by {$sst} {$sod} ";
}

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 레코드 번호 매김
$bnum = $total_count - ($rows * ($page - 1)); # 역순 (10 ~ 1)
// $bnum = ($page - 1) * $rows + 1; # 순번 (1 ~ 10)

$sql = " select *
                {$sql_common}
                {$sql_search}
                {$sql_order}
          ";
$result = sql_query($sql);

$p1['title']    = '포트폴리오 카테고리관리';
//$p1['subtitle'] = '채용관리(PLAYD)';
include_once('./_head.php');

$colspan = 7;
?>
<!-- <form name="f" method="post">
    <div class="pageCount">
        <fieldset>
            <legend class="hid1">검색 테이블</legend>
            <div class="oh">               
                
                <div class="fr">
                    <select name="search_type" id="search_type" class="fl">
                        <option value="all" <?php echo get_selected($search_type, "all"); ?>>전체</option>
                    </select> 
                    <label for="search_txt" class="fl ML10"> 
                        <input type="text" id="search_txt" name="search_txt" value="<?=$search_txt?>">
                    </label>
                    <div class="acButton dib fl ML10">
                        <button class="btn btn-success" type="button" onclick="search()">검색</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</form> -->

<form name="multi_del_frm" id="multi_del_frm" action="<?php echo P1_PAGE_URL ?>/post_category_update.php" onsubmit="return multi_del();" method="post">

<table class="rowTable">
    <thead>
        <tr>
            <th style="width:5%"><input type="checkbox" id="allCheck" /></th>
            <!-- <th style="width:5%">번호</th> -->
            <th style="width:*%">카테고리명</a></th>
            <th style="width:10%">노출여부</th>
			<!-- <th style="width:15%">등록일</th> -->
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {

            $sql2 = " select count(*) as cnt from T_BOARD where B_CODE='playdportfolio' and  B_TYPE = '".$row['C_NAME']."' ";
            $row2 = sql_fetch($sql2);
            $total_count2 = $row2['cnt'];

        ?>
        <tr data-idx="<?=$row['C_SEQ']?>" id="tr_<?=$i?>">
            <td>
                <input type="hidden" name="seq[<?php echo $i ?>]" value="<?php echo $row['C_SEQ'] ?>" id="seq_<?php echo $i ?>">
                <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            </td>
            <!-- <td><?=($bnum-$i)?></td> -->
            <td class="al"><a href="post_category_edit.php?m=u&no=<?=$row['C_SEQ']?>"><?=$row['C_NAME']?></a> (<?=$total_count2?>)</td>
            <td>
				<?php
				if($row['C_USE'] == "Y") echo "노출";
				else if($row['C_USE'] =="N") echo "비노출";
				?>
            </td>
			<!-- <td><?php echo substr($row['C_DATE'],0,10) ?></td> -->
        </tr>
        <?php
        }
        if ($i == 0)
            echo "<tr><td colspan=\"".$colspan."\">자료가 없습니다.</td></tr>";
        ?>
    </tbody>
</table>
<!-- both button -->
<div class="bothButton">
    <div class="fr">
        <input type="submit" class="btn btn-primary" value="선택삭제">
        <a href="<?php echo P1_PAGE_URL ?>/post_category_edit.php?<?=$qstr?>" class="btn btn-primary">등록</a>
    </div>
</div>

</form>

<!-- 뷰 페이지 파라미터 넘겨주기 -->
<form name="view" action="" method="post">
    <input type="hidden" name="m" value="u">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <input type="hidden" name="obj" value="<?=$obj?>">
    <input type="hidden" name="job" value="<?=$job?>">
    <input type="hidden" name="seq" value="">
</form>

<script>

jQuery(document).ready(function() {
            jQuery(".rowTable > tbody").sortable({
                start : function(event, ui) {
                    ui.item.addClass("selected");
                    $(this).attr('data-previndex', ui.item.index());
                }
                , update : function(event, ui) {
                    ui.item.removeClass("selected");

                    var newIndex = ui.item.index();
                    var oldIndex = $(this).attr('data-previndex');
                    
                    console.log($('#tr_' +newIndex).attr('data-idx'), $('#tr_' +oldIndex).attr('data-idx'))
                    var trs = new Array();
                    var nCnt = 0;
                    $("tr[id^='tr_']").each(function (i, el) {
                        trs.push({'nCnt':nCnt, 'c_seq':$(this).attr('data-idx')})
                        nCnt++
                    });

                    console.log(trs);


                    jQuery.ajax({
                                type: "POST",
                                url: "post_category_sort.php",
                                data: {
                                    'data': JSON.stringify(trs)
                                },
                                dataType: 'json',
                                timeout: 600000,
                                error: function (request, error) {
                                        console.log(error + '');
                                },
                                success: function(output){
                                   
                                }
                        });


                    $(this).removeAttr('data-previndex');
                }
            });
        });

</script>

<style>
.rowTable > tbody { cursor:pointer; }
.selected { background-color:#FFFFDF; }

</style>

<?php
include_once('./_tail.php');
?>