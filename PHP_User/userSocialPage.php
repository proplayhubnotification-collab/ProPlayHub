<?php
session_start();
ob_start();

// Include the social page template
include '../Templates/Users/userSocialPage.html.php';
$content = ob_get_clean();

// Include layout
include '../Templates/Users/userLayout.html.php';
?>
