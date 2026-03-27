<?php
if (!defined('_ADM_')) exit; // 개별 페이지 접근 불가


//권한체크
$auth = get_check_admin_auth($member); //alert('권한이 없습니다.', P1_URL);
if($auth['read'] != 'Y') {
    alert('권한이 없습니다.', '/adm/page/login.php');
    exit;
}

$sql_common = " from {$p1['t_ask_table']} ";

$sql_search = " where (1)
                  and a_code = '{$board['BC_CODE']}' ";
                  
// 검색어
if ($search_txt) {    
    // LIKE 보다 INSTR 속도가 빠름
    if (preg_match("/[a-zA-Z]/", $search_txt))
        $sql_instr = " and INSTR(LOWER(a_{$search_type}), LOWER('{$search_txt}')) ";
    else
        $sql_instr = " and INSTR(a_{$search_type}, '{$search_txt}') ";
    
    switch($search_type) {
        case "all":    # 전체
            $sql_search .= " and (a_title like '%{$search_txt}%' || a_cont like '%{$search_txt}%' || a_name like '%{$search_txt}%') ";
            break;
        case "title_cont":    # 제목+내용
            $sql_search .= " and (a_title like '%{$search_txt}%' || a_cont like '%{$search_txt}%') ";
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

$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$page_rows = $board['BC_ROWS'];
$list_page_rows = $board['BC_PAGES_ROWS'];

if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
$from_record = ($page - 1) * $page_rows; // 시작 열을 구함

$sql_order = " order by a_date desc ";
if ($sst) {
    $sql_order = " order by {$sst} {$sod} ";
}

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$page_rows} ";

// echo "sql=".$sql."<br>";

$res = sql_query($sql);

$k = 0;
$i = 0;
$list = array();
while ($row=sql_fetch_array($res)) {
    $list[$i] = $row;
    $list_num = $total_count - ($page - 1) * $list_page_rows;
    $list[$i]['num'] = $list_num - $k;
    $list[$i]['href'] = P1_PAGE_URL.'/qna.php';
    $list[$i]['A_DATE'] = passing_time($list[$i]['A_DATE']);
    // $list[$i]['href'] = P1_PAGE_URL.'/qna.php?bc_code='.$board['BC_CODE'].'&amp;a_seq='.$list[$i]['A_SEQ'].$qstr;

    $i++;
    $k++;
}

$pages = get_paging($page_rows, $page, $total_page, './qna.php?bc_code='.$bc_code.$qstr.'&amp;page=');

$write_href = '';
$write_href = P1_PAGE_URL.'/qna_write.php?bc_code='.$bc_code;

include_once(P1_POST_SKIN_PATH.'/'.$board['BC_SKIN'].'/list.php');
?>