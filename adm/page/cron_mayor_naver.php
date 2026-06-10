<?php
	header("Content-Type: text/html; charset=UTF-8");
	include_once("./_common.php");

	echo "<script type='text/javascript'>alert('완료 메세지가 뜰때까지 창을 벗어나지 마세요.');location.href=../page/post.php?bc_code=nsmpr';</script>";

	/*
	$db_hostname = "localhost";
	$db_database = "playd"; //DB명
	$db_username = "playd"; //DB 계정
	$db_password = "playd_DbpAswD^23A"; //DB 패스워드
	$dbcon = mysql_connect($db_hostname,$db_username,$db_password) or die ("Database Server Connection Error");
	mysql_select_db($db_database,$dbcon) or die ("Database Connection Error");   
	mysql_query("set names utf8");
	*/

	/* 블로그 데이터 수집 전에 기존 블로그 데이터를 지워줍니다. (데이터 중복방지) 
	   삭제방지된 데이터는 삭제하지 않도록 합니다. 2019년 9월 20일. PG메일로 요청 받음. */
	$del_sql = "DELETE FROM T_BOARD WHERE B_CODE='nsmpr' AND B_SITE='네이버블로그' AND (B_SNS_DEL_YN='Y' OR B_SNS_DEL_YN IS NULL)";
	sql_query($del_sql);

	function naver_post(){
		$path = "http://blog.rss.naver.com/nsm_life.xml";
		$xml = new SimpleXMLElement($path,0,true); 

		foreach($xml->channel->item as $channel) {
			$title = addslashes($channel->title);
			$text = addslashes($channel->description);
			$date = date("Y-m-d H:i:s", strtotime($channel->pubDate));
			$link = $channel->link;
			$author = $channel->author;
			$type = "네이버블로그";

			/* 수집하는 데이터의 링크가 기존 데이터에 존재하지 않을때만 insert */
			$select_sql = "SELECT COUNT(*) as cnt FROM T_BOARD WHERE B_LINKURL='{$link}' AND B_SITE='네이버블로그'";
			$row = sql_fetch($select_sql);

			if($row[cnt]=="" || $row[cnt]=="0") {
				$insert_sql = "INSERT INTO T_BOARD(B_CODE,B_TITLE,B_CONT,B_TYPE,B_SITE,B_HITS,B_NOTI_YN,B_EXPS_YN,B_FILE2,B_WRITER,B_REGDATE,B_LINKURL,B_SYSFILE2) VALUES('nsmpr','{$title}','{$text}','보도기사','{$type}','0','N','N','','{$author}','{$date}','{$link}','')";
				sql_query($insert_sql);
			}
		}

	}

	naver_post();		//네이버 수집 실행
?>

<script type="text/javascript">
	alert("수집 완료했습니다.");
	location.href="../page/post.php?bc_code=nsmpr";
</script>
