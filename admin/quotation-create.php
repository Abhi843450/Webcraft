<?php
$pageTitle = 'Create Quotation';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$reqId = intval($_GET['req_id'] ?? 0);
if (!$reqId) redirect(BASE_URL . '/admin/requirements.php');

$req = db()->fetch("SELECT r.*, wt.name as type_name FROM requirements r LEFT JOIN website_types wt ON r.website_type_id = wt.id WHERE r.id = ?", [$reqId]);
if (!$req) { flash('error', 'Requirement not found.'); redirect(BASE_URL . '/admin/requirements.php'); }

$features = db()->fetchAll("SELECT f.name, rf.price FROM requirement_features rf JOIN features f ON rf.feature_id = f.id WHERE rf.requirement_id = ?", [$reqId]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qNum = generateQuotationNumber();
    $subtotal = floatval($_POST['subtotal']);
    $discountType = $_POST['discount_type'] ?? 'none';
    $discountValue = floatval($_POST['discount_value'] ?? 0);
    $taxEnabled = isset($_POST['tax_enabled']) ? 1 : 0;
    $taxPct = floatval($_POST['tax_percentage'] ?? 0);

    $discountAmt = 0;
    if ($discountType === 'fixed') $discountAmt = $discountValue;
    elseif ($discountType === 'percentage') $discountAmt = $subtotal * ($discountValue / 100);

    $taxable = $subtotal - $discountAmt;
    $taxAmt = $taxEnabled ? $taxable * ($taxPct / 100) : 0;
    $total = $taxable + $taxAmt;

    $validDays = intval(getSetting('quotation_validity_days') ?: 30);

    $qId = db()->insert('quotations', [
        'quotation_number' => $qNum,
        'requirement_id' => $reqId,
        'admin_id' => $_SESSION['admin_id'],
        'status' => 'draft',
        'project_name' => $_POST['project_name'],
        'scope_description' => $_POST['scope_description'],
        'subtotal' => $subtotal,
        'discount_type' => $discountType,
        'discount_value' => $discountValue,
        'discount_amount' => $discountAmt,
        'tax_enabled' => $taxEnabled,
        'tax_percentage' => $taxPct,
        'tax_amount' => $taxAmt,
        'total_amount' => $total,
        'validity_days' => $validDays,
        'valid_until' => date('Y-m-d', strtotime("+{$validDays} days")),
        'terms_and_conditions' => $_POST['terms_and_conditions'],
        'notes' => $_POST['notes'],
        'internal_notes' => $_POST['internal_notes']
    ]);

    $itemNames = $_POST['item_name'] ?? [];
    $itemDescs = $_POST['item_description'] ?? [];
    $itemQtys = $_POST['item_quantity'] ?? [];
    $itemPrices = $_POST['item_price'] ?? [];
    $itemOrders = $_POST['item_order'] ?? [];
    foreach ($itemNames as $i => $name) {
        if (!empty(trim($name))) {
            $qty = max(1, intval($itemQtys[$i] ?? 1));
            $price = floatval($itemPrices[$i] ?? 0);
            db()->insert('quotation_items', [
                'quotation_id' => $qId,
                'item_name' => trim($name),
                'description' => $itemDescs[$i] ?? null,
                'quantity' => $qty,
                'unit_price' => $price,
                'total_price' => $qty * $price,
                'display_order' => intval($itemOrders[$i] ?? 0)
            ]);
        }
    }

    db()->update('requirements', ['status' => 'quotation_prepared'], 'id = ?', [$reqId]);
    db()->insert('requirement_status_history', [
        'requirement_id' => $reqId, 'old_status' => $req['status'], 'new_status' => 'quotation_prepared',
        'changed_by' => $_SESSION['admin_id'], 'notes' => "Quotation {$qNum} created"
    ]);

    logAudit('quotation_created', 'quotation', $qId);
    flash('success', 'Quotation created: ' . $qNum);
    redirect(BASE_URL . '/admin/quotation-details.php?id=' . $qId);
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between mb-4">
    <h5>Create Quotation for <?= $req['requirement_id'] ?></h5>
    <a href="<?= BASE_URL ?>/admin/requirement-details.php?id=<?= $reqId ?>" class="btn btn-outline-secondary">Back</a>
</div>

<div class="row">
    <div class="col-md-8">
        <form method="POST" id="quotationForm">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">Project Details</h6></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Project Name</label><input type="text" name="project_name" class="form-control" value="<?= $req['project_name'] ?>" required></div>
                    <div class="mb-3"><label class="form-label">Scope Description</label><textarea name="scope_description" class="form-control" rows="3"><?= $req['project_description'] ?></textarea></div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between">
                    <h6 class="mb-0">Items</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-item"><i class="bi bi-plus"></i> Add Item</button>
                </div>
                <div class="card-body">
                    <div id="items-container">
                        <?php if ($features): ?>
                        <?php foreach ($features as $i => $f): ?>
                        <div class="row g-2 mb-2 item-row">
                            <div class="col-md-4"><input type="text" name="item_name[]" class="form-control" value="<?= $f['name'] ?>" required></div>
                            <div class="col-md-3"><input type="text" name="item_description[]" class="form-control" placeholder="Description"></div>
                            <div class="col-md-1"><input type="number" name="item_quantity[]" class="form-control" value="1" min="1"></div>
                            <div class="col-md-2"><input type="number" name="item_price[]" class="form-control item-price" step="0.01" value="<?= $f['price'] ?>"></div>
                            <div class="col-md-1"><input type="number" name="item_order[]" class="form-control" value="<?= $i ?>"></div>
                            <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm remove-item"><i class="bi bi-x"></i></button></div>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="row g-2 mb-2 item-row">
                            <div class="col-md-4"><input type="text" name="item_name[]" class="form-control" required></div>
                            <div class="col-md-3"><input type="text" name="item_description[]" class="form-control" placeholder="Description"></div>
                            <div class="col-md-1"><input type="number" name="item_quantity[]" class="form-control" value="1" min="1"></div>
                            <div class="col-md-2"><input type="number" name="item_price[]" class="form-control item-price" step="0.01"></div>
                            <div class="col-md-1"><input type="number" name="item_order[]" class="form-control" value="0"></div>
                            <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm remove-item"><i class="bi bi-x"></i></button></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">Discount & Tax</h6></div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-4 mb-3"><label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-select"><option value="none">None</option><option value="fixed">Fixed Amount</option><option value="percentage">Percentage</option></select>
                        </div>
                        <div class="col-md-4 mb-3"><label class="form-label">Discount Value</label><input type="number" name="discount_value" class="form-control" step="0.01" value="0"></div>
                        <div class="col-md-4 mb-3"></div>
                        <div class="col-md-4 mb-3"><label class="form-label">Tax Enabled</label>
                            <select name="tax_enabled" class="form-select"><option value="0">No</option><option value="1">Yes</option></select>
                        </div>
                        <div class="col-md-4 mb-3"><label class="form-label">Tax %</label><input type="number" name="tax_percentage" class="form-control" step="0.01" value="<?= getSetting('tax_percentage') ?: 13 ?>"></div>
                        <div class="col-md-4 mb-3"><label class="form-label">Subtotal</label><input type="number" name="subtotal" id="subtotal" class="form-control" step="0.01" readonly></div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white"><h6 class="mb-0">Terms & Notes</h6></div>
                <div class="card-body">
                    <div class="mb-3"><label class="form-label">Terms & Conditions</label><textarea name="terms_and_conditions" class="form-control" rows="4">1. This quotation is valid for 30 days from the date of issue.
2. 50% advance payment required to start the project.
3. Additional requirements beyond the scope will be quoted separately.
4. Timeline begins after advance payment confirmation.
5. Final payment due upon project completion and before source code handover.</textarea></div>
                    <div class="mb-3"><label class="form-label">Notes (visible to customer)</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
                    <div class="mb-3"><label class="form-label">Internal Notes (admin only)</label><textarea name="internal_notes" class="form-control" rows="2"></textarea></div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">Create Quotation</button>
        </form>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3 sticky-top" style="top:80px">
            <div class="card-header bg-white"><h6 class="mb-0">Customer Info</h6></div>
            <div class="card-body">
                <p class="mb-1"><strong><?= $req['customer_name'] ?></strong></p>
                <p class="mb-1"><small><?= $req['customer_email'] ?></small></p>
                <p class="mb-1"><small><?= $req['customer_phone'] ?: '' ?></small></p>
                <p class="mb-0"><small><?= $req['customer_company'] ?: '' ?></small></p>
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">System Estimate</h6></div>
            <div class="card-body text-center">
                <h3 class="text-primary"><?= formatCurrency($req['admin_override_price'] ?: $req['system_estimate']) ?></h3>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('add-item').addEventListener('click', () => {
    const html = '<div class="row g-2 mb-2 item-row"><div class="col-md-4"><input type="text" name="item_name[]" class="form-control" required></div><div class="col-md-3"><input type="text" name="item_description[]" class="form-control" placeholder="Description"></div><div class="col-md-1"><input type="number" name="item_quantity[]" class="form-control" value="1" min="1"></div><div class="col-md-2"><input type="number" name="item_price[]" class="form-control item-price" step="0.01"></div><div class="col-md-1"><input type="number" name="item_order[]" class="form-control" value="0"></div><div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm remove-item"><i class="bi bi-x"></i></button></div></div>';
    document.getElementById('items-container').insertAdjacentHTML('beforeend', html);
});
document.addEventListener('click', e => { if (e.target.classList.contains('remove-item')) e.target.closest('.item-row').remove(); calcTotal(); });
document.addEventListener('input', e => { if (e.target.classList.contains('item-price')) calcTotal(); });
function calcTotal() { let t = 0; document.querySelectorAll('.item-price').forEach(i => { const qty = i.closest('.item-row').querySelector('input[name="item_quantity[]"]').value || 1; t += parseFloat(i.value || 0) * parseInt(qty); }); document.getElementById('subtotal').value = t.toFixed(2); }
calcTotal();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
