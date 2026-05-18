<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('../login.php');

$page_title = 'Tickets';
$root = '../';

$search = trim($_GET['search'] ?? '');
$status = trim($_GET['status'] ?? '');
$category = trim($_GET['category'] ?? '');

$where = [];
$params = [];

if ($search !== '') {
    $where[] = '(ticket_code LIKE ? OR title LIKE ? OR reporter_name LIKE ?)';
    $keyword = '%' . $search . '%';
    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;
}

if ($status !== '') {
    $where[] = 'status = ?';
    $params[] = $status;
}

if ($category !== '') {
    $where[] = 'category = ?';
    $params[] = $category;
}

$sql = 'SELECT * FROM tickets';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tickets = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="fw-bold mb-1">Tickets</h1>
        <p class="text-muted mb-0">Manage IT issue reports.</p>
    </div>
    <a class="btn btn-primary" href="create.php">Create Ticket</a>
</div>

<div class="app-card p-3 mb-4">
    <form class="row search-row" method="get">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search code/title/reporter" value="<?= e($search) ?>">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <?php foreach (['Open', 'In Progress', 'Resolved', 'Closed'] as $item): ?>
                    <option value="<?= e($item) ?>" <?= $status === $item ? 'selected' : '' ?>><?= e($item) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php foreach (['Network', 'Hardware', 'Software', 'Printer', 'Account', 'Other'] as $item): ?>
                    <option value="<?= e($item) ?>" <?= $category === $item ? 'selected' : '' ?>><?= e($item) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-outline-primary" type="submit">Filter</button>
        </div>
    </form>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Ticket</th>
                <th>Reporter</th>
                <th>Category</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Created</th>
                <th class="text-end">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$tickets): ?>
                <tr><td colspan="7" class="empty-state">No tickets found.</td></tr>
            <?php endif; ?>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td>
                        <div class="fw-bold"><?= e($ticket['ticket_code']) ?></div>
                        <div class="text-muted small"><?= e($ticket['title']) ?></div>
                    </td>
                    <td><?= e($ticket['reporter_name']) ?></td>
                    <td><?= e($ticket['category']) ?></td>
                    <td><?= status_badge($ticket['status']) ?></td>
                    <td><?= priority_badge($ticket['priority']) ?></td>
                    <td><?= pretty_date($ticket['created_at']) ?></td>
                    <td class="text-end">
                        <a href="view.php?id=<?= (int) $ticket['id'] ?>" class="btn btn-sm btn-outline-primary">Open</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
