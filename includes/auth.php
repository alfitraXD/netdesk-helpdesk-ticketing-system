
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

function require_login(string $redirectTo = 'login.php'): void
{
    if (!is_logged_in()) {
        redirect($redirectTo);
    }
}

function redirect_if_logged_in(string $redirectTo = 'dashboard.php'): void
{
    if (is_logged_in()) {
        redirect($redirectTo);
    }
}
