<?php
session_start();
ob_start();



// Include the history template
include '../Templates/Users/History.html.php';
$content = ob_get_clean();

// Include layout
include '../Templates/Users/userLayout.html.php';
?>
