<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('../login.php');

$page_title = 'Inventory';
$root = '../';

$search = trim($_GET['search'] ?? '');
$type = trim($_GET['device_type'] ?? '');
$status = trim($_GET['status'] ?? '');

$where = [];
$params = [];

if ($search !== '') {
    $where[] = '(device_name LIKE ? OR ip_address LIKE ? OR mac_address LIKE ? OR serial_number LIKE ? OR location LIKE ?)';
    $keyword = '%' . $search . '%';
    array_push($params, $keyword, $keyword, $keyword, $keyword, $keyword);
}

if ($type !== '') {
    $where[] = 'device_type = ?';
    $params[] = $type;
}

if ($status !== '') {
    $where[] = 'status = ?';
    $params[] = $status;
}

$sql = 'SELECT * FROM devices';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$devices = $stmt->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h1 class="fw-bold mb-1">Inventory</h1>
        <p class="text-muted mb-0">IT and network device data.</p>
    </div>
    <a class="btn btn-primary" href="create.php">Add Device</a>
</div>

<div class="app-card p-3 mb-4">
    <form class="row search-row" method="get">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search name/IP/MAC/location" value="<?= e($search) ?>">
        </div>
        <div class="col-md-3">
            <select name="device_type" class="form-select">
                <option value="">All Types</option>
                <?php foreach (['Router', 'Switch', 'Access Point', 'Laptop', 'PC', 'Printer', 'Server', 'Other'] as $item): ?>
                    <option value="<?= e($item) ?>" <?= $type === $item ? 'selected' : '' ?>><?= e($item) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <?php foreach (['Active', 'Maintenance', 'Broken', 'Retired'] as $item): ?>
                    <option value="<?= e($item) ?>" <?= $status === $item ? 'selected' : '' ?>><?= e($item) ?></option>
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
                <th>Device</th>
                <th>Type</th>
                <th>IP Address</th>
                <th>MAC</th>
                <th>Location</th>
                <th>Status</th>
                <th class="text-end">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$devices): ?>
                <tr><td colspan="7" class="empty-state">No devices found.</td></tr>
            <?php endif; ?>
            <?php foreach ($devices as $device): ?>
                <tr>
                    <td>
                        <div class="fw-bold"><?= e($device['device_name']) ?></div>
                        <div class="text-muted small"><?= e(trim(($device['brand'] ?? '') . ' ' . ($device['model'] ?? '')) ?: '-') ?></div>
                    </td>
                    <td><?= e($device['device_type']) ?></td>
                    <td><?= e($device['ip_address'] ?: '-') ?></td>
                    <td><?= e($device['mac_address'] ?: '-') ?></td>
                    <td><?= e($device['location'] ?: '-') ?></td>
                    <td><?= device_status_badge($device['status']) ?></td>
                    <td class="text-end">
                        <a href="edit.php?id=<?= (int) $device['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
