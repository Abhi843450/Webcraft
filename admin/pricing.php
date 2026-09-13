<?php
$pageTitle = 'Pricing Rules';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        db()->insert('pricing_rules', [
            'feature_id' => $_POST['feature_id'] ?: null,
            'rule_name' => $_POST['rule_name'],
            'rule_type' => $_POST['rule_type'],
            'base_amount' => floatval($_POST['base_amount']),
            'unit_amount' => floatval($_POST['unit_amount']),
            'included_units' => intval($_POST['included_units']),
            'is_active' => 1
        ]);
        logAudit('pricing_rule_added');
        flash('success', 'Pricing rule added.');
    } elseif ($action === 'update') {
        db()->update('pricing_rules', [
            'feature_id' => $_POST['feature_id'] ?: null,
            'rule_name' => $_POST['rule_name'],
            'rule_type' => $_POST['rule_type'],
            'base_amount' => floatval($_POST['base_amount']),
            'unit_amount' => floatval($_POST['unit_amount']),
            'included_units' => intval($_POST['included_units']),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ], 'id = ?', [$_POST['id']]);
        logAudit('pricing_rule_updated');
        flash('success', 'Pricing rule updated.');
    } elseif ($action === 'delete') {
        db()->delete('pricing_rules', 'id = ?', [$_POST['id']]);
        logAudit('pricing_rule_deleted');
        flash('success', 'Pricing rule deleted.');
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$rules = db()->fetchAll("SELECT pr.*, f.name as feature_name FROM pricing_rules pr LEFT JOIN features f ON pr.feature_id = f.id ORDER BY pr.rule_name");
$features = db()->fetchAll("SELECT id, name FROM features WHERE is_active = 1 ORDER BY name");
require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between mb-4">
    <h5>Pricing Rules</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus"></i> Add Rule</button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Rule</th><th>Type</th><th>Base</th><th>Unit Price</th><th>Included</th><th>Feature</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($rules as $r): ?>
                <tr>
                    <td><strong><?= $r['rule_name'] ?></strong></td>
                    <td><span class="badge bg-info"><?= $r['rule_type'] ?></span></td>
                    <td><?= formatCurrency($r['base_amount']) ?></td>
                    <td><?= formatCurrency($r['unit_amount']) ?></td>
                    <td><?= $r['included_units'] ?></td>
                    <td><?= $r['feature_name'] ?: '-' ?></td>
                    <td><?= $r['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Off</span>' ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary edit-btn" data-json='<?= json_encode($r) ?>'><i class="bi bi-pencil"></i></button>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><input type="hidden" name="action" value="add">
    <div class="modal-header"><h5>Add Pricing Rule</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-3"><label class="form-label">Rule Name *</label><input type="text" name="rule_name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Rule Type</label>
            <select name="rule_type" class="form-select">
                <option value="base_price">Base Price</option><option value="per_page">Per Page</option><option value="per_product">Per Product</option>
                <option value="per_language">Per Language</option><option value="per_user_role">Per User Role</option><option value="additional_item">Additional Item</option>
                <option value="conditional">Conditional</option><option value="range">Range</option><option value="custom">Custom</option>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">Feature (optional)</label>
            <select name="feature_id" class="form-select"><option value="">None</option>
                <?php foreach ($features as $f): ?><option value="<?= $f['id'] ?>"><?= $f['name'] ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="row g-2">
            <div class="col-4 mb-3"><label class="form-label">Base Amount</label><input type="number" name="base_amount" class="form-control" step="0.01" value="0"></div>
            <div class="col-4 mb-3"><label class="form-label">Unit Amount</label><input type="number" name="unit_amount" class="form-control" step="0.01" value="0"></div>
            <div class="col-4 mb-3"><label class="form-label">Included Units</label><input type="number" name="included_units" class="form-control" value="0"></div>
        </div>
    </div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
</div></div></div>

<div class="modal fade" id="editModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><input type="hidden" name="action" value="update"><input type="hidden" name="id" id="edit_id">
    <div class="modal-header"><h5>Edit Pricing Rule</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-3"><label class="form-label">Rule Name *</label><input type="text" name="rule_name" id="edit_name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Rule Type</label>
            <select name="rule_type" id="edit_type" class="form-select">
                <option value="base_price">Base Price</option><option value="per_page">Per Page</option><option value="per_product">Per Product</option>
                <option value="per_language">Per Language</option><option value="per_user_role">Per User Role</option><option value="additional_item">Additional Item</option>
                <option value="conditional">Conditional</option><option value="range">Range</option><option value="custom">Custom</option>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">Feature</label>
            <select name="feature_id" id="edit_feature_id" class="form-select"><option value="">None</option>
                <?php foreach ($features as $f): ?><option value="<?= $f['id'] ?>"><?= $f['name'] ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="row g-2">
            <div class="col-4 mb-3"><label class="form-label">Base Amount</label><input type="number" name="base_amount" id="edit_base" class="form-control" step="0.01"></div>
            <div class="col-4 mb-3"><label class="form-label">Unit Amount</label><input type="number" name="unit_amount" id="edit_unit" class="form-control" step="0.01"></div>
            <div class="col-4 mb-3"><label class="form-label">Included</label><input type="number" name="included_units" id="edit_included" class="form-control"></div>
        </div>
        <div class="form-check"><input type="checkbox" name="is_active" id="edit_active" class="form-check-input"><label class="form-check-label">Active</label></div>
    </div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
    </form>
</div></div></div>

<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const d = JSON.parse(btn.dataset.json);
        document.getElementById('edit_id').value = d.id;
        document.getElementById('edit_name').value = d.rule_name;
        document.getElementById('edit_type').value = d.rule_type;
        document.getElementById('edit_feature_id').value = d.feature_id || '';
        document.getElementById('edit_base').value = d.base_amount;
        document.getElementById('edit_unit').value = d.unit_amount;
        document.getElementById('edit_included').value = d.included_units;
        document.getElementById('edit_active').checked = d.is_active == 1;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
