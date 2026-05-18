<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('../login.php');

$page_title = 'Edit Device';
$root = '../';
$errors = [];
$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM devices WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$device = $stmt->fetch();

if (!$device) {
    flash('danger', 'Device not found.');
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deviceName = trim($_POST['device_name'] ?? '');
    $deviceType = trim($_POST['device_type'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $serialNumber = trim($_POST['serial_number'] ?? '');
    $ipAddress = trim($_POST['ip_address'] ?? '');
    $macAddress = trim($_POST['mac_address'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $status = trim($_POST['status'] ?? 'Active');
    $notes = trim($_POST['notes'] ?? '');

    if ($deviceName === '') $errors[] = 'Device name is required.';
    if ($deviceType === '') $errors[] = 'Device type is required.';

    if (!$errors) {
        $stmt = $pdo->prepare('UPDATE devices SET device_name = ?, device_type = ?, brand = ?, model = ?, serial_number = ?, ip_address = ?, mac_address = ?, location = ?, status = ?, notes = ?, updated_at = NOW() WHERE id = ?');
        $stmt->execute([$deviceName, $deviceType, $brand, $model, $serialNumber, $ipAddress, $macAddress, $location, $status, $notes, $id]);
        flash('success', 'Device updated successfully.');
        redirect('index.php');
    }
} else {
    $_POST = $device;
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="app-card p-4 p-md-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h1 class="fw-bold mb-1">Edit Device</h1>
                    <p class="text-muted mb-0"><?= e($device['device_name']) ?></p>
                </div>
                <form method="post" action="delete.php" data-confirm-delete="Are you sure you want to delete this device?">
                    <input type="hidden" name="id" value="<?= (int) $device['id'] ?>">
                    <button class="btn btn-outline-danger" type="submit">Delete Device</button>
                </form>
            </div>

            <?php if ($errors): ?>
                <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div>
            <?php endif; ?>

            <form method="post">
                <?php include __DIR__ . '/form.php'; ?>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                    <button class="btn btn-primary" type="submit">Update Device</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
