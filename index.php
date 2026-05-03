<?php
declare(strict_types=1);

/**
 * LimeVideo Monolith
 * ------------------
 * Single-file PHP API and SPA shell entry point.
 * API routing, authentication, cache, moderation, analytics, cron queue and
 * video playback metadata are intentionally kept in one monolith.
 */

final class LimeVideo
{
    private array $config = [
        "DEV_MODE" => false,
        "SITE_DOMAIN" => "127.0.0.1:8010",
        "SITE_HTTPS" => false,
        "DB_HOST" => "127.0.0.1",
        "DB_PORT" => 3306,
        "DB_NAME" => "limevideo",
        "DB_USER" => "root",
        "DB_PASSWORD" => "default000",
        "DB_CHARSET" => "utf8mb4",
        "STORAGE_VIDEO_PATH" => __DIR__ . "/uploads/videos",
        "STORAGE_THUMB_PATH" => __DIR__ . "/uploads/thumbs",
        "STORAGE_MAX_SIZE" => 100 * 1024 * 1024,
        "CHAT_VIDEO_ID" => "globalchat01",
        "CHAT_OWNER_USER_ID" => "u_system",
        "CHAT_MESSAGE_LIMIT" => 50,
        "CHAT_MESSAGE_MAX_LENGTH" => 500,
        "CRON_TOKEN" => "",
        "CRON_AUTO_RUN_ENABLED" => false,
        "CRON_AUTO_RUN_LIMIT" => 2,
        "CRON_AUTO_RUN_MIN_INTERVAL" => 60,
        "ANALYTICS_ROLLUP_ENABLED" => true,
        "ANALYTICS_ROLLUP_LOOKBACK_HOURS" => 48,
        "ANALYTICS_ROLLUP_LOOKBACK_DAYS" => 14,
        "ANALYTICS_RAW_RETENTION_DAYS" => 90,
        "ANALYTICS_AUTO_ENQUEUE_MIN_INTERVAL" => 300,
        "SECURITY_CSRF_EXEMPT" => "login,register,provider_webhook,analytics",
        "CAPTCHA_ENABLED" => false,
        "CAPTCHA_SCRIPT_URL" =>
            "https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit",
        "CAPTCHA_PUBLIC_KEY" => "",
        "CAPTCHA_PRIVATE_KEY" => "",
        "CAPTCHA_VERIFY_URL" =>
            "https://challenges.cloudflare.com/turnstile/v0/siteverify",
        "CAPTCHA_FORM_FIELD_NAME" => "captcha_token",
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
    ];
    private ?PDO $pdo = null;
    private string $cacheDir;

    public function __construct()
    {
        $configFile = __DIR__ . "/config.php";
        if (is_file($configFile)) {
            $override = require $configFile;
            if (!is_array($override)) {
                throw new RuntimeException("config.php must return an array.");
            }
            $this->config = array_replace_recursive($this->config, $override);
        }

        $domain = preg_replace(
            "#^https?://#",
            "",
            rtrim((string) $this->config["SITE_DOMAIN"], "/"),
        );
        $scheme = (bool) $this->config["SITE_HTTPS"] ? "https" : "http";
        $this->config["SITE_BASE_URL"] = $scheme . "://" . $domain;

        $this->cacheDir = __DIR__ . "/cache";
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0775, true);
        }
    }

    /**
     * Reads a flat configuration key.
     * Input: $key config name, $default fallback when missing.
     * Output: configured value or fallback value.
     */
    public function cfg(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    public function cachePath(): string
    {
        return $this->cacheDir;
    }

    public function db(): PDO
    {
        if ($this->pdo) {
            return $this->pdo;
        }
        $dsn =
            "mysql:host=" .
            $this->cfg("DB_HOST") .
            ";port=" .
            (int) $this->cfg("DB_PORT", 3306) .
            ";dbname=" .
            $this->cfg("DB_NAME") .
            ";charset=" .
            $this->cfg("DB_CHARSET", "utf8mb4");
        $this->pdo = new PDO(
            $dsn,
            $this->cfg("DB_USER"),
            $this->cfg("DB_PASSWORD"),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        );
        unset(
            $this->config["DB_HOST"],
            $this->config["DB_PORT"],
            $this->config["DB_NAME"],
            $this->config["DB_USER"],
            $this->config["DB_PASSWORD"],
            $this->config["DB_CHARSET"],
        );
        return $this->pdo;
    }

    public function baseUrl(string $path = ""): string
    {
        return rtrim((string) $this->cfg("SITE_BASE_URL"), "/") .
            "/" .
            ltrim($path, "/");
    }

    private function xmlEscape(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, "UTF-8");
    }

    private function sitemapLastmod(?string $value): ?string
    {
        if (!$value) {
            return null;
        }
        $time = strtotime($value);
        return $time ? gmdate("Y-m-d", $time) : null;
    }

    private function buildSitemapUrlEntry(string $loc, ?string $lastmod = null): string
    {
        $entry = "  <url>\n    <loc>" . $this->xmlEscape($loc) . "</loc>\n";
        if ($lastmod) {
            $entry .= "    <lastmod>" . $this->xmlEscape($lastmod) . "</lastmod>\n";
        }
        return $entry . "  </url>\n";
    }

    private function getPublicSitemapUrls(): array
    {
        $urls = [["loc" => $this->baseUrl("/"), "lastmod" => null]];
        $chatVideoId = $this->chatVideoId();
        $chatOwnerId = (string) $this->cfg("CHAT_OWNER_USER_ID", "u_system");

        $videoStmt = $this->db()->prepare(
            "SELECT v.id, COALESCE(v.updated_at, v.created_at) AS lastmod
            FROM videos v
            JOIN users u ON u.id = v.user_id
            WHERE v.status = 'public'
              AND v.processing_status = 'ready'
              AND v.id <> ?
              AND u.status = 'active'
              AND u.id <> ?
              AND NOT EXISTS (
                SELECT 1 FROM bans b
                WHERE b.user_id = u.id
                  AND b.type = 'general'
                  AND b.revoked_at IS NULL
                  AND (b.ends_at IS NULL OR b.ends_at > NOW())
              )
            ORDER BY v.updated_at DESC, v.created_at DESC
            LIMIT 20000",
        );
        $videoStmt->execute([$chatVideoId, $chatOwnerId]);
        foreach ($videoStmt->fetchAll() as $video) {
            $urls[] = [
                "loc" => $this->baseUrl("/video/" . rawurlencode($video["id"])),
                "lastmod" => $this->sitemapLastmod($video["lastmod"] ?? null),
            ];
        }

        $userStmt = $this->db()->prepare(
            "SELECT u.id, COALESCE(u.updated_at, u.created_at) AS lastmod
            FROM users u
            WHERE u.status = 'active'
              AND u.id <> ?
              AND NOT EXISTS (
                SELECT 1 FROM bans b
                WHERE b.user_id = u.id
                  AND b.type = 'general'
                  AND b.revoked_at IS NULL
                  AND (b.ends_at IS NULL OR b.ends_at > NOW())
              )
            ORDER BY u.updated_at DESC, u.created_at DESC
            LIMIT 20000",
        );
        $userStmt->execute([$chatOwnerId]);
        foreach ($userStmt->fetchAll() as $user) {
            $urls[] = [
                "loc" => $this->baseUrl("/profile/" . rawurlencode($user["id"])),
                "lastmod" => $this->sitemapLastmod($user["lastmod"] ?? null),
            ];
        }

        $tagStmt = $this->db()->prepare(
            "SELECT t.slug, MAX(COALESCE(v.updated_at, v.created_at)) AS lastmod
            FROM tags t
            JOIN video_tags vt ON vt.tag_slug = t.slug
            JOIN videos v ON v.id = vt.video_id
            JOIN users u ON u.id = v.user_id
            WHERE v.status = 'public'
              AND v.processing_status = 'ready'
              AND v.id <> ?
              AND u.status = 'active'
              AND u.id <> ?
              AND NOT EXISTS (
                SELECT 1 FROM bans b
                WHERE b.user_id = u.id
                  AND b.type = 'general'
                  AND b.revoked_at IS NULL
                  AND (b.ends_at IS NULL OR b.ends_at > NOW())
              )
            GROUP BY t.slug
            ORDER BY lastmod DESC
            LIMIT 20000",
        );
        $tagStmt->execute([$chatVideoId, $chatOwnerId]);
        foreach ($tagStmt->fetchAll() as $tag) {
            $urls[] = [
                "loc" => $this->baseUrl("/tag/" . rawurlencode($tag["slug"])),
                "lastmod" => $this->sitemapLastmod($tag["lastmod"] ?? null),
            ];
        }

        return $urls;
    }

    public function generateSitemapXml(): bool
    {
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        foreach ($this->getPublicSitemapUrls() as $url) {
            $xml .= $this->buildSitemapUrlEntry($url["loc"], $url["lastmod"] ?? null);
        }
        $xml .= "</urlset>\n";

        $target = __DIR__ . "/sitemap.xml";
        $tmp = $target . ".tmp";
        if (file_put_contents($tmp, $xml, LOCK_EX) === false) {
            error_log("LimeVideo sitemap write failed.");
            return false;
        }
        if (!@rename($tmp, $target)) {
            @unlink($tmp);
            error_log("LimeVideo sitemap rename failed.");
            return false;
        }
        return true;
    }

    public function regenerateSitemap(?string $token = null): void
    {
        $expectedToken = trim((string) $this->cfg("CRON_TOKEN", ""));
        if ($expectedToken === "" || !hash_equals($expectedToken, (string) $token)) {
            $this->jsonResponse(["error" => "Invalid or missing cron token"], 403);
        }

        $this->jsonResponse([
            "success" => $this->generateSitemapXml(),
            "path" => "sitemap.xml",
        ]);
    }

    // --- Security, rate limit and cache helpers ---

    public function checkRateLimit(string $key, int $limit, int $period): void
    {
        $ip = hash("sha256", $_SERVER["REMOTE_ADDR"] ?? "127.0.0.1");
        $file =
            $this->cacheDir . "/ratelimit_" . sha1($key . $ip) . ".tmp";
        $data = file_exists($file)
            ? json_decode((string) file_get_contents($file), true)
            : null;
        if (!$data || time() - $data["start"] > $period) {
            $data = ["count" => 1, "start" => time()];
        } else {
            $data["count"]++;
        }
        file_put_contents($file, json_encode($data), LOCK_EX);
        if ($data["count"] > $limit) {
            $response = ["error" => "Rate limit exceeded"];
            if ((bool) $this->cfg("DEV_MODE")) {
                $response["retry_after"] = $period - (time() - $data["start"]);
            }
            $this->jsonResponse($response, 429);
        }
    }

    public function captchaPublicConfig(): array
    {
        return [
            "enabled" => (bool) $this->cfg("CAPTCHA_ENABLED"),
            "script_url" => (string) $this->cfg("CAPTCHA_SCRIPT_URL"),
            "public_key" => (string) $this->cfg("CAPTCHA_PUBLIC_KEY"),
            "form_field_name" => (string) $this->cfg("CAPTCHA_FORM_FIELD_NAME"),
        ];
    }

    public function captchaTokenFromInput(array $input): string
    {
        $field = (string) $this->cfg("CAPTCHA_FORM_FIELD_NAME");
        return trim(
            (string) ($input[$field] ??
                $input["captcha_token"] ??
                ""),
        );
    }

    private function verifyCaptcha(string $token): void
    {
        if (!(bool) $this->cfg("CAPTCHA_ENABLED")) {
            return;
        }

        $secret = trim((string) $this->cfg("CAPTCHA_PRIVATE_KEY"));
        $verifyUrl = trim((string) $this->cfg("CAPTCHA_VERIFY_URL"));
        if ($secret === "" || $verifyUrl === "") {
            $this->jsonResponse(["error" => "Captcha is not configured"], 503);
        }
        if ($token === "") {
            $this->jsonResponse(["error" => "Captcha verification is required"], 400);
        }

        $payload = http_build_query([
            "secret" => $secret,
            "response" => $token,
            "remoteip" => $_SERVER["REMOTE_ADDR"] ?? "",
        ]);
        $context = stream_context_create([
            "http" => [
                "method" => "POST",
                "header" =>
                    "Content-Type: application/x-www-form-urlencoded\r\n" .
                    "Content-Length: " .
                    strlen($payload) .
                    "\r\n",
                "content" => $payload,
                "timeout" => 5,
            ],
        ]);
        $raw = @file_get_contents($verifyUrl, false, $context);
        $result = $raw ? json_decode($raw, true) : null;

        if (!is_array($result) || empty($result["success"])) {
            $response = ["error" => "Captcha verification failed"];
            if ((bool) $this->cfg("DEV_MODE")) {
                $response["captcha_errors"] = $result["error-codes"] ?? [];
            }
            $this->jsonResponse($response, 400);
        }
    }

    /**
     * Converts a cache key into a safe file-name fragment.
     * Input: raw cache key.
     * Output: short key that can be used inside a file name.
     */
    private function cacheSafeKey(string $key): string
    {
        return substr(preg_replace("/[^a-zA-Z0-9_]+/", "_", $key), 0, 96) ?:
            "entry";
    }

    private function cacheFile(string $key): string
    {
        return $this->cacheDir .
            "/cache_" .
            $this->cacheSafeKey($key) .
            "_" .
            sha1($key) .
            ".tmp";
    }

    public function cacheSet(string $key, mixed $data, int $ttl = 3600): void
    {
        $file = $this->cacheFile($key);
        $tmp = $file . "." . bin2hex(random_bytes(8)) . ".tmp";
        $payload = serialize([
            "expires_at" => time() + $ttl,
            "data" => $data,
        ]);

        if (file_put_contents($tmp, $payload) !== false) {
            if (!@rename($tmp, $file)) {
                @unlink($tmp);
                if ((bool) $this->cfg("DEV_MODE")) {
                    error_log("Cache rename failed: {$tmp} -> {$file}");
                }
            }
        }
    }

    public function cacheGet(string $key, int $ttl = 3600): mixed
    {
        $file = $this->cacheFile($key);
        if (!file_exists($file)) {
            $legacy = $this->cacheDir . "/cache_" . sha1($key) . ".tmp";
            if (file_exists($legacy)) {
                $file = $legacy;
            } else {
                return null;
            }
        }

        $content = @file_get_contents($file);
        if ($content === false) {
            return null;
        }

        $payload = @unserialize($content, ["allowed_classes" => false]);
        if ($payload === false && $content !== serialize(false)) {
            @unlink($file);
            return null;
        }

        if (
            is_array($payload) &&
            array_key_exists("expires_at", $payload) &&
            array_key_exists("data", $payload)
        ) {
            if ((int) $payload["expires_at"] >= time()) {
                return $payload["data"];
            }
            @unlink($file);
            return null;
        }

        // Handle legacy un-wrapped cache entries if they haven't expired based on mtime
        if ($payload !== null && time() - filemtime($file) < $ttl) {
            return $payload;
        }

        @unlink($file);
        return null;
    }

    public function cacheDelete(string $key): void
    {
        foreach (
            [
                $this->cacheFile($key),
                $this->cacheDir . "/cache_" . sha1($key) . ".tmp",
            ]
            as $file
        ) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }
    }

    public function cacheDeletePrefix(string $prefix): void
    {
        $safePrefix = $this->cacheSafeKey($prefix);
        foreach (
            glob($this->cacheDir . "/cache_" . $safePrefix . "*.tmp") ?: []
            as $file
        ) {
            @unlink($file);
        }
    }

    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        $data = $this->cacheGet($key, $ttl);
        if ($data !== null) {
            return $data;
        }
        $data = $callback();
        $this->cacheSet($key, $data, $ttl);
        return $data;
    }

    private function invalidateDiscoveryCaches(): void
    {
        $this->cacheDelete("global_stats");
        $this->cacheDelete("trending_public_v1");
        $this->cacheDelete("trending_public_v2");
        $this->cacheDeletePrefix("search_");
    }

    private function invalidateVideoBaseCaches(?string $userId = null): void
    {
        if ($userId) {
            $this->cacheDeletePrefix("video_base_user_" . $userId . "_");
            return;
        }
        $this->cacheDeletePrefix("video_base_");
    }

    private function chatVideoId(): string
    {
        return (string) $this->cfg("CHAT_VIDEO_ID", "globalchat01");
    }

    private function isChatVideoId(string $videoId): bool
    {
        return hash_equals($this->chatVideoId(), $videoId);
    }

    private function ensureChatChannel(): void
    {
        $chatVideoId = $this->chatVideoId();
        $ownerId = (string) $this->cfg("CHAT_OWNER_USER_ID", "u_system");

        $exists = $this->db()->prepare(
            "SELECT 1 FROM videos WHERE id = ? LIMIT 1",
        );
        $exists->execute([$chatVideoId]);
        if ($exists->fetchColumn()) {
            return;
        }

        $password = password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT);
        $this->db()
            ->prepare(
                "INSERT INTO users (id, username, display_name, email, password_hash, role, status)
            VALUES (?, 'limevideo_system', 'LimeVideo System', 'system@limevideo.local', ?, 'system', 'disabled')
            ON DUPLICATE KEY UPDATE display_name = VALUES(display_name), role = VALUES(role)",
            )
            ->execute([$ownerId, $password]);

        $this->db()
            ->prepare(
                "INSERT INTO videos (id, user_id, title, description, duration, is_sensitive, status, storage_type, file_path, thumbnail_path, processing_status)
            VALUES (?, ?, 'Global Chat', 'Hidden LimeVideo community chat channel.', 0, 0, 'private', 'internal', '', '', 'ready')",
            )
            ->execute([$chatVideoId, $ownerId]);
    }

    /**
     * Checks whether a media source is already an absolute HTTP(S) URL.
     * Input: $url media URL or path.
     * Output: true when the source starts with http:// or https://.
     */
    private function isAbsoluteMediaUrl(string $url): bool
    {
        return (bool) preg_match("#^https?://#i", $url);
    }

    /**
     * Detects direct browser-playable video URLs by file extension.
     * Input: $url external/internal source URL.
     * Output: true for direct video files such as mp4, webm or m3u8.
     */
    private function isDirectVideoUrl(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH) ?: $url;
        return (bool) preg_match("#\.(mp4|webm|ogg|ogv|mov|m4v|m3u8)(\?.*)?$#i", $path);
    }

    /**
     * Converts an internal storage value into a public browser URL.
     * Input: $path raw videos.file_path value.
     * Output: public URL; bare filenames map to /uploads/videos/{file}.
     */
    private function publicMediaUrl(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === "") {
            return "";
        }
        if ($this->isAbsoluteMediaUrl($path) || str_starts_with($path, "/")) {
            return $path;
        }
        if (!str_contains($path, "/")) {
            return "/uploads/videos/" . $path;
        }
        return "/" . ltrim($path, "/");
    }

    /**
     * Chooses how the watch page should present a playback source.
     * Input: $url resolved playback URL.
     * Output: "direct" for in-page player, "external_page" for new-tab provider pages.
     */
    private function detectPlaybackMode(string $url): string
    {
        return $this->isDirectVideoUrl($url) ? "direct" : "external_page";
    }

    /**
     * Resolves the canonical playback URL from the unified videos row.
     * Input: $video database row from videos.
     * Output: playback URL for frontend consumption.
     */
    private function videoPlaybackUrl(array $video): string
    {
        if (($video["storage_type"] ?? "internal") === "external") {
            return trim((string) ($video["playback_url"] ?? ""));
        }
        return $this->publicMediaUrl($video["file_path"] ?? "");
    }

    /**
     * Adds frontend playback metadata to a video row.
     * Input: $video database row.
     * Output: row plus playback_source_url and player_mode.
     */
    private function hydrateVideoPlayback(array $video): array
    {
        $sourceUrl = $this->videoPlaybackUrl($video);
        $mode = (string) ($video["playback_mode"] ?? "");
        if ($mode === "") {
            $mode =
                ($video["storage_type"] ?? "internal") === "external"
                    ? $this->detectPlaybackMode($sourceUrl)
                    : "direct";
        }
        $video["playback_source_url"] = $sourceUrl;
        $video["player_mode"] = $mode === "external_page" ? "external_page" : "direct";
        return $video;
    }

    public function generateId(string $prefix, int $length = 8): string
    {
        $bodyLength = max(1, $length - strlen($prefix) - 1);
        return $prefix .
            "_" .
            substr(bin2hex(random_bytes($length)), 0, $bodyLength);
    }

    public function jsonResponse(mixed $data, int $code = 200): void
    {
        header("Content-Type: application/json");
        header("X-Content-Type-Options: nosniff");
        http_response_code($code);
        echo json_encode($data);
        exit();
    }

    public function errorResponse(\Throwable $exception, int $code = 500): void
    {
        if ((bool) $this->cfg("DEV_MODE")) {
            $data = [
                "error" => $exception->getMessage(),
                "code" => $code,
                "type" => get_class($exception),
                "file" => $exception->getFile(),
                "line" => $exception->getLine(),
                "trace" => array_slice(
                    array_map(function ($frame) {
                        return [
                            "file" => $frame["file"] ?? "unknown",
                            "line" => $frame["line"] ?? 0,
                            "function" => $frame["function"] ?? "unknown",
                            "class" => $frame["class"] ?? null,
                        ];
                    }, $exception->getTrace()),
                    0,
                    5,
                ),
            ];
        } else {
            $data = [
                "error" => match ($code) {
                    400 => "Bad Request",
                    401 => "Unauthorized",
                    403 => "Forbidden",
                    404 => "Not Found",
                    409 => "Conflict",
                    429 => "Too Many Requests",
                    500 => "Internal Server Error",
                    default => "An error occurred",
                },
                "code" => $code,
            ];
        }
        $this->jsonResponse($data, $code);
    }

    public function csrfToken(): string
    {
        if (empty($_SESSION["csrf_token"])) {
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
        }
        return $_SESSION["csrf_token"];
    }

    public function assertCsrf(string $endpoint, string $method): void
    {
        if ($method !== "POST") {
            return;
        }
        if (
            in_array(
                $endpoint,
                array_values(
                    array_filter(
                        array_map(
                            "trim",
                            explode(
                                ",",
                                (string) $this->cfg("SECURITY_CSRF_EXEMPT", ""),
                            ),
                        ),
                    ),
                ),
                true,
            )
        ) {
            return;
        }
        $sent = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? "";
        if (!hash_equals($this->csrfToken(), $sent)) {
            $this->jsonResponse(["error" => "Invalid CSRF token"], 403);
        }
    }

    public function fetch(string $name): mixed
    {
        return match ($name) {
            // Active tag list used by UI category/tag selectors.
            "tags" => $this->remember("portal_tags_v2", 3600, function () {
                return $this->db()
                    ->query("SELECT name, slug FROM tags ORDER BY name ASC")
                    ->fetchAll() ?:
                    [];
            }),

            default => throw new RuntimeException(
                "Unknown fetch: " . $name,
            ),
        };
    }

    // --- Read-side API helpers ---

    /**
     * Loads basic user profile data with cache support.
     * Input: $id user id.
     * Output: user row or null.
     */
    public function getUser(string $id): ?array
    {
        $cacheKey = "user_" . $id;
        $user = $this->cacheGet($cacheKey);
        if ($user) {
            return $user;
        }

        try {
            $stmt = $this->db()->prepare(
                "SELECT id, username, display_name, email, bio, avatar_url, cover_url, role, status, created_at FROM users WHERE id = ? LIMIT 1",
            );
            $stmt->execute([$id]);
            $user = $stmt->fetch() ?: null;
            if ($user) {
                $this->cacheSet($cacheKey, $user);
            }
            return $user;
        } catch (\PDOException $e) {
            if ((bool) $this->cfg("DEV_MODE")) {
                error_log("[DB Error] getUser($id): " . $e->getMessage());
            }
            return null;
        }
    }

    /**
     * Calculates homepage/global counters with cache support.
     * Input: none.
     * Output: user, video and view counters.
     */
    public function getStats(): array
    {
        $cacheKey = "global_stats";
        $stats = $this->cacheGet($cacheKey, 300);
        if ($stats) {
            return $stats;
        }

        $chatVideoId = $this->chatVideoId();
        $stats = [
            "total_users" => $this->db()
                ->query("SELECT COUNT(*) FROM users")
                ->fetchColumn(),
            "total_videos" => (function () use ($chatVideoId) {
                $stmt = $this->db()->prepare(
                    "SELECT COUNT(*) FROM videos WHERE status = 'public' AND id <> ?",
                );
                $stmt->execute([$chatVideoId]);
                return $stmt->fetchColumn();
            })(),
            "total_views" => (function () use ($chatVideoId) {
                $stmt = $this->db()->prepare(
                    "SELECT COALESCE(SUM(views_count), 0) FROM videos WHERE status = 'public' AND id <> ?",
                );
                $stmt->execute([$chatVideoId]);
                return $stmt->fetchColumn() ?: 0;
            })(),
        ];

        $this->cacheSet($cacheKey, $stats);
        return $stats;
    }

    public function validate(
        mixed $value,
        string $type,
        array $options = [],
    ): mixed {
        if ($value === null) {
            return null;
        }
        return match ($type) {
            "id" => is_string($value) &&
            preg_match('/^[a-z0-9_]{1,32}$/i', $value)
                ? $value
                : null,
            "text" => (function () use ($value, $options) {
                $max = $options["max"] ?? 1000;
                $val = trim(strip_tags((string) $value));
                return mb_strlen($val) > $max ? mb_substr($val, 0, $max) : $val;
            })(),
            "enum" => in_array($value, $options["allowed"] ?? [])
                ? $value
                : null,
            "email" => filter_var($value, FILTER_VALIDATE_EMAIL)
                ? $value
                : null,
            "url" => (function () use ($value, $options) {
                $val = trim((string) $value);
                if ($val === "") {
                    return "";
                }
                $max = $options["max"] ?? 500;
                if (mb_strlen($val) > $max) {
                    return null;
                }
                return filter_var($val, FILTER_VALIDATE_URL) &&
                    preg_match("/^https?:\/\//i", $val)
                    ? $val
                    : null;
            })(),
            default => $value,
        };
    }

    // --- Mutation helpers and cache invalidation ---

    private function notificationAllowed(string $userId, string $type): bool
    {
        $stmt = $this->db()->prepare(
            "SELECT notify_comments, notify_follows FROM user_settings WHERE user_id = ? LIMIT 1",
        );
        $stmt->execute([$userId]);
        $settings = $stmt->fetch();
        if (!$settings) {
            return true;
        }

        if (
            in_array($type, ["NEW_COMMENT", "COMMENT_REPLY", "NEW_VIDEO"], true)
        ) {
            return (int) $settings["notify_comments"] === 1;
        }
        if ($type === "FOLLOW") {
            return (int) $settings["notify_follows"] === 1;
        }
        return true;
    }

    public function createNotification(
        string $userId,
        string $type,
        string $title,
        string $body,
        ?string $actorId = null,
        ?string $targetType = null,
        ?string $targetId = null,
        ?array $data = null,
    ): void {
        if (!$this->notificationAllowed($userId, $type)) {
            return;
        }

        // Deduplicate short-term notifications from the same actor and type.
        if ($actorId) {
            $stmt = $this->db()->prepare(
                "SELECT 1 FROM notifications WHERE user_id = ? AND actor_user_id = ? AND type = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 DAY) LIMIT 1",
            );
            $stmt->execute([$userId, $actorId, $type]);
            if ($stmt->fetch()) {
                return;
            }
        }

        $stmt = $this->db()->prepare(
            "INSERT INTO notifications (user_id, actor_user_id, type, target_type, target_id, title, body, data) VALUES (?,?,?,?,?,?,?,?)",
        );
        $stmt->execute([
            $userId,
            $actorId,
            $type,
            $targetType,
            $targetId,
            $title,
            $body,
            $data ? json_encode($data) : null,
        ]);
    }

    /**
     * Creates an action ban and notifies the restricted user.
     * Input: target user, ban type, reason, optional end time and moderator identity.
     * Output: created ban id.
     */
    public function createBan(
        string $userId,
        string $type,
        string $reason,
        ?string $endsAt = null,
        string $bannedByType = "system",
        ?string $bannedByUserId = null,
    ): string {
        if (!in_array($type, ["general", "comment", "video", "chat"], true)) {
            throw new InvalidArgumentException("Invalid ban type");
        }
        $bannedByType = $bannedByType === "user" ? "user" : "system";
        if ($bannedByType === "system") {
            $bannedByUserId = null;
        }

        $banId = $this->generateId("b", 12);
        $stmt = $this->db()->prepare(
            "INSERT INTO bans
            (id, user_id, type, reason, ends_at, banned_by_type, banned_by_user_id)
            VALUES (?,?,?,?,?,?,?)",
        );
        $stmt->execute([
            $banId,
            $userId,
            $type,
            $reason,
            $endsAt,
            $bannedByType,
            $bannedByUserId,
        ]);

        $untilText = $endsAt ? "until {$endsAt}" : "indefinitely";
        $this->createNotification(
            $userId,
            "BAN_APPLIED",
            "Action Restriction Applied",
            "You were restricted from {$type} actions {$untilText}. Reason: {$reason}",
            null,
            "ban",
            $banId,
            [
                "ban_id" => $banId,
                "type" => $type,
                "reason" => $reason,
                "ends_at" => $endsAt,
                "banned_by_type" => $bannedByType,
                "banned_by_user_id" => $bannedByUserId,
            ],
        );

        return $banId;
    }

    /**
     * Finds the active ban that blocks a specific action.
     * Input: $userId target user id, $action comment/video/chat.
     * Output: active ban row or null.
     */
    private function activeBanFor(string $userId, string $action): ?array
    {
        $stmt = $this->db()->prepare(
            "SELECT id, type, reason, ends_at, banned_by_type, banned_by_user_id, created_at
            FROM bans
            WHERE user_id = ?
              AND revoked_at IS NULL
              AND (ends_at IS NULL OR ends_at > NOW())
              AND type IN ('general', ?)
            ORDER BY FIELD(type, 'general', ?) ASC, created_at DESC
            LIMIT 1",
        );
        $stmt->execute([$userId, $action, $action]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Converts internal ban action keys into human-readable labels.
     * Input: action key.
     * Output: short action label for API errors/toasts.
     */
    private function banActionLabel(string $action): string
    {
        return match ($action) {
            "comment" => "commenting",
            "video" => "video publishing",
            "chat" => "global chat messaging",
            default => $action,
        };
    }

    /**
     * Builds the user-facing blocked-action message.
     * Input: active ban row and attempted action.
     * Output: localized-ready status/error message.
     */
    private function banMessage(array $ban, string $action): string
    {
        $scope =
            $ban["type"] === "general"
                ? "all restricted actions"
                : $this->banActionLabel($action);
        $until = $ban["ends_at"]
            ? " until " . $ban["ends_at"]
            : " indefinitely";
        return "You are banned from {$scope}{$until}. Reason: " .
            ($ban["reason"] ?: "No reason provided.");
    }

    /**
     * Stops restricted mutating actions before they touch business logic.
     * Input: action key comment/video/chat.
     * Output: no return; sends HTTP 403 JSON response when banned.
     */
    private function assertActionAllowed(string $action): void
    {
        if (empty($_SESSION["user"])) {
            return;
        }
        $ban = $this->activeBanFor($_SESSION["user"]["id"], $action);
        if (!$ban) {
            return;
        }
        $this->jsonResponse(
            [
                "error" => $this->banMessage($ban, $action),
                "ban" => [
                    "id" => $ban["id"],
                    "type" => $ban["type"],
                    "action" => $action,
                    "reason" => $ban["reason"],
                    "ends_at" => $ban["ends_at"],
                ],
            ],
            403,
        );
    }

    /**
     * Decides whether an account status may start an authenticated session.
     * Input: raw users.status value.
     * Output: true when login is allowed; action bans are checked separately.
     */
    private function canLoginWithStatus(?string $status): bool
    {
        return $status === "active";
    }

    /**
     * Reads the authenticated user id from the current session.
     * Input: none.
     * Output: user id or null for guests.
     */
    private function currentUserId(): ?string
    {
        return $_SESSION["user"]["id"] ?? null;
    }

    /**
     * Reads the authenticated user's role from the current session.
     * Input: none.
     * Output: role key; guests are treated as user.
     */
    private function currentUserRole(): string
    {
        return (string) ($_SESSION["user"]["role"] ?? "user");
    }

    /**
     * Checks whether the current user may inspect hidden video records.
     * Input: none.
     * Output: true for moderator/admin/system roles.
     */
    private function canModerateVideos(): bool
    {
        return in_array(
            $this->currentUserRole(),
            ["moderator", "admin", "system"],
            true,
        );
    }

    /**
     * Applies LimeVideo's video visibility rule.
     * Input: video row containing user_id and status.
     * Output: true when the current viewer may access it.
     */
    private function canViewVideo(array $video): bool
    {
        $status = (string) ($video["status"] ?? "");
        if ($status === "public") {
            return true;
        }
        if ($status === "private") {
            return $this->canModerateVideos() ||
                $this->currentUserId() === ($video["user_id"] ?? null);
        }
        if ($status === "deleted") {
            return $this->canModerateVideos();
        }
        return false;
    }

    /**
     * Loads a video row and stops when it is not visible to the current viewer.
     * Input: video id.
     * Output: visible video row.
     */
    private function visibleVideoOrFail(string $videoId): array
    {
        $stmt = $this->db()->prepare(
            "SELECT id, user_id, status, disable_comments FROM videos WHERE id = ? LIMIT 1",
        );
        $stmt->execute([$videoId]);
        $video = $stmt->fetch();
        if (!$video || !$this->canViewVideo($video)) {
            $this->jsonResponse(["error" => "This video does not exist."], 404);
        }
        return $video;
    }

    private function enqueueCronJob(
        string $eventType,
        string $targetType,
        string $targetId,
        array $payload = [],
        int $priority = 0,
        ?string $dedupeKey = null,
    ): string {
        $jobId = $this->generateId("j", 14);
        $dedupeKey ??= hash(
            "sha256",
            $eventType . "|" . $targetType . "|" . $targetId,
        );
        $stmt = $this->db()->prepare(
            "INSERT INTO cron_jobs
            (id, event_type, target_type, target_id, dedupe_key, priority, payload)
            VALUES (?,?,?,?,?,?,?)
            ON DUPLICATE KEY UPDATE
                updated_at = NOW(),
                priority = GREATEST(priority, VALUES(priority)),
                payload = VALUES(payload),
                available_at = IF(status IN ('completed','working'), available_at, NOW()),
                status = IF(status IN ('completed','working'), status, 'pending')",
        );
        $stmt->execute([
            $jobId,
            $eventType,
            $targetType,
            $targetId,
            $dedupeKey,
            $priority,
            $payload ? json_encode($payload) : null,
        ]);
        return $jobId;
    }

    private function enqueueSitemapRegeneration(
        string $reason,
        array $payload = [],
    ): void {
        $jobId = $this->generateId("j", 14);
        $dedupeKey = hash("sha256", "sitemap_regenerate|main");
        $stmt = $this->db()->prepare(
            "INSERT INTO cron_jobs
            (id, event_type, target_type, target_id, dedupe_key, priority, payload)
            VALUES (?, 'sitemap_regenerate', 'sitemap', 'main', ?, -5, ?)
            ON DUPLICATE KEY UPDATE
                updated_at = NOW(),
                payload = VALUES(payload),
                available_at = IF(status = 'working', available_at, NOW()),
                attempts = IF(status = 'working', attempts, 0),
                completed_at = IF(status = 'working', completed_at, NULL),
                failed_at = IF(status = 'working', failed_at, NULL),
                last_error = NULL,
                status = IF(status = 'working', status, 'pending')",
        );
        $stmt->execute([
            $jobId,
            $dedupeKey,
            json_encode(["reason" => $reason, ...$payload]),
        ]);
    }

    private function cronMarkerFile(string $name): string
    {
        return $this->cacheDir .
            "/jobs_" .
            preg_replace("/[^a-zA-Z0-9_]+/", "_", $name) .
            ".tmp";
    }

    private function markerRecentlyTouched(string $name, int $seconds): bool
    {
        $file = $this->cronMarkerFile($name);
        return file_exists($file) && time() - filemtime($file) < $seconds;
    }

    private function touchCronMarker(string $name): void
    {
        file_put_contents($this->cronMarkerFile($name), (string) time(), LOCK_EX);
    }

    private function maybeScheduleAnalyticsJobs(): void
    {
        if (!(bool) $this->cfg("ANALYTICS_ROLLUP_ENABLED", true)) {
            return;
        }

        $interval = max(
            60,
            (int) $this->cfg("ANALYTICS_AUTO_ENQUEUE_MIN_INTERVAL", 300),
        );
        if ($this->markerRecentlyTouched("analytics_enqueue", $interval)) {
            return;
        }
        $this->touchCronMarker("analytics_enqueue");

        $hourTarget = gmdate("YmdH", strtotime("-1 hour"));
        $dayTarget = gmdate("Ymd", strtotime("-1 day"));
        $this->enqueueCronJob(
            "analytics_rollup_hourly",
            "analytics",
            $hourTarget,
            [
                "lookback_hours" => (int) $this->cfg(
                    "ANALYTICS_ROLLUP_LOOKBACK_HOURS",
                    48,
                ),
            ],
            -10,
        );
        $this->enqueueCronJob(
            "analytics_rollup_daily",
            "analytics",
            $dayTarget,
            [
                "lookback_days" => (int) $this->cfg(
                    "ANALYTICS_ROLLUP_LOOKBACK_DAYS",
                    14,
                ),
            ],
            -20,
        );
        $this->enqueueCronJob(
            "analytics_cleanup_raw",
            "analytics",
            "retention_" . gmdate("Ymd"),
            [
                "retention_days" => (int) $this->cfg(
                    "ANALYTICS_RAW_RETENTION_DAYS",
                    90,
                ),
            ],
            -30,
        );
        $this->maybeAutoRunCronJobs();
    }

    private function maybeAutoRunCronJobs(): void
    {
        if (!(bool) $this->cfg("CRON_AUTO_RUN_ENABLED", false)) {
            return;
        }

        $interval = max(10, (int) $this->cfg("CRON_AUTO_RUN_MIN_INTERVAL", 60));
        if ($this->markerRecentlyTouched("auto_run", $interval)) {
            return;
        }
        $this->touchCronMarker("auto_run");
        $this->runCronJobBatch((int) $this->cfg("CRON_AUTO_RUN_LIMIT", 2));
    }

    private function runCronJobBatch(int $limit = 10): array
    {
        $limit = min(50, max(1, $limit));
        $workerId = gethostname() . "-" . getmypid() . "-" . bin2hex(random_bytes(3));
        $processed = [];

        for ($i = 0; $i < $limit; $i++) {
            $job = $this->claimCronJob($workerId);
            if (!$job) {
                break;
            }
            $processed[] = $this->processCronJob($job, $workerId);
        }

        return ["worker" => $workerId, "processed" => $processed];
    }

    public function runCronJobs(int $limit = 10, ?string $token = null): void
    {
        $expectedToken = trim((string) $this->cfg("CRON_TOKEN", ""));
        if ($expectedToken === "" || !hash_equals($expectedToken, (string) $token)) {
            $this->jsonResponse(["error" => "Invalid or missing cron token"], 403);
        }

        $result = $this->runCronJobBatch($limit);

        $this->jsonResponse([
            "success" => true,
            "worker" => $result["worker"],
            "processed" => $result["processed"],
        ]);
    }

    private function claimCronJob(string $workerId): ?array
    {
        $this->db()
            ->prepare(
                "UPDATE cron_jobs
                SET status = 'working',
                    attempts = attempts + 1,
                    locked_by = ?,
                    locked_at = NOW(),
                    locked_until = DATE_ADD(NOW(), INTERVAL 15 MINUTE),
                    started_at = COALESCE(started_at, NOW()),
                    last_error = NULL
                WHERE id = (
                    SELECT id FROM (
                        SELECT id
                        FROM cron_jobs
                        WHERE available_at <= NOW()
                          AND (
                            status = 'pending'
                            OR (status = 'working' AND locked_until < NOW())
                            OR (status = 'failed' AND attempts < max_attempts)
                          )
                        ORDER BY priority DESC, created_at ASC
                        LIMIT 1
                    ) picked
                )",
            )
            ->execute([$workerId]);

        $stmt = $this->db()->prepare(
            "SELECT * FROM cron_jobs WHERE locked_by = ? AND status = 'working' ORDER BY locked_at DESC LIMIT 1",
        );
        $stmt->execute([$workerId]);
        return $stmt->fetch() ?: null;
    }

    private function processCronJob(array $job, string $workerId): array
    {
        try {
            $result = match ($job["event_type"]) {
                "notification_video" => $this->processVideoNotificationJob($job),
                "sitemap_regenerate" => $this->processSitemapRegenerationJob(),
                "analytics_rollup_hourly" => $this->processAnalyticsRollupJob($job, "hour"),
                "analytics_rollup_daily" => $this->processAnalyticsRollupJob($job, "day"),
                "analytics_cleanup_raw" => $this->processAnalyticsCleanupJob($job),
                default => throw new RuntimeException(
                    "Unknown cron event: " . $job["event_type"],
                ),
            };

            $this->db()
                ->prepare(
                    "UPDATE cron_jobs
                    SET status = 'completed',
                        locked_by = NULL,
                        locked_until = NULL,
                        result = ?,
                        completed_at = NOW()
                    WHERE id = ? AND locked_by = ?",
                )
                ->execute([json_encode($result), $job["id"], $workerId]);

            return [
                "id" => $job["id"],
                "event_type" => $job["event_type"],
                "status" => "completed",
                "result" => $result,
            ];
        } catch (Throwable $e) {
            $willRetry = (int) $job["attempts"] < (int) $job["max_attempts"];
            $backoffMinutes = min(1440, pow(2, (int) $job["attempts"]));
            $this->db()
                ->prepare(
                    "UPDATE cron_jobs
                    SET status = ?,
                        locked_by = NULL,
                        locked_until = NULL,
                        available_at = DATE_ADD(NOW(), INTERVAL ? MINUTE),
                        last_error = ?,
                        failed_at = NOW()
                    WHERE id = ? AND locked_by = ?",
                )
                ->execute([
                    $willRetry ? "failed" : "cancelled",
                    $backoffMinutes,
                    $e->getMessage(),
                    $job["id"],
                    $workerId,
                ]);

            return [
                "id" => $job["id"],
                "event_type" => $job["event_type"],
                "status" => $willRetry ? "failed_retry_scheduled" : "cancelled",
                "error" => $e->getMessage(),
            ];
        }
    }

    private function processSitemapRegenerationJob(): array
    {
        $success = $this->generateSitemapXml();
        if (!$success) {
            throw new RuntimeException("Sitemap generation failed");
        }
        return ["generated" => true, "path" => "sitemap.xml"];
    }

    private function processVideoNotificationJob(array $job): array
    {
        $stmt = $this->db()->prepare(
            "SELECT v.id, v.user_id, v.title, v.status, u.username
            FROM videos v
            JOIN users u ON u.id = v.user_id
            WHERE v.id = ? LIMIT 1",
        );
        $stmt->execute([$job["target_id"]]);
        $video = $stmt->fetch();
        if (!$video || $video["status"] !== "public") {
            return ["sent" => 0, "skipped" => "video_not_public"];
        }

        $followers = $this->db()->prepare(
            "SELECT follower_id FROM follows WHERE followed_id = ?",
        );
        $followers->execute([$video["user_id"]]);

        $sent = 0;
        while ($follower = $followers->fetch()) {
            $this->createNotification(
                $follower["follower_id"],
                "NEW_VIDEO",
                "New Video!",
                "{$video["username"]} uploaded: {$video["title"]}",
                $video["user_id"],
                "video",
                $video["id"],
                ["job_id" => $job["id"]],
            );
            $sent++;
        }

        return ["sent" => $sent, "video_id" => $video["id"]];
    }

    private function processAnalyticsRollupJob(array $job, string $unit): array
    {
        $payload = json_decode((string) ($job["payload"] ?? "{}"), true) ?: [];
        $lookback = $unit === "hour"
            ? max(
                1,
                min(
                    168,
                    (int) ($payload["lookback_hours"] ??
                        $this->cfg("ANALYTICS_ROLLUP_LOOKBACK_HOURS", 48)),
                ),
            )
            : max(
                1,
                min(
                    365,
                    (int) ($payload["lookback_days"] ??
                        $this->cfg("ANALYTICS_ROLLUP_LOOKBACK_DAYS", 14)),
                ),
            );
        $cutoff = (new DateTimeImmutable())
            ->modify("-{$lookback} " . ($unit === "hour" ? "hours" : "days"))
            ->format($unit === "hour" ? "Y-m-d H:00:00" : "Y-m-d 00:00:00");
        $bucketExpr =
            $unit === "hour"
                ? "STR_TO_DATE(DATE_FORMAT(created_at, '%Y-%m-%d %H:00:00'), '%Y-%m-%d %H:%i:%s')"
                : "STR_TO_DATE(DATE_FORMAT(created_at, '%Y-%m-%d 00:00:00'), '%Y-%m-%d %H:%i:%s')";

        $this->db()->beginTransaction();
        try {
            $delete = $this->db()->prepare(
                "DELETE FROM analytics_rollups WHERE bucket_unit = ? AND bucket_start >= ?",
            );
            $delete->execute([$unit, $cutoff]);
            $deleted = $delete->rowCount();

            $insert = $this->db()->prepare(
                "INSERT INTO analytics_rollups
                (bucket_unit, bucket_start, event_type, page, target_type, target_id, source, search_query, category,
                 event_count, unique_sessions, unique_users, total_duration_ms, total_watch_time_ms, max_scroll_depth)
                SELECT
                    ?,
                    {$bucketExpr} AS bucket_start,
                    event_type,
                    page,
                    target_type,
                    target_id,
                    source,
                    search_query,
                    category,
                    COUNT(*) AS event_count,
                    COUNT(DISTINCT session_id) AS unique_sessions,
                    COUNT(DISTINCT user_id) AS unique_users,
                    COALESCE(SUM(duration_ms), 0) AS total_duration_ms,
                    COALESCE(SUM(watch_time_ms), 0) AS total_watch_time_ms,
                    MAX(scroll_depth) AS max_scroll_depth
                FROM analytics_events
                WHERE created_at >= ?
                GROUP BY bucket_start, event_type, page, target_type, target_id, source, search_query, category",
            );
            $insert->execute([$unit, $cutoff]);
            $inserted = $insert->rowCount();
            $this->db()->commit();
        } catch (Throwable $exception) {
            if ($this->db()->inTransaction()) {
                $this->db()->rollBack();
            }
            throw $exception;
        }

        return [
            "unit" => $unit,
            "cutoff" => $cutoff,
            "deleted" => $deleted,
            "inserted" => $inserted,
        ];
    }

    private function processAnalyticsCleanupJob(array $job): array
    {
        $payload = json_decode((string) ($job["payload"] ?? "{}"), true) ?: [];
        $retentionDays = max(
            1,
            (int) ($payload["retention_days"] ??
                $this->cfg("ANALYTICS_RAW_RETENTION_DAYS", 90)),
        );
        $cutoff = (new DateTimeImmutable())
            ->modify("-{$retentionDays} days")
            ->format("Y-m-d H:i:s");
        $stmt = $this->db()->prepare(
            "DELETE FROM analytics_events WHERE created_at < ?",
        );
        $stmt->execute([$cutoff]);

        return ["retention_days" => $retentionDays, "deleted" => $stmt->rowCount()];
    }

    public function vote(
        ?string $targetId,
        ?string $type,
        string $targetType = "video",
    ): void {
        if (!$targetId || !$type) {
            $this->jsonResponse(["error" => "Invalid input"], 400);
        }
        if (empty($_SESSION["user"])) {
            $this->jsonResponse(["error" => "Auth required"], 401);
        }
        $uid = $_SESSION["user"]["id"];
        $targetType = in_array($targetType, ["video", "comment"], true)
            ? $targetType
            : "video";
        $commentVideoId = null;
        if ($targetType === "comment") {
            $lookup = $this->db()->prepare(
                "SELECT c.target_id, v.user_id, v.status
                FROM comments c
                JOIN videos v ON v.id = c.target_id
                WHERE c.id = ? LIMIT 1",
            );
            $lookup->execute([$targetId]);
            $commentVideo = $lookup->fetch();
            $commentVideoId = $commentVideo["target_id"] ?? null;
            if (!$commentVideo || !$this->canViewVideo($commentVideo)) {
                $this->jsonResponse(["error" => "This video does not exist."], 404);
            }
        } else {
            $this->visibleVideoOrFail($targetId);
        }

        $this->db()
            ->prepare(
                "DELETE FROM votes WHERE voter_user_id = ? AND target_type = ? AND target_id = ?",
            )
            ->execute([$uid, $targetType, $targetId]);
        $stmt = $this->db()->prepare(
            "INSERT INTO votes (voter_user_id, target_type, target_id, vote_type) VALUES (?,?,?,?)",
        );
        $stmt->execute([$uid, $targetType, $targetId, $type]);

        // Vote changes affect comment and profile-derived caches.
        if ($targetType === "comment" && $commentVideoId) {
            $this->cacheDeletePrefix("comments_" . $commentVideoId . "_");
        }
        $this->cacheDeletePrefix("profile_");

        $this->jsonResponse(["success" => true]);
    }

    public function comment(
        ?string $videoId,
        ?string $body,
        ?string $parentId = null,
    ): void {
        if (!$videoId || !$body) {
            $this->jsonResponse(["error" => "Invalid input"], 400);
        }
        if (empty($_SESSION["user"])) {
            $this->jsonResponse(["error" => "Auth required"], 401);
        }
        $this->assertActionAllowed("comment");
        if ($this->isChatVideoId($videoId)) {
            $this->jsonResponse(
                ["error" => "Use the chat endpoint for global chat messages"],
                400,
            );
        }
        $uid = $_SESSION["user"]["id"];
        $cid = $this->generateId("c", 10);

        $videoCommentState = $this->visibleVideoOrFail($videoId);
        if ((int) ($videoCommentState["disable_comments"] ?? 0) === 1) {
            $this->jsonResponse(
                ["error" => "Comments are disabled for this video."],
                403,
            );
        }

        $stmt = $this->db()->prepare(
            "INSERT INTO comments (id, user_id, target_id, parent_id, body) VALUES (?,?,?,?,?)",
        );
        $stmt->execute([$cid, $uid, $videoId, $parentId, strip_tags($body)]);

        // Send comment and reply notifications to the affected user.
        if ($parentId) {
            $pStmt = $this->db()->prepare(
                "SELECT user_id FROM comments WHERE id = ? LIMIT 1",
            );
            $pStmt->execute([$parentId]);
            $parent = $pStmt->fetch();
            if ($parent && $parent["user_id"] !== $uid) {
                $this->createNotification(
                    $parent["user_id"],
                    "COMMENT_REPLY",
                    "New Reply",
                    "{$_SESSION["user"]["username"]} replied to your comment.",
                    $uid,
                    "comment",
                    $cid,
                    ["video_id" => $videoId],
                );
            }
        } else {
            $vStmt = $this->db()->prepare(
                "SELECT user_id, title FROM videos WHERE id = ? LIMIT 1",
            );
            $vStmt->execute([$videoId]);
            $video = $vStmt->fetch();
            if ($video && $video["user_id"] !== $uid) {
                $this->createNotification(
                    $video["user_id"],
                    "NEW_COMMENT",
                    "New Comment",
                    "{$_SESSION["user"]["username"]} commented on your video: {$video["title"]}",
                    $uid,
                    "video",
                    $videoId,
                );
            }
        }

        // New comments invalidate comment counts and profile comment lists.
        $this->cacheDeletePrefix("comments_" . $videoId . "_");
        $this->cacheDeletePrefix("profile_");
        $this->jsonResponse(["success" => true, "id" => $cid]);
    }

    public function getChatMessages(?string $after = null): void
    {
        $this->ensureChatChannel();
        $chatVideoId = $this->chatVideoId();
        $limit = min(100, max(1, (int) $this->cfg("CHAT_MESSAGE_LIMIT", 50)));
        $params = [$chatVideoId];
        $sql = "SELECT c.id, c.user_id, c.body, c.created_at, u.username, u.display_name
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.target_id = ? AND c.parent_id IS NULL AND c.status = 'active'";

        if ($after) {
            $sql .= " AND c.created_at > ?";
            $params[] = $after;
        }

        $sql .= " ORDER BY c.created_at DESC LIMIT " . $limit;
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        $messages = array_reverse($stmt->fetchAll());
        $this->jsonResponse($messages);
    }

    public function postChatMessage(?string $body): void
    {
        if (empty($_SESSION["user"])) {
            $this->jsonResponse(["error" => "Auth required"], 401);
        }
        $this->assertActionAllowed("chat");
        $body = $this->validate($body ?? "", "text", [
            "max" => (int) $this->cfg("CHAT_MESSAGE_MAX_LENGTH", 500),
        ]);
        if (!$body) {
            $this->jsonResponse(["error" => "Message is required"], 400);
        }

        $this->ensureChatChannel();
        $chatVideoId = $this->chatVideoId();

        $cid = $this->generateId("c", 10);
        $this->db()
            ->prepare(
                "INSERT INTO comments (id, user_id, target_id, parent_id, body) VALUES (?,?,?,?,?)",
            )
            ->execute([
                $cid,
                $_SESSION["user"]["id"],
                $chatVideoId,
                null,
                $body,
            ]);
        $this->cacheDeletePrefix("comments_" . $chatVideoId . "_");
        $this->logActivity(
            "USER_ACTION",
            "CHAT_MESSAGE_SEND",
            "SUCCESS",
            "USER",
            $_SESSION["user"]["id"],
            "comment",
            $cid,
        );
        $this->jsonResponse(["success" => true, "id" => $cid]);
    }

    public function updateProfile(array $data): void
    {
        if (empty($_SESSION["user"])) {
            $this->jsonResponse(["error" => "Auth required"], 401);
        }
        $uid = $_SESSION["user"]["id"];
        $displayName = $this->validate($data["display_name"] ?? "", "text", [
            "max" => 80,
        ]);
        $bio = $this->validate($data["bio"] ?? "", "text", ["max" => 500]);
        $avatarUrl = $this->validate($data["avatar_url"] ?? "", "url", [
            "max" => 255,
        ]);
        $coverUrl = $this->validate($data["cover_url"] ?? "", "url", [
            "max" => 255,
        ]);
        if ($avatarUrl === null || $coverUrl === null) {
            $this->jsonResponse(["error" => "Invalid profile image URL"], 400);
        }
        if (!$displayName) {
            $displayName = $_SESSION["user"]["username"];
        }

        $this->db()
            ->prepare(
                "UPDATE users SET display_name = ?, bio = ?, avatar_url = ?, cover_url = ? WHERE id = ?",
            )
            ->execute([$displayName, $bio, $avatarUrl, $coverUrl, $uid]);
        $this->cacheDelete("user_" . $uid);
        $this->cacheDeletePrefix("profile_" . $uid . "_");
        $this->invalidateVideoBaseCaches($uid);
        $this->enqueueSitemapRegeneration("profile_updated", ["user_id" => $uid]);
        $_SESSION["user"]["display_name"] = $displayName;
        $this->jsonResponse(["success" => true]);
    }

    /**
     * Loads video comments with time-based pagination and short TTL cache.
     * Input: video id, previous page timestamp, sort type.
     * Output: JSON comment list.
     */
    public function getComments(
        string $videoId,
        ?string $before = null,
        string $sort = "new",
    ): void {
        if ($this->isChatVideoId($videoId)) {
            $this->jsonResponse(
                ["error" => "Use the chat endpoint for global chat messages"],
                400,
            );
        }
        $this->visibleVideoOrFail($videoId);
        $viewerId = $_SESSION["user"]["id"] ?? "guest";
        $cacheKey =
            "comments_{$videoId}_{$viewerId}_{$sort}_" . sha1((string) $before);
        $cached = $this->cacheGet($cacheKey, 30);
        if ($cached !== null) {
            $this->jsonResponse($cached);
        }

        $orderBy =
            $sort === "top"
                ? "votes_sum DESC, c.created_at DESC"
                : "c.created_at DESC";
        $sql = "SELECT c.*, u.username,
                    COALESCE((SELECT SUM(CASE WHEN vote_type='up' THEN 1 ELSE -1 END) FROM votes WHERE target_type = 'comment' AND target_id = c.id), 0) AS votes_sum,
                    (SELECT vote_type FROM votes WHERE target_type = 'comment' AND target_id = c.id AND voter_user_id = ?) AS user_vote
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.target_id = ? AND c.status = 'active' AND c.parent_id IS NULL";

        $params = [$_SESSION["user"]["id"] ?? null, $videoId];
        if ($before) {
            $sql .= " AND c.created_at < ?";
            $params[] = $before;
        }

        $sql .= " ORDER BY $orderBy LIMIT 10";

        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        $comments = $stmt->fetchAll();

        if ($comments) {
            $ids = array_column($comments, "id");
            $placeholders = implode(",", array_fill(0, count($ids), "?"));
            $replySql = "SELECT c.*, u.username,
                            COALESCE((SELECT SUM(CASE WHEN vote_type='up' THEN 1 ELSE -1 END) FROM votes WHERE target_type = 'comment' AND target_id = c.id), 0) AS votes_sum,
                            (SELECT vote_type FROM votes WHERE target_type = 'comment' AND target_id = c.id AND voter_user_id = ?) AS user_vote
                        FROM comments c
                        JOIN users u ON c.user_id = u.id
                        WHERE c.status = 'active' AND c.parent_id IN ($placeholders)
                        ORDER BY c.created_at ASC";
            $replyStmt = $this->db()->prepare($replySql);
            $replyStmt->execute(
                array_merge([$_SESSION["user"]["id"] ?? null], $ids),
            );
            $repliesByParent = [];
            foreach ($replyStmt->fetchAll() as $reply) {
                $repliesByParent[$reply["parent_id"]][] = $reply;
            }
            foreach ($comments as &$comment) {
                $comment["replies"] = $repliesByParent[$comment["id"]] ?? [];
            }
        }

        $this->cacheSet($cacheKey, $comments, 30);
        $this->jsonResponse($comments);
    }

    public function getTrending(): void
    {
        $chatVideoId = $this->chatVideoId();
        $videos = $this->remember("trending_public_v2", 60, function () use (
            $chatVideoId,
        ) {
            $stmt = $this->db()->prepare("SELECT v.*, u.username,
            COALESCE(NULLIF(v.thumbnail_url, ''), v.thumbnail_path) AS thumbnail_path,
            v.views_count AS views
            FROM videos v
            JOIN users u ON v.user_id = u.id
            WHERE v.status = 'public' AND v.id <> ? ORDER BY v.created_at DESC LIMIT 24");
            $stmt->execute([$chatVideoId]);
            return $stmt->fetchAll();
        });
        $this->jsonResponse($videos);
    }

    public function getVideoDetail(string $id): void
    {
        if ($this->isChatVideoId($id)) {
            $this->jsonResponse(["error" => "This video does not exist."], 404);
        }
        $this->db()
            ->prepare(
                "UPDATE videos SET views_count = views_count + 1, updated_at = updated_at WHERE id = ? AND status = 'public'",
            )
            ->execute([$id]);
        $viewerId = $_SESSION["user"]["id"] ?? null;
        $ownerStmt = $this->db()->prepare(
            "SELECT user_id FROM videos WHERE id = ? LIMIT 1",
        );
        $ownerStmt->execute([$id]);
        $ownerId = $ownerStmt->fetchColumn();
        $baseCacheKey = $ownerId
            ? "video_base_user_{$ownerId}_video_{$id}"
            : "video_base_video_{$id}";
        $video = $this->cacheGet($baseCacheKey, 300);

        if ($video === null) {
            $stmt = $this->db()->prepare("SELECT
                v.id, v.user_id, v.title, v.description, v.duration, v.is_sensitive, v.disable_comments, v.status, v.created_at, v.updated_at,
                u.username, u.display_name, u.avatar_url,
                v.storage_type, v.provider, v.provider_asset_id, v.file_path, v.playback_url, v.playback_mode,
                COALESCE(NULLIF(v.thumbnail_url, ''), v.thumbnail_path) AS thumbnail_path,
                v.thumbnail_url, v.processing_status
                FROM videos v
                JOIN users u ON v.user_id = u.id
                WHERE v.id = ? LIMIT 1");
            $stmt->execute([$id]);
            $video = $stmt->fetch();
            if ($video) {
                $baseCacheKey = "video_base_user_{$video["user_id"]}_video_{$id}";
                $this->cacheSet($baseCacheKey, $video, 300);
            }
        }

        if (!$video) {
            $this->jsonResponse(["error" => "This video does not exist."], 404);
        }

        if ($video["status"] !== "public") {
            if (!$this->canViewVideo($video)) {
                $msg =
                    $video["status"] === "deleted"
                        ? "This video has been deleted."
                        : "This video is private.";
                $this->jsonResponse(["error" => $msg], 403);
            }
        }

        $dynamicStmt = $this->db()->prepare("SELECT
            v.views_count,
            v.views_count AS views,
            COALESCE((SELECT SUM(CASE WHEN vote_type='up' THEN 1 ELSE -1 END) FROM votes WHERE target_type = 'video' AND target_id = v.id), 0) AS votes_sum,
            (SELECT vote_type FROM votes WHERE target_type = 'video' AND target_id = v.id AND voter_user_id = ?) AS user_vote,
            (SELECT COUNT(*) FROM comments WHERE target_id = v.id AND status = 'active') AS comments_count,
            (SELECT COUNT(*) FROM savings WHERE video_id = v.id AND user_id = ?) AS is_saved,
            (SELECT COUNT(*) FROM follows WHERE follower_id = ? AND followed_id = v.user_id) AS is_following,
            (SELECT COUNT(*) FROM follows WHERE followed_id = v.user_id) AS followers_count
            FROM videos v
            WHERE v.id = ? LIMIT 1");
        $dynamicStmt->execute([$viewerId, $viewerId, $viewerId, $id]);
        $video = $this->hydrateVideoPlayback(
            array_merge($video, $dynamicStmt->fetch() ?: []),
        );

        // Track the watch action in the activity log.
        $this->logActivity(
            "USER_ACTION",
            "VIEW_VIDEO",
            "SUCCESS",
            isset($_SESSION["user"]) ? "USER" : "GUEST",
            $_SESSION["user"]["id"] ?? null,
            "video",
            $id,
        );

        $this->jsonResponse($video);
    }

    public function getProfile(string $id, string $tab = "videos"): void
    {
        $viewerId = $_SESSION["user"]["id"] ?? "guest";
        $profileCacheKey = "profile_{$id}_{$viewerId}";
        $cachedProfile = $this->cacheGet($profileCacheKey, 60);
        if ($cachedProfile !== null) {
            $this->jsonResponse($cachedProfile);
        }
        $chatVideoId = $this->chatVideoId();

        $stmt = $this->db()->prepare(
            "SELECT id, username, display_name, bio, avatar_url, cover_url, role, created_at, status FROM users WHERE id = ? LIMIT 1",
        );
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        if (!$user || ($user["status"] ?? null) !== "active") {
            $this->jsonResponse(
                ["error" => "This profile is no longer available."],
                404,
            );
        }

        $canSeePrivateProfileVideos =
            $this->canModerateVideos() || $viewerId === $user["id"];
        $profileStatusSql = $canSeePrivateProfileVideos
            ? "status IN ('public','private')"
            : "status = 'public'";
        $profileVideoStatusSql = $canSeePrivateProfileVideos
            ? "v.status IN ('public','private')"
            : "v.status = 'public'";

        $statsStmt = $this->db()->prepare("SELECT
            (SELECT COUNT(*) FROM videos WHERE user_id = ? AND {$profileStatusSql} AND id <> ?) AS videos,
            (SELECT COUNT(*) FROM follows WHERE followed_id = ?) AS followers,
            (SELECT COUNT(*) FROM follows WHERE follower_id = ?) AS following,
            (SELECT COUNT(*) FROM savings WHERE user_id = ?) AS saved,
            (SELECT COUNT(*) FROM comments c JOIN videos v ON v.id = c.target_id WHERE c.user_id = ? AND c.status = 'active' AND {$profileVideoStatusSql} AND c.target_id <> ?) AS comments,
            (SELECT COALESCE(SUM(views_count), 0) FROM videos WHERE user_id = ? AND {$profileStatusSql} AND id <> ?) AS views");
        $statsStmt->execute([
            $user["id"],
            $chatVideoId,
            $user["id"],
            $user["id"],
            $user["id"],
            $user["id"],
            $chatVideoId,
            $user["id"],
            $chatVideoId,
        ]);
        $user["stats"] = $statsStmt->fetch() ?: [
            "videos" => 0,
            "followers" => 0,
            "following" => 0,
            "saved" => 0,
            "comments" => 0,
            "views" => 0,
        ];
        $viewerId = $_SESSION["user"]["id"] ?? null;
        $user["is_following"] = 0;
        if ($viewerId && $viewerId !== $user["id"]) {
            $followStmt = $this->db()->prepare(
                "SELECT COUNT(*) FROM follows WHERE follower_id = ? AND followed_id = ?",
            );
            $followStmt->execute([$viewerId, $user["id"]]);
            $user["is_following"] = (int) $followStmt->fetchColumn();
        }

        $videoSelect = "SELECT v.*, u.username,
            COALESCE(NULLIF(v.thumbnail_url, ''), v.thumbnail_path) AS thumbnail_path,
            v.views_count AS views
            FROM videos v
            JOIN users u ON v.user_id = u.id";

        $vStmt = $this->db()->prepare(
            "$videoSelect WHERE v.user_id = ? AND {$profileVideoStatusSql} AND v.id <> ? ORDER BY v.created_at DESC",
        );
        $vStmt->execute([$user["id"], $chatVideoId]);
        $user["videos"] = $vStmt->fetchAll();

        $sStmt = $this->db()->prepare(
            "$videoSelect JOIN savings s ON s.video_id = v.id WHERE s.user_id = ? AND {$profileVideoStatusSql} AND v.id <> ? ORDER BY s.saved_at DESC",
        );
        $sStmt->execute([$user["id"], $chatVideoId]);
        $user["saved"] = $sStmt->fetchAll();

        $lStmt = $this->db()->prepare(
            "$videoSelect JOIN votes vo ON vo.target_id = v.id WHERE vo.voter_user_id = ? AND vo.target_type = 'video' AND vo.vote_type = 'up' AND {$profileVideoStatusSql} AND v.id <> ? ORDER BY vo.voted_at DESC",
        );
        $lStmt->execute([$user["id"], $chatVideoId]);
        $user["liked"] = $lStmt->fetchAll();

        $cStmt = $this->db()->prepare(
            "SELECT c.*, v.title AS video_title FROM comments c JOIN videos v ON c.target_id = v.id WHERE c.user_id = ? AND c.status = 'active' AND {$profileVideoStatusSql} AND c.target_id <> ? ORDER BY c.created_at DESC LIMIT 30",
        );
        $cStmt->execute([$user["id"], $chatVideoId]);
        $user["comments"] = $cStmt->fetchAll();

        $followersStmt = $this->db()->prepare(
            "SELECT u.id, u.username, u.display_name, u.avatar_url FROM follows f JOIN users u ON u.id = f.follower_id WHERE f.followed_id = ? ORDER BY f.created_at DESC LIMIT 24",
        );
        $followersStmt->execute([$user["id"]]);
        $user["followers"] = $followersStmt->fetchAll();

        $followingStmt = $this->db()->prepare(
            "SELECT u.id, u.username, u.display_name, u.avatar_url FROM follows f JOIN users u ON u.id = f.followed_id WHERE f.follower_id = ? ORDER BY f.created_at DESC LIMIT 24",
        );
        $followingStmt->execute([$user["id"]]);
        $user["following"] = $followingStmt->fetchAll();

        $this->cacheSet($profileCacheKey, $user, 60);
        $this->jsonResponse($user);
    }

    public function createVideo(
        string $userId,
        string $title,
        string $desc,
        string $filePath,
        string $thumbPath,
        array $tags = [],
        int $disableComments = 0,
    ): string {
        if (!empty($_SESSION["user"]) && $_SESSION["user"]["id"] === $userId) {
            $this->assertActionAllowed("video");
        }
        $vid = $this->generateId("v", 12);
        $stmt = $this->db()->prepare(
            "INSERT INTO videos (id, user_id, title, description, disable_comments, storage_type, file_path, thumbnail_path, playback_mode, processing_status) VALUES (?,?,?,?,?, 'internal', ?, ?, 'direct', 'ready')",
        );
        $stmt->execute([
            $vid,
            $userId,
            $title,
            $desc,
            $disableComments ? 1 : 0,
            $filePath,
            $thumbPath,
        ]);

        foreach ($tags as $t) {
            $this->db()
                ->prepare("INSERT IGNORE INTO tags (name, slug) VALUES (?,?)")
                ->execute([$t, $t]);
            $this->db()
                ->prepare(
                    "INSERT INTO video_tags (video_id, tag_slug) VALUES (?,?)",
                )
                ->execute([$vid, $t]);
        }

        $this->enqueueCronJob(
            "notification_video",
            "video",
            $vid,
            ["user_id" => $userId],
            10,
        );

        $this->invalidateDiscoveryCaches();
        $this->enqueueSitemapRegeneration("video_created", [
            "video_id" => $vid,
            "user_id" => $userId,
        ]);
        if ($tags) {
            $this->cacheDelete("portal_tags_v2");
        }
        $this->cacheDeletePrefix("profile_" . $userId . "_");
        $this->cacheDelete("video_base_user_{$userId}_video_{$vid}");
        return $vid;
    }

    public function createExternalVideo(array $input): void
    {
        if (empty($_SESSION["user"])) {
            $this->jsonResponse(["error" => "Auth required"], 401);
        }
        $this->assertActionAllowed("video");

        $provider = "manual_external";
        $title = $this->validate($input["title"] ?? "", "text", ["max" => 200]);
        $description = $this->validate($input["description"] ?? "", "text", [
            "max" => 1000,
        ]);
        $playbackUrl = $this->validate($input["playback_url"] ?? "", "url", [
            "max" => 500,
        ]);
        $thumbnailUrl = $this->validate($input["thumbnail_url"] ?? "", "url", [
            "max" => 500,
        ]);
        $playbackMode = $this->validate(
            $input["playback_mode"] ?? "",
            "enum",
            ["allowed" => ["direct", "external_page"]],
        );
        $duration = max(0, (int) ($input["duration"] ?? 0));
        $isSensitive = !empty($input["is_sensitive"]) ? 1 : 0;
        $disableComments = !empty($input["disable_comments"]) ? 1 : 0;
        $status =
            $this->validate($input["status"] ?? "public", "enum", [
                "allowed" => ["public", "private"],
            ]) ?:
            "public";
        $tags = is_array($input["tags"] ?? null)
            ? array_values(
                array_unique(
                    array_filter(
                        array_map(
                            fn($tag) => $this->validate($tag, "text", [
                                "max" => 50,
                            ]),
                            $input["tags"],
                        ),
                    ),
                ),
            )
            : [];
        if (!$title || !$playbackUrl || $thumbnailUrl === null) {
            $this->jsonResponse(
                [
                    "error" =>
                        "Title and valid playback_url are required",
                ],
                400,
            );
        }
        $playbackMode = $playbackMode ?: $this->detectPlaybackMode($playbackUrl);

        $videoId = $this->generateId("v", 12);
        $assetId = $this->validate(
            $input["provider_asset_id"] ?? $videoId,
            "text",
            ["max" => 120],
        );
        $this->db()
            ->prepare(
                "INSERT INTO videos
            (id, user_id, title, description, duration, is_sensitive, disable_comments, status, storage_type, provider, provider_asset_id, playback_url, thumbnail_url, playback_mode, processing_status, metadata)
            VALUES (?,?,?,?,?,?,?,?, 'external', ?, ?, ?, ?, ?, 'ready', ?)",
            )
            ->execute([
                $videoId,
                $_SESSION["user"]["id"],
                $title,
                $description,
                $duration,
                $isSensitive,
                $disableComments,
                $status,
                $provider,
                $assetId,
                $playbackUrl,
                $thumbnailUrl,
                $playbackMode,
                json_encode(["source" => "manual_external_create"]),
            ]);

        if ($tags) {
            $allowedStmt = $this->db()->prepare(
                "SELECT slug FROM tags WHERE slug IN (" .
                    implode(",", array_fill(0, count($tags), "?")) .
                    ")",
            );
            $allowedStmt->execute($tags);
            $allowedTags = $allowedStmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($allowedTags as $tag) {
                $this->db()
                    ->prepare(
                        "INSERT IGNORE INTO video_tags (video_id, tag_slug) VALUES (?, ?)",
                    )
                    ->execute([$videoId, $tag]);
            }
        }

        $this->invalidateDiscoveryCaches();
        $userId = $_SESSION["user"]["id"];
        $this->cacheDeletePrefix("profile_" . $userId . "_");
        $this->cacheDelete("video_base_user_{$userId}_video_{$videoId}");
        if ($status === "public") {
            $this->enqueueSitemapRegeneration("public_video_created", [
                "video_id" => $videoId,
                "user_id" => $userId,
            ]);
            $this->enqueueCronJob(
                "notification_video",
                "video",
                $videoId,
                ["user_id" => $userId],
                10,
            );
        }
        $this->jsonResponse([
            "success" => true,
            "id" => $videoId,
            "provider" => $provider,
        ]);
    }

    public function providerWebhook(string $provider, array $input): void
    {
        $provider = $this->validate($provider, "text", ["max" => 50]);
        if (!$provider) {
            $this->jsonResponse(["error" => "Invalid provider"], 400);
        }

        $assetId = $this->validate($input["provider_asset_id"] ?? "", "text", [
            "max" => 120,
        ]);
        $status = $this->validate(
            $input["processing_status"] ?? "ready",
            "enum",
            ["allowed" => ["pending", "processing", "ready", "failed"]],
        );
        if (!$assetId || !$status) {
            $this->jsonResponse(["error" => "Invalid webhook payload"], 400);
        }

        $playbackUrl = $this->validate($input["playback_url"] ?? "", "url", [
            "max" => 500,
        ]);
        $thumbnailUrl = $this->validate($input["thumbnail_url"] ?? "", "url", [
            "max" => 500,
        ]);
        if ($playbackUrl === null || $thumbnailUrl === null) {
            $this->jsonResponse(["error" => "Invalid media URL"], 400);
        }
        $duration = isset($input["duration"])
            ? max(0, (int) $input["duration"])
            : null;

        $stmt = $this->db()->prepare(
            "UPDATE videos SET processing_status = ?, playback_url = COALESCE(NULLIF(?, ''), playback_url), thumbnail_url = COALESCE(NULLIF(?, ''), thumbnail_url), playback_mode = CASE WHEN COALESCE(NULLIF(?, ''), playback_url) REGEXP '\\.(mp4|webm|ogg|ogv|mov|m4v|m3u8)(\\\\?.*)?$' THEN 'direct' ELSE 'external_page' END, metadata = ? WHERE provider = ? AND provider_asset_id = ?",
        );
        $stmt->execute([
            $status,
            $playbackUrl,
            $thumbnailUrl,
            $playbackUrl,
            json_encode($input),
            $provider,
            $assetId,
        ]);
        if ($duration !== null) {
            $this->db()
                ->prepare(
                    "UPDATE videos SET duration = ? WHERE provider = ? AND provider_asset_id = ?",
                )
                ->execute([$duration, $provider, $assetId]);
        }

        $this->invalidateDiscoveryCaches();
        $this->invalidateVideoBaseCaches();
        if ($stmt->rowCount() > 0 || $duration !== null) {
            $this->enqueueSitemapRegeneration("provider_video_updated", [
                "provider" => $provider,
                "provider_asset_id" => $assetId,
            ]);
        }
        $this->jsonResponse([
            "success" => true,
            "updated" => $stmt->rowCount(),
        ]);
    }

    public function toggleFollow(string $targetUserId): void
    {
        if (empty($_SESSION["user"])) {
            $this->jsonResponse(["error" => "Auth required"], 401);
        }
        $followerId = $_SESSION["user"]["id"];
        if ($followerId === $targetUserId) {
            $this->jsonResponse(["error" => "Cannot follow yourself"], 400);
        }

        $stmt = $this->db()->prepare(
            "SELECT 1 FROM follows WHERE follower_id = ? AND followed_id = ?",
        );
        $stmt->execute([$followerId, $targetUserId]);

        if ($stmt->fetch()) {
            $this->db()
                ->prepare(
                    "DELETE FROM follows WHERE follower_id = ? AND followed_id = ?",
                )
                ->execute([$followerId, $targetUserId]);
            $this->cacheDeletePrefix("profile_" . $targetUserId . "_");
            $this->cacheDeletePrefix("profile_" . $followerId . "_");
            $this->jsonResponse(["status" => "unfollowed"]);
        } else {
            $this->db()
                ->prepare(
                    "INSERT INTO follows (follower_id, followed_id) VALUES (?,?)",
                )
                ->execute([$followerId, $targetUserId]);

            // Notify the followed user.
            $this->createNotification(
                $targetUserId,
                "FOLLOW",
                "New Follower",
                "{$_SESSION["user"]["username"]} started following you!",
                $followerId,
                "user",
                $followerId,
            );

            $this->cacheDeletePrefix("profile_" . $targetUserId . "_");
            $this->cacheDeletePrefix("profile_" . $followerId . "_");
            $this->jsonResponse(["status" => "followed"]);
        }
    }

    /**
     * Loads a cursor-paginated discovery page.
     * Input: search query, tag category, sort mode, opaque cursor and page size.
     * Output: JSON object with items, next_cursor and has_more.
     */
    public function search(
        string $query,
        string $category = "all",
        string $sort = "newest",
        ?string $cursor = null,
        int $limit = 24,
    ): void {
        $chatVideoId = $this->chatVideoId();
        $sort = in_array($sort, ["newest", "popular", "duration"], true)
            ? $sort
            : "newest";
        $limit = min(48, max(1, $limit));
        $cacheKey =
            "search_" .
            sha1(
                $query .
                    "|" .
                    $category .
                    "|" .
                    $sort .
                    "|" .
                    (string) $cursor .
                    "|" .
                    $limit .
                    "|" .
                    $chatVideoId,
            );
        $cachedPage = $this->cacheGet($cacheKey, 60);
        if ($cachedPage !== null) {
            $this->jsonResponse($cachedPage);
        }

        $sql = "SELECT DISTINCT v.*, u.username,
                    COALESCE(NULLIF(v.thumbnail_url, ''), v.thumbnail_path) AS thumbnail_path,
                    v.views_count AS views
                FROM videos v
                JOIN users u ON v.user_id = u.id
                LEFT JOIN video_tags vt ON v.id = vt.video_id
                WHERE v.status = 'public' AND v.id <> ?";
        $params = [$chatVideoId];

        if ($query !== "") {
            $sql .=
                " AND (v.title LIKE ? OR v.description LIKE ? OR u.username LIKE ? OR vt.tag_slug LIKE ?)";
            $q = "%$query%";
            array_push($params, $q, $q, $q, $q);
        }

        if ($category !== "all") {
            $sql .= " AND vt.tag_slug = ?";
            $params[] = $category;
        }

        $cursorData = $this->decodeSearchCursor($cursor);
        if ($cursorData && ($cursorData["sort"] ?? "") === $sort) {
            if ($sort === "popular") {
                $sql .=
                    " AND (v.views_count < ? OR (v.views_count = ? AND (v.created_at < ? OR (v.created_at = ? AND v.id < ?))))";
                array_push(
                    $params,
                    (int) $cursorData["value"],
                    (int) $cursorData["value"],
                    $cursorData["created_at"],
                    $cursorData["created_at"],
                    $cursorData["id"],
                );
            } elseif ($sort === "duration") {
                $sql .=
                    " AND (v.duration < ? OR (v.duration = ? AND (v.created_at < ? OR (v.created_at = ? AND v.id < ?))))";
                array_push(
                    $params,
                    (int) $cursorData["value"],
                    (int) $cursorData["value"],
                    $cursorData["created_at"],
                    $cursorData["created_at"],
                    $cursorData["id"],
                );
            } else {
                $sql .=
                    " AND (v.created_at < ? OR (v.created_at = ? AND v.id < ?))";
                array_push(
                    $params,
                    $cursorData["created_at"],
                    $cursorData["created_at"],
                    $cursorData["id"],
                );
            }
        }

        $orderBy = match ($sort) {
            "popular" => "v.views_count DESC, v.created_at DESC, v.id DESC",
            "duration" => "v.duration DESC, v.created_at DESC, v.id DESC",
            default => "v.created_at DESC, v.id DESC",
        };
        $sql .= " ORDER BY {$orderBy} LIMIT " . ($limit + 1);
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        $videos = $stmt->fetchAll();
        $hasMore = count($videos) > $limit;
        $items = array_slice($videos, 0, $limit);
        $last = $items ? $items[count($items) - 1] : null;
        $page = [
            "items" => $items,
            "next_cursor" => $hasMore && $last
                ? $this->encodeSearchCursor($last, $sort)
                : null,
            "has_more" => $hasMore,
        ];
        $this->cacheSet($cacheKey, $page, 60);
        $this->jsonResponse($page);
    }

    /**
     * Encodes the last discovery item into an opaque cursor.
     * Input: last video row and active sort mode.
     * Output: base64 JSON cursor for the next page.
     */
    private function encodeSearchCursor(array $video, string $sort): string
    {
        $value = match ($sort) {
            "popular" => (int) ($video["views_count"] ?? 0),
            "duration" => (int) ($video["duration"] ?? 0),
            default => null,
        };
        return base64_encode(
            json_encode([
                "sort" => $sort,
                "value" => $value,
                "created_at" => $video["created_at"] ?? "",
                "id" => $video["id"] ?? "",
            ]),
        );
    }

    /**
     * Decodes a discovery cursor.
     * Input: base64 JSON cursor or null.
     * Output: cursor array when valid, otherwise null.
     */
    private function decodeSearchCursor(?string $cursor): ?array
    {
        if (!$cursor) {
            return null;
        }
        $raw = base64_decode($cursor, true);
        $data = $raw ? json_decode($raw, true) : null;
        if (
            !is_array($data) ||
            empty($data["sort"]) ||
            empty($data["created_at"]) ||
            empty($data["id"])
        ) {
            return null;
        }
        return $data;
    }

    private function notificationUrl(array $notification): string
    {
        if (
            ($notification["target_type"] ?? "") === "video" &&
            !empty($notification["target_id"])
        ) {
            return "/video/" . $notification["target_id"];
        }
        if (
            ($notification["target_type"] ?? "") === "user" &&
            !empty($notification["target_id"])
        ) {
            return "/profile/" . $notification["target_id"];
        }
        if (($notification["target_type"] ?? "") === "comment") {
            $data = json_decode($notification["data"] ?? "{}", true);
            if (!empty($data["video_id"])) {
                return "/video/" . $data["video_id"];
            }
        }
        return "/gallery";
    }

    public function getNotifications(): void
    {
        if (empty($_SESSION["user"])) {
            $this->jsonResponse([], 401);
        }
        $stmt = $this->db()->prepare(
            "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 20",
        );
        $stmt->execute([$_SESSION["user"]["id"]]);
        $items = $stmt->fetchAll();
        foreach ($items as &$item) {
            $item["url"] = $this->notificationUrl($item);
        }
        $this->jsonResponse($items);
    }

    public function markNotificationsRead(): void
    {
        if (empty($_SESSION["user"])) {
            $this->jsonResponse(["success" => false], 401);
        }
        $this->db()
            ->prepare(
                "UPDATE notifications SET read_at = NOW() WHERE user_id = ? AND read_at IS NULL",
            )
            ->execute([$_SESSION["user"]["id"]]);
        $this->jsonResponse(["success" => true]);
    }

    public function toggleSave(string $videoId): void
    {
        if (empty($_SESSION["user"])) {
            $this->jsonResponse(["status" => "error"], 401);
        }
        $this->visibleVideoOrFail($videoId);
        $uid = $_SESSION["user"]["id"];
        $stmt = $this->db()->prepare(
            "SELECT 1 FROM savings WHERE user_id = ? AND video_id = ?",
        );
        $stmt->execute([$uid, $videoId]);
        if ($stmt->fetch()) {
            $this->db()
                ->prepare(
                    "DELETE FROM savings WHERE user_id = ? AND video_id = ?",
                )
                ->execute([$uid, $videoId]);
            $this->cacheDeletePrefix("profile_" . $uid . "_");
            $this->jsonResponse(["status" => "unsaved"]);
        } else {
            $this->db()
                ->prepare(
                    "INSERT INTO savings (user_id, video_id) VALUES (?,?)",
                )
                ->execute([$uid, $videoId]);
            $this->cacheDeletePrefix("profile_" . $uid . "_");
            $this->jsonResponse(["status" => "saved"]);
        }
    }

    public function getSettings(): void
    {
        if (empty($_SESSION["user"])) {
            $this->jsonResponse(["error" => "Auth required"], 401);
        }
        $uid = $_SESSION["user"]["id"];
        $cacheKey = "settings_" . $uid;
        $cached = $this->cacheGet($cacheKey, 300);
        if ($cached !== null) {
            $this->jsonResponse($cached);
        }

        $this->db()
            ->prepare("INSERT IGNORE INTO user_settings (user_id) VALUES (?)")
            ->execute([$uid]);
        $stmt = $this->db()->prepare(
            "SELECT autoplay, show_preroll_ads, show_popunder_ads, notify_comments, notify_follows FROM user_settings WHERE user_id = ? LIMIT 1",
        );
        $stmt->execute([$uid]);
        $settings = $stmt->fetch() ?: [];
        $this->cacheSet($cacheKey, $settings, 300);
        $this->jsonResponse($settings);
    }

    public function updateSettings(array $input): void
    {
        if (empty($_SESSION["user"])) {
            $this->jsonResponse(["error" => "Auth required"], 401);
        }
        $this->visibleVideoOrFail($videoId);
        $uid = $_SESSION["user"]["id"];
        $fields = [
            "autoplay",
            "show_preroll_ads",
            "show_popunder_ads",
            "notify_comments",
            "notify_follows",
        ];
        $values = [];
        foreach ($fields as $field) {
            $values[$field] = !empty($input[$field]) ? 1 : 0;
        }
        $stmt = $this->db()
            ->prepare("INSERT INTO user_settings (user_id, autoplay, show_preroll_ads, show_popunder_ads, notify_comments, notify_follows)
            VALUES (?,?,?,?,?,?)
            ON DUPLICATE KEY UPDATE autoplay = VALUES(autoplay), show_preroll_ads = VALUES(show_preroll_ads), show_popunder_ads = VALUES(show_popunder_ads), notify_comments = VALUES(notify_comments), notify_follows = VALUES(notify_follows)");
        $stmt->execute([
            $uid,
            $values["autoplay"],
            $values["show_preroll_ads"],
            $values["show_popunder_ads"],
            $values["notify_comments"],
            $values["notify_follows"],
        ]);
        $this->cacheDelete("settings_" . $uid);
        $this->jsonResponse(["success" => true, "settings" => $values]);
    }

    public function createReport(array $input): void
    {
        $targetType = $this->validate($input["target_type"] ?? "", "enum", [
            "allowed" => ["video", "comment", "user"],
        ]);
        $targetId = $this->validate($input["target_id"] ?? "", "id");
        $reason = $this->validate($input["reason"] ?? "Other", "text", [
            "max" => 120,
        ]);
        $details = $this->validate($input["details"] ?? "", "text", [
            "max" => 500,
        ]);
        if (
            !$targetType ||
            !$targetId ||
            !$reason ||
            mb_strlen($details) < 10
        ) {
            $this->jsonResponse(
                ["error" => "Report details must be at least 10 characters."],
                400,
            );
        }
        if ($targetType === "video") {
            $this->visibleVideoOrFail($targetId);
        } elseif ($targetType === "comment") {
            $commentStmt = $this->db()->prepare(
                "SELECT c.target_id, v.user_id, v.status
                FROM comments c
                JOIN videos v ON v.id = c.target_id
                WHERE c.id = ? LIMIT 1",
            );
            $commentStmt->execute([$targetId]);
            $commentVideo = $commentStmt->fetch();
            if (!$commentVideo || !$this->canViewVideo($commentVideo)) {
                $this->jsonResponse(["error" => "This comment does not exist."], 404);
            }
        }

        $stmt = $this->db()->prepare(
            "INSERT INTO reports (id, reporter_id, target_type, target_id, reason, details) VALUES (?,?,?,?,?,?)",
        );
        $stmt->execute([
            $this->generateId("r", 12),
            $_SESSION["user"]["id"] ?? null,
            $targetType,
            $targetId,
            $reason,
            $details,
        ]);
        $this->jsonResponse(["success" => true]);
    }

    public function getAds(): void
    {
        $ads = $this->remember("ad_placements_v1", 300, function () {
            $ads = [];
            $services = [];
            foreach (
                array_filter(
                    array_map(
                        "trim",
                        explode(
                            ",",
                            (string) $this->cfg("AD_SERVICE_KEYS", ""),
                        ),
                    ),
                )
                as $service
            ) {
                $part = strtoupper(
                    (string) preg_replace("/[^a-z0-9]+/i", "_", $service),
                );
                $settings = [];
                foreach ($this->config as $key => $value) {
                    $prefix = "AD_SERVICE_{$part}_SETTING_";
                    if (str_starts_with($key, $prefix)) {
                        $settings[strtolower(substr($key, strlen($prefix)))] =
                            $value === "" ? null : $value;
                    }
                }
                $services[$service] = [
                    "display_name" => (string) $this->cfg(
                        "AD_SERVICE_{$part}_DISPLAY_NAME",
                        $service,
                    ),
                    "script_url" =>
                        ($value = $this->cfg(
                            "AD_SERVICE_{$part}_SCRIPT_URL",
                            "",
                        )) === ""
                            ? null
                            : $value,
                    "enabled" => (bool) $this->cfg(
                        "AD_SERVICE_{$part}_ENABLED",
                    ),
                    "settings" => $settings,
                ];
            }
            foreach (
                array_filter(
                    array_map(
                        "trim",
                        explode(
                            ",",
                            (string) $this->cfg("AD_PLACEMENT_KEYS", ""),
                        ),
                    ),
                )
                as $placement
            ) {
                $part = strtoupper(
                    (string) preg_replace("/[^a-z0-9]+/i", "_", $placement),
                );
                $ad = [
                    "source" => (string) $this->cfg(
                        "AD_PLACEMENT_{$part}_SOURCE",
                        "internal",
                    ),
                    "service" => (string) $this->cfg(
                        "AD_PLACEMENT_{$part}_SERVICE",
                        "internal",
                    ),
                    "external_zone_id" =>
                        ($value = $this->cfg(
                            "AD_PLACEMENT_{$part}_EXTERNAL_ZONE_ID",
                            "",
                        )) === ""
                            ? null
                            : $value,
                    "label" => (string) $this->cfg(
                        "AD_PLACEMENT_{$part}_LABEL",
                        "Sponsored",
                    ),
                    "title" => (string) $this->cfg(
                        "AD_PLACEMENT_{$part}_TITLE",
                        "Advertisement",
                    ),
                    "body" => (string) $this->cfg(
                        "AD_PLACEMENT_{$part}_BODY",
                        "",
                    ),
                    "cta_label" => (string) $this->cfg(
                        "AD_PLACEMENT_{$part}_CTA_LABEL",
                        "Learn More",
                    ),
                    "cta_url" => (string) $this->cfg(
                        "AD_PLACEMENT_{$part}_CTA_URL",
                        "#",
                    ),
                    "enabled" => (bool) $this->cfg(
                        "AD_PLACEMENT_{$part}_ENABLED",
                    ),
                    "frequency" => (int) $this->cfg(
                        "AD_PLACEMENT_{$part}_FREQUENCY",
                        1,
                    ),
                ];
                if (empty($ad["enabled"])) {
                    continue;
                }
                $service = $services[$ad["service"] ?? "internal"] ?? null;
                if (
                    ($ad["source"] ?? "internal") === "external" &&
                    (!$service || empty($service["enabled"]))
                ) {
                    continue;
                }
                $ads[$placement] = $this->adaptAdPlacement(
                    ["placement" => $placement, ...$ad],
                    $service,
                );
            }
            return $ads;
        });
        $this->jsonResponse($ads);
    }

    private function adaptAdPlacement(array $ad, ?array $service = null): array
    {
        if (($ad["source"] ?? "internal") === "external") {
            return [
                "placement" => $ad["placement"],
                "source" => "external",
                "service" => $ad["service"],
                "service_name" => $service["display_name"] ?? $ad["service"],
                "script_url" => $service["script_url"] ?? null,
                "external_zone_id" => $ad["external_zone_id"],
                "label" => $ad["label"] ?: "Sponsored",
                "title" => $ad["title"] ?: "Advertisement",
                "body" => $ad["body"],
                "cta_label" => $ad["cta_label"],
                "cta_url" => $ad["cta_url"],
                "frequency" => (int) $ad["frequency"],
            ];
        }

        return [
            "placement" => $ad["placement"],
            "source" => "internal",
            "service" => $ad["service"] ?: "internal",
            "label" => $ad["label"],
            "title" => $ad["title"],
            "body" => $ad["body"],
            "cta_label" => $ad["cta_label"],
            "cta_url" => $ad["cta_url"],
            "frequency" => (int) $ad["frequency"],
        ];
    }

    public function listAdServices(): void
    {
        $services = $this->remember("ad_services_v1", 300, function () {
            $services = [];
            foreach (
                array_filter(
                    array_map(
                        "trim",
                        explode(
                            ",",
                            (string) $this->cfg("AD_SERVICE_KEYS", ""),
                        ),
                    ),
                )
                as $service
            ) {
                $part = strtoupper(
                    (string) preg_replace("/[^a-z0-9]+/i", "_", $service),
                );
                $settings = [];
                foreach ($this->config as $key => $value) {
                    $prefix = "AD_SERVICE_{$part}_SETTING_";
                    if (str_starts_with($key, $prefix)) {
                        $settings[strtolower(substr($key, strlen($prefix)))] =
                            $value === "" ? null : $value;
                    }
                }
                $services[] = [
                    "service" => $service,
                    "display_name" => (string) $this->cfg(
                        "AD_SERVICE_{$part}_DISPLAY_NAME",
                        $service,
                    ),
                    "script_url" =>
                        ($value = $this->cfg(
                            "AD_SERVICE_{$part}_SCRIPT_URL",
                            "",
                        )) === ""
                            ? null
                            : $value,
                    "enabled" => (int) (bool) $this->cfg(
                        "AD_SERVICE_{$part}_ENABLED",
                    ),
                    "settings" => $settings,
                ];
            }
            usort(
                $services,
                fn($a, $b) => [$b["enabled"], $a["display_name"]] <=> [
                    $a["enabled"],
                    $b["display_name"],
                ],
            );
            return $services;
        });
        $this->jsonResponse($services);
    }

    public function getLists(): void
    {
        if (empty($_SESSION["user"])) {
            $this->jsonResponse(["error" => "Auth required"], 401);
        }
        $uid = $_SESSION["user"]["id"];
        $cacheKey = "lists_" . $uid;
        $cached = $this->cacheGet($cacheKey, 60);
        if ($cached !== null) {
            $this->jsonResponse($cached);
        }

        $stmt = $this->db()->prepare(
            "SELECT l.*, COUNT(li.video_id) AS item_count FROM lists l LEFT JOIN list_items li ON li.list_id = l.id WHERE l.user_id = ? AND l.status != 'deleted' GROUP BY l.id ORDER BY l.created_at DESC",
        );
        $stmt->execute([$uid]);
        $lists = $stmt->fetchAll();
        $this->cacheSet($cacheKey, $lists, 60);
        $this->jsonResponse($lists);
    }

    public function toggleWatchLater(string $videoId): void
    {
        if (empty($_SESSION["user"])) {
            $this->jsonResponse(["error" => "Auth required"], 401);
        }
        $uid = $_SESSION["user"]["id"];
        $listId = "wl_" . substr($uid, -5);
        $this->db()
            ->prepare(
                "INSERT IGNORE INTO lists (id, user_id, name, status) VALUES (?, ?, 'Watch Later', 'private')",
            )
            ->execute([$listId, $uid]);
        $stmt = $this->db()->prepare(
            "SELECT 1 FROM list_items WHERE list_id = ? AND video_id = ?",
        );
        $stmt->execute([$listId, $videoId]);
        if ($stmt->fetch()) {
            $this->db()
                ->prepare(
                    "DELETE FROM list_items WHERE list_id = ? AND video_id = ?",
                )
                ->execute([$listId, $videoId]);
            $this->cacheDeletePrefix("profile_" . $uid . "_");
            $this->cacheDelete("lists_" . $uid);
            $this->jsonResponse(["status" => "removed"]);
        }
        $this->db()
            ->prepare(
                "INSERT INTO list_items (list_id, video_id) VALUES (?, ?)",
            )
            ->execute([$listId, $videoId]);
        $this->cacheDeletePrefix("profile_" . $uid . "_");
        $this->cacheDelete("lists_" . $uid);
        $this->jsonResponse(["status" => "added"]);
    }

    public function logActivity(
        string $eventType,
        string $action,
        string $status = "SUCCESS",
        string $actorType = "GUEST",
        ?string $actorId = null,
        ?string $targetType = null,
        ?string $targetId = null,
        array $metadata = [],
    ): void {
        $context = [
            "ip" => $_SERVER["REMOTE_ADDR"] ?? "127.0.0.1",
            "hash" => hash("sha256", $_SERVER["REMOTE_ADDR"] ?? "127.0.0.1"),
            "ua" => $_SERVER["HTTP_USER_AGENT"] ?? null,
        ];

        $stmt = $this->db()->prepare("INSERT INTO activity_logs
            (event_type, action, status, actor_type, actor_id, target_type, target_id, context, metadata)
            VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $eventType,
            $action,
            $status,
            $actorType,
            $actorId,
            $targetType,
            $targetId,
            json_encode($context),
            !empty($metadata) ? json_encode($metadata) : null,
        ]);
    }

    public function logAdminAction(
        string $modId,
        string $targetType,
        string $targetId,
        string $action,
        ?string $reason = null,
    ): void {
        $this->logActivity(
            "ADMIN_ACTION",
            $action,
            "SUCCESS",
            "ADMIN",
            $modId,
            $targetType,
            $targetId,
            ["reason" => $reason],
        );
    }

    private function analyticsInputList(array $input): array
    {
        if (isset($input["events"]) && is_array($input["events"])) {
            return array_values(
                array_filter($input["events"], static fn($event) => is_array($event)),
            );
        }

        $isList = array_is_list($input);
        if ($isList) {
            return array_values(
                array_filter($input, static fn($event) => is_array($event)),
            );
        }

        return [$input];
    }

    private function insertAnalyticsEvent(array $input): void
    {
        $eventType = $this->validate($input["event_type"] ?? "", "text", [
            "max" => 50,
        ]);
        $sessionId = $this->validate($input["session_id"] ?? "", "text", [
            "max" => 36,
        ]);
        if (!$eventType || !$sessionId) {
            throw new InvalidArgumentException("Invalid analytics event");
        }

        $metadata = $input["metadata"] ?? null;
        if (is_array($metadata)) {
            $metadata = json_encode($metadata);
        } elseif (is_string($metadata) && mb_strlen($metadata) <= 2000) {
            json_decode($metadata, true);
            $metadata =
                json_last_error() === JSON_ERROR_NONE ? $metadata : null;
        } else {
            $metadata = null;
        }

        $stmt = $this->db()->prepare("INSERT INTO analytics_events
            (session_id, user_id, event_type, page, route, target_type, target_id, source, search_query, category,
             duration_ms, watch_time_ms, video_current_time, video_duration, scroll_depth, viewport, referrer,
             metadata, ip_hash, user_agent)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $stmt->execute([
            $sessionId,
            $_SESSION["user"]["id"] ?? null,
            $eventType,
            $this->validate($input["page"] ?? null, "text", ["max" => 50]),
            $this->validate($input["route"] ?? null, "text", ["max" => 255]),
            $this->validate($input["target_type"] ?? null, "text", [
                "max" => 50,
            ]),
            $this->validate($input["target_id"] ?? null, "text", ["max" => 64]),
            $this->validate($input["source"] ?? null, "text", ["max" => 80]),
            $this->validate($input["search_query"] ?? null, "text", [
                "max" => 150,
            ]),
            $this->validate($input["category"] ?? null, "text", ["max" => 80]),
            isset($input["duration_ms"])
                ? max(0, (int) $input["duration_ms"])
                : null,
            isset($input["watch_time_ms"])
                ? max(0, (int) $input["watch_time_ms"])
                : null,
            isset($input["video_current_time"])
                ? max(0, (float) $input["video_current_time"])
                : null,
            isset($input["video_duration"])
                ? max(0, (float) $input["video_duration"])
                : null,
            isset($input["scroll_depth"])
                ? min(100, max(0, (int) $input["scroll_depth"]))
                : null,
            $this->validate($input["viewport"] ?? null, "text", ["max" => 40]),
            $this->validate($input["referrer"] ?? null, "text", ["max" => 255]),
            $metadata,
            hash("sha256", $_SERVER["REMOTE_ADDR"] ?? "127.0.0.1"),
            mb_substr((string) ($_SERVER["HTTP_USER_AGENT"] ?? ""), 0, 255),
        ]);
    }

    public function recordAnalytics(array $input): void
    {
        $events = array_slice($this->analyticsInputList($input), 0, 25);
        if (!$events) {
            $this->jsonResponse(["error" => "Invalid analytics event"], 400);
        }

        try {
            $this->db()->beginTransaction();
            foreach ($events as $event) {
                $this->insertAnalyticsEvent($event);
            }
            $this->db()->commit();
        } catch (InvalidArgumentException $exception) {
            if ($this->db()->inTransaction()) {
                $this->db()->rollBack();
            }
            $this->jsonResponse(["error" => $exception->getMessage()], 400);
        } catch (Throwable $exception) {
            if ($this->db()->inTransaction()) {
                $this->db()->rollBack();
            }
            throw $exception;
        }

        $this->maybeScheduleAnalyticsJobs();
        $this->jsonResponse(["success" => true, "count" => count($events)]);
    }

    public function login(string $user, string $pass, string $captchaToken = ""): void
    {
        $this->checkRateLimit("login", 8, 300);
        $this->verifyCaptcha($captchaToken);
        $stmt = $this->db()->prepare(
            "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1",
        );
        $stmt->execute([$user, $user]);
        $u = $stmt->fetch();

        $statusAllowed = $u && $this->canLoginWithStatus($u["status"] ?? null);
        $success =
            $statusAllowed && password_verify($pass, $u["password_hash"]);

        if ($success) {
            session_regenerate_id(true);
            $_SESSION["user"] = [
                "id" => $u["id"],
                "username" => $u["username"],
                "display_name" => $u["display_name"] ?? $u["username"],
                "role" => $u["role"] ?? "user",
            ];
            $this->csrfToken();
            $this->logActivity(
                "LOGIN_ATTEMPT",
                "LOGIN_SUCCESS",
                "SUCCESS",
                "USER",
                $u["id"],
                "user",
                $u["id"],
            );
            $this->cacheDelete("user_" . $u["id"]);
            $this->jsonResponse([
                "success" => true,
                "csrf" => $_SESSION["csrf_token"],
            ]);
        } else {
            // Store failed login attempts for audit.
            $this->logActivity(
                "LOGIN_ATTEMPT",
                "LOGIN_FAILED",
                "FAILED",
                "GUEST",
                null,
                "user",
                $u ? $u["id"] : null,
                [
                    "email" => $user,
                    "reason" => !$u
                        ? "user_not_found"
                        : ($statusAllowed
                            ? "invalid_password"
                            : "status_blocked"),
                ],
            );
            $this->jsonResponse(
                ["error" => "Invalid username or password"],
                401,
            );
        }
    }

    public function register(
        ?string $username,
        ?string $email,
        ?string $password,
        string $captchaToken = "",
    ): void {
        $this->checkRateLimit("register", 5, 600);
        $this->verifyCaptcha($captchaToken);
        $username = $this->validate($username ?? "", "text", ["max" => 50]);
        $email = $this->validate($email ?? "", "email");

        if (!$username || !$email || !$password || strlen($password) < 4) {
            $this->jsonResponse(["error" => "Invalid registration input"], 400);
        }

        $exists = $this->db()->prepare(
            "SELECT 1 FROM users WHERE username = ? OR email = ? LIMIT 1",
        );
        $exists->execute([$username, $email]);
        if ($exists->fetch()) {
            $this->jsonResponse(
                ["error" => "Username or email already exists"],
                409,
            );
        }

        $id = $this->generateId("u", 8);
        $stmt = $this->db()->prepare(
            "INSERT INTO users (id, username, display_name, email, password_hash, status) VALUES (?,?,?,?,?, 'active')",
        );
        $stmt->execute([
            $id,
            $username,
            $username,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
        ]);
        $this->db()
            ->prepare("INSERT INTO user_settings (user_id) VALUES (?)")
            ->execute([$id]);
        $this->cacheDelete("global_stats");
        $this->jsonResponse(["success" => true, "id" => $id]);
    }
}

// --- Runtime bootstrap and API router ---
$App = new LimeVideo();
ini_set("session.use_strict_mode", "1");
session_save_path($App->cachePath());
session_set_cookie_params([
    "lifetime" => 0,
    "path" => "/",
    "secure" => (bool) $App->cfg("SITE_HTTPS"),
    "httponly" => true,
    "samesite" => "Lax",
]);
session_start();

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$method = $_SERVER["REQUEST_METHOD"];

if ($uri === "/sitemap.xml" && is_file(__DIR__ . "/sitemap.xml")) {
    header("Content-Type: application/xml; charset=utf-8");
    readfile(__DIR__ . "/sitemap.xml");
    exit();
}

if (strpos($uri, "/api/") === 0) {
    $endpoint = substr($uri, 5);
    $input = json_decode(file_get_contents("php://input"), true) ?? $_POST;

    try {
        $App->assertCsrf($endpoint, $method);
        if ($method === "POST") {
            match ($endpoint) {
                "vote" => $App->checkRateLimit("vote", 60, 60),
                "comment" => $App->checkRateLimit("comment", 12, 60),
                "chat/messages" => $App->checkRateLimit(
                    "chat_messages",
                    20,
                    60,
                ),
                "report" => $App->checkRateLimit("report", 10, 600),
                "external_video" => $App->checkRateLimit(
                    "external_video",
                    10,
                    600,
                ),
                "analytics" => $App->checkRateLimit("analytics", 240, 60),
                default => null,
            };
        }
        match ($endpoint) {
            "sitemap/regenerate" => $App->regenerateSitemap(
                $_GET["token"] ?? null,
            ),
            "cron/run" => $App->runCronJobs(
                (int) ($_GET["limit"] ?? 10),
                $_GET["token"] ?? null,
            ),
            "stats" => $App->jsonResponse($App->getStats()),
            "tags" => $App->jsonResponse($App->fetch("tags")),
            "site_config" => $App->jsonResponse([
                "domain" => (string) $App->cfg("SITE_DOMAIN"),
                "https" => (bool) $App->cfg("SITE_HTTPS"),
                "base_url" => (string) $App->cfg("SITE_BASE_URL"),
                "dev_mode" => (bool) $App->cfg("DEV_MODE"),
                "captcha" => $App->captchaPublicConfig(),
            ]),
            "trending" => $App->getTrending(),
            "search" => $App->search(
                $App->validate($_GET["q"] ?? "", "text", ["max" => 100]),
                $App->validate($_GET["cat"] ?? "all", "text", ["max" => 50]),
                $App->validate($_GET["sort"] ?? "newest", "enum", [
                    "allowed" => ["newest", "popular", "duration"],
                ]) ?? "newest",
                $App->validate($_GET["cursor"] ?? null, "text", [
                    "max" => 500,
                ]),
                (int) ($_GET["limit"] ?? 24),
            ),
            "video" => $App->getVideoDetail(
                $App->validate($_GET["id"] ?? "", "id"),
            ),
            "comments" => $App->getComments(
                $App->validate($_GET["video_id"] ?? "", "id"),
                $App->validate($_GET["before"] ?? null, "text", ["max" => 40]),
                $App->validate($_GET["sort"] ?? "new", "enum", [
                    "allowed" => ["new", "top"],
                ]) ?? "new",
            ),
            "chat/messages" => $method === "POST"
                ? $App->postChatMessage($input["body"] ?? "")
                : $App->getChatMessages(
                    $App->validate($_GET["after"] ?? null, "text", [
                        "max" => 40,
                    ]),
                ),
            "profile" => $App->getProfile(
                $App->validate($_GET["id"] ?? "", "id"),
                $App->validate($_GET["tab"] ?? "videos", "enum", [
                    "allowed" => [
                        "videos",
                        "saved",
                        "liked",
                        "comments",
                        "about",
                        "all",
                    ],
                ]),
            ),
            "me" => $App->jsonResponse(
                isset($_SESSION["user"])
                    ? array_merge(
                        $App->getUser($_SESSION["user"]["id"]) ?? [],
                        ["csrf" => $App->csrfToken()],
                    )
                    : ["csrf" => $App->csrfToken()],
            ),
            "vote" => $App->vote(
                $App->validate($input["target_id"] ?? "", "id"),
                $App->validate($input["type"] ?? "up", "enum", [
                    "allowed" => ["up", "down"],
                ]),
                $App->validate($input["target_type"] ?? "video", "enum", [
                    "allowed" => ["video", "comment"],
                ]) ?? "video",
            ),
            "follow" => $App->toggleFollow(
                $App->validate($input["user_id"] ?? "", "id"),
            ),
            "comment" => $App->comment(
                $App->validate($input["video_id"] ?? "", "id"),
                $App->validate($input["body"] ?? "", "text", ["max" => 1000]),
                $App->validate($input["parent_id"] ?? null, "id"),
            ),
            "update_profile" => $App->updateProfile($input),
            "login" => $App->login(
                $input["user"] ?? "",
                $input["pass"] ?? "",
                $App->captchaTokenFromInput($input),
            ),
            "register" => $App->register(
                $input["user"] ?? "",
                $input["email"] ?? "",
                $input["pass"] ?? "",
                $App->captchaTokenFromInput($input),
            ),
            "logout" => (function () use ($App, $method) {
                if ($method !== "POST") {
                    $App->jsonResponse(["error" => "Method not allowed"], 405);
                }
                $_SESSION = [];
                session_destroy();
                $App->jsonResponse(["success" => true]);
            })(),
            "notifications" => $App->getNotifications(),
            "read_notifications" => $App->markNotificationsRead(),
            "save" => $App->toggleSave(
                $App->validate($input["video_id"] ?? "", "id"),
            ),
            "settings" => $method === "POST"
                ? $App->updateSettings($input)
                : $App->getSettings(),
            "report" => $App->createReport($input),
            "ads" => $App->getAds(),
            "ad_services" => $App->listAdServices(),
            "external_video" => $App->createExternalVideo($input),
            "provider_webhook" => $App->providerWebhook(
                $App->validate($_GET["provider"] ?? "", "text", ["max" => 50]),
                $input,
            ),
            "lists" => $App->getLists(),
            "watch_later" => $App->toggleWatchLater(
                $App->validate($input["video_id"] ?? "", "id"),
            ),
            "analytics" => $App->recordAnalytics($input),
            default => $App->jsonResponse(
                ["error" => "API endpoint not found"],
                404,
            ),
        };
    } catch (Throwable $e) {
        error_log("LimeVideo API error: " . $e->getMessage());
        if ((bool) $App->cfg("DEV_MODE")) {
            $App->errorResponse($e, 500);
        }
        $App->jsonResponse(["error" => "Server error"], 500);
    }
}

// --- SPA shell fallback ---
// Every non-API path falls back to index.html; frontend owns route resolution.
include "index.html";
exit();
