<?php
include_once('./_common.php');

// 권한검사
$auth = get_check_admin_auth($member); //alert('권한이 없습니다.', P1_URL);
if($auth['read'] != 'Y') {
    alert('권한이 없습니다.', '/adm/page/login.php');
    exit;
}

$sql_common = " from {$p1['t_board_config_table']} ";

$sql_search = " where (1) ";

// 검색어
if ($search_txt) {    
    // LIKE 보다 INSTR 속도가 빠름
    if (preg_match("/[a-zA-Z]/", $search_txt))
        $sql_instr = " and INSTR(LOWER({$search_type}), LOWER('{$search_txt}')) ";
    else
        $sql_instr = " and INSTR({$search_type}, '{$search_txt}') ";
    
    switch($search_type) {
        case "all": # 전체
            $sql_search .= " and (bc_name like '%{$search_txt}%' || bc_code like '%{$search_txt}%') ";
            break;
        default:
            $sql_search .= $sql_instr;
            break;
    }
}

// 게시판종류
if ($type) {
    $sql_search .= " and bc_type = '{$type}' ";
}

// 게시판스킨
if ($skin) {
    $sql_search .= " and bc_skin = '{$skin}' ";
}

$sql_order = " order by bc_reg_dttm ";
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

$p1['title']    = '';
$p1['subtitle'] = '게시판 설정 관리';
include_once('./_head.php');

$colspan = 8;
?>
<form name="f" method="post">
    <!-- search -->
    <div class="pageCount">
        <fieldset>
            <legend class="hid1">검색 테이블</legend>
            <div class="oh">
                <div class="fl">
                    <select name="group" id="group">
                        <option value="">게시판그룹 선택</option>
                        <?php
                        echo option_selected("a", $group, "게시판 관리");
                        echo option_selected("c", $group, "채용관리(PLAYD)");
                        echo option_selected("d", $group, "채용관리(MABLE)");
                        ?>
                    </select>
                </div>
                <div class="fl ML10">
                    <select name="type" id="type">
                        <option value="">게시판종류 선택</option>
                        <?php
                        echo option_selected("post", $type, "일반");
                        echo option_selected("qna", $type, "문의");
                        ?>
                    </select>
                </div>
                <div class="fl ML10">
                    <?php echo get_skin_select('post_skin', 'skin', 'skin', $skin); ?>
                </div>
                <div class="fr">
                    <select name="search_type" id="search_type" class="fl">
                        <option value="all" <?php echo get_selected($search_type, "all"); ?>>전체</option>
                        <option value="bc_name" <?php echo get_selected($search_type, "bc_name"); ?>>게시판명</option>
                        <option value="bc_code" <?php echo get_selected($search_type, "bc_code"); ?>>게시판코드</option>
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

<form name="multi_del_frm" id="multi_del_frm" action="<?php echo P1_PAGE_URL ?>/board_update.php" onsubmit="return multi_del();" method="post">

<table class="rowTable">
    <thead>
        <tr>
            <th><input type="checkbox" id="allCheck" /></th>
            <th>번호</th>
            <th><?php echo subject_sort_link('bc_name') ?>게시판명</a></th>
            <th><?php echo subject_sort_link('bc_code') ?>게시판코드</a></th>
            <th><?php echo subject_sort_link('bc_group') ?>게시판그룹</a></th>
            <th><?php echo subject_sort_link('bc_type') ?>게시판종류</a></th>
            <th><?php echo subject_sort_link('bc_skin') ?>게시판스킨</a></th>
            <th><?php echo subject_sort_link('bc_reg_dttm') ?>등록일시</a></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            switch($row['BC_TYPE']) {
                case "post":
                    $type_txt = "일반";
                    break;
                case "qna":
                    $type_txt = "문의";
                    break;
            }
            
            switch($row['BC_GROUP']) {
                case "a":
                    $group_txt = "게시판 관리";
                    break;
                case "b":
                    $group_txt = "문의관리";
                    break;
                case "c":
                    $group_txt = "채용관리(PLAYD)";
                    break;
                case "d":
                    $group_txt = "채용관리(MABLE)";
                    break;
            }
        ?>
        <tr>
            <td>
                <input type="hidden" name="seq[<?php echo $i ?>]" value="<?php echo $row['BC_SEQ'] ?>" id="seq_<?php echo $i ?>">
                <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            </td>
            <td><?=($bnum-$i)?></td>
            <td class="al"><a href="javascript:view('<?=$row['BC_SEQ']?>', '<?php echo P1_PAGE_URL ?>/board_edit.php')"><?=$row['BC_NAME']?></a></td>
            <td><?=$row['BC_CODE']?></td>
            <td><?=$group_txt?></td>
            <td><?=$type_txt?></td>
            <td><?=$row['BC_SKIN']?></td>
            <td><?=$row['BC_REG_DTTM']?></td>
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
        <?if($auth['del'] == 'Y') {?>
        <input type="submit" class="btn btn-dark" value="삭제">
        <?}?>
        <?if($auth['write'] == 'Y') {?>
        <a href="<?php echo P1_PAGE_URL ?>/board_edit.php?<?=$qstr?>" class="btn btn-primary">등록</a>
        <?}?>
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
    <input type="hidden" name="group" value="<?=$group?>">
    <input type="hidden" name="type" value="<?=$type?>">
    <input type="hidden" name="skin" value="<?=$skin?>">
    <input type="hidden" name="seq" value="">
</form>
<?php
include_once('./_tail.php');
?>