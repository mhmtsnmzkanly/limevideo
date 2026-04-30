SET NAMES utf8mb4;

INSERT INTO users (id, username, display_name, email, password_hash, bio, avatar_url, cover_url, status) VALUES
('u_system', 'limevideo_system', 'LimeVideo System', 'system@limevideo.local', '$2y$12$U7oZG7ZvRTW1pdndxgG.2eM7TTye.l6kr.dg1ZRTpiBg0.6euaQuK', 'Internal LimeVideo system account.', '', '', 'disabled'),
('u_demo1', 'limevideo_master', 'LimeVideo Master', 'limevideo_master@example.com', '$2y$12$U7oZG7ZvRTW1pdndxgG.2eM7TTye.l6kr.dg1ZRTpiBg0.6euaQuK', 'Frontend systems, creator tooling and high performance video interfaces.', '', '', 'active'),
('u_demo2', 'techvision', 'Tech Vision', 'techvision@example.com', '$2y$12$U7oZG7ZvRTW1pdndxgG.2eM7TTye.l6kr.dg1ZRTpiBg0.6euaQuK', 'Delivery experiments, infrastructure notes and product breakdowns.', '', '', 'active')
ON DUPLICATE KEY UPDATE
display_name = VALUES(display_name),
bio = VALUES(bio),
avatar_url = VALUES(avatar_url),
cover_url = VALUES(cover_url),
status = VALUES(status);

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

INSERT IGNORE INTO video_tags (video_id, tag_slug) VALUES
('v_demo001', 'design'),
('v_demo001', 'frontend'),
('v_demo002', 'frontend'),
('v_demo002', 'product'),
('v_demo003', 'security'),
('v_demo004', 'streaming'),
('v_demo005', 'product'),
('v_demo006', 'frontend');

INSERT IGNORE INTO follows (follower_id, followed_id) VALUES
('u_demo2', 'u_demo1');

INSERT IGNORE INTO savings (user_id, video_id) VALUES
('u_demo1', 'v_demo004'),
('u_demo1', 'v_demo006');

INSERT INTO user_settings (user_id) VALUES
('u_demo1'),
('u_demo2')
ON DUPLICATE KEY UPDATE user_id = VALUES(user_id);

INSERT IGNORE INTO votes (voter_user_id, target_type, target_id, vote_type) VALUES
('u_demo1', 'video', 'v_demo004', 'up'),
('u_demo2', 'video', 'v_demo001', 'up'),
('u_demo2', 'video', 'v_demo005', 'up');

INSERT INTO comments (id, user_id, target_id, parent_id, body, status) VALUES
('c_demo001', 'u_demo2', 'v_demo001', NULL, 'The tighter card hierarchy makes the feed easier to scan.', 'active'),
('c_demo002', 'u_demo1', 'v_demo001', 'c_demo001', 'Agreed, keeping ads inside the feed helped a lot.', 'active'),
('c_demo003', 'u_demo1', 'v_demo004', NULL, 'Autoplay and related videos need careful pacing here.', 'active')
ON DUPLICATE KEY UPDATE body = VALUES(body), status = VALUES(status);
