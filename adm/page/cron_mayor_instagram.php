<?php
	header("Content-Type: text/html; charset=UTF-8");
	include_once("./_common.php");

	/*
	$db_hostname = "localhost";
	$db_database = "playd"; //DB명
	$db_username = "playd"; //DB 계정
	$db_password = "playd_DbpAswD^23A"; //DB 패스워드
	$dbcon = mysql_connect($db_hostname,$db_username,$db_password) or die ("Database Server Connection Error");
	mysql_select_db($db_database,$dbcon) or die ("Database Connection Error");   
	mysql_query("set names utf8");
	*/

	function insta_post(){
		global $collectionPostArr,$count;

		/* 인스타그램 데이터 수집 전에 기존 인스타그램 데이터를 지워줍니다. (데이터 중복방지) 	   
		삭제방지된 데이터는 삭제하지 않도록 합니다. 2019년 9월 20일. PG메일로 요청 받음. */
		$del_sql = "DELETE FROM T_BOARD WHERE B_CODE='nsmpr' AND B_SITE='인스타그램' AND (B_SNS_DEL_YN='Y' OR B_SNS_DEL_YN IS NULL)";
		sql_query($del_sql);

		/* 인스타그램 api 시작 */
		$url = "https://api.instagram.com/v1/users/1550595852/media/recent?access_token=1550595852.1668d45.88e7454b0df4473d8e08ff1959b8fc6e";
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url); //접속할 URL 주소
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		$g = curl_exec($ch);
		curl_close ($ch);
		$g = str_replace("<pre>","",$g);
		$g = str_replace("</pre>","",$g);
		$post = json_decode($g);

		/* 수집한 인스타그램 데이터를 DB에 insert 합니다. */
		foreach($post->data as $val){
			$id = iconv("EUC-KR", "UTF-8", $val->id);
			$text = iconv("EUC-KR", "UTF-8", addslashes(str_replace("\n"," ",$val->caption->text)));
			//$img = $val->images->thumbnail->url;
			$img2 = iconv("EUC-KR", "UTF-8", $val->images->low_resolution->url);
			$img3 = iconv("EUC-KR", "UTF-8", $val->images->standard_resolution->url);
			$link = iconv("EUC-KR", "UTF-8", $val->link);
			$date = iconv("EUC-KR", "UTF-8", substr ( date("Y-m-d H:i", $val->created_time) , 0 , 10 ));
			$type = "인스타그램";

			/* 수집하는 데이터의 링크가 기존 데이터에 존재하지 않을때만 insert */
			$select_sql = "SELECT COUNT(*) as cnt FROM T_BOARD WHERE B_LINKURL='{$link}' AND B_SITE='인스타그램'";
			$row = sql_fetch($select_sql);

			if($row[cnt]=="" || $row[cnt]=="0") {
				$insert_sql = "INSERT INTO T_BOARD(B_CODE,B_TITLE,B_CONT,B_TYPE,B_SITE,B_HITS,B_NOTI_YN,B_EXPS_YN,B_FILE2,B_FILE3,B_WRITER,B_REGDATE,B_LINKURL,B_SYSFILE2) VALUES('nsmpr','플레이디 인스타그램 소식','{$text}','보도기사','{$type}','0','N','N','{$img2}','{$img3}','admin','{$date} 00:00:00','{$link}','{$img}')";
				sql_query($insert_sql);
			}
		}
	}
	insta_post();		//인스타그램 수집 실행
?>

<script type="text/javascript">
	alert("수집 완료했습니다.");
	location.href="../page/post.php?bc_code=nsmpr";
</script>

