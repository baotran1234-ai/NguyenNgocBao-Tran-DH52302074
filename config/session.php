<?php
// ================================================================
// config/session.php - QuáÂºÂ£n lÃƒÂ½ Session & Flash Messages
// ================================================================

if (session_status() === PHP_SESSION_NONE) {
    session_save_path(__DIR__ . '/../tmp');
    session_start();
}

// ---- KiáÂ»Æ’m tra Ã„â€˜Ã„Æ’ng nháÂºÂ­p ----
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isAdminLoggedIn(): bool {
    return isset($_SESSION['admin_id']);
}

// ---- LáÂºÂ¥y thÃƒÂ´ng tin ngÃ†Â°áÂ»Âi dÃƒÂ¹ng hiáÂ»â€¡n táÂºÂ¡i ----
function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function currentAdmin(): ?array {
    return $_SESSION['admin'] ?? null;
}

// ---- Flash Messages (MáÂºÂ·c Ã„â€˜áÂ»â€¹nh dÃƒÂ¹ng 1 message duy nháÂºÂ¥t) ----
function setFlash(string $type, string $message): void {
    $_SESSION['flash_message'] = [
        'type'    => $type,
        'message' => $message
    ];
}

function getFlash(): ?array {
    if (!isset($_SESSION['flash_message'])) return null;
    $flash = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
    return $flash;
}

// ---- TÃ†Â°Ã†Â¡ng thÃƒÂ­ch ngÃ†Â°áÂ»Â£c (náÂºÂ¿u cÃƒÂ³ dÃƒÂ¹ng key) ----
function hasFlash(string $key = ''): bool {
    return isset($_SESSION['flash_message']);
}

// ---- Regenerate Session ID (bảo mật) ----
function regenerateSession(): void {
    session_regenerate_id(true);
}