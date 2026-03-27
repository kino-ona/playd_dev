<?php
define('_NAV_', true);
    
include_once('./_common.php');

$y1['title'] = '마이메뉴';
$y1['div_cls'] = 'nav_wrap';
$y1['div_id'] = 'navMymenu';
include_once('./_head.php');
?>
<article class="mymenu">
    <section class="profile">
        <a href="./mymenu_setting" class="btn_setting">환경설정</a>
        <div class="_photo_">
            <img src="<?=$member['thumbnail_url']?>">
        </div>
        <?php if($is_member) { ?>
        <p class="name"><?=$member['nickname']?></p>
        <p class="email"><?=$member['login_accnt']?></p>
        <?php } else { ?>
        <p class="etc">로그인 후 이용하세요.</p>
        <?php } ?>
    </section>
    <section class="list_mymenu">
        <ul>
            <?php if($is_member) { ?>
            <li><a href="./mymenu_regist" class="modify">내 정보 수정</a></li>
            <li><a href="./mymenu_pick_store" class="pick">내가 찜한 업소</a></li>
            <li><a href="./mymenu_coupon" class="coupon">받은 쿠폰</a></li>
            <li><a href="./mymenu_board" class="board">내가 쓴 글</a></li>
            <?php if($member['user_type'] == "2" || $is_admin) { ?>
            <li><a href="./manager" class="manager">매장관리자 앱</a></li>
            <?php } ?>
            <li><a href="./logout" class="btn_logout">로그아웃</a></li>
            <li><a href="./leave" class="btn_withdraw" onclick="return user_leave();">회원탈퇴</a></li>
            <?php } else { ?>
            <li><a href="./login" class="btn_logout">로그인</a></li>
            <?php } ?>
        </ul>
    </section>
	<section>
		<div class="name_card">
			<p>비즈무리사업단</p>
			<span class="address">충청남도 천안시 ㅇㅇㅇ길 40</span>
			<span>문의 : 041-850-8114</span>
		  </div>
	</section>

</article>
<script>
function user_leave() {
    if(!confirm("회원 탈퇴 하시겠습니까?")) {
        return false;
    }
}
</script>
<?php
include_once('./_tail.php');
?>