<?php
include_once('./_common.php');

/* 입사지원관리 정보 */
$sql_common_inc = " from {$p1['mable_t_support_table']} ";

$sql_search_inc = " where s_seq = '{$seq}' ";

$sql_inc = " select *,
                    (select count(*) from {$p1['mable_t_support_view_table']} as cnt where sv_code = s_seq) as CNT
                    {$sql_common_inc}
                    {$sql_search_inc} ";
$row_inc = sql_fetch($sql_inc);
$inc_date_txt = date("Y.m.d", strtotime($row_inc['S_ST_DATE']))." ~ ".date("Y.m.d", strtotime($row_inc['S_ET_DATE']));

/* 지원자 리스트 정보 */
$sql_common = " from {$p1['mable_t_support_view_table']} ";

$sql_search = " where sv_code = '{$row_inc['S_SEQ']}' ";
// $sql_search = " where sv_code = '347' ";

$sql_order = " order by sv_seq desc ";
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

$row_tmp = sql_fetch($sql);

// 다운로드 세션
// 쿠키에 저장된 ID값과 넘어온 ID값을 비교하여 같지 않을 경우 오류 발생
// 다른곳에서 링크 거는것을 방지하기 위한 코드
$ss_name = 'ss_inc_view_'.$row_tmp['SV_SEQ'];
set_session($ss_name, TRUE);

$p1['title']    = '입사지원관리(MABLE)';
$p1['subtitle'] = '채용관리(MABLE)';
include_once('./_head.php');

$colspan = 6;
?>
<script type="text/javascript">
function excel_download() {
    var f = document.f;
    f.action = '<?php echo P1_PAGE_URL ?>/excel_download.php';
    f.mode.value = 'm';
    f.submit();
}
</script>

<form name="f" method="post">
<input type="hidden" name="s_seq" value="<?=$row_inc['S_SEQ']?>">
<input type="hidden" name="s_field" value="<?=$row_inc['S_FIELD']?>">
<input type="hidden" name="mode" value="">
    <div class="pageCount">
        <fieldset>
            <legend class="hid1">엑셀 테이블</legend>
            <div class="oh">
                <div class="fr">
                    <a href="javascript:excel_download();" class="btn btn-success">엑셀파일로 다운</a>
                </div>
            </div>
        </fieldset>
    </div>
</form>

<table class="rowTable">
    <thead>
        <tr>
            <th>모집분야</th>
            <th>대상</th>
            <th>직군</th>
            <th>모집기간</th>
            <th>지원수</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="al"><a href="javascript:view('<?=$row_inc['S_SEQ']?>', '<?php echo P1_PAGE_URL ?>/m_incident_edit.php')"><?=$row_inc['S_FIELD']?></a></td>
            <td><?=$row_inc['S_OBJ']?></td>
            <td><?=$row_inc['S_JOB']?></td>
            <td><?=$inc_date_txt?></td>
            <td><?=$row_inc['CNT']?></td>
        </tr>
    </tbody>
</table>

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

<table class="rowTable MT20">
    <thead>
        <tr>
            <th>번호</th>
            <th>이름</th>
            <th>내/외국인</th>
            <th>이메일</th>
            <th>생년월일</th>
            <th>지원서</th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {
        ?>
        <tr>
            <td><?=($bnum-$i)?></td>
            <td><?=$row['SV_NAME']?></td>
            <td><?=$row['SV_STATE']?></td>
            <td><?=$row['SV_MAIL']?></td>
            <td><?=$row['SV_BIRTH']?></td>
            <?php if ($row['SV_SYSFILE1']) { ?>
            <td><a href="<?php echo P1_PAGE_URL ?>/download_sptv.php?seq=<?=$row['SV_SEQ']?>&mode=m" class="btn-small btn-primary">다운</a></td>
            <?php } else { ?>
            <td></td>
            <?php } ?>
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
        <button class="btn btn-primary" type="button" onclick="fnGoView('<?php echo P1_PAGE_URL ?>/m_incident.php');">입사지원리스트로</button>
    </div>
</div>

<!-- pagination -->
<?php echo get_paging($rows, $page, $total_page, '?seq='.$seq.$qstr.'&amp;page='); ?>

<!-- 목록으로 돌아가기 -->
<form name="golist" action="" method="post">
    <input type="hidden" name="page" value="<?=$page?>">
    <input type="hidden" name="search_type" value="<?=$search_type?>">
    <input type="hidden" name="search_txt" value="<?=$search_txt?>">
    <input type="hidden" name="obj" value="<?=$obj?>">
    <input type="hidden" name="job" value="<?=$job?>">
</form>
<?php
include_once('./_tail.php');
?>