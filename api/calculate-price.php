<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/functions.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) $input = $_POST;

$websiteTypeId = intval($input['website_type_id'] ?? 0);
$features = $input['features'] ?? [];
$pageCount = intval($input['page_count'] ?? 5);
$languageCount = max(1, intval($input['language_count'] ?? 1));
$seoLevel = $input['seo_level'] ?? 'none';
$needsCms = $input['needs_cms'] ?? 'no';
$needsEcommerce = $input['needs_ecommerce'] ?? 'no';
$needsBooking = $input['needs_booking'] ?? 'no';
$needsPayment = $input['needs_payment'] ?? 'no';
$needsUserAccounts = $input['needs_user_accounts'] ?? 'no';
$productQuantity = $input['product_quantity'] ?? '';
$communication = $input['communication_features'] ?? [];
$searchType = $input['search_type'] ?? 'none';
$needsMaps = $input['needs_maps'] ?? 'no';

$total = 0;
$breakdown = [];

// Base website price
if ($websiteTypeId) {
    $type = db()->fetch("SELECT * FROM website_types WHERE id = ?", [$websiteTypeId]);
    if ($type && $type['base_price'] > 0) {
        $total += $type['base_price'];
        $breakdown[] = ['label' => $type['name'] . ' (Base)', 'amount' => $type['base_price']];
    }
}

// Selected features
$featurePrices = [];
if (!empty($features)) {
    $featurePrices = db()->fetchAll("SELECT * FROM features WHERE id IN (" . implode(',', array_fill(0, count($features), '?')) . ")", $features);
}
foreach ($featurePrices as $f) {
    $total += $f['price'];
    $breakdown[] = ['label' => $f['name'], 'amount' => $f['price']];
}

// Page pricing (first 5 included in base, additional charged)
$pageRule = db()->fetch("SELECT * FROM pricing_rules WHERE rule_type = 'per_page' AND is_active = 1 LIMIT 1");
if ($pageRule && $pageCount > $pageRule['included_units']) {
    $extra = $pageCount - $pageRule['included_units'];
    $pageCost = $extra * $pageRule['unit_amount'];
    $total += $pageCost;
    $breakdown[] = ['label' => "Additional Pages ({$extra} pages)", 'amount' => $pageCost];
}

// Language pricing
$langRule = db()->fetch("SELECT * FROM pricing_rules WHERE rule_type = 'per_language' AND is_active = 1 LIMIT 1");
if ($langRule && $languageCount > $langRule['included_units']) {
    $extra = $languageCount - $langRule['included_units'];
    $langCost = $extra * $langRule['unit_amount'];
    $total += $langCost;
    $breakdown[] = ['label' => "Additional Languages ({$extra})", 'amount' => $langCost];
}

// SEO pricing from features
if ($seoLevel !== 'none') {
    $seoFeature = db()->fetch("SELECT * FROM features WHERE slug = ? AND is_active = 1", ['seo-' . $seoLevel]);
    if ($seoFeature && !in_array($seoFeature['id'], $features)) {
        $total += $seoFeature['price'];
        $breakdown[] = ['label' => ucfirst($seoLevel) . ' SEO', 'amount' => $seoFeature['price']];
    }
}

// Product quantity pricing
if ($needsEcommerce === 'yes' && $productQuantity) {
    $productRule = db()->fetch("SELECT * FROM pricing_rules WHERE rule_type = 'per_product' AND is_active = 1 LIMIT 1");
    $qtyMap = ['1-50' => 50, '51-200' => 200, '201-500' => 500, '501-1000' => 1000, '1000+' => 2000];
    $qty = $qtyMap[$productQuantity] ?? 0;
    if ($productRule && $qty > 0) {
        $productCost = $qty * $productRule['unit_amount'];
        $total += $productCost;
        $breakdown[] = ['label' => "Products ({$productQuantity})", 'amount' => $productCost];
    }
}

// Communication features pricing
foreach ($communication as $commFeature) {
    $cf = db()->fetch("SELECT * FROM features WHERE slug = ? AND is_active = 1", [$commFeature]);
    if ($cf && !in_array($cf['id'], $features)) {
        $total += $cf['price'];
        $breakdown[] = ['label' => $cf['name'], 'amount' => $cf['price']];
    }
}

// Advanced search
if ($searchType === 'advanced') {
    $searchFeature = db()->fetch("SELECT * FROM features WHERE slug = 'advanced-search' AND is_active = 1");
    if ($searchFeature && !in_array($searchFeature['id'], $features)) {
        $total += $searchFeature['price'];
        $breakdown[] = ['label' => 'Advanced Search', 'amount' => $searchFeature['price']];
    }
}

// Maps
if ($needsMaps === 'yes') {
    $mapFeature = db()->fetch("SELECT * FROM features WHERE slug = 'gmaps' AND is_active = 1");
    if ($mapFeature && !in_array($mapFeature['id'], $features)) {
        $total += $mapFeature['price'];
        $breakdown[] = ['label' => 'Google Maps', 'amount' => $mapFeature['price']];
    }
}

// Complexity score
$complexity = 0;
$complexity += count($features) * 2;
if ($needsEcommerce === 'yes') $complexity += 15;
if ($needsBooking === 'yes') $complexity += 12;
if ($needsPayment === 'yes') $complexity += 8;
if ($needsUserAccounts === 'yes') $complexity += 10;
if ($languageCount > 1) $complexity += $languageCount * 3;
if ($pageCount > 10) $complexity += 5;
if (in_array('api-integration', $features)) $complexity += 10;
if (in_array('crm', $features)) $complexity += 8;

if ($complexity <= 20) $level = 'basic';
elseif ($complexity <= 50) $level = 'moderate';
elseif ($complexity <= 100) $level = 'advanced';
else $level = 'complex';

$confidence = 'high';
if ($level === 'advanced') $confidence = 'medium';
if ($level === 'complex') $confidence = 'low';

$range = ['min' => round($total * 0.85), 'max' => round($total * 1.2)];

// Categorize breakdown for frontend
$categorized = [
    'base' => 0, 'pages' => 0, 'features' => 0, 'seo' => 0,
    'languages' => 0, 'products' => 0, 'other' => 0
];
foreach ($breakdown as $item) {
    $label = strtolower($item['label']);
    $amt = floatval($item['amount']);
    if (str_contains($label, 'base') || str_contains($label, 'website')) $categorized['base'] += $amt;
    elseif (str_contains($label, 'page')) $categorized['pages'] += $amt;
    elseif (str_contains($label, 'seo')) $categorized['seo'] += $amt;
    elseif (str_contains($label, 'language')) $categorized['languages'] += $amt;
    elseif (str_contains($label, 'product')) $categorized['products'] += $amt;
    else $categorized['features'] += $amt;
}

echo json_encode([
    'success' => true,
    'total' => floatval($total),
    'breakdown' => $categorized,
    'breakdown_items' => $breakdown,
    'complexity' => ['score' => $complexity, 'level' => $level],
    'confidence' => $confidence,
    'range' => $range
]);
