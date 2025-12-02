<?php
session_start();
ob_start();


// Include the payment template
include '../Templates/Users/userPayment.html.php';
$content = ob_get_clean();

// Include layout
include '../Templates/Users/userLayout.html.php';
?>
