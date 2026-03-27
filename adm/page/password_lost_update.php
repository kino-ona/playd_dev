<?php
include_once('./_common.php');
include_once(Y1_LIB_PATH."/register.lib.php");
include_once(Y1_LIB_PATH.'/mailer.lib.php');

if ($is_member) {
    alert("이미 로그인중입니다.");
}

$login_accnt = trim($_POST['login_accnt']);
$nickname    = trim($_POST['nickname']);

if ($msg = valid_login_accnt($login_accnt)) alert($msg, "", true, true);

$sql = " select user_no, nickname, login_accnt, login_key from {$y1['user_master_table']} where login_accnt = '{$login_accnt}' and nickname = '{$nickname}' ";
$user = sql_fetch($sql);
if (!$user['login_accnt'])
    alert('존재하지 않는 회원입니다.');
else if (is_admin($user['login_key']))
    alert('관리자 아이디는 접근 불가합니다.');

// 임시비밀번호 발급
$change_password = rand(100000, 999999);
sql_query(" update {$y1['user_master_table']}
               set login_pswd = '{$change_password}'
             where user_no = '{$user['user_no']}' ");
    
$subject = "[".Y1_FROM_NAME."] 요청하신 회원정보 찾기 안내 메일입니다.";

$content = "";
$content .= '
<table cellpadding="0" cellspacing="0" style="width:790px;margin:0 auto;border:0;background-color:#c5c5c5;text-align:center">
    <tbody>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" style="width:750px;margin:20px;border:0;background-color:#fff;text-align:center">
                    <tbody>
                        <tr>
                            <td colspan="2">
                                <table cellpadding="0" cellspacing="0" style="width:612px;margin:15px auto 20px;font-size:12px;border:0;text-align:center">
                                    <tbody>
                                        <tr>
                                            <td style="padding:45px;background-color:#f4f4f4;color:#454545;font-size:12px;line-height:20px">
                                                <b>'.$user['nickname'].' ('.$user['login_accnt'].') 회원님</b><br>
                                                회원님의 임시비밀번호를 알려드립니다.
                                                <div style="width:272px;height:48px;margin:30px auto;font-size:14px;color:#454545;font-weight:bold;border:3px solid #d8d8d8;background-color:#fff;line-height:48px">'.$change_password.'</div>
                                                개인정보 보호를 위해 임시 비밀번호로 로그인 후<br>
                                                <b>반드시 새로운 비밀번호로 변경</b>하여 사용해 주시기 바랍니다.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>';

mailer(Y1_FROM_NAME, Y1_FROM_MAIL, $user['login_accnt'], $subject, $content, 1);

alert($login_accnt.' 메일로 회원아이디와 비밀번호가 발송 되었습니다.\\n메일을 확인하여 주십시오.', Y1_PAGE_URL.'/login');
?>
