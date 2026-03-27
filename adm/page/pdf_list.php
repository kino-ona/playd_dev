<?php
include_once('./_common.php');

$sql_common = " from {$p1['t_pds_table']} ";

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
            $sql_search .= " and (s_field like '%{$search_txt}%' || s_cont like '%{$search_txt}%') ";
            break;
        default:
            $sql_search .= $sql_instr;
            break;
    }
}

// 대상
if ($obj) {
    $sql_search .= " and s_obj = '{$obj}' ";
}

// 직군
if ($job) {
    $sql_search .= " and s_job = jobskin}' ";
}

$sql_order = " order by s_date desc ";
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
          limit {$from_record}, {$rows} ";
$result = sql_query($sql);



//카테고리 
$sql1 = "select * from  {$p1['t_pds_category_table']} where c_use='Y' ";
$result1 = sql_query($sql1);


$p1['title']    = 'PDF게시판';
//$p1['subtitle'] = '채용관리(PLAYD)';
include_once('./_head.php');

$colspan = 7;
?>
<form name="f" method="post">
    <!-- search -->
    <div class="pageCount">
        <fieldset>
            <legend class="hid1">검색 테이블</legend>
            <div class="oh">               
                <div class="fl ML10">
                    <select name="s_type" id="s_type">
                        <option value="">카테고리 선택</option>
                        <?php
						 for ($i=0; $row1=sql_fetch_array($result1); $i++) {
							echo "<option value='".$row1['C_NAME']."'>".$row1['C_NAME']."</option>";
						}
                        ?>
                    </select>
                </div>
                <div class="fr">
                    <select name="search_type" id="search_type" class="fl">
                        <option value="all" <?php echo get_selected($search_type, "all"); ?>>전체</option>
                        <option value="cont" <?php echo get_selected($search_type, "cont"); ?>>내용</option>
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
</form>

<form name="multi_del_frm" id="multi_del_frm" action="<?php echo P1_PAGE_URL ?>/pdf_update.php" onsubmit="return multi_del();" method="post">

<table class="rowTable">
    <thead>
        <tr>
            <th style="width:5%"><input type="checkbox" id="allCheck" /></th>
            <th style="width:5%">번호</th>
            <th style="width:20%"><?php echo subject_sort_link('s_field') ?>카테고리</a></th>
            <th style="width:*%"><?php echo subject_sort_link('s_obj') ?>제목</a></th>
            <th style="width:10%">노출여부</th>
			<th style="width:15%">등록일</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            $noti_yn = ($row['S_NOTI_YN'] == "Y") ? "노출" : "비노출";
        ?>
        <tr>
            <td>
                <input type="hidden" name="seq[<?php echo $i ?>]" value="<?php echo $row['S_SEQ'] ?>" id="seq_<?php echo $i ?>">
                <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            </td>
            <td><?=($bnum-$i)?></td>
            <td><a href="javascript:view('<?=$row['S_SEQ']?>', '<?php echo P1_PAGE_URL ?>/pdf_edit.php')"><?=$row['S_TYPE']?></a></td>
            <td class="al"><a href="javascript:view('<?=$row['S_SEQ']?>', '<?php echo P1_PAGE_URL ?>/pdf_edit.php')"><?=$row['S_TITLE']?></a></td>
            <td><?=$noti_yn?></td>
            <td><?=substr($row['S_DATE'],0,10)?></td>
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
<div class="fl">
        전체 <span style="font-size:14px;color:#aa0000; padding-left:5px;"><?=number_format($total_count)?></span>
    </div>
    <div class="fr">
        <input type="submit" class="btn btn-primary" value="선택삭제">
        <a href="<?php echo P1_PAGE_URL ?>/pdf_edit.php?<?=$qstr?>" class="btn btn-primary">등록</a>
    </div>
</div>

</form>

<!-- pagination -->
<?php echo get_paging($rows, $page, $total_page, '?'.$qstr.'&amp;page='); ?>

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
<?php
include_once('./_tail.php');
?>