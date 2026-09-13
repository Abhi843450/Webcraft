<?php
$pageTitle = 'Requirement Details';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
if (!$id) redirect(BASE_URL . '/admin/requirements.php');

$req = db()->fetch("SELECT r.*, wt.name as type_name, a.name as assigned_name FROM requirements r LEFT JOIN website_types wt ON r.website_type_id = wt.id LEFT JOIN admins a ON r.assigned_to = a.id WHERE r.id = ?", [$id]);
if (!$req) { flash('error', 'Requirement not found.'); redirect(BASE_URL . '/admin/requirements.php'); }

$notes = db()->fetchAll("SELECT n.*, a.name as admin_name FROM admin_notes n JOIN admins a ON n.admin_id = a.id WHERE n.requirement_id = ? ORDER BY n.created_at DESC", [$id]);
$history = db()->fetchAll("SELECT h.*, a.name as admin_name FROM requirement_status_history h LEFT JOIN admins a ON h.changed_by = a.id WHERE h.requirement_id = ? ORDER BY h.created_at DESC", [$id]);
$features = db()->fetchAll("SELECT f.name, f.category_id, rf.price FROM requirement_features rf JOIN features f ON rf.feature_id = f.id WHERE rf.requirement_id = ?", [$id]);
$admins = db()->fetchAll("SELECT id, name FROM admins WHERE is_active = 1 ORDER BY name");
$files = db()->fetchAll("SELECT * FROM requirement_files WHERE requirement_id = ?", [$id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_status') {
        $newStatus = $_POST['new_status'];
        $oldStatus = $req['status'];
        db()->update('requirements', ['status' => $newStatus], 'id = ?', [$id]);
        db()->insert('requirement_status_history', [
            'requirement_id' => $id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $_SESSION['admin_id'],
            'notes' => $_POST['status_notes'] ?? null
        ]);
        logAudit('status_changed', 'requirement', $id, ['from' => $oldStatus, 'to' => $newStatus]);
        flash('success', 'Status updated.');
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    if ($action === 'assign') {
        db()->update('requirements', ['assigned_to' => intval($_POST['assigned_to']) ?: null], 'id = ?', [$id]);
        logAudit('requirement_assigned', 'requirement', $id);
        flash('success', 'Requirement assigned.');
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    if ($action === 'add_note') {
        db()->insert('admin_notes', [
            'requirement_id' => $id,
            'admin_id' => $_SESSION['admin_id'],
            'note' => $_POST['note']
        ]);
        logAudit('note_added', 'requirement', $id);
        flash('success', 'Note added.');
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    if ($action === 'update_price') {
        db()->update('requirements', [
            'admin_override_price' => floatval($_POST['override_price']),
            'price_override_reason' => $_POST['price_reason']
        ], 'id = ?', [$id]);
        logAudit('price_overridden', 'requirement', $id, ['price' => $_POST['override_price']]);
        flash('success', 'Price updated.');
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}

$pages = json_decode($req['selected_pages'], true) ?: [];
$ecomFeatures = json_decode($req['ecommerce_features'], true) ?: [];
$bookingFeatures = json_decode($req['booking_features'], true) ?: [];
$paymentMethods = json_decode($req['payment_methods'], true) ?: [];
$securityFeatures = json_decode($req['security_features'], true) ?: [];
$seoFeatures = json_decode($req['seo_features'], true) ?: [];
$designStyle = json_decode($req['design_style'], true) ?: [];

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1"><?= $req['requirement_id'] ?> - <?= $req['customer_name'] ?></h5>
        <small class="text-muted">Submitted <?= date('M j, Y \a\t g:i A', strtotime($req['created_at'])) ?></small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/admin/quotation-create.php?req_id=<?= $id ?>" class="btn btn-success"><i class="bi bi-file-earmark-plus"></i> Create Quotation</a>
        <a href="<?= BASE_URL ?>/admin/requirements.php" class="btn btn-outline-secondary">Back to List</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h4 class="text-primary mb-0"><?= formatCurrency($req['admin_override_price'] ?: $req['system_estimate']) ?></h4>
                <small class="text-muted">Estimated Price</small>
                <?php if ($req['admin_override_price']): ?>
                <br><small class="text-success">Admin Override</small>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h4><?= getStatusBadge($req['status']) ?></h4>
                <small class="text-muted">Current Status</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h4 class="text-info mb-0"><?= ucfirst($req['complexity_level']) ?></h4>
                <small class="text-muted">Complexity</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h4 class="text-info mb-0"><?= $req['estimate_confidence'] ? ucfirst($req['estimate_confidence']) : 'N/A' ?></h4>
                <small class="text-muted">Confidence</small>
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-3" id="detailTabs" role="tablist">
    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#overview">Overview</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#project">Project</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#design">Design</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#features">Features</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#ecommerce">E-commerce</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#booking">Booking</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#pricing">Price</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#notes">Notes (<?= count($notes) ?>)</a></li>
    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#activity">Activity</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="overview">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white"><h6 class="mb-0">Customer Information</h6></div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr><td class="text-muted" style="width:40%">Name</td><td><?= $req['customer_name'] ?></td></tr>
                            <tr><td class="text-muted">Email</td><td><?= $req['customer_email'] ?></td></tr>
                            <tr><td class="text-muted">Phone</td><td><?= $req['customer_phone'] ?: '-' ?></td></tr>
                            <tr><td class="text-muted">Company</td><td><?= $req['customer_company'] ?: '-' ?></td></tr>
                            <tr><td class="text-muted">City</td><td><?= $req['customer_city'] ?: '-' ?></td></tr>
                            <tr><td class="text-muted">Country</td><td><?= $req['customer_country'] ?: '-' ?></td></tr>
                            <tr><td class="text-muted">Preferred Contact</td><td><?= ucfirst($req['preferred_contact']) ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white"><h6 class="mb-0">Project Overview</h6></div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr><td class="text-muted" style="width:40%">Website Type</td><td><?= $req['type_name'] ?: 'Not specified' ?></td></tr>
                            <tr><td class="text-muted">Project Name</td><td><?= $req['project_name'] ?: '-' ?></td></tr>
                            <tr><td class="text-muted">Timeline</td><td><?= TIMELINE_OPTIONS[$req['timeline']] ?? '-' ?></td></tr>
                            <tr><td class="text-muted">Budget</td><td><?= BUDGET_OPTIONS[$req['budget_range']] ?? '-' ?></td></tr>
                            <tr><td class="text-muted">Existing Website</td><td><?= ucfirst(str_replace('_', ' ', $req['existing_website'])) ?></td></tr>
                            <tr><td class="text-muted">Languages</td><td><?= $req['language_count'] ?></td></tr>
                            <tr><td class="text-muted">SEO Level</td><td><?= ucfirst($req['seo_level']) ?></td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="project">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6>Project Description</h6>
                <p><?= nl2br($req['project_description'] ?: '-') ?></p>
                <h6>Special Requirements</h6>
                <p><?= nl2br($req['special_requirements'] ?: '-') ?></p>
                <h6>Specific Workflow</h6>
                <p><?= nl2br($req['specific_workflow'] ?: '-') ?></p>
                <?php if ($req['existing_website_url']): ?>
                <h6>Existing Website</h6>
                <p><a href="<?= $req['existing_website_url'] ?>" target="_blank"><?= $req['existing_website_url'] ?></a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="design">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6>Design Available</h6>
                <p><?= ucfirst(str_replace('_', ' ', $req['design_available'])) ?></p>
                <h6>Design Style</h6>
                <p><?= implode(', ', $designStyle) ?: '-' ?></p>
                <h6>Logo</h6>
                <p><?= ucfirst(str_replace('_', ' ', $req['has_logo'])) ?></p>
                <h6>Brand Colors</h6>
                <p><?= ucfirst(str_replace('_', ' ', $req['has_brand_colors'])) ?></p>
                <h6>Brand Guidelines</h6>
                <p><?= ucfirst($req['has_brand_guidelines']) ?></p>
                <?php if ($req['reference_notes']): ?>
                <h6>Reference Notes</h6>
                <p><?= nl2br($req['reference_notes']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="features">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6>Selected Features</h6>
                <?php if ($features): ?>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>Feature</th><th>Price</th></tr></thead>
                        <tbody>
                        <?php foreach ($features as $f): ?>
                        <tr><td><?= $f['name'] ?></td><td><?= formatCurrency($f['price']) ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted">No features selected</p>
                <?php endif; ?>

                <h6>Pages</h6>
                <p><?= implode(', ', $pages) ?: '-' ?></p>
                <h6>Page Count</h6>
                <p><?= $req['page_count'] ?: '-' ?></p>

                <h6>CMS</h6>
                <p><?= ucfirst($req['needs_cms']) ?></p>
                <?php if ($req['needs_cms'] === 'yes'): ?>
                <h6>CMS Manage Items</h6>
                <p><?= implode(', ', json_decode($req['cms_manage_items'], true) ?: []) ?></p>
                <?php endif; ?>

                <h6>User Accounts</h6>
                <p><?= ucfirst($req['needs_user_accounts']) ?></p>
                <h6>User Roles</h6>
                <p><?= implode(', ', json_decode($req['user_roles'], true) ?: []) ?></p>

                <h6>Communication</h6>
                <p><?= implode(', ', json_decode($req['communication_features'], true) ?: []) ?></p>
                <h6>Search</h6>
                <p><?= ucfirst($req['search_type']) ?></p>
                <h6>Maps</h6>
                <p><?= ucfirst($req['needs_maps']) ?></p>
                <h6>Analytics</h6>
                <p><?= implode(', ', json_decode($req['analytics_features'], true) ?: []) ?></p>
                <h6>Security</h6>
                <p><?= implode(', ', $securityFeatures) ?: '-' ?></p>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="ecommerce">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6>E-commerce</h6>
                <p><?= ucfirst($req['needs_ecommerce']) ?></p>
                <?php if ($req['needs_ecommerce'] === 'yes'): ?>
                <h6>Product Quantity</h6>
                <p><?= $req['product_quantity'] ?: '-' ?></p>
                <h6>E-commerce Features</h6>
                <p><?= implode(', ', $ecomFeatures) ?: '-' ?></p>
                <h6>Payment Methods</h6>
                <p><?= implode(', ', $paymentMethods) ?: '-' ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="booking">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6>Booking</h6>
                <p><?= ucfirst($req['needs_booking']) ?></p>
                <?php if ($req['needs_booking'] === 'yes'): ?>
                <h6>Booking Types</h6>
                <p><?= implode(', ', json_decode($req['booking_types'], true) ?: []) ?></p>
                <h6>Booking Features</h6>
                <p><?= implode(', ', $bookingFeatures) ?: '-' ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="pricing">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>System Estimate</h6>
                        <h3 class="text-primary"><?= formatCurrency($req['system_estimate']) ?></h3>
                    </div>
                    <div class="col-md-6">
                        <h6>Admin Override</h6>
                        <h3 class="text-success"><?= $req['admin_override_price'] ? formatCurrency($req['admin_override_price']) : 'Not set' ?></h3>
                        <?php if ($req['price_override_reason']): ?>
                        <p class="text-muted"><small><?= $req['price_override_reason'] ?></small></p>
                        <?php endif; ?>
                    </div>
                </div>

                <hr>
                <h6>Override Price</h6>
                <form method="POST">
                    <input type="hidden" name="action" value="update_price">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="number" name="override_price" class="form-control" step="0.01" value="<?= $req['admin_override_price'] ?: $req['system_estimate'] ?>">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="price_reason" class="form-control" placeholder="Reason for adjustment" value="<?= $req['price_override_reason'] ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="notes">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="add_note">
                    <div class="mb-2">
                        <textarea name="note" class="form-control" rows="3" placeholder="Add a private note..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Add Note</button>
                </form>
            </div>
        </div>
        <?php foreach ($notes as $note): ?>
        <div class="card border-0 shadow-sm mb-2">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <strong><?= $note['admin_name'] ?></strong>
                    <small class="text-muted"><?= timeAgo($note['created_at']) ?></small>
                </div>
                <p class="mb-0 mt-1"><?= nl2br($note['note']) ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="tab-pane fade" id="activity">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" class="mb-4">
                    <input type="hidden" name="action" value="update_status">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Change Status</label>
                            <select name="new_status" class="form-select" required>
                                <?php foreach (STATUS_OPTIONS as $val => $label): ?>
                                <option value="<?= $val ?>" <?= $req['status'] === $val ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Notes</label>
                            <input type="text" name="status_notes" class="form-control" placeholder="Optional notes">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Update</button>
                        </div>
                    </div>
                </form>

                <form method="POST" class="mb-4">
                    <input type="hidden" name="action" value="assign">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Assign To</label>
                            <select name="assigned_to" class="form-select">
                                <option value="">Unassigned</option>
                                <?php foreach ($admins as $a): ?>
                                <option value="<?= $a['id'] ?>" <?= $req['assigned_to'] == $a['id'] ? 'selected' : '' ?>><?= $a['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Assign</button>
                        </div>
                    </div>
                </form>

                <h6>Status History</h6>
                <?php foreach ($history as $h): ?>
                <div class="d-flex mb-2">
                    <div class="me-3"><small class="text-muted"><?= timeAgo($h['created_at']) ?></small></div>
                    <div>
                        <span class="badge bg-secondary"><?= STATUS_OPTIONS[$h['old_status']] ?? $h['old_status'] ?></span>
                        <i class="bi bi-arrow-right mx-1"></i>
                        <?= getStatusBadge($h['new_status']) ?>
                        <?php if ($h['admin_name']): ?> <small class="text-muted">by <?= $h['admin_name'] ?></small><?php endif; ?>
                        <?php if ($h['notes']): ?><br><small class="text-muted"><?= $h['notes'] ?></small><?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
