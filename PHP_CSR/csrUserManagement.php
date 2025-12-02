<?php
session_start();
ob_start();

include '../Templates/Csr/csrUserManagement.html.php';
$content = ob_get_clean();

include '../Templates/Csr/csrLayout.html.php';
?>