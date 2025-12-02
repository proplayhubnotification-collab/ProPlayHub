<?php
session_start();
ob_start();

// Include the history page template
include '../Templates/Users/userHistory.html.php';
$content = ob_get_clean();

// Include layout
include '../Templates/Users/userLayout.html.php';
?>
