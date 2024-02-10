<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check for duplicate username
    $user = array_filter($_SESSION['users'], function ($user) use ($username) {
        return $user['username'] === $username;
    });

    if (!empty($user))
    {
        $message = "User already exists in the system. Please choose another username.";
        response($message, false, 'error', 'Error');
    } 
    else
    {
        //add user
        $_SESSION['users'][] = array('username' => $username, 'password' => $password, 'role' => 'customer');
        $message = "User {$username} added successfully.";
        response($message, true, 'success', 'Success');
    }
} else {
    header("Location: index.php");
}

// Function to set session response
function response($message, $success, $icon, $title)
{
    $_SESSION['response'] = compact('message', 'success', 'icon', 'title');
}
?>
