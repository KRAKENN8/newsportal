<?php
/**
 * CyberPulse Unified Security Engine
 * Provides defense-in-depth protection:
 * - HTTP Security Headers (Clickjacking, MIME-sniffing, Referrer Policy)
 * - Hardened Session Management & Session Fixation Mitigation
 * - Cryptographic Cross-Site Request Forgery (CSRF) Protection
 * - IP & Session Rate Limiting / Brute-Force Throttling
 */
class Security {
    /**
     * Dispatch baseline defensive HTTP headers
     */
    public static function sendSecurityHeaders(): void {
        if (!headers_sent()) {
            header('X-Frame-Options: SAMEORIGIN');
            header('X-Content-Type-Options: nosniff');
            header('Referrer-Policy: strict-origin-when-cross-origin');
            header('X-XSS-Protection: 1; mode=block');
            header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
        }
    }

    /**
     * Start hardened session with defense-in-depth cookie parameters
     */
    public static function initSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);

            // Configure secure session cookie attributes
            if (!headers_sent()) {
                session_set_cookie_params([
                    'lifetime' => 0,
                    'path'     => '/',
                    'domain'   => '',
                    'secure'   => $isSecure,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            }
            session_start();
        }
    }

    /**
     * Mitigate session fixation by rotating session ID on privilege level changes
     */
    public static function regenerateSession(): void {
        if (session_status() === PHP_SESSION_ACTIVE && !headers_sent()) {
            @session_regenerate_id(true);
        }
    }

    /**
     * Get or create a cryptographically secure CSRF token
     */
    public static function getCsrfToken(): string {
        self::initSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Render hidden HTML form input with active CSRF token
     */
    public static function renderCsrfField(): string {
        $token = htmlspecialchars(self::getCsrfToken(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    /**
     * Verify whether a submitted CSRF token matches the session token
     */
    public static function validateCsrfToken(?string $token = null): bool {
        self::initSession();

        if (empty($_SESSION['csrf_token'])) {
            return false;
        }

        if ($token === null) {
            $token = $_POST['csrf_token'] ?? $_GET['csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        }

        if (!is_string($token) || empty($token)) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Check if client exceeded maximum allowed attempts
     *
     * @param string $action Action key (e.g. 'login', 'admin_login')
     * @param int $maxAttempts Maximum allowed attempts
     * @param int $decaySeconds Lockout duration in seconds
     * @return array [isBlocked => bool, remainingSeconds => int]
     */
    public static function checkRateLimit(string $action, int $maxAttempts = 5, int $decaySeconds = 300): array {
        self::initSession();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $key = 'rate_' . md5($action . '_' . $ip);

        if (!isset($_SESSION[$key])) {
            return ['isBlocked' => false, 'remainingSeconds' => 0];
        }

        $record = $_SESSION[$key];
        $now = time();

        // Expired lockout
        if ($now - $record['last_attempt'] > $decaySeconds) {
            unset($_SESSION[$key]);
            return ['isBlocked' => false, 'remainingSeconds' => 0];
        }

        if ($record['count'] >= $maxAttempts) {
            $remaining = $decaySeconds - ($now - $record['last_attempt']);
            return ['isBlocked' => true, 'remainingSeconds' => max(1, $remaining)];
        }

        return ['isBlocked' => false, 'remainingSeconds' => 0];
    }

    /**
     * Increment failed attempt count
     */
    public static function recordFailedAttempt(string $action): void {
        self::initSession();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $key = 'rate_' . md5($action . '_' . $ip);
        $now = time();

        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 1, 'last_attempt' => $now];
        } else {
            $_SESSION[$key]['count']++;
            $_SESSION[$key]['last_attempt'] = $now;
        }
    }

    /**
     * Reset rate limit upon successful action
     */
    public static function clearRateLimit(string $action): void {
        self::initSession();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $key = 'rate_' . md5($action . '_' . $ip);
        unset($_SESSION[$key]);
    }
}
