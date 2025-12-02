<?php
// PHP_CSR/csrOrders.php
// Logic for CSR Order Management

ob_start();
include __DIR__ . '/../Templates/Csr/csrOrders.html.php';
$content = ob_get_clean();

include __DIR__ . '/../Templates/Csr/csrLayout.html.php';
?>
