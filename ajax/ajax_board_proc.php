<?php
    include_once($_SERVER["DOCUMENT_ROOT"].'/include/_common.php');

    header('Content-Type: application/json; charset=UTF-8');

    $cmd = nvl($_POST['cmd']);
    $CSRFToken = nvl($_POST['CSRFToken']);
    $CSRFToken2 = nvl($_POST['CSRFToken2']);
    if (!isset($_POST['cmd']) || $_POST['cmd'] === '') {
        echo json_encode(array('success' => false, 'error' => 'cmd'), JSON_UNESCAPED_UNICODE);
        exit;
    }
    if($cmd=='letter_write'){

        // if($CSRFToken2 != $_SESSION['CSRFToken2']){
        //     $postData = array('success' => false, 'err_token' => $_SESSION['CSRFToken2']);
        //     echo json_encode($postData, JSON_UNESCAPED_UNICODE);
        //     exit;
        // }
        // $_SESSION['CSRFToken2'] = '';

        $user_name = nvl($_POST['user_name']);
        $user_profession = nvl($_POST['user_profession']);
        $user_mail = nvl($_POST['user_mail']);
        $user_position = nvl($_POST['user_position']);
        $user_company = nvl($_POST['user_company']);
        $user_team = nvl($_POST['user_team']);

        $sql = 'insert into T_NEWSLETTER ( NS_NAME, NS_MAIL, NS_JIKUP, NS_JIKLV, NS_COMPANY, NS_DIV, NS_REGDATE) ';
        $sql.= " values ('".$user_name."', '".$user_mail."', '".$user_profession."', '".$user_position."','".$user_company."','".$user_team."', now())";
        sql_query($sql);
    } else if($cmd=='newsreport'){

        // if($CSRFToken2 != $_SESSION['CSRFToken2']){
        //     $postData = array('success' => false, 'err_token' => $_SESSION['CSRFToken2']);
        //     echo json_encode($postData, JSON_UNESCAPED_UNICODE);
        //     exit;
        // }
        // $_SESSION['CSRFToken2'] = '';

        $user_name = nvl($_POST['user_name']);
        $user_profession = nvl($_POST['user_profession']);
        $user_mail = nvl($_POST['user_mail']);
        $user_position = nvl($_POST['user_position']);
        $user_company = nvl($_POST['user_company']);
        $user_team = nvl($_POST['user_team']);
        $user_marketing = nvl($_POST['user_marketing']);
        $user_seq = nvl($_POST['user_seq']);

        $sql = 'insert into T_NEWSREPORT ( NS_NAME, NS_MAIL, NS_JIKUP, NS_JIKLV, NS_COMPANY, NS_DIV, NS_MARKETING, NS_B_SEQ,  NS_REGDATE) ';
        $sql.= " values ('".$user_name."', '".$user_mail."', '".$user_profession."', '".$user_position."','".$user_company."','".$user_team."', '".$user_marketing."', '".$user_seq."',  now())";
        sql_query($sql);
    } else if($cmd=='ask_write'){
        // if($CSRFToken != $_SESSION['CSRFToken']){
        //     $postData = array('success' => false, 'err_token' => $_SESSION['CSRFToken']);
        //     echo json_encode($postData, JSON_UNESCAPED_UNICODE);
        //     exit;
        // }
        //$_SESSION['CSRFToken'] = '';


        $a_code = 'nsmad';
        $a_re_yn = 'N';
        $a_id = '';

        $a_type = nvl($_POST['a_type']);
        $a_type_gubun3 = nvl($_POST['a_type_gubun3']);
        $a_type_gubun2 = nvl($_POST['a_type_gubun2']);
        $a_type_gubun1 = nvl($_POST['a_type_gubun1']);
        $a_title = nvl($_POST['a_title']);
        $a_cont = nvl(addslashes($_POST['a_cont']));
        $a_name = $_POST['a_name'];
        $a_mail = $_POST['a_mail'];
        $a_jik = $_POST['a_jik'];
        $a_corp_name = $_POST['a_corp_name'];
        $a_url = nvl($_POST['a_url']);
        $a_tel = $_POST['a_tel'];
        $a_market_yn = nvl($_POST['a_market_yn']);
        $a_market2_yn = nvl($_POST['a_market2_yn']);
        $a_project_dt = nvl($_POST['a_project_dt']);
        $a_sysfile1 = nvl($_POST['a_sysfile1']);
        $a_file1 = nvl($_POST['a_file1']);
        $a_etc_memo = nvl($_POST['a_etc_memo']);
        $a_connet = nvl($_POST['a_connet'] ?? '');

        if($a_corp_name) {
            $a_corp_name = Encrypt($a_corp_name);
        }
        if($a_name) {
            $a_name = Encrypt($a_name);
        }
        if($a_jik){
            $a_jik = Encrypt($a_jik);
        }
        if($a_tel){
            $a_tel = Encrypt($a_tel);
        }
        if($a_mail){
            $a_mail = Encrypt($a_mail);
        }
        

        $sql = 'insert into T_ASK ( ';
        $sql.= 'A_CODE,A_NAME,A_TEL,A_MAIL,A_URL,A_ID,A_TYPE,A_TYPE_GUBUN1,A_TYPE_GUBUN2,A_TYPE_GUBUN3,A_TITLE,';
        $sql.= 'A_CONT,A_RE_YN,A_DATE,A_CORP_NAME,A_JIK,A_PROJECT_DT,A_CONNET,A_MARKET_YN,A_MARKET2_YN,A_FILE1, A_SYSFILE1 ,A_ETC_MEMO';
        $sql.= ') ';
        $sql.= " values ('".$a_code."', '".$a_name."', '".$a_tel."', '".$a_mail."','".$a_url."','".$a_id."','".$a_type."', ";
        $sql.= " '".$a_type_gubun1."', '".$a_type_gubun2."', '".$a_type_gubun3."', '".$a_title."', '".$a_cont."',  ";
        $sql.= " '".$a_re_yn."',now(),'".$a_corp_name."','".$a_jik."','".$a_project_dt."','".$a_connet."' ,'".$a_market_yn."','".$a_market2_yn."', '".$a_file1."','".$a_sysfile1."' ,'".$a_etc_memo."' ";
        $sql.= " ) ";
        sql_query($sql);

    } else if($cmd=='report_write'){

        // if($CSRFToken != $_SESSION['CSRFToken']){
        //     $postData = array('success' => false, 'err_token' => $_SESSION['CSRFToken']);
        //     echo json_encode($postData, JSON_UNESCAPED_UNICODE);
        //     exit;
        // }

        include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
        $securimage = new Securimage();
        if ($securimage->check($_POST['ct_captcha']) == false) {
            $postData = array('success' => false, 'errcode' => $securimage->check($_POST['ct_captcha']));
            echo json_encode($postData, JSON_UNESCAPED_UNICODE);
            exit;
        }

        //$_SESSION['CSRFToken'] = '';
        $a_re_yn = 'N';
        $a_id = '';

        $a_name = nvl($_POST['a_name']);
        $a_tel = nvl($_POST['a_tel']);
        $a_mail = nvl($_POST['a_mail']);
        $a_passwd = nvl($_POST['a_passwd']);
        $a_title = nvl(addslashes($_POST['a_title']));
        $a_place = nvl($_POST['a_place']);
        $a_place_dt = nvl($_POST['a_place_dt']);
        $a_relation = nvl($_POST['a_relation']);
        $a_cont = nvl(addslashes($_POST['a_cont']));
        $a_cont_add1 = nvl($_POST['a_cont_add1']);
        $a_cont_add2 = nvl($_POST['a_cont_add2']);
        $a_cont_add3 = nvl($_POST['a_cont_add3']);
        $a_sysfile1 = nvl($_POST['a_sysfile1']);
        $a_file1 = nvl($_POST['a_file1']);

        $a_name = Encrypt($a_name);
        $a_tel = Encrypt($a_tel);
        $a_mail = Encrypt($a_mail);
        $a_passwd = Encrypt($a_passwd);
            

        $sql = 'insert into T_REPORT ( ';
        $sql.= 'A_ID,A_NAME,A_TEL,A_MAIL,A_PASSWD,A_TITLE,A_PLACE,A_PLACE_DT,A_RELATION,A_CONT,A_CONT_ADD1,';
        $sql.= 'A_CONT_ADD2,A_CONT_ADD3,A_RE_YN,A_DATE ';
        $sql.= ') ';
        $sql.= " values ('".$a_id."', '".$a_name."', '".$a_tel."', '".$a_mail."','".$a_passwd."','".$a_title."','".$a_place."', ";
        $sql.= " '".$a_place_dt."', '".$a_relation."', '".$a_cont."', '".$a_cont_add1."', '".$a_cont_add2."','".$a_cont_add3."',  ";
        $sql.= " '".$a_re_yn."',now()  ";
        $sql.= " ) ";
        sql_query($sql);
        $re_seq = sql_insert_id();


        if($a_sysfile1){
            $a_sysfile1_arr = json_decode($a_sysfile1, true);
            $a_file1_arr = json_decode($a_file1, true);
            for ($i = 0; $i < count($a_sysfile1_arr); $i++)  {
                $sysfile_name = $a_sysfile1_arr[$i];
                $file1_name = $a_file1_arr[$i];
                sql_query(" insert into T_BOARD_FILES (FI_NAME,FI_ORG, B_SEQ, FI_SORT, FI_INDEX, FI_REGDATE) values ('".$file1_name."', '".$sysfile_name."', '".$re_seq."', '".$i."', '99',  now()) ");
            }
        }

        // 담당자 메일 발송
        include_once($_SERVER["DOCUMENT_ROOT"].'/adm/lib/mailer.lib.php');
        $text= file_get_contents($_SERVER["DOCUMENT_ROOT"].'/mailform/mailform.html');
        $send_dt = date('Y.m.d');
        $text = preg_replace('/#문의일자/i',$send_dt, $text);

        @mailer("플레이디", "noreply_home@playd.info", "justin@playd.com", "홈페이지를 통해 윤리신고가 접수되었습니다.", $text, 1);
        @mailer("플레이디", "noreply_home@playd.info", "sangkyu.kim@playd.com", "홈페이지를 통해 윤리신고가 접수되었습니다.", $text, 1);
        @mailer("플레이디", "noreply_home@playd.info", "sungmin.yoo@playd.com", "홈페이지를 통해 윤리신고가 접수되었습니다.", $text, 1);


    } else if($cmd=='report_login'){
        $a_mail = nvl($_POST['a_mail']);
        $a_passwd = nvl($_POST['a_passwd']);
        $a_mail = Encrypt($a_mail);
        $a_passwd = Encrypt($a_passwd);

        $passwdCheck = sql_fetch(" select A_PASSWD_FAIL from T_REPORT where A_MAIL='".$a_mail."' limit 1 ");
        if($passwdCheck['A_PASSWD_FAIL'] >= 5){
            $postData = array('success' => false, 'apasswdfail' => 'X');
            echo json_encode($postData, JSON_UNESCAPED_UNICODE);
            exit;
        }


        $sql = " select a.* from T_REPORT a where a.A_MAIL='".$a_mail."' and a.A_PASSWD = '".$a_passwd."' limit 1 ";

        $totalResult = sql_fetch($sql);

        if($totalResult['A_SEQ']){
            set_session('report_mail', $a_mail);
            set_session('report_pwd', $a_passwd);
            set_session('report_name', $totalResult['A_NAME']);

             // 비번 설공 횟수 업데이트
             sql_query(" update T_REPORT set A_PASSWD_FAIL = 0  where A_MAIL='".$a_mail."' ");

            $postData = array('success' => true);
            echo json_encode($postData, JSON_UNESCAPED_UNICODE);
        } else {
            // 비번 실패 횟수 업데이트
            sql_query(" update T_REPORT set A_PASSWD_FAIL = A_PASSWD_FAIL + 1 where A_MAIL='".$a_mail."' ");
            $apasswdfail = 0;
            $totalResult2 = sql_fetch(" select A_PASSWD_FAIL from T_REPORT where A_MAIL='".$a_mail."' limit 1 ");
            if($totalResult2['A_PASSWD_FAIL']){
                $apasswdfail = $totalResult2['A_PASSWD_FAIL'];
            }

            $postData = array('success' => false, 'apasswdfail' => $apasswdfail);
            echo json_encode($postData, JSON_UNESCAPED_UNICODE);
        }

        exit;
    } else if($cmd=='report_reply_write'){
        $comment = nvl(addslashes($_POST['comment']));
        $report_no = nvl($_POST['report_no']);
        $no = nvl($_POST['no']);

        $report_mail= get_session('report_mail');
        $report_pwd = get_session('report_pwd');
        $report_name = get_session('report_name');
        if(!$report_mail){
            $postData = array('success' => false);
            echo json_encode($postData, JSON_UNESCAPED_UNICODE);
            exit;
        }

        if($no){
            $sql = "update T_COMMENT set ";
            $sql.= "comment = '".$comment."' where no = ".$no;
        } else {
            $sql = 'insert into T_COMMENT ( ';
            $sql.= 'report_no, writer, comment, wdate, ip, email';
            $sql.= ') ';
            $sql.= " values ('".$report_no."', '".$report_name."', '".$comment."',now(),'".$_SERVER['REMOTE_ADDR']."','".$report_mail."' ";
            $sql.= " ) ";
        }

        sql_query($sql);

    } else if($cmd=='report_reply_delete'){
        $no = nvl($_POST['no']);
        $report_no = nvl($_POST['report_no']);

        $sql = 'delete from T_COMMENT where no = '.$no.' and report_no = '.$report_no;
        sql_query($sql);
    }


    $postData = array('success' => true);
    echo json_encode($postData, JSON_UNESCAPED_UNICODE);
