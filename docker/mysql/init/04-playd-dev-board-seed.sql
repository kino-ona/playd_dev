-- 사이드 메뉴에서 쓰는 게시판 코드별 T_BOARD_CONFIG 기본 행 (로컬 전용)
-- 테이블은 03-playd-dev-content-tables.sql 에서 생성됩니다.
-- 수동 적용: docker compose exec -T mysql mysql -uroot -proot playd < docker/mysql/init/04-playd-dev-board-seed.sql

SET NAMES utf8mb4;

INSERT INTO `T_BOARD_CONFIG` (
  `BC_CODE`, `BC_NAME`, `BC_UPLOAD_NM`, `BC_GROUP`, `BC_TYPE`, `BC_SKIN`, `BC_EDITOR`,
  `BC_ROWS`, `BC_PAGES_ROWS`,
  `BC_SITE_USE_YN`, `BC_EXPS_USE_YN`, `BC_TYPE_USE_YN`, `BC_NOTI_USE_YN`, `BC_DATE_USE_YN`,
  `BC_CORP_NAME_USE_YN`, `BC_NAME_USE_YN`, `BC_SHARE_USE_YN`,
  `BC_REG_DTTM`, `BC_USE_YN`
)
SELECT 'playdportfolio','포트폴리오','playdportfolio','a','post','portfolio','',
  10, 10, '1','1','1','1','0','0','0','0', NOW(), '1'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `T_BOARD_CONFIG` WHERE `BC_CODE` = 'playdportfolio');

INSERT INTO `T_BOARD_CONFIG` (
  `BC_CODE`, `BC_NAME`, `BC_UPLOAD_NM`, `BC_GROUP`, `BC_TYPE`, `BC_SKIN`, `BC_EDITOR`,
  `BC_ROWS`, `BC_PAGES_ROWS`,
  `BC_SITE_USE_YN`, `BC_EXPS_USE_YN`, `BC_TYPE_USE_YN`, `BC_NOTI_USE_YN`, `BC_DATE_USE_YN`,
  `BC_CORP_NAME_USE_YN`, `BC_NAME_USE_YN`, `BC_SHARE_USE_YN`,
  `BC_REG_DTTM`, `BC_USE_YN`
)
SELECT 'nsmnw','뉴스레터','nsmnw','a','post','newsletter','',
  10, 10, '1','1','0','1','0','0','0','0', NOW(), '1'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `T_BOARD_CONFIG` WHERE `BC_CODE` = 'nsmnw');

INSERT INTO `T_BOARD_CONFIG` (
  `BC_CODE`, `BC_NAME`, `BC_UPLOAD_NM`, `BC_GROUP`, `BC_TYPE`, `BC_SKIN`, `BC_EDITOR`,
  `BC_ROWS`, `BC_PAGES_ROWS`,
  `BC_SITE_USE_YN`, `BC_EXPS_USE_YN`, `BC_TYPE_USE_YN`, `BC_NOTI_USE_YN`, `BC_DATE_USE_YN`,
  `BC_CORP_NAME_USE_YN`, `BC_NAME_USE_YN`, `BC_SHARE_USE_YN`,
  `BC_REG_DTTM`, `BC_USE_YN`
)
SELECT 'report','리포트','report','a','post','report','',
  10, 10, '1','1','0','1','0','0','0','0', NOW(), '1'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `T_BOARD_CONFIG` WHERE `BC_CODE` = 'report');

INSERT INTO `T_BOARD_CONFIG` (
  `BC_CODE`, `BC_NAME`, `BC_UPLOAD_NM`, `BC_GROUP`, `BC_TYPE`, `BC_SKIN`, `BC_EDITOR`,
  `BC_ROWS`, `BC_PAGES_ROWS`,
  `BC_SITE_USE_YN`, `BC_EXPS_USE_YN`, `BC_TYPE_USE_YN`, `BC_NOTI_USE_YN`, `BC_DATE_USE_YN`,
  `BC_CORP_NAME_USE_YN`, `BC_NAME_USE_YN`, `BC_SHARE_USE_YN`,
  `BC_REG_DTTM`, `BC_USE_YN`
)
SELECT 'nsmexp','광고컬럼','nsmexp','a','post','ad','',
  10, 10, '1','1','0','1','0','0','0','0', NOW(), '1'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `T_BOARD_CONFIG` WHERE `BC_CODE` = 'nsmexp');

INSERT INTO `T_BOARD_CONFIG` (
  `BC_CODE`, `BC_NAME`, `BC_UPLOAD_NM`, `BC_GROUP`, `BC_TYPE`, `BC_SKIN`, `BC_EDITOR`,
  `BC_ROWS`, `BC_PAGES_ROWS`,
  `BC_SITE_USE_YN`, `BC_EXPS_USE_YN`, `BC_TYPE_USE_YN`, `BC_NOTI_USE_YN`, `BC_DATE_USE_YN`,
  `BC_CORP_NAME_USE_YN`, `BC_NAME_USE_YN`, `BC_SHARE_USE_YN`,
  `BC_REG_DTTM`, `BC_USE_YN`
)
SELECT 'playdprivate','개인정보처리방침','playdprivate','a','post','file','',
  10, 10, '1','1','0','1','0','0','0','0', NOW(), '1'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `T_BOARD_CONFIG` WHERE `BC_CODE` = 'playdprivate');

INSERT INTO `T_BOARD_CONFIG` (
  `BC_CODE`, `BC_NAME`, `BC_UPLOAD_NM`, `BC_GROUP`, `BC_TYPE`, `BC_SKIN`, `BC_EDITOR`,
  `BC_ROWS`, `BC_PAGES_ROWS`,
  `BC_SITE_USE_YN`, `BC_EXPS_USE_YN`, `BC_TYPE_USE_YN`, `BC_NOTI_USE_YN`, `BC_DATE_USE_YN`,
  `BC_CORP_NAME_USE_YN`, `BC_NAME_USE_YN`, `BC_SHARE_USE_YN`,
  `BC_REG_DTTM`, `BC_USE_YN`
)
SELECT 'ir_notice','전자공고','ir_notice','a','post','basic','',
  10, 10, '1','1','0','1','0','0','0','0', NOW(), '1'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `T_BOARD_CONFIG` WHERE `BC_CODE` = 'ir_notice');

INSERT INTO `T_BOARD_CONFIG` (
  `BC_CODE`, `BC_NAME`, `BC_UPLOAD_NM`, `BC_GROUP`, `BC_TYPE`, `BC_SKIN`, `BC_EDITOR`,
  `BC_ROWS`, `BC_PAGES_ROWS`,
  `BC_SITE_USE_YN`, `BC_EXPS_USE_YN`, `BC_TYPE_USE_YN`, `BC_NOTI_USE_YN`, `BC_DATE_USE_YN`,
  `BC_CORP_NAME_USE_YN`, `BC_NAME_USE_YN`, `BC_SHARE_USE_YN`,
  `BC_REG_DTTM`, `BC_USE_YN`
)
SELECT 'pr_notice','공시정보','pr_notice','a','post','basic','',
  10, 10, '1','1','0','1','0','0','0','0', NOW(), '1'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `T_BOARD_CONFIG` WHERE `BC_CODE` = 'pr_notice');

INSERT INTO `T_BOARD_CONFIG` (
  `BC_CODE`, `BC_NAME`, `BC_UPLOAD_NM`, `BC_GROUP`, `BC_TYPE`, `BC_SKIN`, `BC_EDITOR`,
  `BC_ROWS`, `BC_PAGES_ROWS`,
  `BC_SITE_USE_YN`, `BC_EXPS_USE_YN`, `BC_TYPE_USE_YN`, `BC_NOTI_USE_YN`, `BC_DATE_USE_YN`,
  `BC_CORP_NAME_USE_YN`, `BC_NAME_USE_YN`, `BC_SHARE_USE_YN`,
  `BC_REG_DTTM`, `BC_USE_YN`
)
SELECT 'files','파일 업로드','files','a','post','file','',
  10, 10, '1','1','0','1','0','0','0','0', NOW(), '1'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `T_BOARD_CONFIG` WHERE `BC_CODE` = 'files');

INSERT INTO `T_BOARD_CONFIG` (
  `BC_CODE`, `BC_NAME`, `BC_UPLOAD_NM`, `BC_GROUP`, `BC_TYPE`, `BC_SKIN`, `BC_EDITOR`,
  `BC_ROWS`, `BC_PAGES_ROWS`,
  `BC_SITE_USE_YN`, `BC_EXPS_USE_YN`, `BC_TYPE_USE_YN`, `BC_NOTI_USE_YN`, `BC_DATE_USE_YN`,
  `BC_CORP_NAME_USE_YN`, `BC_NAME_USE_YN`, `BC_SHARE_USE_YN`,
  `BC_REG_DTTM`, `BC_USE_YN`
)
SELECT 'nsmad','광고문의','nsmad','b','qna','qna_basic','',
  10, 10, '1','1','0','1','0','0','0','0', NOW(), '1'
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `T_BOARD_CONFIG` WHERE `BC_CODE` = 'nsmad');
