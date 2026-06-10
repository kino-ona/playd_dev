<?php
define('_MEMBER_', true);

include_once('./_common.php');

// 로그인중인 경우 회원가입 할 수 없습니다.
if ($is_member) {
    goto_url(Y1_URL);
}

$y1['title'] = '회원가입';
include_once('./_head.php');
?>
<div class="btn_wrap account y_pos">
    <a href="<?php echo Y1_PAGE_URL ?>/register_step0?login_type=email" class="rbtn mail">이메일 주소로 회원가입</a>
    <a href="<?php echo Y1_PAGE_URL ?>/social_popup.php?provider=facebook&social_type=regist" class="rbtn facebook social">페이스북으로 회원가입</a>
    <a href="<?php echo Y1_PAGE_URL ?>/social_popup.php?provider=kakao&social_type=regist" class="rbtn kakao social">카카오톡으로 회원가입</a>
</div>
<div class="page_ctrl">
    <a href="#" onclick="history.back();" class="prev">이전으로</a>
</div>
<?php
include_once('./_tail.php');
?>

