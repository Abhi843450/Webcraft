<?php
$pageTitle = 'Admin Users';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        db()->insert('admins', [
            'role_id' => intval($_POST['role_id']),
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'username' => $_POST['username'],
            'password' => $hash,
            'phone' => $_POST['phone'] ?: null,
            'is_active' => 1
        ]);
        logAudit('admin_user_added');
        flash('success', 'Admin user added.');
    } elseif ($action === 'update') {
        $data = [
            'role_id' => intval($_POST['role_id']),
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'username' => $_POST['username'],
            'phone' => $_POST['phone'] ?: null,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        db()->update('admins', $data, 'id = ?', [$_POST['id']]);
        logAudit('admin_user_updated');
        flash('success', 'Admin user updated.');
    } elseif ($action === 'delete') {
        if (intval($_POST['id']) !== intval($_SESSION['admin_id'])) {
            db()->delete('admins', 'id = ?', [$_POST['id']]);
            logAudit('admin_user_deleted');
            flash('success', 'Admin user deleted.');
        } else {
            flash('error', 'Cannot delete yourself.');
        }
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$admins = db()->fetchAll("SELECT a.*, r.name as role_name FROM admins a JOIN admin_roles r ON a.role_id = r.id ORDER BY a.name");
$roles = db()->fetchAll("SELECT * FROM admin_roles ORDER BY name");
require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between mb-4">
    <h5>Admin Users</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus"></i> Add Admin</button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Name</th><th>Email</th><th>Username</th><th>Role</th><th>Status</th><th>Last Login</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $a): ?>
                <tr>
                    <td><strong><?= $a['name'] ?></strong></td>
                    <td><?= $a['email'] ?></td>
                    <td><?= $a['username'] ?></td>
                    <td><span class="badge bg-primary"><?= $a['role_name'] ?></span></td>
                    <td><?= $a['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?></td>
                    <td><?= $a['last_login'] ? timeAgo($a['last_login']) : 'Never' ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary edit-btn" data-json='<?= json_encode($a) ?>'><i class="bi bi-pencil"></i></button>
                        <?php if ($a['id'] != $_SESSION['admin_id']): ?>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $a['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><input type="hidden" name="action" value="add">
    <div class="modal-header"><h5>Add Admin User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Username *</label><input type="text" name="username" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Password *</label><input type="password" name="password" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Role *</label>
            <select name="role_id" class="form-select" required>
                <?php foreach ($roles as $r): ?><option value="<?= $r['id'] ?>"><?= $r['name'] ?></option><?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
</div></div></div>

<div class="modal fade" id="editModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><input type="hidden" name="action" value="update"><input type="hidden" name="id" id="edit_id">
    <div class="modal-header"><h5>Edit Admin User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="mb-3"><label class="form-label">Name *</label><input type="text" name="name" id="edit_name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Email *</label><input type="email" name="email" id="edit_email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Username *</label><input type="text" name="username" id="edit_username" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Password (leave blank to keep)</label><input type="password" name="password" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Phone</label><input type="text" name="phone" id="edit_phone" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Role *</label>
            <select name="role_id" id="edit_role_id" class="form-select" required>
                <?php foreach ($roles as $r): ?><option value="<?= $r['id'] ?>"><?= $r['name'] ?></option><?php endforeach; ?>
            </select>
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
        document.getElementById('edit_name').value = d.name;
        document.getElementById('edit_email').value = d.email;
        document.getElementById('edit_username').value = d.username;
        document.getElementById('edit_phone').value = d.phone || '';
        document.getElementById('edit_role_id').value = d.role_id;
        document.getElementById('edit_active').checked = d.is_active == 1;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
