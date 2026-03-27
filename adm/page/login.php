<?php
session_start();
define('_LOGIN_', true);

include_once './_common.php';

$p1['title'] = '로그인';
include_once './_head.sub.php';

$url = $_GET['url'];

// url 체크
check_url_host($url);

// 이미 로그인 중이라면
// if ($is_member) {
//     if ($url)
//         goto_url($url);
//     else
//         goto_url(P1_URL);
// }

$login_url = login_url($url);
$login_action_url = './login_check.php';
?>
<script type="text/javascript">
	function frmsubmit(){
		if($("#manager_id").val() == "" ) {
			$("#manager_id").focus();
			alert("아이디를 입력해 주세요");

		}else if($("#manager_pw").val() == "" ) {
			$("#manager_pw").focus();
			alert("패스워드를 입력해주세요");

		}else{
			document.loginfrm.submit();
		}
	}

    //<?= $_SERVER['REMOTE_ADDR'] ?>
</script>

<div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
        	<div class="m-t-40 card-box">
                <div class="text-center m-t-20">
                <img src="/static/images/playd-logo.png" width="200"/>
                    <h4 class="text-muted p-20">PLAY.D Administrator </h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal m-t-10" id="loginfrm" name="loginfrm"  action="<?= $login_action_url ?>" method="post">
						<input type="hidden" name="url" value="/adm/page/administration.php">
						<div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" id="manager_id" name="m_id" placeholder="아이디" onkeypress="if(event.keyCode ==13){frmsubmit();}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" id="manager_pw" name="m_pw" v placeholder="비밀번호" onkeypress="if(event.keyCode ==13){frmsubmit();}">
                            </div>
                            <div class="col-xs-12">
                                <div class="checkbox checkbox-danger">
                                        <input  type="checkbox" name="id_save" id="id_save" value="Y" onclick="javascript:idSave();">
                                        <label for="id_save">
                                            아이디 저장
                                        </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12">
                                <button class="btn btn-inverse btn-bordred btn-block waves-effect waves-light" type="button" onclick="frmsubmit()">로그인</button>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <div class="col-xs-12">*비밀번호 분실 시<br>‘yunhye.jeong@playd.com’ 메일로문의해 주세요.</div>
                        </div>

                    </form>

                </div>
            </div>

            <p class="text-muted text-center">Copyright ⓒ PlayD Co., Ltd. All rights reserved.</p>

        </div>
<script>

$( document ).ready(function() {
    if(getCookie("playd_id_save") != "" ){
        $('#id_save').prop('checked', true);
        $('#manager_id').val(getCookie("playd_id_save"));
    }else{
        $('#id_save').prop('checked', false);
        $('#manager_id').val('');
    }


});


function idSave(){
    if($('#id_save').is(":checked") == true){
        setCookie("playd_id_save", $('#manager_id').val(),1000 );
    }else{
		setCookie("playd_id_save", '',1000 );
    }
}


function getCookie( name ){
	var nameOfCookie = name + "=";
		var x = 0;
	while ( x <= document.cookie.length ){
        var y = (x+nameOfCookie.length);
        if ( document.cookie.substring( x, y ) == nameOfCookie ) {
        	if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
        		endOfCookie = document.cookie.length;
        		return unescape( document.cookie.substring( y, endOfCookie ) );
    	}
		x = document.cookie.indexOf( " ", x ) + 1;
		if ( x == 0 )break;
	}
   	return "";
}

function setCookie( name, value, expiredays ){
	var todayDate = new Date();
	todayDate.setDate( todayDate.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";";
}

</script>



<?php include_once './_tail.sub.php';
?>
