<?php
declare(strict_types=1);

defined("LIMEVIDEO") or exit();

/*
 * LimeVideo configuration template. This file is not loaded at runtime.
 *
 * Copy this file to config.php and change values for your environment.
 * config.php is required at runtime and must not be committed.
 * Use nested lowercase snake_case keys.
 * app.site_base_url is derived from app.site_domain and app.site_https.
 */

return [
    // App / site settings.
    "app" => [
        "dev_mode" => false,
        "site_domain" => "example.com",
        "site_https" => true,
    ],

    // Database settings. Private/server-only.
    "database" => [
        "host" => "127.0.0.1",
        "port" => 3306,
        "name" => "limevideo",
        "user" => "limevideo_user",
        "password" => "CHANGE_ME",
        "charset" => "utf8mb4",
    ],

    // Chat settings.
    "chat" => [
        "video_id" => "globalchat01",
        "owner_user_id" => "u_system",
        "message_limit" => 50,
        "message_max_length" => 500,
    ],

    // Cron / jobs settings. token is private/server-only.
    "cron" => [
        "token" => "CHANGE_ME_LONG_RANDOM_SECRET",
        "auto_run_enabled" => false,
        "auto_run_limit" => 2,
        "auto_run_min_interval" => 60,
    ],

    // Analytics settings.
    "analytics" => [
        "rollup_enabled" => true,
        "rollup_lookback_hours" => 48,
        "rollup_lookback_days" => 14,
        "raw_retention_days" => 90,
        "auto_enqueue_min_interval" => 300,
    ],

    // CSRF settings.
    "csrf" => [
        "exempt_endpoints" => ["login", "register", "provider_webhook", "analytics"],
    ],

    // Cloudflare Turnstile captcha settings.
    // Turnstile script and verify URL are hardcoded in index.php.
    "captcha" => [
        "enabled" => false,
        "site_key" => "CHANGE_ME_SITE_KEY",
        "secret_key" => "CHANGE_ME_SECRET_KEY",
        "form_field_name" => "captcha_token",
    ],

    // Optional local upload settings. If omitted from config.php, safe defaults are used.
    "uploads" => [
        "video_dir" => "uploads/videos",
        "thumbnail_dir" => "uploads/thumbs",
        "max_video_size_bytes" => 524288000,
        "max_thumbnail_size_bytes" => 5242880,
        "video_extensions" => ["mp4", "webm", "mov"],
        "video_mime_types" => ["video/mp4", "video/webm", "video/quicktime"],
        "thumbnail_extensions" => ["jpg", "jpeg", "png", "webp"],
        "thumbnail_mime_types" => ["image/jpeg", "image/png", "image/webp"],
    ],

    // Ad services. Public display/script values are frontend-safe.
    "ads" => [
        "service_keys" => ["internal", "vast", "gam", "custom_js"],
        "services" => [
            "internal" => [
                "display_name" => "Internal Ad Placements",
                "script_url" => "",
                "enabled" => true,
                "settings" => [
                    "mode" => "fallback",
                ],
            ],
            "vast" => [
                "display_name" => "VAST Compatible Service",
                "script_url" => "",
                "enabled" => false,
                "settings" => [
                    "adapter" => "planned",
                ],
            ],
            "gam" => [
                "display_name" => "Google Ad Manager",
                "script_url" => "",
                "enabled" => false,
                "settings" => [
                    "adapter" => "planned",
                ],
            ],
            "custom_js" => [
                "display_name" => "Custom JavaScript Ad Service",
                "script_url" => "",
                "enabled" => false,
                "settings" => [
                    "adapter" => "planned",
                ],
            ],
        ],

        // Ad placements. Public labels, copy and URLs may be exposed to the frontend.
        "placement_keys" => [
            "feed_native",
            "watch_sidebar",
            "preroll",
            "popunder",
            "leaderboard",
        ],
        "placements" => [
            "feed_native" => [
                "source" => "internal",
                "service" => "internal",
                "external_zone_id" => "",
                "label" => "Sponsored",
                "title" => "LimeVideo VPN Pro",
                "body" => "Secure creator sessions from every network.",
                "cta_label" => "Learn More",
                "cta_url" => "#",
                "enabled" => true,
                "frequency" => 5,
            ],
            "watch_sidebar" => [
                "source" => "internal",
                "service" => "internal",
                "external_zone_id" => "",
                "label" => "Sponsored",
                "title" => "Upgrade to LimeVideo Pro",
                "body" => "Creator analytics and cleaner watch sessions.",
                "cta_label" => "View Plans",
                "cta_url" => "#",
                "enabled" => true,
                "frequency" => 1,
            ],
            "preroll" => [
                "source" => "internal",
                "service" => "internal",
                "external_zone_id" => "",
                "label" => "Advertisement",
                "title" => "Sponsored Video",
                "body" => "Video will start shortly.",
                "cta_label" => "Skip Ad",
                "cta_url" => "#",
                "enabled" => true,
                "frequency" => 1,
            ],
            "popunder" => [
                "source" => "internal",
                "service" => "internal",
                "external_zone_id" => "",
                "label" => "Special Offer",
                "title" => "LimeVideo API",
                "body" => "Build your own platform in 10 minutes.",
                "cta_label" => "Check Now",
                "cta_url" => "#",
                "enabled" => true,
                "frequency" => 1,
            ],
            "leaderboard" => [
                "source" => "internal",
                "service" => "internal",
                "external_zone_id" => "",
                "label" => "Sponsored",
                "title" => "LimeVideo Creator Stack",
                "body" => "A compact toolkit for creators and curators.",
                "cta_label" => "Explore",
                "cta_url" => "#",
                "enabled" => true,
                "frequency" => 1,
            ],
        ],
    ],
];
