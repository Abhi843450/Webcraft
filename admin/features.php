<?php
$pageTitle = 'Features';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($_POST['name'])));
        db()->insert('features', [
            'category_id' => intval($_POST['category_id']),
            'name' => $_POST['name'],
            'slug' => $slug,
            'description' => $_POST['description'],
            'price' => floatval($_POST['price']),
            'pricing_type' => $_POST['pricing_type'],
            'display_order' => intval($_POST['display_order']),
            'is_active' => 1
        ]);
        logAudit('feature_added');
        flash('success', 'Feature added.');
    } elseif ($action === 'update') {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($_POST['name'])));
        db()->update('features', [
            'category_id' => intval($_POST['category_id']),
            'name' => $_POST['name'],
            'slug' => $slug,
            'description' => $_POST['description'],
            'price' => floatval($_POST['price']),
            'pricing_type' => $_POST['pricing_type'],
            'display_order' => intval($_POST['display_order']),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ], 'id = ?', [$_POST['id']]);
        logAudit('feature_updated');
        flash('success', 'Feature updated.');
    } elseif ($action === 'delete') {
        db()->delete('features', 'id = ?', [$_POST['id']]);
        logAudit('feature_deleted');
        flash('success', 'Feature deleted.');
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$features = db()->fetchAll("SELECT f.*, fc.name as category_name FROM features f JOIN feature_categories fc ON f.category_id = fc.id ORDER BY fc.display_order, f.display_order, f.name");
$categories = db()->fetchAll("SELECT * FROM feature_categories WHERE is_active = 1 ORDER BY display_order");
require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between mb-4">
    <h5>Features</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus"></i> Add Feature</button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Name</th><th>Category</th><th>Price</th><th>Type</th><th>Order</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($features as $f): ?>
                    <tr>
                        <td><strong><?= $f['name'] ?></strong></td>
                        <td><span class="badge bg-light text-dark"><?= $f['category_name'] ?></span></td>
                        <td><?= formatCurrency($f['price']) ?></td>
                        <td><small><?= $f['pricing_type'] ?></small></td>
                        <td><?= $f['display_order'] ?></td>
                        <td><?= $f['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Off</span>' ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary edit-btn" data-json='<?= json_encode($f) ?>'><i class="bi bi-pencil"></i></button>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $f['id'] ?>">
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <form method="POST"><input type="hidden" name="action" value="add">
    <div class="modal-header"><h5>Add Feature</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="row g-2">
            <div class="col-md-6 mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Category *</label>
                <select name="category_id" class="form-select" required>
                    <?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>"><?= $c['name'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Price (NPR)</label><input type="number" name="price" class="form-control" step="0.01" value="0"></div>
            <div class="col-md-4 mb-3"><label class="form-label">Pricing Type</label>
                <select name="pricing_type" class="form-select">
                    <option value="fixed">Fixed</option><option value="per_page">Per Page</option><option value="per_product">Per Product</option>
                    <option value="per_language">Per Language</option><option value="per_user_role">Per User Role</option><option value="percentage">Percentage</option>
                </select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Display Order</label><input type="number" name="display_order" class="form-control" value="0"></div>
            <div class="col-12 mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control"></textarea></div>
        </div>
    </div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
</div></div></div>

<div class="modal fade" id="editModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <form method="POST"><input type="hidden" name="action" value="update"><input type="hidden" name="id" id="edit_id">
    <div class="modal-header"><h5>Edit Feature</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="row g-2">
            <div class="col-md-6 mb-3"><label class="form-label">Name *</label><input type="text" name="name" id="edit_name" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Category *</label>
                <select name="category_id" id="edit_category_id" class="form-select" required>
                    <?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>"><?= $c['name'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Price (NPR)</label><input type="number" name="price" id="edit_price" class="form-control" step="0.01"></div>
            <div class="col-md-4 mb-3"><label class="form-label">Pricing Type</label>
                <select name="pricing_type" id="edit_pricing_type" class="form-select">
                    <option value="fixed">Fixed</option><option value="per_page">Per Page</option><option value="per_product">Per Product</option>
                    <option value="per_language">Per Language</option><option value="per_user_role">Per User Role</option><option value="percentage">Percentage</option>
                </select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Display Order</label><input type="number" name="display_order" id="edit_order" class="form-control"></div>
            <div class="col-12 mb-3"><label class="form-label">Description</label><textarea name="description" id="edit_desc" class="form-control"></textarea></div>
            <div class="col-12"><div class="form-check"><input type="checkbox" name="is_active" id="edit_active" class="form-check-input"><label class="form-check-label">Active</label></div></div>
        </div>
    </div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
    </form>
</div></div></div>

<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const d = JSON.parse(btn.dataset.json);
        document.getElementById('edit_id').value = d.id;
        document.getElementById('edit_name').value = d.name;
        document.getElementById('edit_category_id').value = d.category_id;
        document.getElementById('edit_price').value = d.price;
        document.getElementById('edit_pricing_type').value = d.pricing_type;
        document.getElementById('edit_order').value = d.display_order;
        document.getElementById('edit_desc').value = d.description || '';
        document.getElementById('edit_active').checked = d.is_active == 1;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
