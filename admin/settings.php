<?php
$pageTitle = 'Settings';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groups = ['general', 'pricing', 'email'];
    foreach ($_POST as $key => $value) {
        if (in_array($key, ['action'])) continue;
        setSetting($key, $value);
    }
    logAudit('settings_updated');
    flash('success', 'Settings updated.');
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$settings = db()->fetchAll("SELECT * FROM settings ORDER BY setting_group, setting_key");
$grouped = [];
foreach ($settings as $s) { $grouped[$s['setting_group']][] = $s; }

require_once __DIR__ . '/../includes/header.php';
?>

<h5 class="mb-4">Settings</h5>

<form method="POST">
<?php foreach ($grouped as $group => $items): ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h6 class="mb-0 text-capitalize"><?= $group ?> Settings</h6></div>
    <div class="card-body">
        <?php foreach ($items as $item): ?>
        <div class="mb-3">
            <label class="form-label fw-bold"><?= $item['description'] ?: $item['setting_key'] ?></label>
            <?php if ($item['setting_type'] === 'textarea'): ?>
                <textarea name="<?= $item['setting_key'] ?>" class="form-control" rows="3"><?= $item['setting_value'] ?></textarea>
            <?php elseif ($item['setting_type'] === 'boolean'): ?>
                <select name="<?= $item['setting_key'] ?>" class="form-select" style="max-width:200px">
                    <option value="1" <?= $item['setting_value'] == 1 ? 'selected' : '' ?>>Yes</option>
                    <option value="0" <?= $item['setting_value'] == 0 ? 'selected' : '' ?>>No</option>
                </select>
            <?php elseif ($item['setting_type'] === 'number'): ?>
                <input type="number" name="<?= $item['setting_key'] ?>" class="form-control" value="<?= $item['setting_value'] ?>" style="max-width:200px">
            <?php else: ?>
                <input type="text" name="<?= $item['setting_key'] ?>" class="form-control" value="<?= $item['setting_value'] ?>">
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endforeach; ?>

<button type="submit" class="btn btn-primary btn-lg">Save Settings</button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
