<?php
include_once('./_common.php');
include_once('./_admin.php');


$create_query = " create table T_IP ( ";
$create_query .= " seq int not null auto_increment, ";
$create_query .= " IP varchar(100), ";
$create_query .= " REG_DT datetime, ";
$create_query .= " REG_NAME varchar(100), ";
$create_query .= " primary key(seq) ";
$create_query .= " ) ";

sql_fetch($create_query);



?>