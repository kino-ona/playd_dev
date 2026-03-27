<?php
include_once('./_common.php');

// 포트폴리오 카테고리 테이블 만들기
$query = "CREATE TABLE `T_POST_CATEGORY` ( ";
$query .= "  `C_SEQ` int NOT NULL AUTO_INCREMENT, ";
$query .= "   `C_NAME` varchar(100) DEFAULT NULL COMMENT '카테고리명', ";
$query .= "    `C_USE` char(1) DEFAULT '1' COMMENT 'Y:사용/N:미사용', ";
$query .= "    `C_DATE` datetime DEFAULT NULL, ";
$query .= "    `C_SORT` int DEFAULT 0, ";
$query .= "    PRIMARY KEY (`C_SEQ`) USING BTREE ";
$query .= "  ) ";
sql_query($query);


// $query = " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES ('IT/통신','Y',NULL,21); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES ('가전/전기','Y',NULL,22); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES ('가정/생활','Y',NULL,20); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES ('건축/인테리어','Y',NULL,19); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES ('결혼/출산/육아','Y',NULL,18); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES ('관공서/단체','Y',NULL,17); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (7,'교육','Y',NULL,16); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (8,'금융/보험','Y',NULL,15); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (9,'레저/취미','Y',NULL,13); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (10,'문화/예술/게임','Y',NULL,12); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (21,'부동산','Y',NULL,11); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (22,'산업 자원','Y',NULL,10); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (23,'식품/음료','Y',NULL,9); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (24,'여행/교통','Y',NULL,8); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (25,'의료/건강','Y',NULL,2); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (26,'의류/패션잡화','Y',NULL,5); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (27,'화장품/미용','Y',NULL,7); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (28,'자동차','Y',NULL,3); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (29,'전문 서비스','Y',NULL,1); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (30,'종합쇼핑','Y',NULL,0); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (31,'취업','Y',NULL,6); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (32,'인쇄/문구/사무기기','Y',NULL,4); ";
// $query .= " INSERT INTO `` (`C_SEQ`,`C_NAME`,`C_USE`,`C_DATE`,`C_SORT`) VALUES (33,'기타','Y',NULL,14); "
// sql_query($query);


$query  = " ALTER TABLE `T_BOARD` ADD COLUMN `B_EXT7` VARCHAR(200) NULL ";
sql_query($query);

$query  = " ALTER TABLE `T_BOARD` ADD COLUMN `B_FILE6` VARCHAR(100) NULL ";
sql_query($query);



?>