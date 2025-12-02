<?php
session_start();
ob_start();


// Include the store template
include '../Templates/Users/userStore.html.php';
$content = ob_get_clean();

// Include layout
include '../Templates/Users/userLayout.html.php';
?>
