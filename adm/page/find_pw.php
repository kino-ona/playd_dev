<?php
include_once('./_common.php');

if ($is_member) {
    alert("이미 로그인중입니다.");
}

$emplyr_id   = $_POST['emplyr_id'];      # 아이디
$cmpny_nm    = $_POST['cmpny_nm'];       # 회사명
$email_adres = $_POST['email_adres'];    # 이메일

// 비밀번호 정보
if($emplyr_id && $cmpny_nm && $email_adres) {
    $sql = "select a.secret_no
              from {$t1['emplyr_table']}   a,
                   {$t1['taxi_cmp_table']} b
             where a.cmpny_code  = b.cmpny_code
               and a.email_adres = '{$email_adres}'
               and b.cmpny_nm    = '{$cmpny_nm}'
               and a.emplyr_id   = '{$emplyr_id}'";
    $row = sql_fetch($sql);
    $res = ($row) ? $row['secret_no'] : "등록된 정보가 없습니다.";
}

$t1['title'] = '비밀번호 찾기';
include_once('./_head.php');
?>
		<!-- container -->
		<div id="member_container">
			<div class="inner">
				<div class="sect member_tab">
					<div class="head">
						<ul>
							<li><a href="find_id">아이디 찾기</a></li>
							<li class="cur"><a href="find_pw">비밀번호 찾기</a></li>
						</ul>
					</div>
					<div class="member_wrap find_pw">
						<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
							<fieldset>
								<legend>비밀번호 찾기 폼</legend>
								<div class="txt_wrap">
									<p class="desc"><?=$config['SITE_NM']?> 스마트정산시스템</p>
									<p class="tit">비밀번호 찾기</p>
								</div>
								<div class="box">
                                    <?php if($emplyr_id && $cmpny_nm && $email_adres) { ?>
                                    <input type="text" class="input" value="<?=$res?>" readOnly>
                                    <?php } else { ?>
									<p>1. 사용하시는 아이디를 입력하세요.</p>
									<input type="text" class="input" name="emplyr_id" placeholder="아이디" />
									<p>2. 등록하셨던 사업자명을 입력하세요.</p>
									<input type="text" class="input" name="cmpny_nm" placeholder="사업자명" />
									<p>3. 등록된 이메일 주소를 입력하세요.</p>
									<input type="email" class="input" name="email_adres" placeholder="이메일" />
                                    <?php } ?>
								</div>
								<div class="btn_wrap">
									<button type="submit" class="btn type1 submit">확인</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- //container -->
<?php
include_once('./_tail.php');
?>