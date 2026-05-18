<?php
require_once __DIR__ . '/includes/auth.php';
require_login('login.php');

$page_title = 'Dashboard';
$root = '';

$ticketCounts = $pdo->query("SELECT status, COUNT(*) AS total FROM tickets GROUP BY status")->fetchAll();
$counts = ['Open' => 0, 'In Progress' => 0, 'Resolved' => 0, 'Closed' => 0];
foreach ($ticketCounts as $row) {
    $counts[$row['status']] = (int) $row['total'];
}

$totalTickets = array_sum($counts);
$totalDevices = (int) $pdo->query('SELECT COUNT(*) FROM devices')->fetchColumn();
$activeDevices = (int) $pdo->query("SELECT COUNT(*) FROM devices WHERE status = 'Active'")->fetchColumn();
$maintenanceDevices = (int) $pdo->query("SELECT COUNT(*) FROM devices WHERE status = 'Maintenance'")->fetchColumn();

$latestTickets = $pdo->query('SELECT * FROM tickets ORDER BY created_at DESC LIMIT 5')->fetchAll();
$latestDevices = $pdo->query('SELECT * FROM devices ORDER BY created_at DESC LIMIT 5')->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero-card hero-gradient p-4 p-md-5 mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-8">
            <span class="badge text-bg-primary mb-3">IT Helpdesk System</span>
            <h1 class="fw-bold display-6 mb-3">NetDesk Dashboard</h1>
            <p class="text-muted mb-0">Manage IT issue reports, monitor ticket progress, and organize office or school device data from one simple system.</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="tickets/create.php" class="btn btn-primary me-2 mb-2">Create Ticket</a>
            <a href="inventory/create.php" class="btn btn-outline-primary mb-2">Add Device</a>
        </div>
    </div>
</section>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card-stat">
            <div class="text-muted small">Total Tickets</div>
            <div class="stat-number"><?= $totalTickets ?></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card-stat">
            <div class="text-muted small">Open Tickets</div>
            <div class="stat-number"><?= $counts['Open'] ?></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card-stat">
            <div class="text-muted small">Total Devices</div>
            <div class="stat-number"><?= $totalDevices ?></div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card-stat">
            <div class="text-muted small">Need Maintenance</div>
            <div class="stat-number"><?= $maintenanceDevices ?></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="table-card">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0 fw-bold">Latest Tickets</h2>
                <a href="tickets/index.php" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Priority</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!$latestTickets): ?>
                        <tr><td colspan="4" class="empty-state">No tickets available yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($latestTickets as $ticket): ?>
                        <tr>
                            <td><a class="fw-bold text-decoration-none" href="tickets/view.php?id=<?= (int) $ticket['id'] ?>"><?= e($ticket['ticket_code']) ?></a></td>
                            <td><?= e($ticket['title']) ?></td>
                            <td><?= status_badge($ticket['status']) ?></td>
                            <td><?= priority_badge($ticket['priority']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="table-card">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0 fw-bold">Latest Devices</h2>
                <a href="inventory/index.php" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th>Device</th>
                        <th>IP</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!$latestDevices): ?>
                        <tr><td colspan="3" class="empty-state">No devices available yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($latestDevices as $device): ?>
                        <tr>
                            <td><?= e($device['device_name']) ?></td>
                            <td><?= e($device['ip_address'] ?: '-') ?></td>
                            <td><?= device_status_badge($device['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
