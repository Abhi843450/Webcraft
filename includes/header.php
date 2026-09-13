<?php
require_once __DIR__ . '/functions.php';
$admin = getAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <nav class="sidebar bg-dark text-white" id="sidebar">
            <div class="p-3 border-bottom border-secondary">
                <h5 class="mb-0"><i class="bi bi-gear"></i> <?= APP_NAME ?></h5>
            </div>
            <ul class="nav flex-column p-2">
                <li class="nav-item">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/dashboard.php">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'requirements.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/requirements.php">
                        <i class="bi bi-clipboard-check"></i> Requirements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'quotations.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/quotations.php">
                        <i class="bi bi-file-earmark-text"></i> Quotations
                    </a>
                </li>
                <?php if (hasPermission('pricing')): ?>
                <li class="nav-item">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'website-types.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/website-types.php">
                        <i class="bi bi-globe"></i> Website Types
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'features.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/features.php">
                        <i class="bi bi-puzzle"></i> Features
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'pricing.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/pricing.php">
                        <i class="bi bi-cash-stack"></i> Pricing
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'questions.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/questions.php">
                        <i class="bi bi-question-circle"></i> Questions
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/users.php">
                        <i class="bi bi-people"></i> Admin Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white <?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/settings.php">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </li>
            </ul>
            <div class="mt-auto p-3 border-top border-secondary">
                <div class="d-flex align-items-center mb-2">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:32px;height:32px;">
                        <small><?= strtoupper(substr($admin['name'], 0, 1)) ?></small>
                    </div>
                    <div class="ms-2">
                        <small class="d-block"><?= $admin['name'] ?></small>
                        <small class="text-muted"><?= $admin['role_name'] ?></small>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/admin/logout.php" class="btn btn-outline-light btn-sm w-100">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </a>
            </div>
        </nav>

        <main class="flex-grow-1">
            <div class="bg-white border-bottom p-3 d-flex align-items-center">
                <button class="btn btn-sm btn-outline-secondary d-md-none me-2" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 flex-grow-1"><?= $pageTitle ?? 'Dashboard' ?></h5>
                <span class="text-muted"><?= date('F j, Y') ?></span>
            </div>
            <div class="p-4">
                <?php
                $flashSuccess = flash('success');
                $flashError = flash('error');
                if ($flashSuccess): ?>
                    <div class="alert alert-success alert-dismissible fade show"><?= $flashSuccess ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif;
                if ($flashError): ?>
                    <div class="alert alert-danger alert-dismissible fade show"><?= $flashError ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
