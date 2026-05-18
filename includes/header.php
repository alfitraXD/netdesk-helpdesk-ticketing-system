<?php
$page_title = $page_title ?? 'NetDesk';
$root = $root ?? '';
$user = current_user();
$flash = get_flash();
$current_path = str_replace('\\', '/', $_SERVER['PHP_SELF'] ?? '');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title) ?> - NetDesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= e($root) ?>static/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg app-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= e($root) ?>dashboard.php">NetDesk</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($user): ?>
                    <li class="nav-item"><a class="nav-link <?= str_contains($current_path, 'dashboard') ? 'active' : '' ?>" href="<?= e($root) ?>dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link <?= str_contains($current_path, 'tickets') ? 'active' : '' ?>" href="<?= e($root) ?>tickets/index.php">Tickets</a></li>
                    <li class="nav-item"><a class="nav-link <?= str_contains($current_path, 'inventory') ? 'active' : '' ?>" href="<?= e($root) ?>inventory/index.php">Inventory</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link <?= str_contains($current_path, 'report') ? 'active' : '' ?>" href="<?= e($root) ?>report.php">Report Issue</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <?php if ($user): ?>
                    <span class="small text-muted d-none d-md-inline">Hi, <?= e($user['name']) ?></span>
                    <a class="btn btn-sm btn-outline-danger" href="<?= e($root) ?>logout.php">Logout</a>
                <?php else: ?>
                    <a class="btn btn-sm btn-primary" href="<?= e($root) ?>login.php">Admin Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<main class="container py-4">
    <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
            <?= e($flash['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
