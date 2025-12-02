<?php
// PHP_CSR/csrStore.php
// Logic for CSR Store Management (Products & Vouchers)

include __DIR__ . '/../Templates/Csr/csrStore.html.php';
$content = ob_get_clean();

include __DIR__ . '/../Templates/Csr/csrLayout.html.php';
?>
