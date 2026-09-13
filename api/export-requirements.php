<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$where = 'r.is_archived = 0';
$params = [];

if (!empty($_GET['status'])) { $where .= " AND r.status = ?"; $params[] = $_GET['status']; }
if (!empty($_GET['type'])) { $where .= " AND r.website_type_id = ?"; $params[] = $_GET['type']; }
if (!empty($_GET['search'])) {
    $s = '%' . $_GET['search'] . '%';
    $where .= " AND (r.customer_name LIKE ? OR r.customer_email LIKE ? OR r.requirement_id LIKE ?)";
    $params = array_merge($params, [$s, $s, $s]);
}

$requirements = db()->fetchAll("SELECT r.requirement_id, r.customer_name, r.customer_email, r.customer_phone, r.customer_company, wt.name as type_name, r.system_estimate, r.admin_override_price, r.status, r.created_at FROM requirements r LEFT JOIN website_types wt ON r.website_type_id = wt.id WHERE {$where} ORDER BY r.created_at DESC", $params);

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="requirements_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Customer', 'Email', 'Phone', 'Company', 'Type', 'System Estimate', 'Admin Override', 'Status', 'Submitted']);
foreach ($requirements as $r) {
    fputcsv($output, [
        $r['requirement_id'], $r['customer_name'], $r['customer_email'], $r['customer_phone'],
        $r['customer_company'], $r['type_name'], $r['system_estimate'], $r['admin_override_price'],
        STATUS_OPTIONS[$r['status']] ?? $r['status'], $r['created_at']
    ]);
}
fclose($output);
exit;
