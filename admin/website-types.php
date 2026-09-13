<?php
$pageTitle = 'Website Types';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($_POST['name'])));
        db()->insert('website_types', [
            'name' => $_POST['name'],
            'slug' => $slug,
            'description' => $_POST['description'],
            'base_price' => floatval($_POST['base_price']),
            'display_order' => intval($_POST['display_order']),
            'is_active' => 1
        ]);
        logAudit('website_type_added');
        flash('success', 'Website type added.');
    } elseif ($action === 'update') {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', trim($_POST['name'])));
        db()->update('website_types', [
            'name' => $_POST['name'],
            'slug' => $slug,
            'description' => $_POST['description'],
            'base_price' => floatval($_POST['base_price']),
            'display_order' => intval($_POST['display_order']),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ], 'id = ?', [$_POST['id']]);
        logAudit('website_type_updated');
        flash('success', 'Website type updated.');
    } elseif ($action === 'delete') {
        db()->delete('website_types', 'id = ?', [$_POST['id']]);
        logAudit('website_type_deleted');
        flash('success', 'Website type deleted.');
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$types = db()->fetchAll("SELECT * FROM website_types ORDER BY display_order, name");
require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between mb-4">
    <h5>Website Types</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus"></i> Add Type</button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Name</th><th>Base Price</th><th>Order</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($types as $type): ?>
                <tr>
                    <td><strong><?= $type['name'] ?></strong><br><small class="text-muted"><?= $type['description'] ?></small></td>
                    <td><?= formatCurrency($type['base_price']) ?></td>
                    <td><?= $type['display_order'] ?></td>
                    <td><?= $type['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $type['id'] ?>" data-name="<?= $type['name'] ?>" data-desc="<?= $type['description'] ?>" data-price="<?= $type['base_price'] ?>" data-order="<?= $type['display_order'] ?>" data-active="<?= $type['is_active'] ?>"><i class="bi bi-pencil"></i></button>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $type['id'] ?>">
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
    <div class="modal-header"><h5>Add Website Type</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control"></textarea></div>
        <div class="row g-2">
            <div class="col-6"><label class="form-label">Base Price (NPR)</label><input type="number" name="base_price" class="form-control" step="0.01" value="0"></div>
            <div class="col-6"><label class="form-label">Display Order</label><input type="number" name="display_order" class="form-control" value="0"></div>
        </div>
    </div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
</div></div></div>

<div class="modal fade" id="editModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><input type="hidden" name="action" value="update"><input type="hidden" name="id" id="edit_id">
    <div class="modal-header"><h5>Edit Website Type</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" id="edit_name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Description</label><textarea name="description" id="edit_desc" class="form-control"></textarea></div>
        <div class="row g-2">
            <div class="col-6"><label class="form-label">Base Price (NPR)</label><input type="number" name="base_price" id="edit_price" class="form-control" step="0.01"></div>
            <div class="col-6"><label class="form-label">Display Order</label><input type="number" name="display_order" id="edit_order" class="form-control"></div>
        </div>
        <div class="form-check mt-2"><input type="checkbox" name="is_active" id="edit_active" class="form-check-input"><label class="form-check-label">Active</label></div>
    </div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
    </form>
</div></div></div>

<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('edit_id').value = btn.dataset.id;
        document.getElementById('edit_name').value = btn.dataset.name;
        document.getElementById('edit_desc').value = btn.dataset.desc;
        document.getElementById('edit_price').value = btn.dataset.price;
        document.getElementById('edit_order').value = btn.dataset.order;
        document.getElementById('edit_active').checked = btn.dataset.active == 1;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
