<?php
session_start();

//all users
$users = $_SESSION['users'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Decode JSON string into a PHP associative array
    $userData = json_decode($_POST["editUser"], true);
    $findName = $userData['username'];

    $username = $_POST["username"];
    $password = $_POST["password"];

    //Find user to be updated
    $user = array_filter($users, function ($user) use ($findName) {
        return $user['username'] === $findName;
    });

    //Check user exist or not
    if (empty($user)) {
        $message = "User not found for {$username}.";
        response($message, false, 'error', 'Error');
    } else {
        $key = key($user);

        //check whether the name in already exist or not except the sppecific user
        $boolean = isDuplicateName($users, $key, $username);

        if ($boolean)
        {
            $message = "User already exists in the system. Please choose another username.";
            response($message, false, 'error', 'Error');
        } else {
            //update user
            $_SESSION['users'][$key] = array('username' => $username, 'password' => $password, 'role' => 'customer');
            $message = "User {$username} updated successfully.";
            response($message, true, 'success', 'Success');
        }
    }
} else {
    header("Location: index.php");
}

// Function to check duplicate name
function isDuplicateName($users, $key, $username)
{
    unset($users[$key]);

    $user = array_filter($users, function ($user) use ($username) {
        return $user['username'] === $username;
    });

    return empty($user) ? false : true;
}

// Function to set session response
function response($message, $success, $icon, $title)
{
    $_SESSION['response'] = compact('message', 'success', 'icon', 'title');
}
