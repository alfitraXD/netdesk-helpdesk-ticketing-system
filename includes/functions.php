<?php
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function status_badge(string $status): string
{
    $class = match ($status) {
        'Open' => 'badge-open',
        'In Progress' => 'badge-progress',
        'Resolved' => 'badge-resolved',
        'Closed' => 'badge-closed',
        default => 'badge-muted',
    };

    return '<span class="status-badge ' . $class . '">' . e($status) . '</span>';
}

function priority_badge(string $priority): string
{
    $class = match ($priority) {
        'High' => 'priority-high',
        'Medium' => 'priority-medium',
        'Low' => 'priority-low',
        default => 'priority-low',
    };

    return '<span class="priority-badge ' . $class . '">' . e($priority) . '</span>';
}

function device_status_badge(string $status): string
{
    $class = match ($status) {
        'Active' => 'badge-resolved',
        'Maintenance' => 'badge-progress',
        'Broken' => 'badge-open',
        'Retired' => 'badge-closed',
        default => 'badge-muted',
    };

    return '<span class="status-badge ' . $class . '">' . e($status) . '</span>';
}

function make_ticket_code(): string
{
    return 'NTD-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
}

function pretty_date(?string $date): string
{
    if (!$date) {
        return '-';
    }

    return date('d M Y, H:i', strtotime($date));
}
