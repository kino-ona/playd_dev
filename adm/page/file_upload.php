<?php
include_once('./_common.php');

$folder=(!$folder)?date("Ym"):$folder;


$sql_common = " from T_IMAGE ";
$sql_search = " where  I_FOLDER ='$folder' and I_DEL_YN ='N'  ";

$sql_order = " order by I_REGDATE desc ";
if ($sst) {
    $sql_order = " order by {$sst} {$sod} ";
}

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 레코드 번호 매김
$bnum = $total_count - ($rows * ($page - 1)); # 역순 (10 ~ 1)
// $bnum = ($page - 1) * $rows + 1; # 순번 (1 ~ 10)

$sql = " select *
                {$sql_common}
                {$sql_search}
                {$sql_order}
          limit {$from_record}, {$rows} ";


$result = sql_query($sql);


$p1['title']    = '';
$p1['subtitle'] = '파일 업로드';
include_once('./_head.php');

$colspan = 4;
?>

<script type="text/javascript">
	$(document).ready(function(){
		var result = "";
		if(result == "fail"){
			alert("다시 시도해주세요.");
		}
		
		// 최저 최고 연식 뿌려주기
		var option = "";
		for (var i = new Date().getFullYear()+1; i > 2009; i--) {
			for(var j = 12; j >= 1; j--){
				var month = "";
				if(j <= 9){
					month = "0"+j;
				}else{
					month = j;
				}
				option = '<option value="'+i+''+month+'">'+i+''+month+'</option>';
				$('#select_dates').append(option);
			}
		}

		autoSelect("select_dates","<?=$folder?>");
		$("#select_dates").change(function(){
//			document.f1.action = "/adm/page/file_upload.php";
//			document.f1.submit();
			document.location.href = "/adm/page/file_upload.php?folder="+ $("#select_dates").val();
		});
	});
	
	function upload(_this){
		if($("#file_upload").val() == ''){
			alert("업로드 할 이미지를 선택하세요.");
			return;
		}


		var f = _this.form;
		var token = get_ajax_token();
		console.log(token);
		if(!token) {
			alert("토큰 정보가 올바르지 않습니다.");
			return false;
		}

		var $f = $(f);

		if(typeof f.token === "undefined")
			$f.prepend('<input type="hidden" name="token" value="">');

		$f.find("input[name=token]").val(token);

		document.f1.action = "/adm/page/file_upload_insert.php";
		document.f1.submit();
	}
	
	function del(seq){
		if(confirm("삭제 하시겠습니까?")){
			$("#seq").val(seq);
			$("#del_folder").val($("#select_dates").val());
			document.f2.action = "/adm/page/file_upload_insert.php";
			document.f2.submit();
		}
	}
</script>



	<form method="post" action="" name="f1" id="f1" enctype="multipart/form-data">
		<!-- search -->
		<div class="pageCount">
			<fieldset>
				<legend class="hid1">검색 테이블</legend>
				<div class="oh">
					<div class="fl">
						<label for="select_dates" class="MR10">
							업로드 폴더 선택
						</label>
						<select id="select_dates" name="folder"></select>
					</div>
					<div class="fr">
						<label for="file_upload" class="fl MR10"> 
							파일 업로드
						</label>
						<input type="file" id="file_upload" name="attach" class="fl" value="" />
						
						<div class="acButton dib fl ML10">
							<button class="btn btn-success" type="button" onclick="upload(this)">업로드</button>
						</div>
					</div>
				</div>
			</fieldset>
		</div>
	</form>

	<form method="post" action="" name="f2" id="f2" enctype="multipart/form-data">
		<input type="hidden" name="seq" id="seq" value="" />
		<input type="hidden" id="del_folder" name="folder" value="" />
		<input type="hidden" name="m" value="u" />
		<fieldset>
			<legend class="hid1">팝업 등록 및 수정</legend>
			<table class="colTable">
				<colgroup>
					<col width="320" />
					<col width="500" />
				</colgroup>
				<tr>
					<th>파일</th>
					<th>경로</th>
				</tr>
<?
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            $date_txt = passing_time($row['NS_REGDATE']);
?>
				<tr>
					<td>
						<div class="file_img"><img src="<?=$row[I_FILE]?>?>" alt="업로드 이미지" /></div>
					</td>
					<td>
						<div class="oh">
							<div><?=$_SERVER['HTTP_HOST']?><?=$row[I_FILE]?></div>
							<div class="acButton MT10 al">
								<button class="btn btn-inverse" type="button" onclick="del('<?=$row[I_SEQ]?>')">삭제</button>
							</div>
						</div>
					</td>
				</tr>
<?}?>


			</table>
		</fieldset>
	</form>

<!-- pagination -->
<?php echo get_paging($rows, $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<?php
include_once('./_tail.php');
?>