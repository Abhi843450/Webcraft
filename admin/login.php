<?php
require_once __DIR__ . '/../includes/functions.php';
if (isLoggedIn()) redirect(BASE_URL . '/admin/dashboard.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    $admin = db()->fetch("SELECT a.*, r.name as role_name FROM admins a JOIN admin_roles r ON a.role_id = r.id WHERE a.email = ? OR a.username = ?", [$email, $email]);

    if ($admin && password_verify($password, $admin['password'])) {
        if (!$admin['is_active']) {
            $error = 'Account is disabled.';
        } elseif ($admin['locked_until'] && strtotime($admin['locked_until']) > time()) {
            $error = 'Account is temporarily locked. Try again later.';
        } else {
            db()->update('admins', [
                'last_login' => date('Y-m-d H:i:s'),
                'login_attempts' => 0,
                'locked_until' => null
            ], 'id = ?', [$admin['id']]);

            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_role'] = $admin['role_name'];

            logAudit('admin_login', 'admin', $admin['id']);
            redirect(BASE_URL . '/admin/dashboard.php');
        }
    } else {
        if ($admin) {
            $attempts = $admin['login_attempts'] + 1;
            $lockUntil = $attempts >= 5 ? date('Y-m-d H:i:s', strtotime('+15 minutes')) : null;
            db()->update('admins', ['login_attempts' => $attempts, 'locked_until' => $lockUntil], 'id = ?', [$admin['id']]);
        }
        $error = 'Invalid email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .login-card { max-width: 420px; margin: 80px auto; }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold">Admin Panel</h4>
                        <p class="text-muted">Sign in to your account</p>
                    </div>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email or Username</label>
                            <input type="text" name="email" class="form-control" required value="<?= sanitize($_POST['email'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Sign In</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="<?= BASE_URL ?>/" class="text-decoration-none">← Back to Website</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
