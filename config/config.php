<?php
define('APP_NAME', 'WebCraft Studio');
define('APP_TAGLINE', 'Website Requirements & Quotation System');
define('BASE_PATH', dirname(__DIR__));

// Dynamic BASE_URL: works locally and on Render
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isLocal = str_contains($host, 'localhost');
define('BASE_URL', $isLocal ? '/website-requirement-builder' : '');
define('UPLOAD_PATH', BASE_PATH . '/assets/uploads');
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024);

define('ALLOWED_UPLOAD_TYPES', [
    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'application/zip',
    'text/plain'
]);

define('STATUS_OPTIONS', [
    'new' => 'New',
    'contacted' => 'Contacted',
    'under_review' => 'Under Review',
    'requirement_clarification' => 'Requirement Clarification',
    'quotation_prepared' => 'Quotation Prepared',
    'quotation_sent' => 'Quotation Sent',
    'negotiation' => 'Negotiation',
    'approved' => 'Approved',
    'rejected' => 'Rejected',
    'on_hold' => 'On Hold',
    'converted' => 'Converted to Project',
    'completed' => 'Completed',
    'cancelled' => 'Cancelled'
]);

define('TIMELINE_OPTIONS', [
    'no_deadline' => 'No specific deadline',
    '1month' => 'Within 1 month',
    '1_2months' => '1-2 months',
    '2_3months' => '2-3 months',
    '3_6months' => '3-6 months',
    '6plus' => '6+ months'
]);

define('BUDGET_OPTIONS', [
    'below_25k' => 'Below NPR 25,000',
    '25k_50k' => 'NPR 25,000-50,000',
    '50k_100k' => 'NPR 50,000-100,000',
    '100k_250k' => 'NPR 100,000-250,000',
    '250k_500k' => 'NPR 250,000-500,000',
    '500k_plus' => 'NPR 500,000+',
    'not_sure' => 'Not sure'
]);

// Auto-init DB on production (Render) first run
if (!$isLocal) {
    require_once BASE_PATH . '/install.php';
}
