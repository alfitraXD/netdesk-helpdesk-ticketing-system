<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('../login.php');

$page_title = 'Create Ticket';
$root = '../';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $priority = trim($_POST['priority'] ?? 'Low');
    $reporterName = trim($_POST['reporter_name'] ?? '');
    $reporterEmail = trim($_POST['reporter_email'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') $errors[] = 'Ticket title is required.';
    if ($category === '') $errors[] = 'Category is required.';
    if ($reporterName === '') $errors[] = 'Reporter name is required.';
    if ($description === '') $errors[] = 'Description is required.';

    if (!$errors) {
        $ticketCode = make_ticket_code();
        $stmt = $pdo->prepare('INSERT INTO tickets (ticket_code, title, category, priority, reporter_name, reporter_email, description, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$ticketCode, $title, $category, $priority, $reporterName, $reporterEmail, $description, 'Open', current_user()['id']]);
        flash('success', 'Ticket created successfully.');
        redirect('view.php?id=' . $pdo->lastInsertId());
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="app-card p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold mb-1">Create Ticket</h1>
                    <p class="text-muted mb-0">Create a manual ticket from the admin panel.</p>
                </div>
                <a href="index.php" class="btn btn-outline-secondary">Back</a>
            </div>

            <?php if ($errors): ?>
                <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div>
            <?php endif; ?>

            <form method="post">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Ticket Title</label>
                        <input type="text" name="title" class="form-control" value="<?= e($_POST['title'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Priority</label>
                        <select name="priority" class="form-select">
                            <?php foreach (['Low', 'Medium', 'High'] as $priority): ?>
                                <option value="<?= e($priority) ?>" <?= ($_POST['priority'] ?? 'Low') === $priority ? 'selected' : '' ?>><?= e($priority) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="">Select category</option>
                            <?php foreach (['Network', 'Hardware', 'Software', 'Printer', 'Account', 'Other'] as $category): ?>
                                <option value="<?= e($category) ?>" <?= ($_POST['category'] ?? '') === $category ? 'selected' : '' ?>><?= e($category) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Reporter Name</label>
                        <input type="text" name="reporter_name" class="form-control" value="<?= e($_POST['reporter_name'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Reporter Email</label>
                        <input type="email" name="reporter_email" class="form-control" value="<?= e($_POST['reporter_email'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="5" required><?= e($_POST['description'] ?? '') ?></textarea>
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                        <button class="btn btn-primary" type="submit">Save Ticket</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
