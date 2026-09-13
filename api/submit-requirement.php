<?php
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(BASE_URL . '/requirements.php');
}

// Support both form-encoded and JSON submissions
$jsonInput = file_get_contents('php://input');
$parsedJson = json_decode($jsonInput, true);
$isJson = is_array($parsedJson) && !empty($parsedJson);
if ($isJson) {
    $data = $parsedJson;
} else {
    verifyCsrf();
    $data = $_POST;
}

function jsonResponse($success, $message = '', $extra = []) {
    header('Content-Type: application/json');
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra));
    exit;
}

// Validate required fields
$required = ['customer_name', 'customer_email', 'website_type_id'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        if ($isJson) { jsonResponse(false, 'Please fill in all required fields.'); }
        flash('error', 'Please fill in all required fields.');
        header('Location: ' . BASE_URL . '/requirements.php?step=' . ($_POST['current_step'] ?? 1));
        exit;
    }
}

if (!filter_var($data['customer_email'], FILTER_VALIDATE_EMAIL)) {
    if ($isJson) { jsonResponse(false, 'Please enter a valid email address.'); }
    flash('error', 'Please enter a valid email address.');
    header('Location: ' . BASE_URL . '/requirements.php?step=' . ($_POST['current_step'] ?? 1));
    exit;
}

// Process features
$featureIds = array_filter(array_map('intval', $data['features'] ?? []));
$featureList = [];
if ($featureIds) {
    $placeholders = implode(',', array_fill(0, count($featureIds), '?'));
    $featureList = db()->fetchAll("SELECT id, name, price FROM features WHERE id IN ({$placeholders})", $featureIds);
}

// Calculate price server-side
$websiteType = db()->fetch("SELECT * FROM website_types WHERE id = ?", [intval($data['website_type_id'])]);
$basePrice = $websiteType ? $websiteType['base_price'] : 0;
$featureTotal = array_sum(array_column($featureList, 'price'));

$pageCount = intval($data['page_count'] ?? 5);
$pageRule = db()->fetch("SELECT * FROM pricing_rules WHERE rule_type = 'per_page' AND is_active = 1 LIMIT 1");
$pageCost = 0;
if ($pageRule && $pageCount > $pageRule['included_units']) {
    $pageCost = ($pageCount - $pageRule['included_units']) * $pageRule['unit_amount'];
}

$langCount = max(1, intval($data['language_count'] ?? 1));
$langRule = db()->fetch("SELECT * FROM pricing_rules WHERE rule_type = 'per_language' AND is_active = 1 LIMIT 1");
$langCost = 0;
if ($langRule && $langCount > $langRule['included_units']) {
    $langCost = ($langCount - $langRule['included_units']) * $langRule['unit_amount'];
}

$totalEstimate = $basePrice + $featureTotal + $pageCost + $langCost;

// Complexity
$complexity = count($featureIds) * 2;
if (($data['needs_ecommerce'] ?? '') === 'yes') $complexity += 15;
if (($data['needs_booking'] ?? '') === 'yes') $complexity += 12;
if (($data['needs_payment'] ?? '') === 'yes') $complexity += 8;
if (($data['needs_user_accounts'] ?? '') === 'yes') $complexity += 10;
if ($langCount > 1) $complexity += $langCount * 3;
if ($pageCount > 10) $complexity += 5;

if ($complexity <= 20) $level = 'basic';
elseif ($complexity <= 50) $level = 'moderate';
elseif ($complexity <= 100) $level = 'advanced';
else $level = 'complex';

$confidence = 'high';
if ($level === 'advanced') $confidence = 'medium';
if ($level === 'complex') $confidence = 'low';

$requirementId = generateRequirementId();

// File uploads
$uploadedFiles = [];
if (!empty($_FILES['files']['name'][0])) {
    $uploadDir = UPLOAD_PATH . '/' . time() . '_' . bin2hex(random_bytes(8));
    mkdir($uploadDir, 0755, true);

    foreach ($_FILES['files']['name'] as $i => $name) {
        if ($_FILES['files']['error'][$i] !== UPLOAD_ERR_OK) continue;

        $tmpName = $_FILES['files']['tmp_name'][$i];
        $size = $_FILES['files']['size'][$i];
        $type = $_FILES['files']['type'][$i];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if ($size > MAX_UPLOAD_SIZE) continue;
        if (!in_array($type, ALLOWED_UPLOAD_TYPES)) continue;

        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
        $storedName = bin2hex(random_bytes(16)) . '.' . $ext;
        move_uploaded_file($tmpName, $uploadDir . '/' . $storedName);

        $uploadedFiles[] = [
            'file_type' => pathinfo($name, PATHINFO_EXTENSION),
            'original_name' => $name,
            'stored_name' => $storedName,
            'file_size' => $size,
            'mime_type' => $type,
            'upload_path' => str_replace(BASE_PATH, '', $uploadDir)
        ];
    }
}

// Insert requirement
$reqId = db()->insert('requirements', [
    'requirement_id' => $requirementId,
    'resume_token' => bin2hex(random_bytes(32)),
    'status' => 'new',
    'website_type_id' => intval($data['website_type_id']),
    'website_type_other' => $data['website_type_other'] ?? null,
    'project_purpose' => json_encode($data['project_purpose'] ?? []),
    'project_purpose_other' => $data['project_purpose_other'] ?? null,
    'project_name' => $data['project_name'] ?? null,
    'project_description' => $data['project_description'] ?? null,
    'existing_website' => $data['existing_website'] ?? 'no',
    'existing_website_url' => $data['existing_website_url'] ?? null,
    'existing_website_problems' => json_encode($data['existing_website_problems'] ?? []),
    'design_available' => $data['design_available'] ?? 'need_design',
    'design_style' => json_encode($data['design_style'] ?? []),
    'design_style_other' => $data['design_style_other'] ?? null,
    'reference_urls' => json_encode(array_filter(array_map('trim', explode("\n", $data['reference_urls'] ?? '')))),
    'reference_notes' => $data['reference_notes'] ?? null,
    'has_logo' => $data['has_logo'] ?? 'no',
    'has_brand_colors' => $data['has_brand_colors'] ?? 'no',
    'has_brand_guidelines' => $data['has_brand_guidelines'] ?? 'no',
    'selected_pages' => json_encode($data['selected_pages'] ?? []),
    'custom_pages' => json_encode(array_filter(array_map('trim', explode("\n", $data['custom_pages'] ?? '')))),
    'page_count' => $data['page_count'] ?? null,
    'content_provider' => $data['content_provider'] ?? 'customer',
    'content_requirements' => json_encode($data['content_requirements'] ?? []),
    'needs_cms' => $data['needs_cms'] ?? 'no',
    'cms_manage_items' => json_encode($data['cms_manage_items'] ?? []),
    'needs_user_accounts' => $data['needs_user_accounts'] ?? 'no',
    'user_features' => json_encode($data['user_features'] ?? []),
    'user_roles' => json_encode($data['user_roles'] ?? []),
    'user_roles_other' => $data['user_roles_other'] ?? null,
    'needs_ecommerce' => $data['needs_ecommerce'] ?? 'no',
    'ecommerce_features' => json_encode($data['ecommerce_features'] ?? []),
    'product_quantity' => $data['product_quantity'] ?? null,
    'needs_booking' => $data['needs_booking'] ?? 'no',
    'booking_types' => json_encode($data['booking_types'] ?? []),
    'booking_features' => json_encode($data['booking_features'] ?? []),
    'needs_payment' => $data['needs_payment'] ?? 'no',
    'payment_methods' => json_encode($data['payment_methods'] ?? []),
    'payment_methods_other' => $data['payment_methods_other'] ?? null,
    'communication_features' => json_encode($data['communication_features'] ?? []),
    'search_type' => $data['search_type'] ?? 'none',
    'search_filters' => json_encode($data['search_filters'] ?? []),
    'needs_maps' => $data['needs_maps'] ?? 'no',
    'map_features' => json_encode($data['map_features'] ?? []),
    'language_count' => $langCount,
    'languages' => json_encode($data['languages'] ?? []),
    'seo_level' => $data['seo_level'] ?? 'none',
    'seo_features' => json_encode($data['seo_features'] ?? []),
    'analytics_features' => json_encode($data['analytics_features'] ?? []),
    'security_features' => json_encode($data['security_features'] ?? []),
    'hosting_domain' => json_encode([
        'domain' => $data['domain_status'] ?? null,
        'hosting' => $data['hosting_status'] ?? null,
        'email' => $data['email_status'] ?? null
    ]),
    'integrations' => json_encode($data['integrations'] ?? []),
    'integrations_detail' => json_encode($data['integrations_detail'] ?? []),
    'special_requirements' => $data['special_requirements'] ?? null,
    'specific_workflow' => $data['specific_workflow'] ?? null,
    'timeline' => $data['timeline'] ?? 'no_deadline',
    'launch_date' => !empty($data['launch_date']) ? $data['launch_date'] : null,
    'budget_range' => $data['budget_range'] ?? null,
    'needs_maintenance' => $data['needs_maintenance'] ?? 'no',
    'maintenance_features' => json_encode($data['maintenance_features'] ?? []),
    'customer_name' => $data['customer_name'],
    'customer_email' => $data['customer_email'],
    'customer_phone' => $data['customer_phone'] ?? null,
    'customer_company' => $data['customer_company'] ?? null,
    'customer_address' => $data['customer_address'] ?? null,
    'customer_city' => $data['customer_city'] ?? null,
    'customer_country' => $data['customer_country'] ?? 'Nepal',
    'preferred_contact' => $data['preferred_contact'] ?? 'email',
    'submitted_ip' => $_SERVER['REMOTE_ADDR'] ?? null,
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
    'system_estimate' => $totalEstimate,
    'complexity_score' => $complexity,
    'complexity_level' => $level,
    'estimate_confidence' => $confidence,
    'submission_json' => json_encode($data)
]);

// Insert feature associations
foreach ($featureList as $f) {
    db()->insert('requirement_features', [
        'requirement_id' => $reqId,
        'feature_id' => $f['id'],
        'price' => $f['price']
    ]);
}

// Insert uploaded files
foreach ($uploadedFiles as $file) {
    db()->insert('requirement_files', [
        'requirement_id' => $reqId,
        'file_type' => $file['file_type'],
        'original_name' => $file['original_name'],
        'stored_name' => $file['stored_name'],
        'file_size' => $file['file_size'],
        'mime_type' => $file['mime_type']
    ]);
}

// Initial status history
db()->insert('requirement_status_history', [
    'requirement_id' => $reqId,
    'old_status' => null,
    'new_status' => 'new',
    'notes' => 'Requirement submitted'
]);

logAudit('requirement_submitted', 'requirement', $reqId);

$_SESSION['submitted_requirement'] = [
    'id' => $requirementId,
    'estimate' => $totalEstimate,
    'type' => $websiteType['name'] ?? 'Website',
    'timeline' => TIMELINE_OPTIONS[$data['timeline'] ?? 'no_deadline'] ?? 'Flexible'
];

if ($isJson) {
    jsonResponse(true, '', [
        'requirement_id' => $requirementId,
        'estimate' => $totalEstimate,
        'redirect' => BASE_URL . '/thank-you.php'
    ]);
}

header('Location: ' . BASE_URL . '/thank-you.php');
exit;
