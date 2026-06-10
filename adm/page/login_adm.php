<?php
define('_MEMBER_', true);

include_once('./_common.php');

$y1['title'] = '로그인';
include_once('./_head.sub.php');

$url = $_GET['url'];

// url 체크
check_url_host($url);

// 이미 로그인 중이라면
if ($is_member) {
    if ($url)
        goto_url($url);
    else
        goto_url(Y1_ADM_URL);
}

$login_url        = login_url($url, 1);
$login_action_url = "./login_check";
?>
<div id="join" class="_bg">
    <form id="flogin" name="flogin" action="<?php echo $login_action_url ?>" method="post">
    <input type="hidden" name="url" value="<?php echo $login_url ?>">

    <p class="logo"><img src="<?php echo Y1_IMAGES_URL ?>/common/logo.png" alt="logo"></p>
    <article class="login_page _pc">
        <section>
            <div class="input_box">
                <ul>
                    <li>
                        <label for="login_accnt">이메일 주소</label>
                        <input type="text" name="login_accnt" id="login_accnt" required />
                    </li>
                    <li>
                        <label for="login_pswd">비밀번호 입력</label>
                        <input type="password" name="login_pswd" id="login_pswd" required />
                    </li>
                </ul>
            </div>
            <div class="btn_wrap">
                <input type="submit" class="rbtn mail" value="로그인">
            </div>
        </section>
    </article>
    
    </form>
</div>
<?php
include_once('./_tail.sub.php');
?>