<?php
require_once __DIR__ . '/includes/auth.php';
redirect_if_logged_in('dashboard.php');

$page_title = 'Admin Login';
$root = '';
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email and password are required.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ];
            flash('success', 'Login successful. Welcome to NetDesk.');
            redirect('dashboard.php');
        }

        $error = 'Incorrect email or password.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="login-wrapper">
    <div class="login-card app-card p-4 p-md-5">
        <div class="mb-4 text-center">
            <h1 class="fw-bold mb-2">NetDesk</h1>
            <p class="text-muted mb-0">Admin Helpdesk Login</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="off">
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" value="<?= e($_POST['email'] ?? 'admin@netdesk.local') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="admin123" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>

        <div class="detail-box mt-4 small">
            <div class="fw-bold mb-1">Demo Account</div>
            <div>Email: <code>admin@netdesk.local</code></div>
            <div>Password: <code>admin123</code></div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
