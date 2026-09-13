<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$totalReqs = db()->count('requirements', 'is_archived = 0');
$newReqs = db()->count('requirements', "status = 'new' AND is_archived = 0");
$underReview = db()->count('requirements', "status = 'under_review' AND is_archived = 0");
$quoted = db()->count('requirements', "status IN ('quotation_prepared','quotation_sent') AND is_archived = 0");
$approved = db()->count('requirements', "status = 'approved' AND is_archived = 0");
$rejected = db()->count('requirements', "status = 'rejected' AND is_archived = 0");
$completed = db()->count('requirements', "status = 'completed' AND is_archived = 0");

$weekReqs = db()->count('requirements', "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND is_archived = 0");
$monthReqs = db()->count('requirements', "created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND is_archived = 0");

$estRevenue = db()->fetch("SELECT COALESCE(SUM(COALESCE(admin_override_price, system_estimate)), 0) as total FROM requirements WHERE status IN ('approved','converted','completed') AND is_archived = 0");
$avgValue = db()->fetch("SELECT COALESCE(AVG(COALESCE(admin_override_price, system_estimate)), 0) as avg_val FROM requirements WHERE system_estimate > 0 AND is_archived = 0");

$popularTypes = db()->fetchAll("SELECT wt.name, COUNT(r.id) as cnt FROM requirements r LEFT JOIN website_types wt ON r.website_type_id = wt.id WHERE r.is_archived = 0 GROUP BY wt.name ORDER BY cnt DESC LIMIT 5");

$recentReqs = db()->fetchAll("SELECT r.*, wt.name as type_name FROM requirements r LEFT JOIN website_types wt ON r.website_type_id = wt.id WHERE r.is_archived = 0 ORDER BY r.created_at DESC LIMIT 10");

require_once __DIR__ . '/../includes/header.php';
?>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h3 class="mb-0"><?= $totalReqs ?></h3><small>Total Requirements</small></div>
                    <i class="bi bi-clipboard-check fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h3 class="mb-0"><?= $newReqs ?></h3><small>New Requirements</small></div>
                    <i class="bi bi-envelope fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h3 class="mb-0"><?= $underReview ?></h3><small>Under Review</small></div>
                    <i class="bi bi-search fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div><h3 class="mb-0"><?= $quoted + $approved + $completed ?></h3><small>Quoted / Approved / Done</small></div>
                    <i class="bi bi-check-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h4 class="text-primary"><?= $weekReqs ?></h4>
                <small class="text-muted">This Week</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h4 class="text-primary"><?= $monthReqs ?></h4>
                <small class="text-muted">This Month</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h4 class="text-success"><?= formatCurrency($estRevenue['total']) ?></h4>
                <small class="text-muted">Estimated Revenue</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h4 class="text-info"><?= formatCurrency($avgValue['avg_val']) ?></h4>
                <small class="text-muted">Avg Project Value</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Recent Requirements</h6>
                <a href="<?= BASE_URL ?>/admin/requirements.php" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentReqs as $req): ?>
                            <tr>
                                <td><a href="<?= BASE_URL ?>/admin/requirement-details.php?id=<?= $req['id'] ?>"><?= $req['requirement_id'] ?></a></td>
                                <td><?= $req['customer_name'] ?></td>
                                <td><?= $req['type_name'] ?? 'N/A' ?></td>
                                <td><?= formatCurrency($req['admin_override_price'] ?: $req['system_estimate']) ?></td>
                                <td><?= getStatusBadge($req['status']) ?></td>
                                <td><?= timeAgo($req['created_at']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if (empty($recentReqs)): ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">No requirements yet</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Popular Website Types</h6></div>
            <div class="card-body">
                <?php foreach ($popularTypes as $type): ?>
                <div class="d-flex justify-content-between mb-2">
                    <span><?= $type['name'] ?? 'N/A' ?></span>
                    <span class="badge bg-primary"><?= $type['cnt'] ?></span>
                </div>
                <?php endforeach; ?>
                <?php if (empty($popularTypes)): ?>
                <p class="text-muted text-center">No data yet</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
