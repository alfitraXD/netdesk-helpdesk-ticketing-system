<?php
require_once __DIR__ . '/../includes/auth.php';
require_login('../login.php');

$page_title = 'Add Device';
$root = '../';
$errors = [];

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
        $stmt = $pdo->prepare('INSERT INTO devices (device_name, device_type, brand, model, serial_number, ip_address, mac_address, location, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$deviceName, $deviceType, $brand, $model, $serialNumber, $ipAddress, $macAddress, $location, $status, $notes]);
        flash('success', 'Device added successfully.');
        redirect('index.php');
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="app-card p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold mb-1">Add Device</h1>
                    <p class="text-muted mb-0">Add an IT or network device.</p>
                </div>
                <a href="index.php" class="btn btn-outline-secondary">Back</a>
            </div>

            <?php if ($errors): ?>
                <div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div>
            <?php endif; ?>

            <form method="post">
                <?php include __DIR__ . '/form.php'; ?>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
                    <button class="btn btn-primary" type="submit">Save Device</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
