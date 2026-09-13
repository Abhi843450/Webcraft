<?php
$pageTitle = 'Quotations';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$where = '1';
$params = [];
if (!empty($_GET['status'])) { $where .= " AND q.status = ?"; $params[] = $_GET['status']; }
if (!empty($_GET['search'])) { $s = '%' . $_GET['search'] . '%'; $where .= " AND (q.quotation_number LIKE ? OR r.customer_name LIKE ? OR r.customer_email LIKE ?)"; $params = array_merge($params, [$s, $s, $s]); }

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$total = db()->fetch("SELECT COUNT(*) as cnt FROM quotations q JOIN requirements r ON q.requirement_id = r.id WHERE {$where}", $params)['cnt'];
$quotations = db()->fetchAll("SELECT q.*, r.requirement_id, r.customer_name, r.customer_email, a.name as admin_name FROM quotations q JOIN requirements r ON q.requirement_id = r.id JOIN admins a ON q.admin_id = a.id WHERE {$where} ORDER BY q.created_at DESC LIMIT {$perPage} OFFSET {$offset}", $params);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5>Quotations <span class="badge bg-primary"><?= $total ?></span></h5>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Search..." value="<?= sanitize($_GET['search'] ?? '') ?>"></div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="draft" <?= ($_GET['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="sent" <?= ($_GET['status'] ?? '') === 'sent' ? 'selected' : '' ?>>Sent</option>
                    <option value="accepted" <?= ($_GET['status'] ?? '') === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                    <option value="rejected" <?= ($_GET['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Quotation #</th><th>Requirement</th><th>Customer</th><th>Amount</th><th>Status</th><th>Date</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($quotations as $q): ?>
                    <tr>
                        <td><a href="<?= BASE_URL ?>/admin/quotation-details.php?id=<?= $q['id'] ?>" class="fw-bold text-decoration-none"><?= $q['quotation_number'] ?></a></td>
                        <td><?= $q['requirement_id'] ?></td>
                        <td><?= $q['customer_name'] ?></td>
                        <td class="fw-bold"><?= formatCurrency($q['total_amount']) ?></td>
                        <td>
                            <?php
                            $colors = ['draft'=>'secondary','sent'=>'primary','accepted'=>'success','rejected'=>'danger','expired'=>'warning'];
                            echo '<span class="badge bg-' . ($colors[$q['status']] ?? 'secondary') . '">' . ucfirst($q['status']) . '</span>';
                            ?>
                        </td>
                        <td><?= timeAgo($q['created_at']) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/quotation-details.php?id=<?= $q['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="<?= BASE_URL ?>/admin/quotation-edit.php?id=<?= $q['id'] ?>" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($quotations)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No quotations found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
