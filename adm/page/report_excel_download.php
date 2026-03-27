<?php
include_once('./_common.php');

require_once P1_LIB_PATH."/phpexcel/PHPExcel.php"; // PHPExcel.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.
require_once P1_LIB_PATH."/phpexcel/PHPExcel/IOFactory.php"; // IOFactory.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.

$search_type = trim($_POST['search_type']);
$search_txt  = trim($_POST['search_txt']);

$sql_common = " from T_NEWSREPORT ";

$sql_search = " where (1) ";

// 검색어
if ($search_txt) {    
    // LIKE 보다 INSTR 속도가 빠름
    if (preg_match("/[a-zA-Z]/", $search_txt))
        $sql_instr = " and INSTR(LOWER(ns_{$search_type}), LOWER('{$search_txt}')) ";
    else
        $sql_instr = " and INSTR(ns_{$search_type}, '{$search_txt}') ";
    
    switch($search_type) {
        default:
            $sql_search .= $sql_instr;
            break;
    }
}

$sql = " select *
                {$sql_common}
                {$sql_search} ";
$result = sql_query($sql);

$objPHPExcel = new PHPExcel();

$title = "리포트 신청자 내역";

for ($i=3; $row=sql_fetch_array($result); $i++) {
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells("A1:C1")
                ->setCellValue("A1", $title)
                ->setCellValue("A2", "번호")
                ->setCellValue("B2", "다운받은 리포트 명")
                ->setCellValue("C2", "이름")
                ->setCellValue("D2", "직업")
                ->setCellValue("E2", "이메일")
                ->setCellValue("F2", "직급")
                ->setCellValue("G2", "회사명(소속)")
                ->setCellValue("H2", "부서(팀명)")
                ->setCellValue("I2", "개인정보 수집 동의 여부")
                ->setCellValue("J2", "마케팅 정보 수집 동의 여부")
                ->setCellValue("K2", "신청일");
                
    // A1열 폰트 설정
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1")
                ->applyFromArray(
                    array(
                        'font' => array(
                            'bold' => 'true',
                            'size' => '20'
                        )
                    )
                )
                ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                
    foreach(range('A', 'J') as $k => $cell) {
        // A2~C2열 배경색 지정
        $objPHPExcel->setActiveSheetIndex(0)->getStyle($cell."2")
                    ->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb'=>'DCE6F1'),
                            )
                        )
                    )
                    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        // 보더 줄 지정 및 중간정렬
        $objPHPExcel->setActiveSheetIndex(0)->getStyle($cell.$i)
                    ->applyFromArray(
                        array(
                            'borders' => array(
                                'outline' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                                    'color' => array('argb'=>'000000')
                                )
                            )
                        )
                    )
                    ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    
        // 자동 열너비 지정 
        // $objPHPExcel->getActiveSheet()->getColumnDimension($cell)->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension($cell)->setWidth(30);
    }

    $seqTitle = '';
    $row2 = sql_fetch(" select B_TITLE from T_BOARD where B_SEQ = '".$row['NS_B_SEQ']."' ");
    $seqTitle = $row2['B_TITLE'];

    
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$i", ($i-2))
                ->setCellValue("B$i", $seqTitle)
                ->setCellValue("C$i", $row['NS_NAME'])
                ->setCellValue("D$i", $row['NS_JIKUP'])
                ->setCellValue("E$i", $row['NS_MAIL'])
                ->setCellValue("F$i", $row['NS_JIKLV'])
                ->setCellValue("G$i", $row['NS_COMPANY'])
                ->setCellValue("H$i", $row['NS_DIV'])
                ->setCellValue("I$i", 'O')
                ->setCellValue("J$i", $row['NS_MARKETING']=='Y'?'O':'X')
                ->setCellValue("K$i", $row['NS_REGDATE']);
}

if ($i == 3) alert("신청내역이 없습니다.", "./newsreport.php");

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