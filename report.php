<?php
require_once __DIR__ . '/includes/auth.php';

$page_title = 'Report Issue';
$root = '';
$successCode = null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $priority = trim($_POST['priority'] ?? 'Low');
    $reporterName = trim($_POST['reporter_name'] ?? '');
    $reporterEmail = trim($_POST['reporter_email'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') $errors[] = 'Issue title is required.';
    if ($category === '') $errors[] = 'Category is required.';
    if ($reporterName === '') $errors[] = 'Reporter name is required.';
    if ($description === '') $errors[] = 'Issue description is required.';

    if (!$errors) {
        $ticketCode = make_ticket_code();
        $stmt = $pdo->prepare('INSERT INTO tickets (ticket_code, title, category, priority, reporter_name, reporter_email, description, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL)');
        $stmt->execute([$ticketCode, $title, $category, $priority, $reporterName, $reporterEmail, $description, 'Open']);
        $successCode = $ticketCode;
        $_POST = [];
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="app-card p-4 p-md-5">
            <div class="mb-4">
                <h1 class="fw-bold mb-2">Report IT Issue</h1>
                <p class="text-muted mb-0">A public form for users or employees to report device, network, printer, or application issues.</p>
            </div>

            <?php if ($successCode): ?>
                <div class="alert alert-success">
                    Report submitted successfully. Save your ticket code: <strong><?= e($successCode) ?></strong>
                </div>
            <?php endif; ?>

            <?php if ($errors): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= e($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Issue Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Example: Second-floor WiFi keeps disconnecting" value="<?= e($_POST['title'] ?? '') ?>" required>
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
                        <label class="form-label fw-semibold">Reporter Email <span class="text-muted fw-normal">optional</span></label>
                        <input type="email" name="reporter_email" class="form-control" value="<?= e($_POST['reporter_email'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Issue Description</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Describe the issue briefly and clearly..." required><?= e($_POST['description'] ?? '') ?></textarea>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">Submit Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
