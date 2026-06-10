<?php
include_once('./_common.php');


//권한체크
$auth = get_check_admin_auth($member); //alert('권한이 없습니다.', P1_URL);
if($auth['read'] != 'Y') {
    alert('권한이 없습니다.', '/adm/page/login.php');
    exit;
}
if($_SESSION['ss_m_id'] != 'admin_et')
{ 
    echo "<script>alert('해당 메뉴의 접근권한이 없습니다.'); history.back(); </script>";
    exit;
}


$sql_common = " from {$p1['t_report_table']} ";

$sql_search = " where (1) ";

// 검색어
if ($search_txt) {
    // LIKE 보다 INSTR 속도가 빠름
    if (preg_match("/[a-zA-Z]/", $search_txt))
        $sql_instr = " and INSTR(LOWER(a_{$search_type}), LOWER('{$search_txt}')) ";
    else
        $sql_instr = " and INSTR(a_{$search_type}, '{$search_txt}') ";

    switch($search_type) {
        case "all": # 전체
            $sql_search .= " and (a_cont like '%{$search_txt}%' || a_mail like '%{$search_txt}%') ";
            break;
        default:
            $sql_search .= $sql_instr;
            break;
    }
}

// 확인여부
if ($re_yn) {
    $sql_search .= " and a_re_yn = '{$re_yn}' ";
}

if ($start_dt && $end_dt) {
    $sql_search .= " and a_date between '{$start_dt}' and '{$end_dt}' ";
}

$sql_order = " order by a_date desc ";
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

$p1['title']    = '윤리경영제보';
$p1['subtitle'] = '문의관리';
include_once('./_head.php');

$colspan = 6;
?>
<form name="f" method="post">
    <!-- search -->
    <div class="pageCount">
        <fieldset>
            <legend class="hid1">검색 테이블</legend>
            <div class="oh">
                <div class="fl">
                    <select name="re_yn" id="re_yn">
                        <option value="">확인여부</option>
                        <?php
                        echo option_selected("Y", $re_yn, "확인");
                        echo option_selected("N", $re_yn, "미확인");
                        ?>
                    </select>
                </div>

                <div class="fl" style="padding-left:10px;">
                    <input type="text" id="start_dt" name="start_dt" readonly style="min-width:110px; width:110px;" value="<?=$start_dt?>" placeholder="기간 시작" >
                    <button type="button" style="background:none;" class="btn font-25 m-0 p-0"><i class="ti ti-calendar"></i></button>
                     <input type="text" id="end_dt" name="end_dt" readonly  style="min-width:110px; width:110px;" value="<?=$end_dt?>" placeholder="기간 종료" >
                     <button type="button" style="background:none;"   class="btn font-25 m-0 p-0"><i class="ti ti-calendar"></i></button>
                    </div>


                <div class="fr">



                    <select name="search_type" id="search_type" class="fl">
                        <option value="all" <?php echo get_selected($search_type, "all"); ?>>전체</option>
                        <option value="cont" <?php echo get_selected($search_type, "cont"); ?>>문의내용</option>
                        <option value="mail" <?php echo get_selected($search_type, "mail"); ?>>이메일</option>
                    </select>
                    <label for="search_txt" class="fl ML10">
                        <input type="text" id="search_txt" name="search_txt" value="<?=$search_txt?>">
                    </label>
                    <div class="acButton dib fl ML10">
                        <button class="btn btn-success" type="button" onclick="search2()">검색</button>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</form>

<form name="multi_del_frm" id="multi_del_frm" action="<?php echo P1_PAGE_URL ?>/report_update.php" onsubmit="return multi_del();" method="post">

<table class="rowTable">
    <thead>
        <tr>
            <th><input type="checkbox" id="allCheck" /></th>
            <th>번호</th>
            <th><?php echo subject_sort_link('a_title') ?>제목</a></th>
            <th><?php echo subject_sort_link('a_mail') ?>이메일</a></th>
            <th><?php echo subject_sort_link('a_re_yn') ?>확인여부</a></th>
            <th><?php echo subject_sort_link('a_date') ?>문의일</a></th>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            $content   = utf8_strcut(get_text($row['A_TITLE']), 65);
            $re_yn_txt = ($row['A_RE_YN'] == "Y") ? "확인" : "미확인";
            $date_txt  = passing_time($row['A_DATE']);
        ?>
        <tr>
            <td>
                <input type="hidden" name="seq[<?php echo $i ?>]" value="<?php echo $row['A_SEQ'] ?>" id="seq_<?php echo $i ?>">
                <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
            </td>
            <td><?=($bnum-$i)?></td>
            <td class="al"><a href="javascript:view('<?=$row['A_SEQ']?>', '<?php echo P1_PAGE_URL ?>/report_edit.php')"><?=$content?></a></td>
            <td><?=Decrypt($row['A_MAIL'])?></td>
            <td><?=$re_yn_txt?></td>
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
        <?if($auth['write']=='Y'){?>
        <a href="<?php echo P1_PAGE_URL ?>/report_edit.php?<?=$qstr?>" class="btn btn-primary">등록</a>
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
    <input type="hidden" name="re_yn" value="<?=$re_yn?>">
    <input type="hidden" name="seq" value="">
</form>

<script>

$(document).ready(function(){

    $.datepicker.setDefaults({
        dateFormat: 'yymmdd',
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        yearSuffix: '년'
    });

    $('#start_dt').datepicker({
            dateFormat: "yy-mm-dd"
        });
        $('#end_dt').datepicker({
            dateFormat: "yy-mm-dd"
        });


        $('.ti-calendar').eq(0).on('click', function(){
            $('#start_dt').datepicker('show');
        });
        $('.ti-calendar').eq(1).on('click', function(){
            $('#end_dt').datepicker('show');
        });
});

function search2()
{
    var f = document.f;

    f.submit();

}

</script>
<?php
include_once('./_tail.php');
?>