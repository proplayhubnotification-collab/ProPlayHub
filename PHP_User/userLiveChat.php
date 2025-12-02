<?php
session_start();
ob_start();

// Check if user is logged in (optional, depending on requirements)

// Include the LiveChat template
include '../Templates/Users/userLiveChat.html.php';
$content = ob_get_clean();

// Include layout
include '../Templates/Users/userLayout.html.php';
?>