<?php
$pageTitle = 'Edit Quotation';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
$quote = db()->fetch("SELECT q.*, r.customer_name, r.customer_email, r.customer_phone, r.customer_company, r.project_name, r.project_description, r.system_estimate, r.admin_override_price FROM quotations q JOIN requirements r ON q.requirement_id = r.id WHERE q.id = ?", [$id]);
if (!$quote) redirect(BASE_URL . '/admin/quotations.php');

$items = db()->fetchAll("SELECT * FROM quotation_items WHERE quotation_id = ? ORDER BY display_order", [$id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    db()->update('quotations', [
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
        'terms_and_conditions' => $_POST['terms_and_conditions'],
        'notes' => $_POST['notes'],
        'internal_notes' => $_POST['internal_notes']
    ], 'id = ?', [$id]);

    db()->delete('quotation_items', 'quotation_id = ?', [$id]);
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
                'quotation_id' => $id,
                'item_name' => trim($name),
                'description' => $itemDescs[$i] ?? null,
                'quantity' => $qty,
                'unit_price' => $price,
                'total_price' => $qty * $price,
                'display_order' => intval($itemOrders[$i] ?? 0)
            ]);
        }
    }

    logAudit('quotation_updated', 'quotation', $id);
    flash('success', 'Quotation updated.');
    redirect(BASE_URL . '/admin/quotation-details.php?id=' . $id);
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between mb-4">
    <h5>Edit Quotation <?= $quote['quotation_number'] ?></h5>
    <a href="<?= BASE_URL ?>/admin/quotation-details.php?id=<?= $id ?>" class="btn btn-outline-secondary">Back</a>
</div>

<form method="POST">
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="mb-3"><label class="form-label">Project Name</label><input type="text" name="project_name" class="form-control" value="<?= $quote['project_name'] ?>" required></div>
            <div class="mb-3"><label class="form-label">Scope</label><textarea name="scope_description" class="form-control" rows="3"><?= $quote['scope_description'] ?></textarea></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white d-flex justify-content-between"><h6 class="mb-0">Items</h6><button type="button" class="btn btn-sm btn-outline-primary" id="add-item"><i class="bi bi-plus"></i></button></div>
        <div class="card-body">
            <div id="items-container">
                <?php foreach ($items as $i => $item): ?>
                <div class="row g-2 mb-2 item-row">
                    <div class="col-md-4"><input type="text" name="item_name[]" class="form-control" value="<?= $item['item_name'] ?>" required></div>
                    <div class="col-md-3"><input type="text" name="item_description[]" class="form-control" value="<?= $item['description'] ?>"></div>
                    <div class="col-md-1"><input type="number" name="item_quantity[]" class="form-control" value="<?= $item['quantity'] ?>" min="1"></div>
                    <div class="col-md-2"><input type="number" name="item_price[]" class="form-control item-price" step="0.01" value="<?= $item['unit_price'] ?>"></div>
                    <div class="col-md-1"><input type="number" name="item_order[]" class="form-control" value="<?= $item['display_order'] ?>"></div>
                    <div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm remove-item"><i class="bi bi-x"></i></button></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3 mb-3"><label class="form-label">Discount Type</label>
                    <select name="discount_type" class="form-select"><option value="none" <?= $quote['discount_type'] === 'none' ? 'selected' : '' ?>>None</option><option value="fixed" <?= $quote['discount_type'] === 'fixed' ? 'selected' : '' ?>>Fixed</option><option value="percentage" <?= $quote['discount_type'] === 'percentage' ? 'selected' : '' ?>>Percentage</option></select>
                </div>
                <div class="col-md-3 mb-3"><label class="form-label">Discount Value</label><input type="number" name="discount_value" class="form-control" step="0.01" value="<?= $quote['discount_value'] ?>"></div>
                <div class="col-md-3 mb-3"><label class="form-label">Tax</label>
                    <select name="tax_enabled" class="form-select"><option value="0" <?= !$quote['tax_enabled'] ? 'selected' : '' ?>>No</option><option value="1" <?= $quote['tax_enabled'] ? 'selected' : '' ?>>Yes</option></select>
                </div>
                <div class="col-md-3 mb-3"><label class="form-label">Tax %</label><input type="number" name="tax_percentage" class="form-control" step="0.01" value="<?= $quote['tax_percentage'] ?>"></div>
                <div class="col-md-4 mb-3"><label class="form-label">Subtotal</label><input type="number" name="subtotal" id="subtotal" class="form-control" step="0.01" value="<?= $quote['subtotal'] ?>" readonly></div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="mb-3"><label class="form-label">Terms & Conditions</label><textarea name="terms_and_conditions" class="form-control" rows="4"><?= $quote['terms_and_conditions'] ?></textarea></div>
            <div class="mb-3"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2"><?= $quote['notes'] ?></textarea></div>
            <div class="mb-3"><label class="form-label">Internal Notes</label><textarea name="internal_notes" class="form-control" rows="2"><?= $quote['internal_notes'] ?></textarea></div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg">Update Quotation</button>
</form>

<script>
document.getElementById('add-item').addEventListener('click', () => {
    const html = '<div class="row g-2 mb-2 item-row"><div class="col-md-4"><input type="text" name="item_name[]" class="form-control" required></div><div class="col-md-3"><input type="text" name="item_description[]" class="form-control"></div><div class="col-md-1"><input type="number" name="item_quantity[]" class="form-control" value="1" min="1"></div><div class="col-md-2"><input type="number" name="item_price[]" class="form-control item-price" step="0.01"></div><div class="col-md-1"><input type="number" name="item_order[]" class="form-control" value="0"></div><div class="col-md-1"><button type="button" class="btn btn-outline-danger btn-sm remove-item"><i class="bi bi-x"></i></button></div></div>';
    document.getElementById('items-container').insertAdjacentHTML('beforeend', html);
});
document.addEventListener('click', e => { if (e.target.classList.contains('remove-item')) e.target.closest('.item-row').remove(); calcTotal(); });
document.addEventListener('input', e => { if (e.target.classList.contains('item-price')) calcTotal(); });
function calcTotal() { let t = 0; document.querySelectorAll('.item-price').forEach(i => { const qty = i.closest('.item-row').querySelector('input[name="item_quantity[]"]').value || 1; t += parseFloat(i.value || 0) * parseInt(qty); }); document.getElementById('subtotal').value = t.toFixed(2); }
calcTotal();
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
