<?php
session_start();
ob_start();

// Include the cart template
include '../Templates/Users/userCart.html.php';
$content = ob_get_clean();

// Include layout
include '../Templates/Users/userLayout.html.php';
?>
