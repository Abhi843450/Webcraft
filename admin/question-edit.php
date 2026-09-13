<?php
$pageTitle = 'Edit Question';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = intval($_GET['id'] ?? 0);
$question = db()->fetch("SELECT * FROM questions WHERE id = ?", [$id]);
if (!$question) redirect(BASE_URL . '/admin/questions.php');

$options = db()->fetchAll("SELECT * FROM question_options WHERE question_id = ? ORDER BY display_order", [$id]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    db()->update('questions', [
        'category_id' => intval($_POST['category_id']),
        'question' => $_POST['question'],
        'description' => $_POST['description'] ?: null,
        'question_type' => $_POST['question_type'],
        'field_name' => $_POST['field_name'],
        'is_required' => isset($_POST['is_required']) ? 1 : 0,
        'placeholder' => $_POST['placeholder'] ?: null,
        'display_order' => intval($_POST['display_order']),
        'is_active' => isset($_POST['is_active']) ? 1 : 0,
        'show_if_question_id' => $_POST['show_if_question_id'] ?: null,
        'show_if_value' => $_POST['show_if_value'] ?: null
    ], 'id = ?', [$id]);

    db()->delete('question_options', 'question_id = ?', [$id]);
    $optionLabels = $_POST['option_label'] ?? [];
    $optionPrices = $_POST['option_price'] ?? [];
    $optionOrders = $_POST['option_order'] ?? [];
    foreach ($optionLabels as $i => $label) {
        if (!empty(trim($label))) {
            db()->insert('question_options', [
                'question_id' => $id,
                'label' => trim($label),
                'value' => strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', trim($label))),
                'price_impact' => floatval($optionPrices[$i] ?? 0),
                'display_order' => intval($optionOrders[$i] ?? 0),
                'is_active' => 1
            ]);
        }
    }
    logAudit('question_updated', 'question', $id);
    flash('success', 'Question updated.');
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$categories = db()->fetchAll("SELECT * FROM question_categories WHERE is_active = 1 ORDER BY display_order");
$allQuestions = db()->fetchAll("SELECT id, question FROM questions WHERE id != ? ORDER BY id", [$id]);
require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between mb-4">
    <h5>Edit Question</h5>
    <a href="<?= BASE_URL ?>/admin/questions.php" class="btn btn-outline-secondary">Back</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST">
            <div class="row g-2">
                <div class="col-md-6 mb-3"><label class="form-label">Category *</label>
                    <select name="category_id" class="form-select" required>
                        <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $question['category_id'] == $c['id'] ? 'selected' : '' ?>><?= $c['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3"><label class="form-label">Question Type *</label>
                    <select name="question_type" class="form-select" required>
                        <?php foreach (['single_choice','multiple_choice','yes_no','text','textarea','number','email','phone','url','date','file'] as $t): ?>
                        <option value="<?= $t ?>" <?= $question['question_type'] === $t ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$t)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 mb-3"><label class="form-label">Question *</label><input type="text" name="question" class="form-control" required value="<?= $question['question'] ?>"></div>
                <div class="col-md-6 mb-3"><label class="form-label">Field Name *</label><input type="text" name="field_name" class="form-control" required value="<?= $question['field_name'] ?>"></div>
                <div class="col-md-6 mb-3"><label class="form-label">Description</label><input type="text" name="description" class="form-control" value="<?= $question['description'] ?>"></div>
                <div class="col-md-4 mb-3"><label class="form-label">Display Order</label><input type="number" name="display_order" class="form-control" value="<?= $question['display_order'] ?>"></div>
                <div class="col-md-4 mb-3"><label class="form-label">Placeholder</label><input type="text" name="placeholder" class="form-control" value="<?= $question['placeholder'] ?>"></div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <div class="form-check me-3"><input type="checkbox" name="is_required" class="form-check-input" id="r1" <?= $question['is_required'] ? 'checked' : '' ?>><label class="form-check-label" for="r1">Required</label></div>
                    <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input" id="r2" <?= $question['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="r2">Active</label></div>
                </div>
                <div class="col-md-6 mb-3"><label class="form-label">Show if Question</label>
                    <select name="show_if_question_id" class="form-select"><option value="">Always show</option>
                        <?php foreach ($allQuestions as $q): ?>
                        <option value="<?= $q['id'] ?>" <?= $question['show_if_question_id'] == $q['id'] ? 'selected' : '' ?>><?= $q['question'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3"><label class="form-label">Show if Value</label><input type="text" name="show_if_value" class="form-control" value="<?= $question['show_if_value'] ?>"></div>
            </div>

            <h6 class="mt-3">Options</h6>
            <div id="options-container">
                <?php foreach ($options as $opt): ?>
                <div class="row g-2 mb-2 option-row">
                    <div class="col-md-5"><input type="text" name="option_label[]" class="form-control" placeholder="Label" value="<?= $opt['label'] ?>"></div>
                    <div class="col-md-3"><input type="number" name="option_price[]" class="form-control" placeholder="Price Impact" step="0.01" value="<?= $opt['price_impact'] ?>"></div>
                    <div class="col-md-2"><input type="number" name="option_order[]" class="form-control" placeholder="Order" value="<?= $opt['display_order'] ?>"></div>
                    <div class="col-md-2"><button type="button" class="btn btn-outline-danger btn-sm remove-option"><i class="bi bi-x"></i></button></div>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="add-option"><i class="bi bi-plus"></i> Add Option</button>

            <div><button type="submit" class="btn btn-primary">Update Question</button></div>
        </form>
    </div>
</div>

<script>
document.getElementById('add-option').addEventListener('click', () => {
    const html = '<div class="row g-2 mb-2 option-row"><div class="col-md-5"><input type="text" name="option_label[]" class="form-control" placeholder="Label"></div><div class="col-md-3"><input type="number" name="option_price[]" class="form-control" placeholder="Price Impact" step="0.01"></div><div class="col-md-2"><input type="number" name="option_order[]" class="form-control" placeholder="Order"></div><div class="col-md-2"><button type="button" class="btn btn-outline-danger btn-sm remove-option"><i class="bi bi-x"></i></button></div></div>';
    document.getElementById('options-container').insertAdjacentHTML('beforeend', html);
});
document.addEventListener('click', e => { if (e.target.classList.contains('remove-option')) e.target.closest('.option-row').remove(); });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
