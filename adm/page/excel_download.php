<?php
include_once('./_common.php');

require_once P1_LIB_PATH."/phpexcel/PHPExcel.php"; // PHPExcel.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.
require_once P1_LIB_PATH."/phpexcel/PHPExcel/IOFactory.php"; // IOFactory.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.

$s_seq = trim($_POST['s_seq']);
$title = trim($_POST['s_field']);
$mode  = trim($_POST['mode']);

if ($mode == "p") {
    $sql_common = " from {$p1['t_support_view_table']} ";
    $goto_url   = "./incident_view.php?seq=".$s_seq;
}

if ($mode == "m") {
    $sql_common = " from {$p1['mable_t_support_view_table']} ";
    $goto_url   = "./m_incident_view.php?seq=".$s_seq;
}

if ($mode == "pl") {
    $sql_common = " from {$p1['t_pool_view_table']} ";
    $goto_url   = "./pl_incident_view.php?seq=".$s_seq;
}

$sql_search = " where sv_code = '{$s_seq}' ";

$sql = " select *
                {$sql_common}
                {$sql_search} ";
$result = sql_query($sql);

$objPHPExcel = new PHPExcel();

for ($i=3; $row=sql_fetch_array($result); $i++) {
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
                ->mergeCells("A1:E1")
                ->setCellValue("A1", $title)
                ->setCellValue("A2", "번호")
                ->setCellValue("B2", "이름")
                ->setCellValue("C2", "내/외국인")
                ->setCellValue("D2", "이메일")
                ->setCellValue("E2", "생년월일");
                
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
                
    foreach(range('A', 'E') as $k => $cell) {
        // A2~E2열 배경색 지정
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
    
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$i", ($i-2))
                ->setCellValue("B$i", $row['SV_NAME'])
                ->setCellValue("C$i", $row['SV_STATE'])
                ->setCellValue("D$i", $row['SV_MAIL'])
                ->setCellValue("E$i", $row['SV_BIRTH']);
}

if ($i == 3) alert("지원내역이 없습니다.", $goto_url);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 
// 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
//$filename = iconv("UTF-8", "EUC-KR", $title);

// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment;filename=".$title.".xls");
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

exit;
?>