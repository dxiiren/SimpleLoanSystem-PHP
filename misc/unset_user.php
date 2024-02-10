<?php
session_start();

// Unset the 'loans' session variable
unset($_SESSION['users']);
unset($_SESSION['loans']);
$_SESSION['users'][] = array('username' => 'admin', 'password' => 'password', 'role' => 'admin');

// Redirect back to the page where the form was submitted from
header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>
