<?php
session_start();

// session_unset();
unset($_SESSION['auth']);
unset($_SESSION['loan']);
unset($_SESSION['dd']);

//setup user account;
$users = array(
    array('username' => 'admin', 'password' => 'password', 'role' => 'admin'),
    array('username' => 'user1', 'password' => 'password', 'role' => 'customer'),
    array('username' => 'user2', 'password' => 'password', 'role' => 'customer'),
    array('username' => 'user3', 'password' => 'password', 'role' => 'customer'),
);

if(!isset($_SESSION['users'])) {
    $_SESSION['users'] = $users;
}


header("Location: login.php");
