<?php
session_start();
ob_start();

// Check if user is logged in


// Include the notification template
include '../Templates/Users/userNotification.html.php';
$content = ob_get_clean();

// Include layout
include '../Templates/Users/userLayout.html.php';
?>
