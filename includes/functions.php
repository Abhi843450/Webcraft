<?php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

function isLoggedIn() {
    return isset($_SESSION['admin_id']) && $_SESSION['admin_id'] > 0;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }
}

function getAdmin() {
    if (!isLoggedIn()) return null;
    return db()->fetch("SELECT a.*, r.name as role_name, r.permissions FROM admins a JOIN admin_roles r ON a.role_id = r.id WHERE a.id = ?", [$_SESSION['admin_id']]);
}

function hasPermission($permission) {
    $admin = getAdmin();
    if (!$admin) return false;
    $perms = json_decode($admin['permissions'], true);
    if (isset($perms['all']) && $perms['all']) return true;
    return isset($perms[$permission]) && $perms[$permission];
}

function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

function verifyCsrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            die('CSRF token validation failed.');
        }
    }
}

function formatCurrency($amount) {
    $symbol = 'NPR';
    return $symbol . ' ' . number_format($amount, 0);
}

function generateRequirementId() {
    $year = date('Y');
    $last = db()->fetch("SELECT requirement_id FROM requirements ORDER BY id DESC LIMIT 1");
    if ($last) {
        $num = intval(substr($last['requirement_id'], -5)) + 1;
    } else {
        $num = 1;
    }
    return sprintf('REQ-%s-%05d', $year, $num);
}

function generateQuotationNumber() {
    $year = date('Y');
    $last = db()->fetch("SELECT quotation_number FROM quotations ORDER BY id DESC LIMIT 1");
    if ($last) {
        $num = intval(substr($last['quotation_number'], -5)) + 1;
    } else {
        $num = 1;
    }
    return sprintf('QUO-%s-%05d', $year, $num);
}

function getStatusBadge($status) {
    $colors = [
        'new' => 'primary',
        'contacted' => 'info',
        'under_review' => 'warning',
        'requirement_clarification' => 'secondary',
        'quotation_prepared' => 'info',
        'quotation_sent' => 'primary',
        'negotiation' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
        'on_hold' => 'secondary',
        'converted' => 'success',
        'completed' => 'dark',
        'cancelled' => 'danger'
    ];
    $color = $colors[$status] ?? 'secondary';
    $label = STATUS_OPTIONS[$status] ?? ucfirst(str_replace('_', ' ', $status));
    return '<span class="badge bg-' . $color . '">' . $label . '</span>';
}

function logAudit($action, $targetType = null, $targetId = null, $details = null) {
    db()->insert('audit_logs', [
        'admin_id' => $_SESSION['admin_id'] ?? null,
        'action' => $action,
        'target_type' => $targetType,
        'target_id' => $targetId,
        'details' => $details ? json_encode($details) : null,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
    ]);
}

function flash($key, $message = null) {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
    } else {
        $msg = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
}

function getSetting($key) {
    static $settings = null;
    if ($settings === null) {
        $rows = db()->fetchAll("SELECT setting_key, setting_value FROM settings");
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    return $settings[$key] ?? null;
}

function setSetting($key, $value) {
    db()->query("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?", [$key, $value, $value]);
}

function timeAgo($datetime) {
    $now = new DateTime();
    $past = new DateTime($datetime);
    $diff = $now->diff($past);
    if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'just now';
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}
