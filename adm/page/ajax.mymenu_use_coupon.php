<?php
include_once('./_common.php');

if ($is_guest) {
    echo "login";
    exit;
}

// 사용자쿠폰번호
$user_cupon_no = trim($_POST['user_cupon_no']);

// 사용자 쿠폰 정보
$coupon = get_user_coupon($user_cupon_no);

// 자기자신 쿠폰인지?
if($coupon['user_no'] != $member['user_no']) {
    // echo "non-matched";
    echo "잘못된 쿠폰정보입니다.";
    exit;
}

// 만료기간이 지났는지?
if(Y1_TIME_YMDHIS >= $coupon['exp_dttm']) {
    // echo "expired";
    echo "사용기간이 만료된 쿠폰입니다.";
    exit;
}

// 이미 사용한 쿠폰인지?
if($coupon['use_state'] == "2") {
    // echo "used";
    echo "이미 사용한 쿠폰입니다.";
    exit;
}

$sql = " update {$y1['user_cupon_table']}
            set use_state = '2',
                use_dttm = '".Y1_TIME_YMDHIS."'
          where user_cupon_no = '{$user_cupon_no}' ";
sql_query($sql);
echo "쿠폰 사용이 완료되었습니다.";
?>