<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Device Name</label>
        <input type="text" name="device_name" class="form-control" value="<?= e($_POST['device_name'] ?? '') ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Device Type</label>
        <select name="device_type" class="form-select" required>
            <option value="">Select type</option>
            <?php foreach (['Router', 'Switch', 'Access Point', 'Laptop', 'PC', 'Printer', 'Server', 'Other'] as $type): ?>
                <option value="<?= e($type) ?>" <?= ($_POST['device_type'] ?? '') === $type ? 'selected' : '' ?>><?= e($type) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Brand</label>
        <input type="text" name="brand" class="form-control" value="<?= e($_POST['brand'] ?? '') ?>" placeholder="MikroTik, TP-Link, HP, Lenovo">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Model</label>
        <input type="text" name="model" class="form-control" value="<?= e($_POST['model'] ?? '') ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Serial Number</label>
        <input type="text" name="serial_number" class="form-control" value="<?= e($_POST['serial_number'] ?? '') ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Status</label>
        <select name="status" class="form-select">
            <?php foreach (['Active', 'Maintenance', 'Broken', 'Retired'] as $status): ?>
                <option value="<?= e($status) ?>" <?= ($_POST['status'] ?? 'Active') === $status ? 'selected' : '' ?>><?= e($status) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">IP Address</label>
        <input type="text" name="ip_address" class="form-control" value="<?= e($_POST['ip_address'] ?? '') ?>" placeholder="192.168.1.1">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">MAC Address</label>
        <input type="text" name="mac_address" class="form-control" value="<?= e($_POST['mac_address'] ?? '') ?>" placeholder="AA:BB:CC:DD:EE:FF">
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Location</label>
        <input type="text" name="location" class="form-control" value="<?= e($_POST['location'] ?? '') ?>" placeholder="Admin Office, Computer Lab, 2nd Floor">
    </div>
    <div class="col-12">
        <label class="form-label fw-semibold">Notes</label>
        <textarea name="notes" class="form-control" rows="4"><?= e($_POST['notes'] ?? '') ?></textarea>
    </div>
</div>
