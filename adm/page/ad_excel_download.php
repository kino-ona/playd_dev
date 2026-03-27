<?php
include_once('./_common.php');

require_once P1_LIB_PATH."/phpexcel/PHPExcel.php"; // PHPExcel.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.
require_once P1_LIB_PATH."/phpexcel/PHPExcel/IOFactory.php"; // IOFactory.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.

$search_type = trim($_POST['search_type']);
$search_txt  = trim($_POST['search_txt']);
$re_yn  = trim($_POST['re_yn']);

$sql_common = " from {$p1['t_ask_table']} ";

$sql_search = " where (1)
                  and a_code = 'nsmad' ";

   
// 검색어
if ($search_txt) {    
    // LIKE 보다 INSTR 속도가 빠름
    if (preg_match("/[a-zA-Z]/", $search_txt))
        $sql_instr = " and INSTR(LOWER(a_{$search_type}), LOWER('{$search_txt}')) ";
    else
        $sql_instr = " and INSTR(a_{$search_type}, '{$search_txt}') ";
    
    switch($search_type) {
        case "all":    # 전체
            $sql_search .= " and (a_title like '%{$search_txt}%' || a_cont like '%{$search_txt}%' || a_name like '%{$search_txt}%') ";
            break;
        case "title_cont":    # 제목+내용
            $sql_search .= " and (a_title like '%{$search_txt}%' || a_cont like '%{$search_txt}%') ";
            break;
        default:
            $sql_search .= $sql_instr;
            break;
    }
}

// 확인여부
if ($re_yn) {
    $sql_search .= " and a_re_yn = '{$re_yn}' ";
}

$sql = " select *
                {$sql_common}
                {$sql_search} order by a_date desc ";
$result = sql_query($sql);


$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];


$objPHPExcel = new PHPExcel();

$title = "광고문의 신청자 내역";
$nCnt = 0;
for ($i=3; $row=sql_fetch_array($result); $i++) {

  
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells("A1:C1")
                ->setCellValue("A1", $title)
                ->setCellValue("A2", "번호")
                ->setCellValue("B2", "등록일자")
                ->setCellValue("C2", "회사명")
                ->setCellValue("D2", "담당자 성함")
                ->setCellValue("E2", "연락처")
                ->setCellValue("F2", "이메일")
                ->setCellValue("G2", "URL")
                ->setCellValue("H2", "프로젝트 유형")
                ->setCellValue("I2", "목적")
                ->setCellValue("J2", "예산");
  
    
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$i",  $total_count - $nCnt)
                ->setCellValue("B$i", date("Y-m-d", strtotime($row['A_DATE'])))
                ->setCellValue("C$i", Decrypt($row['A_CORP_NAME']))
                ->setCellValue("D$i", Decrypt($row['A_NAME']))
                ->setCellValue("E$i", Decrypt($row['A_TEL']))
                ->setCellValue("F$i", Decrypt($row['A_MAIL']))
                ->setCellValue("G$i", $row['A_URL'])
                ->setCellValue("H$i", $row['A_TYPE'])
                ->setCellValue("I$i", $row['A_TYPE_GUBUN2'])
                ->setCellValue("J$i", $row['A_TYPE_GUBUN1']);

    $nCnt++;
}

if ($i == 3) alert("신청내역이 없습니다.", "/adm/page/qna.php?bc_code=nsmad");

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 
// 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
$filename = iconv("UTF-8", "EUC-KR", $title);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$title.".xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

exit;
?>