<?php
session_start();

// Unset the 'loans' session variable
unset($_SESSION['loans']);

// Redirect back to the page where the form was submitted from
header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>
