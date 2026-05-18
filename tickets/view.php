<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('../login.php');

$page_title = 'Ticket Detail';
$root = '../';
$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM tickets WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    flash('danger', 'Ticket not found.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = trim($_POST['status'] ?? $ticket['status']);
    $note = trim($_POST['note'] ?? '');

    if (!in_array($newStatus, ['Open', 'In Progress', 'Resolved', 'Closed'], true)) {
        $newStatus = $ticket['status'];
    }

    if ($newStatus !== $ticket['status']) {
        $update = $pdo->prepare('UPDATE tickets SET status = ?, updated_at = NOW() WHERE id = ?');
        $update->execute([$newStatus, $id]);
    }

    if ($note !== '' || $newStatus !== $ticket['status']) {
        $noteText = $note !== '' ? $note : 'Status updated.';
        $insert = $pdo->prepare('INSERT INTO ticket_notes (ticket_id, user_id, note, old_status, new_status) VALUES (?, ?, ?, ?, ?)');
        $insert->execute([$id, current_user()['id'], $noteText, $ticket['status'], $newStatus]);
    }

    flash('success', 'Ticket updated successfully.');
    redirect('view.php?id=' . $id);
}

$noteStmt = $pdo->prepare('SELECT ticket_notes.*, users.name AS user_name FROM ticket_notes LEFT JOIN users ON ticket_notes.user_id = users.id WHERE ticket_id = ? ORDER BY ticket_notes.created_at DESC');
$noteStmt->execute([$id]);
$notes = $noteStmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="fw-bold mb-1"><?= e($ticket['ticket_code']) ?></h1>
        <p class="text-muted mb-0"><?= e($ticket['title']) ?></p>
    </div>
    <div class="d-flex gap-2">
        <a href="index.php" class="btn btn-outline-secondary">Back</a>
        <form method="post" action="delete.php" data-confirm-delete="Are you sure you want to delete this ticket?">
            <input type="hidden" name="id" value="<?= (int) $ticket['id'] ?>">
            <button class="btn btn-outline-danger" type="submit">Delete</button>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="app-card p-4 mb-4">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <?= status_badge($ticket['status']) ?>
                <?= priority_badge($ticket['priority']) ?>
                <span class="status-badge badge-muted"><?= e($ticket['category']) ?></span>
            </div>
            <h2 class="h5 fw-bold">Description</h2>
            <p class="mb-0" style="white-space: pre-line;"><?= e($ticket['description']) ?></p>
        </div>

        <div class="app-card p-4">
            <h2 class="h5 fw-bold mb-3">Notes / Activity</h2>
            <?php if (!$notes): ?>
                <div class="empty-state">No handling notes yet.</div>
            <?php endif; ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($notes as $note): ?>
                    <div class="note-item">
                        <div class="d-flex justify-content-between gap-3 mb-1">
                            <strong><?= e($note['user_name'] ?? 'System') ?></strong>
                            <span class="text-muted small"><?= pretty_date($note['created_at']) ?></span>
                        </div>
                        <p class="mb-2" style="white-space: pre-line;"><?= e($note['note']) ?></p>
                        <?php if ($note['old_status'] !== $note['new_status']): ?>
                            <div class="small text-muted">Status: <?= e($note['old_status']) ?> &rarr; <?= e($note['new_status']) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="app-card p-4 mb-4">
            <h2 class="h5 fw-bold mb-3">Reporter</h2>
            <div class="detail-box mb-2">
                <div class="text-muted small">Name</div>
                <div class="fw-bold"><?= e($ticket['reporter_name']) ?></div>
            </div>
            <div class="detail-box mb-2">
                <div class="text-muted small">Email</div>
                <div class="fw-bold"><?= e($ticket['reporter_email'] ?: '-') ?></div>
            </div>
            <div class="detail-box">
                <div class="text-muted small">Created</div>
                <div class="fw-bold"><?= pretty_date($ticket['created_at']) ?></div>
            </div>
        </div>

        <div class="app-card p-4">
            <h2 class="h5 fw-bold mb-3">Update Ticket</h2>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select" data-status-preview>
                        <?php foreach (['Open', 'In Progress', 'Resolved', 'Closed'] as $status): ?>
                            <option value="<?= e($status) ?>" <?= $ticket['status'] === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Note</label>
                    <textarea name="note" class="form-control" rows="4" placeholder="Handling notes..."></textarea>
                </div>
                <button class="btn btn-primary w-100" type="submit">Update</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
