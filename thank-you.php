<?php
require_once __DIR__ . '/includes/functions.php';
$submission = $_SESSION['submitted_requirement'] ?? null;
unset($_SESSION['submitted_requirement']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .success-checkmark { width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #28a745, #20c997); display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; animation: scaleIn 0.5s ease; }
        @keyframes scaleIn { from { transform: scale(0); } to { transform: scale(1); } }
        .req-card { max-width: 600px; margin: 0 auto; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="req-card">
            <?php if ($submission): ?>
            <div class="text-center mb-4">
                <div class="success-checkmark"><i class="bi bi-check-lg text-white" style="font-size:3rem"></i></div>
                <h2 class="fw-bold">Thank You!</h2>
                <p class="text-muted">Your website requirements have been received successfully.</p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        <h6 class="text-muted">Requirement ID</h6>
                        <h4 class="text-primary fw-bold"><?= $submission['id'] ?></h4>
                    </div>
                    <table class="table table-borderless">
                        <tr><td class="text-muted" style="width:40%">Website Type</td><td class="fw-bold"><?= $submission['type'] ?></td></tr>
                        <tr><td class="text-muted">Estimated Cost</td><td class="fw-bold text-success"><?= formatCurrency($submission['estimate']) ?></td></tr>
                        <tr><td class="text-muted">Timeline</td><td class="fw-bold"><?= $submission['timeline'] ?></td></tr>
                    </table>

                    <div class="alert alert-info mt-3 mb-0">
                        <i class="bi bi-info-circle"></i>
                        <small>This estimate is generated automatically based on the requirements you selected. Final pricing may change after requirement verification, design discussion, technical assessment, and project scope confirmation.</small>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted mb-3">Our team will review your requirements and contact you shortly.</p>
                <a href="<?= BASE_URL ?>/" class="btn btn-primary">Back to Home</a>
            </div>
            <?php else: ?>
            <div class="text-center">
                <div class="mb-4"><i class="bi bi-clipboard-check text-primary" style="font-size:5rem"></i></div>
                <h2>No Submission Found</h2>
                <p class="text-muted">You haven't submitted any requirements yet.</p>
                <a href="<?= BASE_URL ?>/requirements.php" class="btn btn-primary">Start Your Project</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
