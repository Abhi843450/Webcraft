<?php
$pageTitle = 'Requirements';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$where = 'r.is_archived = 0';
$params = [];

if (!empty($_GET['status'])) {
    $where .= " AND r.status = ?";
    $params[] = $_GET['status'];
}
if (!empty($_GET['type'])) {
    $where .= " AND r.website_type_id = ?";
    $params[] = $_GET['type'];
}
if (!empty($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $where .= " AND (r.customer_name LIKE ? OR r.customer_email LIKE ? OR r.requirement_id LIKE ? OR r.customer_company LIKE ?)";
    $params = array_merge($params, [$search, $search, $search, $search]);
}

$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$countSql = "SELECT COUNT(*) as cnt FROM requirements r WHERE {$where}";
$total = db()->fetch($countSql, $params)['cnt'];
$totalPages = ceil($total / $perPage);

$sql = "SELECT r.*, wt.name as type_name FROM requirements r LEFT JOIN website_types wt ON r.website_type_id = wt.id WHERE {$where} ORDER BY r.created_at DESC LIMIT {$perPage} OFFSET {$offset}";
$requirements = db()->fetchAll($sql, $params);
$websiteTypes = db()->fetchAll("SELECT * FROM website_types WHERE is_active = 1 ORDER BY display_order");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'archive') {
        db()->update('requirements', ['is_archived' => 1], 'id = ?', [$_POST['id']]);
        logAudit('requirement_archived', 'requirement', $_POST['id']);
        flash('success', 'Requirement archived.');
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0">Requirements <span class="badge bg-primary"><?= $total ?></span></h5>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search name, email, ID..." value="<?= sanitize($_GET['search'] ?? '') ?>">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <?php foreach (STATUS_OPTIONS as $val => $label): ?>
                        <option value="<?= $val ?>" <?= ($_GET['status'] ?? '') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <?php foreach ($websiteTypes as $type): ?>
                        <option value="<?= $type['id'] ?>" <?= ($_GET['type'] ?? '') == $type['id'] ? 'selected' : '' ?>><?= $type['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
            <div class="col-md-1">
                <a href="<?= BASE_URL ?>/admin/requirements.php" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
            <div class="col-md-2">
                <a href="<?= BASE_URL ?>/api/export-requirements.php?<?= http_build_query($_GET) ?>" class="btn btn-outline-success w-100"><i class="bi bi-download"></i> Export CSV</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Company</th>
                        <th>Website Type</th>
                        <th>Est. Price</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requirements as $req): ?>
                    <tr>
                        <td><a href="<?= BASE_URL ?>/admin/requirement-details.php?id=<?= $req['id'] ?>" class="text-decoration-none fw-bold"><?= $req['requirement_id'] ?></a></td>
                        <td>
                            <?= $req['customer_name'] ?><br>
                            <small class="text-muted"><?= $req['customer_email'] ?></small>
                        </td>
                        <td><?= $req['customer_company'] ?: '-' ?></td>
                        <td><?= $req['type_name'] ?? '-' ?></td>
                        <td class="fw-bold"><?= formatCurrency($req['admin_override_price'] ?: $req['system_estimate']) ?></td>
                        <td><?= getStatusBadge($req['status']) ?></td>
                        <td><small><?= timeAgo($req['created_at']) ?></small></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="<?= BASE_URL ?>/admin/requirement-details.php?id=<?= $req['id'] ?>" class="btn btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                                <a href="<?= BASE_URL ?>/admin/quotation-create.php?req_id=<?= $req['id'] ?>" class="btn btn-outline-success" title="Create Quotation"><i class="bi bi-file-earmark-plus"></i></a>
                                <form method="POST" class="d-inline" onsubmit="return confirm('Archive this requirement?')">
                                    <input type="hidden" name="action" value="archive">
                                    <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Archive"><i class="bi bi-archive"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($requirements)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">No requirements found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if ($totalPages > 1): ?>
<nav class="mt-3">
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
