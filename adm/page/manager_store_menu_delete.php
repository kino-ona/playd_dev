<?php
include_once('./_common.php');

if ($is_guest) {
    alert("로그인 후 이용하세요.", "./login");
}

$sql = " select count(*) as cnt
           from {$y1['store_info_table']}
          where user_no = '{$member['user_no']}'
            and service_state in (1, 2) ";
$row = sql_fetch($sql);
if ($row['cnt'] > 0) {
    alert("가맹점회원 심의 중입니다.");
}

$user_no = $member['user_no'];        # 사용자번호
$menu_no = trim($_GET['menu_no']);    # 메뉴번호

$menu = get_menu($menu_no);
if (!$menu['menu_no']) {
    
    alert('존재하지 않는 메뉴입니다.');
} else {
    // 메뉴정보 delete
    menu_delete($menu['menu_no']);
    
    alert("삭제되었습니다.", Y1_PAGE_URL.'/manager_store?tab=detail'.$qstr);
}
?>