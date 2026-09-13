<?php
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
$quote = db()->fetch("SELECT q.*, r.customer_name, r.customer_email, r.customer_phone, r.customer_company, r.requirement_id FROM quotations q JOIN requirements r ON q.requirement_id = r.id WHERE q.id = ?", [$id]);
if (!$quote) die('Quotation not found');

$items = db()->fetchAll("SELECT * FROM quotation_items WHERE quotation_id = ? ORDER BY display_order", [$id]);

$companyName = getSetting('company_name') ?: 'Company';
$companyEmail = getSetting('company_email') ?: '';
$companyPhone = getSetting('company_phone') ?: '';
$companyAddress = getSetting('company_address') ?: '';

$html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>
body{font-family:Arial,sans-serif;margin:40px;color:#333;font-size:12px}
.header{display:flex;justify-content:space-between;border-bottom:3px solid #4f46e5;padding-bottom:20px;margin-bottom:30px}
.company-name{font-size:24px;font-weight:bold;color:#4f46e5}
table{width:100%;border-collapse:collapse;margin:20px 0}
th{background:#f8f9fa;padding:10px;text-align:left;border-bottom:2px solid #dee2e6}
td{padding:10px;border-bottom:1px solid #eee}
.text-right{text-align:right}
.total-row{font-weight:bold;background:#f8f9fa;font-size:14px}
.badge{display:inline-block;padding:3px 10px;border-radius:4px;color:white;font-size:11px}
.badge-draft{background:#6c757d}.badge-sent{background:#0d6efd}.badge-accepted{background:#198754}.badge-rejected{background:#dc3545}
.footer{margin-top:40px;padding-top:20px;border-top:1px solid #eee;font-size:10px;color:#666}
</style></head><body>
<div class="header"><div><div class="company-name">{$companyName}</div><div>{$companyEmail}<br>{$companyPhone}<br>{$companyAddress}</div></div>
<div style="text-align:right"><h2 style="color:#4f46e5">QUOTATION</h2><p><strong>{$quote["quotation_number"]}</strong></p><p>Date: ' . date('M j, Y', strtotime($quote['created_at'])) . '</p><p>Valid Until: ' . ($quote['valid_until'] ? date('M j, Y', strtotime($quote['valid_until'])) : 'N/A') . '</p></div></div>
<div style="margin-bottom:20px"><h4>Bill To:</h4><p><strong>{$quote["customer_name"]}</strong><br>{$quote["customer_email"]}<br>{$quote["customer_phone"]}<br>{$quote["customer_company"]}</p></div>
<h4>Project: {$quote["project_name"]}</h4>
<table><thead><tr><th>Item</th><th>Description</th><th class="text-right">Qty</th><th class="text-right">Unit Price</th><th class="text-right">Total</th></tr></thead><tbody>';
foreach ($items as $item) {
    $html .= "<tr><td>{$item['item_name']}</td><td>{$item['description']}</td><td class='text-right'>{$item['quantity']}</td><td class='text-right'>NPR " . number_format($item['unit_price'], 0) . "</td><td class='text-right'>NPR " . number_format($item['total_price'], 0) . "</td></tr>";
}
$html .= '</tbody></table><table style="width:300px;margin-left:auto">';
$html .= "<tr><td>Subtotal</td><td class='text-right'>NPR " . number_format($quote['subtotal'], 0) . "</td></tr>";
if ($quote['discount_amount'] > 0) $html .= "<tr><td>Discount</td><td class='text-right' style='color:green'>-NPR " . number_format($quote['discount_amount'], 0) . "</td></tr>";
if ($quote['tax_enabled']) $html .= "<tr><td>Tax ({$quote['tax_percentage']}%)</td><td class='text-right'>NPR " . number_format($quote['tax_amount'], 0) . "</td></tr>";
$html .= "<tr class='total-row'><td>Total</td><td class='text-right'>NPR " . number_format($quote['total_amount'], 0) . "</td></tr></table>";
if ($quote['terms_and_conditions']) $html .= "<div style='margin-top:30px'><h4>Terms & Conditions</h4><p>" . nl2br($quote['terms_and_conditions']) . "</p></div>";
if ($quote['notes']) $html .= "<div><h4>Notes</h4><p>" . nl2br($quote['notes']) . "</p></div>";
$html .= '<div class="footer">This is a computer-generated quotation.</div></body></html>';

header('Content-Type: text/html');
echo $html;
exit;
