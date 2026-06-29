<?php
include_once('./_common.php');

check_token();

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

$store_no   = trim($_POST['store_no']);      # 매장번호
$user_no    = trim($member['user_no']);      # 사용자번호
$appl_no    = trim($_POST['appl_no']);       # 쿠폰번호
$appl_state = trim($_POST['appl_state']);    # 쿠폰상태

// 쿠폰 정보
$sql_cu = " select *
              from {$y1['cupon_appl_table']}
             where appl_no = '{$appl_no}' ";
$coupon = sql_fetch($sql_cu);
if (!$coupon['appl_no'])
    alert("존재하지 않는 쿠폰입니다.");

// 매장 정보
$store = get_store($store_no);

// 로그인중이고 자신의 매장이라면 패스
if (!($member['user_no'] && $store['user_no'] === $member['user_no']))
    alert('권한이 없습니다.');

// 전체 미사용 처리
$sql_up = " update {$y1['cupon_appl_table']}
               set appl_state = '3',
                   upd_dttm = '".Y1_TIME_YMDHIS."' ";
sql_query($sql_up);

// 쿠폰정보 update
sql_query(" update {$y1['cupon_appl_table']}
               set appl_state = '{$appl_state}',
                   upd_dttm = '".Y1_TIME_YMDHIS."'
             where appl_no = '{$coupon['appl_no']}'
               and store_no = '{$store['store_no']}' ");

alert("수정되었습니다.", Y1_PAGE_URL.'/manager_coupon?'.$qstr);
?>