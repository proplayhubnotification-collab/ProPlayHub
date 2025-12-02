<?php
// PHP_CSR/csrProfile.php
// Logic for CSR Profile/Dashboard

// In a real app, fetch stats from DB
$stats = [
    'revenue' => 15000000,
    'orders' => 120,
    'new_users' => 45,
    'pending_issues' => 5
];

include __DIR__ . '/../Templates/Csr/csrProfile.html.php';
$content = ob_get_clean();

// Include layout
include '../Templates/Csr/csrLayout.html.php';
?>
