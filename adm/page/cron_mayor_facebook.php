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

	/* 페이스북 데이터 수집 전에 기존 페이스북 데이터를 지워줍니다. (데이터 중복방지) 	   
	삭제방지된 데이터는 삭제하지 않도록 합니다. 2019년 9월 20일. PG메일로 요청 받음. */
	$del_sql = "DELETE FROM T_BOARD WHERE B_CODE='nsmpr' AND B_SITE='페이스북' AND (B_SNS_DEL_YN='Y' OR B_SNS_DEL_YN IS NULL)";
	sql_query($del_sql);

	function facebook_post(){
		global $collectionPostArr,$count;
		$url = "https://graph.facebook.com/v2.12/3277667855637184/feed?access_token=667703547059627|9jcnxhpvI5utLKkRzLY0O0559HY";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		$g = curl_exec($ch);

		curl_close($ch);
		$links = json_decode($g);

		//echo "<pre>";
		//print_r($links);
		//echo "</pre>";

		foreach($links->data as $key=>$value){
			$url2 = "https://graph.facebook.com/".$value->id."/attachments?access_token=667703547059627|9jcnxhpvI5utLKkRzLY0O0559HY";

			$ch2 = curl_init();
			curl_setopt($ch2, CURLOPT_URL, $url2);
			curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 30);
			$g2 = curl_exec($ch2);

			curl_close($ch2);
			$links2 = json_decode($g2);
			$post2 = $links2->data;
			$img="";
			$link="";

			if(isset($post2[0]->subattachments->data[0]->media->image->src)){
				$img = $post2[0]->subattachments->data[0]->media->image->src;
				$link = $post2[0]->subattachments->data[0]->target->url;
				$text = $post2[0]->subattachments->data[0]->title;
			}else if(isset($post2[0]->media->image->src)){
				$img = $post2[0]->media->image->src;
				$link = $post2[0]->target->url;
				$text = $post2[0]->subattachments->data[0]->title;
			}

			$id = $value->id;
			$date = substr( $value->created_time , 0 , 10 );
			$type = "페이스북";

			/* 수집하는 데이터의 링크가 기존 데이터에 존재하지 않을때만 insert */
			$select_sql = "SELECT COUNT(*) as cnt FROM T_BOARD WHERE B_LINKURL='{$link}' AND B_SITE='페이스북'";
			$row = sql_fetch($select_sql);

			if($row[cnt]=="" || $row[cnt]=="0") {	
				$insert_sql = "INSERT INTO T_BOARD(B_CODE,B_TITLE,B_CONT,B_TYPE,B_SITE,B_HITS,B_NOTI_YN,B_EXPS_YN,B_FILE2,B_WRITER,B_REGDATE,B_LINKURL,B_SYSFILE2) VALUES('nsmpr','페이스북 소식','{$text}','보도기사','{$type}','0','N','N','{$img}','admin','{$date} 00:00:00','{$link}','{$img}')";
				sql_query($insert_sql);
			}
		}
	}
	facebook_post();		//페이스북 수집 실행
?>

<script type="text/javascript">
	alert("수집 완료했습니다.");
	location.href="../page/post.php?bc_code=nsmpr";
</script>
