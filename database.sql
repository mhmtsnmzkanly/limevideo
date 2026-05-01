-- --------------------------------------------------------
-- LimeVideo Platform - Nihai Veritabanı Şeması
-- --------------------------------------------------------

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. KULLANICILAR (Benzersiz ID: char(8))
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` char(8) NOT NULL,
  `username` varchar(50) NOT NULL,
  `display_name` varchar(80) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `cover_url` varchar(255) DEFAULT NULL,
  `status` enum('active', 'pending', 'disabled', 'banned', 'deleted') DEFAULT 'active',
  `is_banned` tinyint(1) NOT NULL DEFAULT 0,
  `ban_reason` varchar(255) DEFAULT NULL,
  `ban_ends_at` datetime DEFAULT NULL,
  `banned_by` char(8) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_ban_status` (`status`,`is_banned`,`ban_ends_at`),
  KEY `idx_users_banned_by` (`banned_by`),
  CONSTRAINT `fk_users_banned_by` FOREIGN KEY (`banned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ACTION BANS
DROP TABLE IF EXISTS `bans`;
CREATE TABLE `bans` (
  `id` char(12) NOT NULL,
  `user_id` char(8) NOT NULL,
  `type` enum('general','comment','video','chat') NOT NULL,
  `reason` text NOT NULL,
  `starts_at` datetime NOT NULL DEFAULT current_timestamp(),
  `ends_at` datetime DEFAULT NULL,
  `banned_by_type` enum('system','user') NOT NULL DEFAULT 'system',
  `banned_by_user_id` char(8) DEFAULT NULL,
  `revoked_at` datetime DEFAULT NULL,
  `revoked_by_user_id` char(8) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_bans_user_active` (`user_id`,`type`,`revoked_at`,`starts_at`,`ends_at`),
  KEY `idx_bans_ends_at` (`ends_at`),
  KEY `idx_bans_banned_by` (`banned_by_user_id`),
  KEY `idx_bans_revoked_by` (`revoked_by_user_id`),
  CONSTRAINT `fk_bans_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bans_banned_by_user` FOREIGN KEY (`banned_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_bans_revoked_by_user` FOREIGN KEY (`revoked_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. VİDEOLAR (Benzersiz ID: char(12))
DROP TABLE IF EXISTS `video_data`;
DROP TABLE IF EXISTS `videos`;
CREATE TABLE `videos` (
  `id` char(12) NOT NULL,
  `user_id` char(8) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) DEFAULT 0,
  `views_count` bigint(20) unsigned NOT NULL DEFAULT 0,
  `is_sensitive` tinyint(1) DEFAULT 0,
  `disable_comments` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('public', 'private', 'deleted') DEFAULT 'public',
  `storage_type` enum('internal','external') NOT NULL DEFAULT 'internal',
  `provider` varchar(50) DEFAULT NULL,
  `provider_asset_id` varchar(120) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `playback_url` varchar(500) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `thumbnail_url` varchar(500) DEFAULT NULL,
  `playback_mode` enum('direct','external_page') NOT NULL DEFAULT 'direct',
  `processing_status` enum('pending','processing','ready','failed') NOT NULL DEFAULT 'ready',
  `metadata` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_videos_status_created` (`status`,`created_at`),
  KEY `idx_videos_user_status_created` (`user_id`,`status`,`created_at`),
  KEY `idx_videos_storage_processing` (`storage_type`,`processing_status`),
  KEY `idx_videos_provider_asset` (`provider`,`provider_asset_id`),
  CONSTRAINT `fk_video_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. LİSTELER (Benzersiz ID: char(8))
DROP TABLE IF EXISTS `lists`;
CREATE TABLE `lists` (
  `id` char(8) NOT NULL,
  `user_id` char(8) NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_sensitive` tinyint(1) DEFAULT 0,
  `status` enum('public', 'private', 'deleted') DEFAULT 'public',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_lists_user_status_created` (`user_id`,`status`,`created_at`),
  CONSTRAINT `fk_list_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. YORUMLAR (Benzersiz ID: char(10))
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` char(10) NOT NULL,
  `user_id` char(8) NOT NULL,
  `target_id` char(12) NOT NULL,
  `parent_id` char(10) DEFAULT NULL,
  `body` text NOT NULL,
  `status` enum('active', 'pending', 'hidden', 'deleted') DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_comments_target_parent_status_created` (`target_id`,`parent_id`,`status`,`created_at`),
  KEY `idx_comments_user_status_created` (`user_id`,`status`,`created_at`),
  CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comment_video` FOREIGN KEY (`target_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_comment_parent` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. OYLAR
DROP TABLE IF EXISTS `votes`;
CREATE TABLE `votes` (
  `voter_user_id` char(8) NOT NULL,
  `target_type` enum('video','comment') NOT NULL DEFAULT 'video',
  `target_id` varchar(12) NOT NULL,
  `vote_type` enum('up', 'down') NOT NULL,
  `voted_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`voter_user_id`, `target_type`, `target_id`),
  KEY `idx_votes_target` (`target_type`,`target_id`),
  KEY `idx_votes_voter_time` (`voter_user_id`,`voted_at`),
  CONSTRAINT `fk_vote_voter` FOREIGN KEY (`voter_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. KAYDEDİLENLER (Savings)
DROP TABLE IF EXISTS `savings`;
CREATE TABLE `savings` (
  `user_id` char(8) NOT NULL,
  `video_id` char(12) NOT NULL,
  `saved_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`, `video_id`),
  KEY `idx_savings_video` (`video_id`),
  CONSTRAINT `fk_save_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_save_video` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. LİSTE ÖĞELERİ
DROP TABLE IF EXISTS `list_items`;
CREATE TABLE `list_items` (
  `list_id` char(8) NOT NULL,
  `video_id` char(12) NOT NULL,
  `added_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`list_id`, `video_id`),
  KEY `idx_list_items_video` (`video_id`),
  CONSTRAINT `fk_li_list` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_li_video` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. TAGS
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  PRIMARY KEY (`slug`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. VIDEO TAGS (Map)
DROP TABLE IF EXISTS `video_tags`;
CREATE TABLE `video_tags` (
  `video_id` char(12) NOT NULL,
  `tag_slug` varchar(50) NOT NULL,
  PRIMARY KEY (`video_id`, `tag_slug`),
  KEY `idx_video_tags_tag_video` (`tag_slug`,`video_id`),
  CONSTRAINT `fk_vt_video` FOREIGN KEY (`video_id`) REFERENCES `videos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_vt_tag` FOREIGN KEY (`tag_slug`) REFERENCES `tags` (`slug`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. BİLDİRİMLER
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` char(8) NOT NULL,
  `actor_user_id` char(8) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `target_type` varchar(30) DEFAULT NULL,
  `target_id` varchar(32) DEFAULT NULL,
  `title` varchar(120) NOT NULL,
  `body` text NOT NULL,
  `data` longtext DEFAULT NULL,
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notifications_user_created` (`user_id`,`created_at`),
  KEY `idx_notifications_user_read` (`user_id`,`read_at`,`created_at`),
  CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notifications_actor` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. TAKİPLER
DROP TABLE IF EXISTS `follows`;
CREATE TABLE `follows` (
  `follower_id` char(8) NOT NULL,
  `followed_id` char(8) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`follower_id`,`followed_id`),
  KEY `idx_follows_followed` (`followed_id`),
  CONSTRAINT `fk_follows_follower` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_follows_followed` FOREIGN KEY (`followed_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. UNIFIED ACTIVITY LOGGING SYSTEM
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_type` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `status` enum('SUCCESS','FAILED') NOT NULL DEFAULT 'SUCCESS',
  `actor_type` enum('USER','ADMIN','GUEST','SYSTEM') NOT NULL,
  `actor_id` char(8) DEFAULT NULL,
  `target_type` varchar(30) DEFAULT NULL, -- user, video, comment, etc.
  `target_id` varchar(32) DEFAULT NULL,
  `context` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_log_event` (`event_type`, `action`),
  KEY `idx_log_actor` (`actor_type`, `actor_id`),
  KEY `idx_log_target` (`target_type`, `target_id`),
  KEY `idx_log_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. ANALYTICS EVENTS
DROP TABLE IF EXISTS `analytics_events`;
CREATE TABLE `analytics_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` char(36) NOT NULL,
  `user_id` char(8) DEFAULT NULL,
  `event_type` varchar(50) NOT NULL,
  `page` varchar(50) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `target_type` varchar(50) DEFAULT NULL,
  `target_id` varchar(64) DEFAULT NULL,
  `source` varchar(80) DEFAULT NULL,
  `search_query` varchar(150) DEFAULT NULL,
  `category` varchar(80) DEFAULT NULL,
  `duration_ms` int(10) unsigned DEFAULT NULL,
  `watch_time_ms` int(10) unsigned DEFAULT NULL,
  `video_current_time` decimal(10,3) DEFAULT NULL,
  `video_duration` decimal(10,3) DEFAULT NULL,
  `scroll_depth` tinyint(3) unsigned DEFAULT NULL,
  `viewport` varchar(40) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `ip_hash` char(64) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_analytics_session_created` (`session_id`,`created_at`),
  KEY `idx_analytics_user_created` (`user_id`,`created_at`),
  KEY `idx_analytics_event_created` (`event_type`,`created_at`),
  KEY `idx_analytics_target_created` (`target_type`,`target_id`,`created_at`),
  KEY `idx_analytics_search_created` (`search_query`,`category`,`created_at`),
  CONSTRAINT `fk_analytics_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. ANALYTICS ROLLUPS
DROP TABLE IF EXISTS `analytics_rollups`;
CREATE TABLE `analytics_rollups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bucket_unit` enum('hour','day') NOT NULL,
  `bucket_start` datetime NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `page` varchar(50) DEFAULT NULL,
  `target_type` varchar(50) DEFAULT NULL,
  `target_id` varchar(64) DEFAULT NULL,
  `source` varchar(80) DEFAULT NULL,
  `search_query` varchar(150) DEFAULT NULL,
  `category` varchar(80) DEFAULT NULL,
  `event_count` int(10) unsigned NOT NULL DEFAULT 0,
  `unique_sessions` int(10) unsigned NOT NULL DEFAULT 0,
  `unique_users` int(10) unsigned NOT NULL DEFAULT 0,
  `total_duration_ms` bigint(20) unsigned NOT NULL DEFAULT 0,
  `total_watch_time_ms` bigint(20) unsigned NOT NULL DEFAULT 0,
  `max_scroll_depth` tinyint(3) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_rollups_bucket` (`bucket_unit`,`bucket_start`),
  KEY `idx_rollups_event_bucket` (`event_type`,`bucket_unit`,`bucket_start`),
  KEY `idx_rollups_target_bucket` (`target_type`,`target_id`,`bucket_unit`,`bucket_start`),
  KEY `idx_rollups_search_bucket` (`search_query`,`category`,`bucket_unit`,`bucket_start`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. USER SETTINGS
DROP TABLE IF EXISTS `user_settings`;
CREATE TABLE `user_settings` (
  `user_id` char(8) NOT NULL,
  `autoplay` tinyint(1) NOT NULL DEFAULT 1,
  `show_preroll_ads` tinyint(1) NOT NULL DEFAULT 1,
  `show_popunder_ads` tinyint(1) NOT NULL DEFAULT 1,
  `notify_comments` tinyint(1) NOT NULL DEFAULT 1,
  `notify_follows` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_settings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. REPORTS / MODERATION QUEUE
DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
  `id` char(12) NOT NULL,
  `reporter_id` char(8) DEFAULT NULL,
  `target_type` enum('video','comment','user') NOT NULL,
  `target_id` varchar(32) NOT NULL,
  `reason` varchar(120) NOT NULL,
  `details` text DEFAULT NULL,
  `status` enum('open','reviewing','resolved','dismissed') NOT NULL DEFAULT 'open',
  `reviewed_by` char(8) DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `resolution_note` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_reports_target` (`target_type`,`target_id`),
  KEY `idx_reports_status` (`status`,`created_at`),
  KEY `idx_reports_reviewer` (`reviewed_by`,`reviewed_at`),
  CONSTRAINT `fk_reports_user` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_reports_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. CRON JOBS / BACKGROUND QUEUE
DROP TABLE IF EXISTS `cron_jobs`;
CREATE TABLE `cron_jobs` (
  `id` char(14) NOT NULL,
  `event_type` varchar(80) NOT NULL,
  `target_type` varchar(40) NOT NULL,
  `target_id` varchar(64) NOT NULL,
  `dedupe_key` char(64) DEFAULT NULL,
  `status` enum('pending','working','completed','failed','cancelled') NOT NULL DEFAULT 'pending',
  `priority` smallint(6) NOT NULL DEFAULT 0,
  `attempts` smallint(5) unsigned NOT NULL DEFAULT 0,
  `max_attempts` smallint(5) unsigned NOT NULL DEFAULT 3,
  `available_at` datetime NOT NULL DEFAULT current_timestamp(),
  `locked_by` varchar(80) DEFAULT NULL,
  `locked_at` datetime DEFAULT NULL,
  `locked_until` datetime DEFAULT NULL,
  `payload` json DEFAULT NULL,
  `result` json DEFAULT NULL,
  `last_error` text DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `failed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cron_jobs_dedupe` (`dedupe_key`),
  KEY `idx_cron_jobs_pick` (`status`,`available_at`,`priority`,`created_at`),
  KEY `idx_cron_jobs_lock` (`status`,`locked_until`),
  KEY `idx_cron_jobs_event_status` (`event_type`,`status`,`created_at`),
  KEY `idx_cron_jobs_target` (`target_type`,`target_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. ADMİN AYARLARI (Opsiyonel, eğer gerekirse)

SET FOREIGN_KEY_CHECKS = 1;
