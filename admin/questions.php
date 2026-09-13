<?php
$pageTitle = 'Questions';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $qId = db()->insert('questions', [
            'category_id' => intval($_POST['category_id']),
            'question' => $_POST['question'],
            'description' => $_POST['description'] ?: null,
            'question_type' => $_POST['question_type'],
            'field_name' => $_POST['field_name'],
            'is_required' => isset($_POST['is_required']) ? 1 : 0,
            'placeholder' => $_POST['placeholder'] ?: null,
            'display_order' => intval($_POST['display_order']),
            'is_active' => 1,
            'show_if_question_id' => $_POST['show_if_question_id'] ?: null,
            'show_if_value' => $_POST['show_if_value'] ?: null
        ]);
        $options = json_decode($_POST['options'] ?? '[]', true);
        foreach ($options as $opt) {
            if (!empty($opt['label'])) {
                db()->insert('question_options', [
                    'question_id' => $qId,
                    'label' => $opt['label'],
                    'value' => strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', trim($opt['label']))),
                    'price_impact' => floatval($opt['price_impact'] ?? 0),
                    'display_order' => $opt['order'] ?? 0
                ]);
            }
        }
        logAudit('question_added');
        flash('success', 'Question added.');
    } elseif ($action === 'delete') {
        db()->delete('questions', 'id = ?', [$_POST['id']]);
        logAudit('question_deleted');
        flash('success', 'Question deleted.');
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$questions = db()->fetchAll("SELECT q.*, qc.name as category_name FROM questions q JOIN question_categories qc ON q.category_id = qc.id ORDER BY qc.display_order, q.display_order, q.id");
$categories = db()->fetchAll("SELECT * FROM question_categories WHERE is_active = 1 ORDER BY display_order");
require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between mb-4">
    <h5>Questions</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="bi bi-plus"></i> Add Question</button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Question</th><th>Category</th><th>Type</th><th>Required</th><th>Order</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($questions as $q): ?>
                    <tr>
                        <td><strong><?= $q['question'] ?></strong><br><small class="text-muted"><?= $q['field_name'] ?></small></td>
                        <td><span class="badge bg-light text-dark"><?= $q['category_name'] ?></span></td>
                        <td><small><?= $q['question_type'] ?></small></td>
                        <td><?= $q['is_required'] ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle text-muted"></i>' ?></td>
                        <td><?= $q['display_order'] ?></td>
                        <td><?= $q['is_active'] ? '<span class="badge bg-success">On</span>' : '<span class="badge bg-secondary">Off</span>' ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/question-edit.php?id=<?= $q['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $q['id'] ?>">
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <form method="POST"><input type="hidden" name="action" value="add">
    <div class="modal-header"><h5>Add Question</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <div class="row g-2">
            <div class="col-md-6 mb-3"><label class="form-label">Category *</label>
                <select name="category_id" class="form-select" required>
                    <?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>"><?= $c['name'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3"><label class="form-label">Question Type *</label>
                <select name="question_type" class="form-select" required>
                    <option value="single_choice">Single Choice</option><option value="multiple_choice">Multiple Choice</option>
                    <option value="yes_no">Yes/No</option><option value="text">Text</option><option value="textarea">Textarea</option>
                    <option value="number">Number</option><option value="email">Email</option><option value="phone">Phone</option>
                    <option value="url">URL</option><option value="date">Date</option><option value="file">File</option>
                </select>
            </div>
            <div class="col-12 mb-3"><label class="form-label">Question *</label><input type="text" name="question" class="form-control" required></div>
            <div class="col-md-6 mb-3"><label class="form-label">Field Name *</label><input type="text" name="field_name" class="form-control" required placeholder="e.g. needs_booking"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Description</label><input type="text" name="description" class="form-control"></div>
            <div class="col-md-4 mb-3"><label class="form-label">Display Order</label><input type="number" name="display_order" class="form-control" value="0"></div>
            <div class="col-md-4 mb-3"><label class="form-label">Placeholder</label><input type="text" name="placeholder" class="form-control"></div>
            <div class="col-md-4 mb-3 d-flex align-items-end"><div class="form-check"><input type="checkbox" name="is_required" class="form-check-input" id="req_check"><label class="form-check-label" for="req_check">Required</label></div></div>
            <div class="col-md-6 mb-3"><label class="form-label">Show if Question</label>
                <select name="show_if_question_id" class="form-select"><option value="">Always show</option>
                    <?php foreach ($questions as $q2): ?><option value="<?= $q2['id'] ?>"><?= $q2['question'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3"><label class="form-label">Show if Value</label><input type="text" name="show_if_value" class="form-control" placeholder="e.g. yes"></div>
            <div class="col-12 mb-3">
                <label class="form-label">Options (JSON array with label and price_impact)</label>
                <textarea name="options" class="form-control" rows="4" placeholder='[{"label":"Yes","price_impact":10000},{"label":"No","price_impact":0}]'></textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary">Save</button></div>
    </form>
</div></div></div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
