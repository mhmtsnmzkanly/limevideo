<?php
declare(strict_types=1);

/*
 * LimeVideo local configuration example.
 *
 * Copy this file to config.php and change the values for your environment.
 * config.php is ignored by git and must not be served or committed.
 */

return [
    // App/site settings. SITE_BASE_URL is derived from SITE_DOMAIN and SITE_HTTPS.
    "DEV_MODE" => false,
    "SITE_DOMAIN" => "example.com",
    "SITE_HTTPS" => true,

    // Database settings. Private/server-only.
    "DB_HOST" => "127.0.0.1",
    "DB_PORT" => 3306,
    "DB_NAME" => "limevideo",
    "DB_USER" => "limevideo_user",
    "DB_PASSWORD" => "CHANGE_ME",
    "DB_CHARSET" => "utf8mb4",

    // Global chat settings.
    "CHAT_VIDEO_ID" => "globalchat01",
    "CHAT_OWNER_USER_ID" => "u_system",
    "CHAT_MESSAGE_LIMIT" => 50,
    "CHAT_MESSAGE_MAX_LENGTH" => 500,

    // Cron/jobs settings. CRON_TOKEN is private/server-only.
    "CRON_TOKEN" => "CHANGE_ME_LONG_RANDOM_SECRET",
    "CRON_AUTO_RUN_ENABLED" => false,
    "CRON_AUTO_RUN_LIMIT" => 2,
    "CRON_AUTO_RUN_MIN_INTERVAL" => 60,

    // Analytics settings.
    "ANALYTICS_ROLLUP_ENABLED" => true,
    "ANALYTICS_ROLLUP_LOOKBACK_HOURS" => 48,
    "ANALYTICS_ROLLUP_LOOKBACK_DAYS" => 14,
    "ANALYTICS_RAW_RETENTION_DAYS" => 90,
    "ANALYTICS_AUTO_ENQUEUE_MIN_INTERVAL" => 300,

    // Security/CSRF settings.
    "SECURITY_CSRF_EXEMPT" => "login,register,provider_webhook,analytics",

    // Captcha settings. v1 supports Turnstile / Turnstile-compatible captcha only.
    // CAPTCHA_SCRIPT_URL must provide window.turnstile. hCaptcha/reCAPTCHA need a future provider adapter.
    // CAPTCHA_VERIFY_URL and CAPTCHA_PRIVATE_KEY are private/server-only.
    "CAPTCHA_ENABLED" => false,
    "CAPTCHA_PROVIDER" => "turnstile",
    "CAPTCHA_SCRIPT_URL" =>
        "https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit",
    "CAPTCHA_PUBLIC_KEY" => "CHANGE_ME_PUBLIC_KEY",
    "CAPTCHA_PRIVATE_KEY" => "CHANGE_ME_PRIVATE_KEY",
    "CAPTCHA_VERIFY_URL" =>
        "https://challenges.cloudflare.com/turnstile/v0/siteverify",
    "CAPTCHA_FORM_FIELD_NAME" => "captcha_token",

    // Ad services. Public display/script values are frontend-safe; provider credentials should not be placed here.
    "AD_SERVICE_KEYS" => "internal,vast,gam,custom_js",
    "AD_SERVICE_INTERNAL_DISPLAY_NAME" => "Internal Ad Placements",
    "AD_SERVICE_INTERNAL_SCRIPT_URL" => "",
    "AD_SERVICE_INTERNAL_ENABLED" => true,
    "AD_SERVICE_INTERNAL_SETTING_MODE" => "fallback",
    "AD_SERVICE_VAST_DISPLAY_NAME" => "VAST Compatible Service",
    "AD_SERVICE_VAST_SCRIPT_URL" => "",
    "AD_SERVICE_VAST_ENABLED" => false,
    "AD_SERVICE_VAST_SETTING_ADAPTER" => "planned",
    "AD_SERVICE_GAM_DISPLAY_NAME" => "Google Ad Manager",
    "AD_SERVICE_GAM_SCRIPT_URL" => "",
    "AD_SERVICE_GAM_ENABLED" => false,
    "AD_SERVICE_GAM_SETTING_ADAPTER" => "planned",
    "AD_SERVICE_CUSTOM_JS_DISPLAY_NAME" => "Custom JavaScript Ad Service",
    "AD_SERVICE_CUSTOM_JS_SCRIPT_URL" => "",
    "AD_SERVICE_CUSTOM_JS_ENABLED" => false,
    "AD_SERVICE_CUSTOM_JS_SETTING_ADAPTER" => "planned",

    // Ad placements. Public labels/copy/URLs are frontend-safe.
    "AD_PLACEMENT_KEYS" =>
        "feed_native,watch_sidebar,preroll,popunder,leaderboard",
    "AD_PLACEMENT_FEED_NATIVE_SOURCE" => "internal",
    "AD_PLACEMENT_FEED_NATIVE_SERVICE" => "internal",
    "AD_PLACEMENT_FEED_NATIVE_EXTERNAL_ZONE_ID" => "",
    "AD_PLACEMENT_FEED_NATIVE_LABEL" => "Sponsored",
    "AD_PLACEMENT_FEED_NATIVE_TITLE" => "LimeVideo VPN Pro",
    "AD_PLACEMENT_FEED_NATIVE_BODY" =>
        "Secure creator sessions from every network.",
    "AD_PLACEMENT_FEED_NATIVE_CTA_LABEL" => "Learn More",
    "AD_PLACEMENT_FEED_NATIVE_CTA_URL" => "#",
    "AD_PLACEMENT_FEED_NATIVE_ENABLED" => true,
    "AD_PLACEMENT_FEED_NATIVE_FREQUENCY" => 5,
    "AD_PLACEMENT_WATCH_SIDEBAR_SOURCE" => "internal",
    "AD_PLACEMENT_WATCH_SIDEBAR_SERVICE" => "internal",
    "AD_PLACEMENT_WATCH_SIDEBAR_EXTERNAL_ZONE_ID" => "",
    "AD_PLACEMENT_WATCH_SIDEBAR_LABEL" => "Sponsored",
    "AD_PLACEMENT_WATCH_SIDEBAR_TITLE" => "Upgrade to LimeVideo Pro",
    "AD_PLACEMENT_WATCH_SIDEBAR_BODY" =>
        "Creator analytics and cleaner watch sessions.",
    "AD_PLACEMENT_WATCH_SIDEBAR_CTA_LABEL" => "View Plans",
    "AD_PLACEMENT_WATCH_SIDEBAR_CTA_URL" => "#",
    "AD_PLACEMENT_WATCH_SIDEBAR_ENABLED" => true,
    "AD_PLACEMENT_WATCH_SIDEBAR_FREQUENCY" => 1,
    "AD_PLACEMENT_PREROLL_SOURCE" => "internal",
    "AD_PLACEMENT_PREROLL_SERVICE" => "internal",
    "AD_PLACEMENT_PREROLL_EXTERNAL_ZONE_ID" => "",
    "AD_PLACEMENT_PREROLL_LABEL" => "Advertisement",
    "AD_PLACEMENT_PREROLL_TITLE" => "Sponsored Video",
    "AD_PLACEMENT_PREROLL_BODY" => "Video will start shortly.",
    "AD_PLACEMENT_PREROLL_CTA_LABEL" => "Skip Ad",
    "AD_PLACEMENT_PREROLL_CTA_URL" => "#",
    "AD_PLACEMENT_PREROLL_ENABLED" => true,
    "AD_PLACEMENT_PREROLL_FREQUENCY" => 1,
    "AD_PLACEMENT_POPUNDER_SOURCE" => "internal",
    "AD_PLACEMENT_POPUNDER_SERVICE" => "internal",
    "AD_PLACEMENT_POPUNDER_EXTERNAL_ZONE_ID" => "",
    "AD_PLACEMENT_POPUNDER_LABEL" => "Special Offer",
    "AD_PLACEMENT_POPUNDER_TITLE" => "LimeVideo API",
    "AD_PLACEMENT_POPUNDER_BODY" =>
        "Build your own platform in 10 minutes.",
    "AD_PLACEMENT_POPUNDER_CTA_LABEL" => "Check Now",
    "AD_PLACEMENT_POPUNDER_CTA_URL" => "#",
    "AD_PLACEMENT_POPUNDER_ENABLED" => true,
    "AD_PLACEMENT_POPUNDER_FREQUENCY" => 1,
    "AD_PLACEMENT_LEADERBOARD_SOURCE" => "internal",
    "AD_PLACEMENT_LEADERBOARD_SERVICE" => "internal",
    "AD_PLACEMENT_LEADERBOARD_EXTERNAL_ZONE_ID" => "",
    "AD_PLACEMENT_LEADERBOARD_LABEL" => "Sponsored",
    "AD_PLACEMENT_LEADERBOARD_TITLE" => "LimeVideo Creator Stack",
    "AD_PLACEMENT_LEADERBOARD_BODY" =>
        "A compact toolkit for creators and curators.",
    "AD_PLACEMENT_LEADERBOARD_CTA_LABEL" => "Explore",
    "AD_PLACEMENT_LEADERBOARD_CTA_URL" => "#",
    "AD_PLACEMENT_LEADERBOARD_ENABLED" => true,
    "AD_PLACEMENT_LEADERBOARD_FREQUENCY" => 1,

    // Public/frontend-safe notes: site metadata, captcha public values and ad display values may be exposed.
    // Private/server-only notes: database password, cron token and captcha private key must stay in config.php only.
];
