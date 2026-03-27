<?php
include_once('./_common.php');
include_once('./_admin.php');

// 권한검사
$auth = get_check_admin_auth($member); //alert('권한이 없습니다.', P1_URL);
if($auth['read'] != 'Y') {
    alert('권한이 없습니다.', '/adm/page/login.php');
    exit;
}

$sql_common = " from {$p1['t_mgr_table']} a ";


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
            $sql_search .= " and (m_id like '%{$search_txt}%' || m_name like '%{$search_txt}%') ";
            break;
        case "g_name": # 전체
            $sql_search .= " and g_seq in ( select k.g_seq from T_GROUP k where k.g_name like '%{$search_txt}%' ) ";
            break;
        default:
            $sql_search .= $sql_instr;
            break;
    }
}

if($m_use_yn){
    $sql_search .= " and m_use_yn = '".$m_use_yn."' ";
}

// 권한
if ($auth_tp) {
    $sql_search .= " and m_auth_tp = '{$auth_tp}' ";
}

$sql_order = " order by m_seq desc ";
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

$sql = " select *, (select G_NAME from T_GROUP where G_SEQ = a.G_SEQ) AS G_NAME
                {$sql_common}
                {$sql_search}
                {$sql_order}
          limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$p1['title']    = '';
$p1['subtitle'] = '관리자 계정 관리';
include_once('./_head.php');

$colspan = 7;
?>
<form method="post" action="<?php echo P1_PAGE_URL ?>/administration.php" name="f">
    <!-- search -->
    <div class="pageCount">
        <fieldset>
            <legend class="hid1">검색 테이블</legend>
            <div class="oh">
                <div class="fr">
                    <select name="m_use_yn" id="m_use_yn" class="fl" style="margin-right:10px;">
                        <option value="">사용여부 선택</option>
                        <option value="Y" <?php echo get_selected($_REQUEST['m_use_yn'], "Y"); ?>>사용</option>
                        <option value="N" <?php echo get_selected($_REQUEST['m_use_yn'], "N"); ?>>미사용</option>
                    </select>
                    <select name="search_type" id="search_type" class="fl">
                        <option value="all" <?php echo get_selected($_REQUEST['search_type'], "all"); ?>>전체</option>
                        <option value="m_id" <?php echo get_selected($_REQUEST['search_type'], "m_id"); ?>>사용자ID</option>
                        <option value="m_name" <?php echo get_selected($_REQUEST['search_type'], "m_name"); ?>>사용자명</option>
                        <option value="m_mail" <?php echo get_selected($_REQUEST['search_type'], "m_mail"); ?>>이메일</option>
                        <option value="g_name" <?php echo get_selected($_REQUEST['search_type'], "g_name"); ?>>그룸명</option>
                    </select>
                    <label for="search_txt" class="fl ML10">
                        <input type="text" id="search_txt" name="search_txt" value="<?=$_REQUEST['search_txt']?>">
                    </label>
                    <div class="acButton dib fl ML10">
                        <button class="btn btn-default" type="button" onclick="reset2()">초기화</button>
                        <button class="btn btn-success" type="button" onclick="search()">검색</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</form>
<table class="rowTable">
    <thead>
        <tr>
        <th><input type="checkbox" id="allcheck" value="" onclick="javascript:allCheck(this.checked);"></th>

            <th>번호</th>
            <th><?php echo subject_sort_link('m_id') ?>사용자ID</a></th>
            <th><?php echo subject_sort_link('m_name') ?>사용자명</a></th>
            <th><?php echo subject_sort_link('m_mail') ?>이메일</a></th>
            <th><?php echo subject_sort_link('m_group') ?>그룹명</a></th>
            <th><?php echo subject_sort_link('m_use_yn') ?>사용여부</a></th>
            <th><?php echo subject_sort_link('m_regdate') ?>등록일</a></th>
        </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
    ?>
        <tr>
            <td><?if($row['M_ID']!='admin'){?><input type="checkbox" id="check_<?=$row['M_SEQ']?>" value="<?=$row['M_SEQ']?>"><?}?></td>
            <td><?=($bnum-$i)?></td>
            <td class="al"><a href="javascript:view('<?=$row['M_SEQ']?>', '<?php echo P1_PAGE_URL ?>/administration_edit.php')"><?=$row['M_ID']?></a></td>
            <td><?=$row['M_NAME']?></td>
            <td><?=$row['M_MAIL']?></td>
            <td><?=$row['G_NAME']?></td>
            <td><?=$row['M_USE_YN']=='Y'?'사용':'미사용'?></td>
            <td><?=$row['M_REGDATE']?></td>
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
        <a href="javascript:adminDelete();" class="btn btn-default">삭제</a>
        <?}?>
        <?if($auth['write'] == 'Y') {?>
        <a href="<?php echo P1_PAGE_URL ?>/administration_edit.php?<?=$qstr?>" class="btn btn-primary">등록</a>
        <?}?>
    </div>
</div>

<!-- pagination -->
<?php echo get_paging($rows, $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<!-- 뷰 페이지 파라미터 넘겨주기 -->
<form name="view" action="" method="post" id="view">
    <input type="hidden" name="m" value="u">
    <input type="hidden" name="pagelist" value="<?=$page?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <input type="hidden" name="auth_tp" value="<?=$auth_tp?>">
    <input type="hidden" name="seq" value="">
</form>
<?php
include_once('./_tail.php');
?>

<script>
    function reset2(){
        document.location.href = '<?=basename($_SERVER["PHP_SELF"])?>';
    }
    function allCheck(val){
        if(val){
            $("input[id^='check_']").each(function (i, el) {
                $(this).prop('checked', true);
            });
        } else {
            $("input[id^='check_']").each(function (i, el) {
                $(this).prop('checked', false);
            });
        }
    }
    function adminDelete(){
        var seq_arr = '';
        $("input[id^='check_']").each(function (i, el) {
                if($(this).is(':checked')){
                    seq_arr += $(this).val() +",";
                }
        });
        if(seq_arr==''){
            alert('선택된 계정이 없습니다.');
            return;
        }

        if(confirm('계정을 삭제하시겠습니까?')){
            document.view.action = 'administration_del.php';
            document.view.m.value = 'all';
            document.view.seq.value = seq_arr;
            document.view.submit();
        }
    }
</script>