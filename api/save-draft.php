<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/functions.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['error' => 'No data received']);
    exit;
}

$token = $input['resume_token'] ?? null;
$data = $input['form_data'] ?? [];

if ($token) {
    // Update existing draft
    $req = db()->fetch("SELECT id FROM requirements WHERE resume_token = ?", [$token]);
    if ($req) {
        db()->update('requirements', [
            'submission_json' => json_encode($data)
        ], 'resume_token = ?', [$token]);
        echo json_encode(['success' => true, 'token' => $token]);
        exit;
    }
}

// Create new draft
$token = bin2hex(random_bytes(32));
$reqId = db()->insert('requirements', [
    'requirement_id' => generateRequirementId(),
    'resume_token' => $token,
    'status' => 'new',
    'customer_name' => $data['customer_name'] ?? 'Draft',
    'customer_email' => $data['customer_email'] ?? '',
    'submission_json' => json_encode($data),
    'is_archived' => 0
]);

echo json_encode(['success' => true, 'token' => $token]);
