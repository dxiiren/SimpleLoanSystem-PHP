<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];

    $user = array_filter($_SESSION['users'], function ($user) use ($username) {
        return $user['username'] === $username;
    });

    if (!empty($user)) {
        // Remove user
        unset($_SESSION['users'][key($user)]);

        $message = "User {$username} removed successfully.";
        response($message, true, 'success', 'Success');
    } else {
        $message = "User not found for {$username}.";
        response($message, false, 'error', 'Error');
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
