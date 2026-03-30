-- 로컬 Docker MariaDB 최초 기동 시에만 적용됩니다(데이터 볼륨이 비어 있을 때).
-- 운영 DB 덤프를 넣었다면 이 파일을 삭제/비운 뒤 `docker compose down -v` 로 볼륨을 새로 만들거나,
-- 덤프 import 후에는 수동으로 이 시드를 적용하지 마세요.
--
-- 테스트 계정:  아이디 admin / 비밀번호 admin  (로컬 전용, 반드시 변경)

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `T_MGR` (
  `M_SEQ` int(11) NOT NULL AUTO_INCREMENT,
  `M_ID` varchar(50) NOT NULL,
  `M_PW` varchar(255) NOT NULL,
  `M_USE_YN` char(1) NOT NULL DEFAULT 'Y',
  `M_AUTH_TP` char(1) NOT NULL DEFAULT '1',
  `G_SEQ` int(11) DEFAULT 0,
  `M_MAIL` varchar(200) DEFAULT NULL,
  `M_NAME` varchar(100) DEFAULT NULL,
  `M_PHONE` varchar(50) DEFAULT NULL,
  `M_REGDATE` datetime DEFAULT NULL,
  `M_UREGDATE` datetime DEFAULT NULL,
  `PASSWD_FAIL_CNT` int(11) DEFAULT 0,
  `PASSWD_CHG_DT` datetime DEFAULT NULL,
  `PASSWD_OLD` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`M_SEQ`),
  UNIQUE KEY `uk_m_id` (`M_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `T_MGR_LOG` (
  `ML_IDX` bigint(20) NOT NULL AUTO_INCREMENT,
  `ML_MODE` varchar(50) DEFAULT NULL,
  `ML_PATH` varchar(500) DEFAULT NULL,
  `ML_LINK` varchar(500) DEFAULT NULL,
  `ML_IP` varchar(45) DEFAULT NULL,
  `M_ID` varchar(50) DEFAULT NULL,
  `M_SEQ` int(11) DEFAULT NULL,
  `ML_REG` datetime DEFAULT NULL,
  PRIMARY KEY (`ML_IDX`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- mysql_native_password (PHP sql_password / MariaDB PASSWORD() 와 동일한 41바이트 형식)
-- 비밀번호: admin
INSERT INTO `T_MGR` (`M_ID`, `M_PW`, `M_USE_YN`, `M_AUTH_TP`, `G_SEQ`, `M_MAIL`, `M_NAME`, `PASSWD_FAIL_CNT`, `PASSWD_CHG_DT`, `PASSWD_OLD`, `M_REGDATE`)
SELECT 'admin', '*4ACFE3202A5FF5CF467898FC58AAB1D615029441', 'Y', '1', 0, 'admin@local.test', 'Local Dev', 0, DATE_ADD(NOW(), INTERVAL 90 DAY), '*4ACFE3202A5FF5CF467898FC58AAB1D615029441', NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `T_MGR` WHERE `M_ID` = 'admin');
