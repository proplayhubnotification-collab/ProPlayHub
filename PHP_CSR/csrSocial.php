<?php

ob_start();
include __DIR__ . '/../Templates/Csr/csrSocial.html.php';
$content = ob_get_clean();

include __DIR__ . '/../Templates/Csr/csrLayout.html.php';
?>
