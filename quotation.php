<?php
require_once __DIR__ . '/includes/functions.php';

$token = $_GET['token'] ?? null;
$quoteId = intval($_GET['id'] ?? 0);

if ($token) {
    $quote = db()->fetch("SELECT q.*, r.customer_name, r.customer_email, r.customer_phone, r.customer_company, r.requirement_id FROM quotations q JOIN requirements r ON q.requirement_id = r.id WHERE q.resume_token = ?", [$token]);
} elseif ($quoteId) {
    $quote = db()->fetch("SELECT q.*, r.customer_name, r.customer_email, r.customer_phone, r.customer_company, r.requirement_id FROM quotations q JOIN requirements r ON q.requirement_id = r.id WHERE q.id = ?", [$quoteId]);
} else {
    $quote = null;
}

$items = $quote ? db()->fetchAll("SELECT * FROM quotation_items WHERE quotation_id = ? ORDER BY display_order", [$quote['id']]) : [];

if (!$quote) {
    $title = 'Quotation Not Found';
} else {
    $title = 'Quotation ' . $quote['quotation_number'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>/"><i class="bi bi-code-slash"></i> <?= APP_NAME ?></a>
        </div>
    </nav>

    <div class="container py-5">
        <?php if (!$quote): ?>
        <div class="text-center py-5">
            <i class="bi bi-exclamation-triangle text-warning" style="font-size:4rem"></i>
            <h2 class="mt-3">Quotation Not Found</h2>
            <p class="text-muted">The quotation link is invalid or has expired.</p>
            <a href="<?= BASE_URL ?>/" class="btn btn-primary">Go Home</a>
        </div>
        <?php else: ?>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h2 class="text-primary fw-bold">Quotation</h2>
                                <h4 class="mb-0"><?= $quote['quotation_number'] ?></h4>
                            </div>
                            <div class="text-end">
                                <?php
                                $colors = ['draft'=>'secondary','sent'=>'primary','accepted'=>'success','rejected'=>'danger','expired'=>'warning'];
                                echo '<span class="badge bg-' . ($colors[$quote['status']] ?? 'secondary') . ' fs-6">' . ucfirst($quote['status']) . '</span>';
                                ?>
                                <div class="mt-2 text-muted">
                                    Date: <?= date('M j, Y', strtotime($quote['created_at'])) ?><br>
                                    Valid Until: <?= $quote['valid_until'] ? date('M j, Y', strtotime($quote['valid_until'])) : 'N/A' ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted">From</h6>
                                <p class="mb-0 fw-bold"><?= getSetting('company_name') ?></p>
                                <p class="mb-0 text-muted"><?= getSetting('company_email') ?></p>
                                <p class="mb-0 text-muted"><?= getSetting('company_phone') ?></p>
                                <p class="mb-0 text-muted"><?= getSetting('company_address') ?></p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h6 class="text-muted">Bill To</h6>
                                <p class="mb-0 fw-bold"><?= $quote['customer_name'] ?></p>
                                <p class="mb-0 text-muted"><?= $quote['customer_email'] ?></p>
                                <p class="mb-0 text-muted"><?= $quote['customer_phone'] ?></p>
                                <p class="mb-0 text-muted"><?= $quote['customer_company'] ?></p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Project: <?= $quote['project_name'] ?></h5>
                            <?php if ($quote['scope_description']): ?>
                            <p class="text-muted"><?= nl2br($quote['scope_description']) ?></p>
                            <?php endif; ?>
                        </div>

                        <h6>Items</h6>
                        <table class="table">
                            <thead class="table-light">
                                <tr><th>Item</th><th>Description</th><th class="text-center">Qty</th><th class="text-end">Unit Price</th><th class="text-end">Total</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= $item['item_name'] ?></td>
                                    <td class="text-muted"><?= $item['description'] ?></td>
                                    <td class="text-center"><?= $item['quantity'] ?></td>
                                    <td class="text-end">NPR <?= number_format($item['unit_price'], 0) ?></td>
                                    <td class="text-end">NPR <?= number_format($item['total_price'], 0) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div class="row justify-content-end">
                            <div class="col-md-5">
                                <table class="table table-sm">
                                    <tr><td>Subtotal</td><td class="text-end">NPR <?= number_format($quote['subtotal'], 0) ?></td></tr>
                                    <?php if ($quote['discount_amount'] > 0): ?>
                                    <tr class="text-success"><td>Discount</td><td class="text-end">-NPR <?= number_format($quote['discount_amount'], 0) ?></td></tr>
                                    <?php endif; ?>
                                    <?php if ($quote['tax_enabled']): ?>
                                    <tr><td>Tax (<?= $quote['tax_percentage'] ?>%)</td><td class="text-end">NPR <?= number_format($quote['tax_amount'], 0) ?></td></tr>
                                    <?php endif; ?>
                                    <tr class="fw-bold border-top fs-5"><td>Total</td><td class="text-end text-primary">NPR <?= number_format($quote['total_amount'], 0) ?></td></tr>
                                </table>
                            </div>
                        </div>

                        <?php if ($quote['terms_and_conditions']): ?>
                        <div class="mt-4">
                            <h6>Terms & Conditions</h6>
                            <p class="text-muted small"><?= nl2br($quote['terms_and_conditions']) ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if ($quote['notes']): ?>
                        <div class="mt-3">
                            <h6>Notes</h6>
                            <p class="text-muted"><?= nl2br($quote['notes']) ?></p>
                        </div>
                        <?php endif; ?>

                        <div class="alert alert-info mt-4 mb-0">
                            <small><i class="bi bi-info-circle"></i> This quotation was generated based on your submitted requirements. For any questions, please contact us.</small>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="<?= BASE_URL ?>/" class="btn btn-outline-primary">Back to Home</a>
                    <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer"></i> Print</button>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
