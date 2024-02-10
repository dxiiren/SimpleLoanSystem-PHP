<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loan_id = $_POST["loan_id"];

    $loan = array_filter($_SESSION['loans'], function ($loan) use ($loan_id) {
        return $loan['loan_id'] === $loan_id;
    });

    if (!empty($loan)) {
        // Remove user
        $key = key($loan);
        $username = $loan['customer'];
        unset($_SESSION['loans'][$key]);

        $message = "Loan for customer {$username} removed successfully.";
        response($message, true, 'success', 'Success');
    } else {
        $message = "Loan not found.";
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
