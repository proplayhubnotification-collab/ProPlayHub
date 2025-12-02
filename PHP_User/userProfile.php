<?php
session_start();
ob_start();


// Include the profile template
include '../Templates/Users/userProfile.html.php';
$content = ob_get_clean();

// Include layout
include '../Templates/Users/userLayout.html.php';
?>
