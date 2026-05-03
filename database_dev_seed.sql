SET NAMES utf8mb4;

DELETE FROM cron_jobs WHERE id LIKE 'cron_demo%';
DELETE FROM analytics_rollups WHERE event_type IN ('page_view', 'video_watch') AND source IN ('ui', 'player');
DELETE FROM analytics_events WHERE session_id IN ('00000000-0000-4000-8000-000000000001', '00000000-0000-4000-8000-000000000002');
DELETE FROM activity_logs WHERE context LIKE '%dev-seed%' OR context LIKE '%rep_demo0001%' OR context LIKE '%ban_demo0001%';
DELETE FROM notifications WHERE data LIKE '%dev_seed%' OR data LIKE '%ban_demo0001%' OR data LIKE '%rep_demo0002%';

INSERT INTO users (id, username, display_name, email, password_hash, bio, avatar_url, cover_url, status, is_banned, ban_reason, ban_ends_at, banned_by) VALUES
('u_system', 'limevideo_system', 'LimeVideo System', 'system@limevideo.local', '$2y$12$U7oZG7ZvRTW1pdndxgG.2eM7TTye.l6kr.dg1ZRTpiBg0.6euaQuK', 'Internal LimeVideo system account.', '', '', 'disabled', 0, NULL, NULL, NULL),
('u_demo1', 'limevideo_master', 'LimeVideo Master', 'limevideo_master@example.com', '$2y$12$U7oZG7ZvRTW1pdndxgG.2eM7TTye.l6kr.dg1ZRTpiBg0.6euaQuK', 'Frontend systems, creator tooling and high performance video interfaces.', '', '', 'active', 0, NULL, NULL, NULL),
('u_demo2', 'techvision', 'Tech Vision', 'techvision@example.com', '$2y$12$U7oZG7ZvRTW1pdndxgG.2eM7TTye.l6kr.dg1ZRTpiBg0.6euaQuK', 'Delivery experiments, infrastructure notes and product breakdowns.', '', '', 'active', 0, NULL, NULL, NULL),
('u_banned', 'limited_creator', 'Limited Creator', 'limited@example.com', '$2y$12$U7oZG7ZvRTW1pdndxgG.2eM7TTye.l6kr.dg1ZRTpiBg0.6euaQuK', 'Demo account with active moderation restrictions.', '', '', 'banned', 1, 'Repeated spam during development tests.', DATE_ADD(NOW(), INTERVAL 7 DAY), 'u_system')
ON DUPLICATE KEY UPDATE
display_name = VALUES(display_name),
bio = VALUES(bio),
avatar_url = VALUES(avatar_url),
cover_url = VALUES(cover_url),
status = VALUES(status),
is_banned = VALUES(is_banned),
ban_reason = VALUES(ban_reason),
ban_ends_at = VALUES(ban_ends_at),
banned_by = VALUES(banned_by);

INSERT INTO tags (name, slug) VALUES
('Design', 'design'),
('Frontend', 'frontend'),
('Security', 'security'),
('Streaming', 'streaming'),
('Product', 'product')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO videos (id, user_id, title, description, duration, is_sensitive, disable_comments, status, storage_type, file_path, thumbnail_path, processing_status) VALUES
('globalchat01', 'u_system', 'Global Chat', 'Hidden LimeVideo community chat channel.', 0, 0, 0, 'private', 'internal', '', '', 'ready'),
('v_demo001', 'u_demo1', 'LimeVideo UI Design System', 'A compact walkthrough of the LimeVideo interface language.', 320, 0, 0, 'public', 'internal', 'demo-001.mp4', '', 'ready'),
('v_demo002', 'u_demo1', 'Modern SPA Routing Notes', 'Path based routing and API-backed rendering in a small video app.', 735, 0, 0, 'public', 'internal', 'demo-002.mp4', '', 'ready'),
('v_demo003', 'u_demo2', 'Secure Creator Workflows', 'Security patterns for creator platforms and account flows.', 545, 1, 0, 'public', 'internal', 'demo-003.mp4', '', 'ready'),
('v_demo004', 'u_demo2', '60 FPS Streaming Setup', 'A practical look at playback, buffering and video delivery.', 905, 0, 0, 'public', 'internal', 'demo-004.mp4', '', 'ready'),
('v_demo005', 'u_demo1', 'Product Feed Cleanup', 'Turning a crowded homepage into a focused discovery feed.', 410, 0, 0, 'public', 'internal', 'demo-005.mp4', '', 'ready'),
('v_demo006', 'u_demo2', 'Comment Systems and Replies', 'Building nested discussion UI without overwhelming the watch page.', 660, 0, 0, 'public', 'internal', 'demo-006.mp4', '', 'ready')
ON DUPLICATE KEY UPDATE
title = VALUES(title),
description = VALUES(description),
duration = VALUES(duration),
is_sensitive = VALUES(is_sensitive),
disable_comments = VALUES(disable_comments),
status = VALUES(status),
storage_type = VALUES(storage_type),
file_path = VALUES(file_path),
thumbnail_path = VALUES(thumbnail_path),
processing_status = VALUES(processing_status);

INSERT INTO bans (id, user_id, type, reason, starts_at, ends_at, banned_by_type, banned_by_user_id) VALUES
('ban_demo0001', 'u_banned', 'comment', 'Comment spam in multiple watch pages.', NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'system', NULL),
('ban_demo0002', 'u_banned', 'chat', 'Flooding global chat with repeated messages.', NOW(), NULL, 'user', 'u_demo1'),
('ban_demo0003', 'u_demo2', 'video', 'Resolved upload moderation test ban.', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY), 'user', 'u_demo1')
ON DUPLICATE KEY UPDATE
reason = VALUES(reason),
ends_at = VALUES(ends_at),
banned_by_type = VALUES(banned_by_type),
banned_by_user_id = VALUES(banned_by_user_id);

INSERT IGNORE INTO video_tags (video_id, tag_slug) VALUES
('v_demo001', 'design'),
('v_demo001', 'frontend'),
('v_demo002', 'frontend'),
('v_demo002', 'product'),
('v_demo003', 'security'),
('v_demo004', 'streaming'),
('v_demo005', 'product'),
('v_demo006', 'frontend');

INSERT INTO lists (id, user_id, name, is_sensitive, status) VALUES
('l_demo01', 'u_demo1', 'Developer Watchlist', 0, 'public'),
('l_demo02', 'u_demo2', 'Private Research Queue', 1, 'private')
ON DUPLICATE KEY UPDATE
name = VALUES(name),
is_sensitive = VALUES(is_sensitive),
status = VALUES(status);

INSERT IGNORE INTO list_items (list_id, video_id) VALUES
('l_demo01', 'v_demo001'),
('l_demo01', 'v_demo004'),
('l_demo02', 'v_demo003');

INSERT IGNORE INTO follows (follower_id, followed_id) VALUES
('u_demo2', 'u_demo1'),
('u_banned', 'u_demo1');

INSERT IGNORE INTO savings (user_id, video_id) VALUES
('u_demo1', 'v_demo004'),
('u_demo1', 'v_demo006'),
('u_banned', 'v_demo001');

INSERT INTO user_settings (user_id) VALUES
('u_demo1'),
('u_demo2'),
('u_banned')
ON DUPLICATE KEY UPDATE user_id = VALUES(user_id);

INSERT IGNORE INTO votes (voter_user_id, target_type, target_id, vote_type) VALUES
('u_demo1', 'video', 'v_demo004', 'up'),
('u_demo2', 'video', 'v_demo001', 'up'),
('u_demo2', 'video', 'v_demo005', 'up'),
('u_demo1', 'comment', 'c_demo001', 'up'),
('u_banned', 'comment', 'c_demo003', 'down');

INSERT INTO comments (id, user_id, target_id, parent_id, body, status) VALUES
('c_demo001', 'u_demo2', 'v_demo001', NULL, 'The tighter card hierarchy makes the feed easier to scan.', 'active'),
('c_demo002', 'u_demo1', 'v_demo001', 'c_demo001', 'Agreed, keeping ads inside the feed helped a lot.', 'active'),
('c_demo003', 'u_demo1', 'v_demo004', NULL, 'Autoplay and related videos need careful pacing here.', 'active'),
('c_demo004', 'u_banned', 'globalchat01', NULL, 'This is a seeded global chat message from a restricted user.', 'active'),
('c_demo005', 'u_demo2', 'v_demo003', NULL, 'Hidden moderation sample comment.', 'hidden')
ON DUPLICATE KEY UPDATE body = VALUES(body), status = VALUES(status);

INSERT INTO notifications (user_id, actor_user_id, type, target_type, target_id, title, body, data, read_at) VALUES
('u_demo1', 'u_demo2', 'follow', 'user', 'u_demo2', 'New follower', 'Tech Vision followed your profile.', JSON_OBJECT('source', 'dev_seed'), NULL),
('u_demo1', 'u_demo2', 'comment', 'video', 'v_demo001', 'New comment', 'Tech Vision commented on your video.', JSON_OBJECT('comment_id', 'c_demo001'), NULL),
('u_banned', NULL, 'ban', 'user', 'u_banned', 'Account restriction applied', 'You are banned from commenting for 7 days.', JSON_OBJECT('ban_id', 'ban_demo0001', 'type', 'comment'), NULL),
('u_demo2', 'u_demo1', 'moderation', 'report', 'rep_demo0002', 'Report reviewed', 'Your report was resolved by LimeVideo Master.', JSON_OBJECT('status', 'resolved'), NOW());

INSERT INTO reports (id, reporter_id, target_type, target_id, reason, details, status, reviewed_by, reviewed_at, resolution_note) VALUES
('rep_demo0001', 'u_demo2', 'video', 'v_demo003', 'sensitive-content', 'Seeded open report for sensitive video review.', 'open', NULL, NULL, NULL),
('rep_demo0002', 'u_demo1', 'comment', 'c_demo005', 'abuse', 'Seeded resolved report for a hidden comment.', 'resolved', 'u_demo1', NOW(), 'Comment was hidden in dev seed.'),
('rep_demo0003', 'u_demo2', 'user', 'u_banned', 'spam', 'Seeded reviewing report for restricted user behavior.', 'reviewing', 'u_demo1', NULL, NULL)
ON DUPLICATE KEY UPDATE
reason = VALUES(reason),
details = VALUES(details),
status = VALUES(status),
reviewed_by = VALUES(reviewed_by),
reviewed_at = VALUES(reviewed_at),
resolution_note = VALUES(resolution_note);

INSERT INTO activity_logs (event_type, action, status, actor_type, actor_id, target_type, target_id, context, metadata) VALUES
('LOGIN_ATTEMPT', 'LOGIN_SUCCESS', 'SUCCESS', 'USER', 'u_demo1', 'user', 'u_demo1', JSON_OBJECT('ip', 'dev-seed'), JSON_OBJECT('seed', true)),
('MODERATION', 'BAN_CREATED', 'SUCCESS', 'SYSTEM', NULL, 'user', 'u_banned', JSON_OBJECT('ban_id', 'ban_demo0001'), JSON_OBJECT('type', 'comment')),
('REPORT', 'REPORT_SUBMITTED', 'SUCCESS', 'USER', 'u_demo2', 'video', 'v_demo003', JSON_OBJECT('report_id', 'rep_demo0001'), JSON_OBJECT('reason', 'sensitive-content'));

INSERT INTO analytics_events (session_id, user_id, event_type, page, route, target_type, target_id, source, search_query, category, duration_ms, watch_time_ms, video_current_time, video_duration, scroll_depth, viewport, referrer, metadata, ip_hash, user_agent) VALUES
('00000000-0000-4000-8000-000000000001', 'u_demo1', 'page_view', 'gallery', '/gallery', NULL, NULL, 'ui', NULL, NULL, NULL, NULL, NULL, NULL, 10, '1440x900', '', JSON_OBJECT('seed', true), SHA2('127.0.0.1', 256), 'LimeVideo Dev Seed'),
('00000000-0000-4000-8000-000000000001', 'u_demo1', 'video_watch', 'watch', '/watch/v_demo001', 'video', 'v_demo001', 'player', NULL, NULL, NULL, 45000, 45.000, 320.000, 80, '1440x900', '/gallery', JSON_OBJECT('seed', true), SHA2('127.0.0.1', 256), 'LimeVideo Dev Seed'),
('00000000-0000-4000-8000-000000000002', NULL, 'search', 'gallery', '/gallery?q=security', NULL, NULL, 'ui', 'security', 'all', NULL, NULL, NULL, NULL, 35, '390x844', '', JSON_OBJECT('results_count', 1), SHA2('10.0.0.2', 256), 'LimeVideo Mobile Seed');

INSERT INTO analytics_rollups (bucket_unit, bucket_start, event_type, page, target_type, target_id, source, search_query, category, event_count, unique_sessions, unique_users, total_duration_ms, total_watch_time_ms, max_scroll_depth) VALUES
('hour', DATE_FORMAT(NOW(), '%Y-%m-%d %H:00:00'), 'page_view', 'gallery', NULL, NULL, 'ui', NULL, NULL, 4, 2, 1, 0, 0, 35),
('day', DATE_FORMAT(NOW(), '%Y-%m-%d 00:00:00'), 'video_watch', 'watch', 'video', 'v_demo001', 'player', NULL, NULL, 2, 1, 1, 0, 90000, 80);

INSERT INTO cron_jobs (id, event_type, target_type, target_id, dedupe_key, status, priority, attempts, max_attempts, available_at, locked_by, locked_at, locked_until, payload, result, last_error, started_at, completed_at, failed_at) VALUES
('cron_demo00001', 'notification_video', 'video', 'v_demo001', SHA2('notification_video:video:v_demo001', 256), 'pending', 10, 0, 3, NOW(), NULL, NULL, NULL, JSON_OBJECT('video_id', 'v_demo001'), NULL, NULL, NULL, NULL, NULL),
('cron_demo00002', 'analytics_rollup_hourly', 'analytics', 'hourly', SHA2('analytics_rollup_hourly:dev_seed', 256), 'completed', 0, 1, 3, DATE_SUB(NOW(), INTERVAL 1 HOUR), 'dev-seed', DATE_SUB(NOW(), INTERVAL 50 MINUTE), NULL, JSON_OBJECT('lookback_hours', 48), JSON_OBJECT('rows', 2), NULL, DATE_SUB(NOW(), INTERVAL 50 MINUTE), DATE_SUB(NOW(), INTERVAL 49 MINUTE), NULL),
('cron_demo00003', 'analytics_cleanup_raw', 'analytics', 'cleanup', SHA2('analytics_cleanup_raw:dev_seed', 256), 'failed', -5, 3, 3, DATE_SUB(NOW(), INTERVAL 2 HOUR), NULL, NULL, NULL, JSON_OBJECT('retention_days', 90), NULL, 'Seeded failed job for UI testing.', DATE_SUB(NOW(), INTERVAL 2 HOUR), NULL, DATE_SUB(NOW(), INTERVAL 110 MINUTE))
ON DUPLICATE KEY UPDATE
status = VALUES(status),
attempts = VALUES(attempts),
result = VALUES(result),
last_error = VALUES(last_error),
completed_at = VALUES(completed_at),
failed_at = VALUES(failed_at);
