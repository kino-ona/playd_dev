<?php
include_once('./_common.php');
include_once('./_admin.php');

// 권한검사
$auth = get_check_admin_auth($member); //alert('권한이 없습니다.', P1_URL);
if($auth['read'] != 'Y') {
    alert('권한이 없습니다.', '/adm/page/login.php');
    exit;
}

$sql_common = " from T_IP a ";
$sql_search = " where (1) ";

$sql_order = " order by seq desc ";
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
$p1['subtitle'] = 'IP 관리';
include_once('./_head.php');

$colspan = 7;
?>
<form method="post" action="<?php echo P1_PAGE_URL ?>/administration.php" name="f">
    <!-- search -->
    <div class="pageCount">
        <fieldset>
            <div class="oh">
                <div class="fr">

                    <label for="search_txt" class="fl ML10">
                    IP 정보
                        <input type="text" id="ip" name="ip" value="">
                    </label>
                    <div class="acButton dib fl ML10">
                        <button class="btn btn-success" type="button" onclick="ipAppend()">추가</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</form>
<table class="rowTable">
    <thead>
        <tr>
            <th>IP</th>
            <th>등록일시</th>
            <th>작성자</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

    ?>
        <tr>
            <td><?=$row['IP']?></td>
            <td><?=$row['REG_DT']?></td>
            <td><?=$row['REG_NAME']?></td>
            <td><a href="javascript:ipDelete('<?=$row['seq']?>');" class="btn btn-default">삭제</a></td>
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
</div>

<!-- pagination -->
<?php echo get_paging($rows, $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<!-- 뷰 페이지 파라미터 넘겨주기 -->
<form name="view" action="administration_ip_insert.php" method="post" id="view">
    <input type="hidden" name="ip" value="">
    <input type="hidden" name="cmd" value="">
    <input type="hidden" name="reg_name" value="">
    <input type="hidden" name="seq" value="">
</form>
<?php
include_once('./_tail.php');
?>

<script>
    function ipAppend(){
        if($('#ip').val()==''){
            alert('IP를 입력해 주세요.');
            return;
        }
        document.view.action = 'administration_ip_insert.php';
        document.view.cmd.value = 'insert';
        document.view.reg_name.value = '<?=$member['M_ID']?>';
        document.view.ip.value = $('#ip').val();
        document.view.submit();
    }
    function ipDelete(seq){
        if(confirm('해당 IP를 삭제하시겠습니까?')){
            document.view.action = 'administration_ip_insert.php';
            document.view.cmd.value = 'delete';
            document.view.seq.value = seq;
            document.view.submit();
        }
    }
</script>