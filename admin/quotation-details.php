<?php
$pageTitle = 'Quotation Details';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
$quote = db()->fetch("SELECT q.*, r.customer_name, r.customer_email, r.customer_phone, r.customer_company, r.customer_address, r.requirement_id, r.project_name FROM quotations q JOIN requirements r ON q.requirement_id = r.id WHERE q.id = ?", [$id]);
if (!$quote) redirect(BASE_URL . '/admin/quotations.php');

$items = db()->fetchAll("SELECT * FROM quotation_items WHERE quotation_id = ? ORDER BY display_order", [$id]);
$history = db()->fetchAll("SELECT h.*, a.name as admin_name FROM quotation_status_history h LEFT JOIN admins a ON h.changed_by = a.id WHERE h.quotation_id = ? ORDER BY h.created_at DESC", [$id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'change_status') {
        $old = $quote['status'];
        $new = $_POST['new_status'];
        db()->update('quotations', ['status' => $new, 'sent_at' => ($new === 'sent') ? date('Y-m-d H:i:s') : null, 'responded_at' => in_array($new, ['accepted','rejected']) ? date('Y-m-d H:i:s') : null], 'id = ?', [$id]);
        db()->insert('quotation_status_history', ['quotation_id' => $id, 'old_status' => $old, 'new_status' => $new, 'changed_by' => $_SESSION['admin_id'], 'notes' => $_POST['notes'] ?? null]);
        logAudit('quotation_status_changed', 'quotation', $id, ['from' => $old, 'to' => $new]);
        flash('success', 'Quotation status updated.');
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between mb-4">
    <div>
        <h5 class="mb-0"><?= $quote['quotation_number'] ?></h5>
        <small class="text-muted">For <?= $quote['requirement_id'] ?></small>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/admin/quotation-edit.php?id=<?= $id ?>" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
        <a href="<?= BASE_URL ?>/api/quotation-pdf.php?id=<?= $id ?>" class="btn btn-outline-danger" target="_blank"><i class="bi bi-file-pdf"></i> PDF</a>
        <a href="<?= BASE_URL ?>/admin/quotations.php" class="btn btn-outline-secondary">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <h6>From</h6>
                        <p class="mb-0"><?= getSetting('company_name') ?></p>
                        <p class="mb-0"><?= getSetting('company_email') ?></p>
                        <p class="mb-0"><?= getSetting('company_phone') ?></p>
                        <p class="mb-0"><?= getSetting('company_address') ?></p>
                    </div>
                    <div class="col-6 text-end">
                        <h6>To</h6>
                        <p class="mb-0"><?= $quote['customer_name'] ?></p>
                        <p class="mb-0"><?= $quote['customer_email'] ?></p>
                        <p class="mb-0"><?= $quote['customer_phone'] ?></p>
                        <p class="mb-0"><?= $quote['customer_company'] ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><strong>Project:</strong> <?= $quote['project_name'] ?></div>
                    <div class="col-4"><strong>Date:</strong> <?= date('M j, Y', strtotime($quote['created_at'])) ?></div>
                    <div class="col-4"><strong>Valid Until:</strong> <?= $quote['valid_until'] ? date('M j, Y', strtotime($quote['valid_until'])) : '-' ?></div>
                </div>

                <h6>Scope</h6>
                <p><?= nl2br($quote['scope_description'] ?: '-') ?></p>

                <h6>Items</h6>
                <table class="table">
                    <thead><tr><th>Item</th><th>Description</th><th class="text-center">Qty</th><th class="text-end">Price</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= $item['item_name'] ?></td>
                        <td><?= $item['description'] ?></td>
                        <td class="text-center"><?= $item['quantity'] ?></td>
                        <td class="text-end"><?= formatCurrency($item['unit_price']) ?></td>
                        <td class="text-end"><?= formatCurrency($item['total_price']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="row justify-content-end">
                    <div class="col-md-4">
                        <table class="table table-sm">
                            <tr><td>Subtotal</td><td class="text-end"><?= formatCurrency($quote['subtotal']) ?></td></tr>
                            <?php if ($quote['discount_amount'] > 0): ?>
                            <tr class="text-success"><td>Discount</td><td class="text-end">-<?= formatCurrency($quote['discount_amount']) ?></td></tr>
                            <?php endif; ?>
                            <?php if ($quote['tax_enabled']): ?>
                            <tr><td>Tax (<?= $quote['tax_percentage'] ?>%)</td><td class="text-end"><?= formatCurrency($quote['tax_amount']) ?></td></tr>
                            <?php endif; ?>
                            <tr class="fw-bold border-top"><td>Total</td><td class="text-end"><?= formatCurrency($quote['total_amount']) ?></td></tr>
                        </table>
                    </div>
                </div>

                <?php if ($quote['terms_and_conditions']): ?>
                <h6>Terms & Conditions</h6>
                <p><?= nl2br($quote['terms_and_conditions']) ?></p>
                <?php endif; ?>

                <?php if ($quote['notes']): ?>
                <h6>Notes</h6>
                <p><?= nl2br($quote['notes']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body text-center">
                <h4><?= formatCurrency($quote['total_amount']) ?></h4>
                <small class="text-muted">Total Amount</small>
                <div class="mt-2">
                    <?php
                    $colors = ['draft'=>'secondary','sent'=>'primary','accepted'=>'success','rejected'=>'danger','expired'=>'warning'];
                    echo '<span class="badge bg-' . ($colors[$quote['status']] ?? 'secondary') . ' fs-6">' . ucfirst($quote['status']) . '</span>';
                    ?>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6>Change Status</h6>
                <form method="POST">
                    <input type="hidden" name="action" value="change_status">
                    <div class="mb-2"><select name="new_status" class="form-select">
                        <option value="draft" <?= $quote['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="sent" <?= $quote['status'] === 'sent' ? 'selected' : '' ?>>Sent</option>
                        <option value="accepted" <?= $quote['status'] === 'accepted' ? 'selected' : '' ?>>Accepted</option>
                        <option value="rejected" <?= $quote['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                    </select></div>
                    <div class="mb-2"><input type="text" name="notes" class="form-control" placeholder="Notes"></div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Status History</h6></div>
            <div class="card-body">
                <?php foreach ($history as $h): ?>
                <div class="mb-2">
                    <small class="text-muted"><?= timeAgo($h['created_at']) ?></small><br>
                    <?= getStatusBadge($h['old_status']) ?> <i class="bi bi-arrow-right"></i> <?= getStatusBadge($h['new_status']) ?>
                    <?php if ($h['admin_name']): ?><br><small>by <?= $h['admin_name'] ?></small><?php endif; ?>
                    <?php if ($h['notes']): ?><br><small class="text-muted"><?= $h['notes'] ?></small><?php endif; ?>
                </div>
                <hr>
                <?php endforeach; ?>
                <?php if (empty($history)): ?><p class="text-muted">No history</p><?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
