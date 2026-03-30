-- 관리자 그룹·메뉴 권한 (administration*.php, head.php, login_check.php)
-- 데이터 볼륨이 이미 있으면 자동 적용되지 않습니다. 필요 시 수동 실행:
--   docker compose exec -T mysql mysql -uroot -proot playd < docker/mysql/init/02-playd-dev-group.sql

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS `T_GROUP` (
  `G_SEQ` int(11) NOT NULL AUTO_INCREMENT,
  `G_NAME` varchar(200) NOT NULL,
  `G_REGDATE` datetime DEFAULT NULL,
  PRIMARY KEY (`G_SEQ`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `T_GROUP_AUTH` (
  `G_SEQ` int(11) NOT NULL,
  `G_MENU` varchar(20) NOT NULL,
  `G_AUTH_READ` char(1) NOT NULL DEFAULT 'N',
  `G_AUTH_WRITE` char(1) NOT NULL DEFAULT 'N',
  `G_AUTH_DEL` char(1) NOT NULL DEFAULT 'N',
  `G_REGDATE` datetime DEFAULT NULL,
  PRIMARY KEY (`G_SEQ`, `G_MENU`),
  KEY `idx_t_group_auth_g_seq` (`G_SEQ`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
