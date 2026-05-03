<?php
declare(strict_types=1);

/*
 * LimeVideo local configuration example.
 *
 * Copy this file to config.php and change the values for your environment.
 * config.php is ignored by git and must not be served or committed.
 */

return [
    // Application
    "DEV_MODE" => false,

    // Site settings
    "SITE_DOMAIN" => "example.com",
    "SITE_HTTPS" => true,

    // Database
    "DB_HOST" => "127.0.0.1",
    "DB_PORT" => 3306,
    "DB_NAME" => "limevideo",
    "DB_USER" => "limevideo_user",
    "DB_PASSWORD" => "CHANGE_ME",
    "DB_CHARSET" => "utf8mb4",

    // Local file storage
    "STORAGE_VIDEO_PATH" => __DIR__ . "/uploads/videos",
    "STORAGE_THUMB_PATH" => __DIR__ . "/uploads/thumbs",
    "STORAGE_MAX_SIZE" => 100 * 1024 * 1024,

    // Global chat
    "CHAT_VIDEO_ID" => "globalchat01",
    "CHAT_OWNER_USER_ID" => "u_system",
    "CHAT_MESSAGE_LIMIT" => 50,
    "CHAT_MESSAGE_MAX_LENGTH" => 500,

    // Cron and analytics
    "CRON_TOKEN" => "CHANGE_ME_LONG_RANDOM_SECRET",
    "CRON_AUTO_RUN_ENABLED" => false,
    "CRON_AUTO_RUN_LIMIT" => 2,
    "CRON_AUTO_RUN_MIN_INTERVAL" => 60,
    "ANALYTICS_ROLLUP_ENABLED" => true,
    "ANALYTICS_ROLLUP_LOOKBACK_HOURS" => 48,
    "ANALYTICS_ROLLUP_LOOKBACK_DAYS" => 14,
    "ANALYTICS_RAW_RETENTION_DAYS" => 90,
    "ANALYTICS_AUTO_ENQUEUE_MIN_INTERVAL" => 300,

    // Security
    "SECURITY_CSRF_EXEMPT" => "login,register,provider_webhook,analytics",
    "CAPTCHA_ENABLED" => false,
    "CAPTCHA_SCRIPT_URL" =>
        "https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit",
    "CAPTCHA_PUBLIC_KEY" => "CHANGE_ME_PUBLIC_KEY",
    "CAPTCHA_PRIVATE_KEY" => "CHANGE_ME_PRIVATE_KEY",
    "CAPTCHA_VERIFY_URL" =>
        "https://challenges.cloudflare.com/turnstile/v0/siteverify",
    "CAPTCHA_FORM_FIELD_NAME" => "captcha_token",

    // Ad services and placements
    "AD_SERVICE_KEYS" => "internal,vast,gam,custom_js",
    "AD_PLACEMENT_KEYS" =>
        "feed_native,watch_sidebar,preroll,popunder,leaderboard",
];
