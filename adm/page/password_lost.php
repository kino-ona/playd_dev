<?php
define('_MEMBER_', true);

include_once('./_common.php');

if ($is_member) {
    alert("이미 로그인중입니다.");
}

$g5['title'] = '비밀번호 찾기';
include_once(Y1_PATH.'/head.php');

$action_url = Y1_PAGE_URL."/password_lost_update.php";
?>
<form name="fpasswordlost" id="fpasswordlost" action="<?php echo $action_url ?>" method="post" autocomplete="off">

<article>
    <section>
        <h2>비밀번호찾기</h2>
        <p class="comm">회원정보에 등록된 이메일 주소로<br>비밀번호를 확인합니다.</p>
        <div class="input_box">
            <ul>
                <li>
                    <label for="login_accnt">이메일 주소</label>
                    <input type="text" class="in_btn" name="login_accnt" id="login_accnt" placeholder="이메일을 입력하세요" required>
                </li>
                <li>
                    <label for="nickname">닉네임</label>
                    <input type="text" name="nickname" id="nickname" placeholder="닉네임을 입력하세요" required>
                </li>
            </ul>
        </div>
    </section>
    <div class="btn_wrap">
        <input type="submit" class="rbtn find" value="이메일주소로 확인하기">
    </div>
    <div class="page_ctrl">
        <a href="#" class="prev" onclick="history.back();">이전으로</a>
    </div>
</article>

</form>
<?php
include_once(Y1_PATH.'/tail.php');
?>