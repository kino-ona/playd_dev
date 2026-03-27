<?php
include_once('./_common.php');

// 권한검사
$auth = get_check_admin_auth($member); //alert('권한이 없습니다.', P1_URL);
if($auth['read'] != 'Y') {
    alert('권한이 없습니다.', '/adm/page/login.php');
    exit;
}

$sql_common = " from {$p1['t_newsletter_table']} ";

$sql_search = " where (1) ";

// 검색어
if ($search_txt) {    
    // LIKE 보다 INSTR 속도가 빠름
    if (preg_match("/[a-zA-Z]/", $search_txt))
        $sql_instr = " and INSTR(LOWER(ns_{$search_type}), LOWER('{$search_txt}')) ";
    else
        $sql_instr = " and INSTR(ns_{$search_type}, '{$search_txt}') ";
    
    switch($search_type) {
        default:
            $sql_search .= $sql_instr;
            break;
    }
}

$sql_order = " order by ns_regdate desc ";
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
$p1['subtitle'] = '뉴스레터 신청자';
include_once('./_head.php');

$colspan = 4;
?>
<script type="text/javascript">
function excel_download() {
    var f = document.f;
    f.action = '<?php echo P1_PAGE_URL ?>/nl_excel_download.php';
    f.submit();
}
</script>

<form name="f" method="post">
    <!-- search -->
    <div class="pageCount">
        <fieldset>
            <legend class="hid1">검색 테이블</legend>
            <div class="oh">
				<div class="fl">
					<a href="javascript:excel_download();" class="btn btn-success">엑셀파일로 다운</a>
				</div>
                <div class="fr">
                    <select name="search_type" id="search_type" class="fl">
                        <option value="mail" <?php echo get_selected($search_type, "mail"); ?>>이메일</option>
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

<form name="multi_del_frm" id="multi_del_frm" action="<?php echo P1_PAGE_URL ?>/newsletter_update.php" onsubmit="return multi_del();" method="post">

<table class="rowTable">
    <thead>
        <tr>
            <th><input type="checkbox" id="allCheck" /></th>
            <th>번호</th>
            <th>이름</th>
            <th>직업</th>
            <th><?php echo subject_sort_link('ns_mail') ?>이메일</a></th>
            <th>직급</th>
            <th>회사명(소속)</th>
            <th>부서(팀명)</th>
            <!-- <th>수신동의</th> -->
            <th><?php echo subject_sort_link('ns_regdate') ?>신청일</a></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            //$date_txt = passing_time($row['NS_REGDATE']);
            $date_txt = substr($row['NS_REGDATE'],0,16);  
        ?>
        <tr>
            <td>
                <input type="hidden" name="seq[<?php echo $i ?>]" value="<?php echo $row['NS_SEQ'] ?>" id="seq_<?php echo $i ?>">
                <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            </td>
            <td><?=($bnum-$i)?></td>
            <td><?=$row['NS_NAME']?></td>
            <td><?=$row['NS_JIKUP']?></td>
            <td><?=$row['NS_MAIL']?></td>
            <td><?=$row['NS_JIKLV']?></td>
            <td><?=$row['NS_COMPANY']?></td>
            <td><?=$row['NS_DIV']?></td>
            <td><?=$date_txt?></td>
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
        <?if($auth['del']=='Y'){?>
        <input type="submit" class="btn btn-primary" value="선택삭제">
        <?}?>
    </div>
</div>

</form>

<!-- pagination -->
<?php echo get_paging($rows, $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<?php
include_once('./_tail.php');
?>