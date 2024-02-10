<?php
session_start();

// Common first names
$first_names = array('John', 'Mary', 'David', 'Sarah', 'Michael', 'Jennifer', 'James', 'Jessica', 'Christopher', 'Lisa');

// Common last names
$last_names = array('Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez');

// Generate random usernames and passwords for new users
$users = array();
for ($i = 0; $i < 10; $i++) {
    $random_first_name = $first_names[array_rand($first_names)];
    $random_last_name = $last_names[array_rand($last_names)];
    $random_username = strtolower($random_first_name . $random_last_name . rand(100, 999));
    $random_password = 'password'; // Set a default password for simplicity
    $users[] = array('username' => $random_username, 'password' => $random_password, 'role' => 'customer');
}

// Insert the generated users into the session
$_SESSION['users'] = array_merge($_SESSION['users'], $users);

// Redirect back to the page where the form was submitted from
header("Location: ".$_SERVER['HTTP_REFERER']);
exit();
?>
