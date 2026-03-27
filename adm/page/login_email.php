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
        goto_url(Y1_PAGE_URL.'/store_list');
}

$login_url        = login_url($url);
$login_action_url = "./login_check";
?>
<div id="join" class="_bg">
    <form id="flogin" name="flogin" action="<?php echo $login_action_url ?>" method="post">
    <input type="hidden" name="url" value="<?php echo $login_url ?>">
    <input type="hidden" name="app_ver" id="app_ver">
    <input type="hidden" name="device_os" id="device_os">
    <input type="hidden" name="device_id" id="device_id">
    <input type="hidden" name="device_model" id="device_model">
    <input type="hidden" name="os_ver" id="os_ver">
    <input type="hidden" name="token_id" id="token_id">

    <p class="logo"><img src="<?php echo Y1_IMAGES_URL ?>/common/logo_new2.png" alt="logo"></p>
    <article class="login_page">
        <section>
            <div class="input_box">
                <ul>
                    <li>
                        <label for="login_accnt">이메일 주소</label>
                        <input type="text" class="in_btn" name="login_accnt" id="login_accnt" required />
                    </li>
                    <li>
                        <label for="login_pswd">비밀번호 입력</label>
                        <input type="password" name="login_pswd" id="login_pswd" required />
                    </li>
                </ul>
            </div>
            <div class="login_opt">
                <div class="keep">
                    <input type="checkbox" class="in_btn" name="auto_login" id="login_auto_login">
                    <label for="login_auto_login">로그인 상태 유지</label>
                </div>
                <div class="find">
                    <a href="<?php echo Y1_PAGE_URL ?>/password_lost">비밀번호찾기</a>
                </div>
            </div>
            <div class="btn_wrap">
                <input type="submit" class="rbtn mail" value="로그인">
            </div>
        </section>
        <div class="page_ctrl">
            <a href="#" class="prev" onclick="history.back();">이전으로</a>
        </div>
    </article>
    
    </form>
</div>
<script>
$(function() {
    if(window.hasOwnProperty('android')) {
        var device_info = jQuery.parseJSON(window.android.getdeviceInfo());
        app_device_update(device_info);
    } else {
        window.location = "jscall://getdeviceInfo?app_device_update";
    }
    
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시겠습니까?");
        }
    });
});
</script>
<?php
include_once('./_tail.sub.php');
?>