<?php
session_start();

$auth = $_SESSION['auth'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && $auth['role'] == 'admin') {

    //get all data;
    $loan_id = uniqid();
    $admin = $auth['username'];
    $customer = $_POST["customer_name_add"];
    $principal_amount = $_POST["principal_amount_add"];
    $interest_rate = $_POST["interest_rate_add"];
    $year = $_POST["year_add"];
    $total_payment = 0;

    // Calculate loan details
    [$total_amount_repay, $total_monthly_repay, $total_interest_repay] = calcLoan($principal_amount, $interest_rate, $year);

    //store loan
    $_SESSION['loans'][] = compact(
        'loan_id',
        'admin',
        'customer',
        'principal_amount',
        'interest_rate',
        'year',
        'total_payment',
        'total_amount_repay',
        'total_monthly_repay',
        'total_interest_repay'
    );

    $message = "Loan for customer: {$customer} added successfully.";
    response($message, true, 'success', 'Success');
} else {
    header("Location: index.php");
}

function calcLoan($principal_amount, $interest_rate, $year)
{
    $interest = $principal_amount * $year * ($interest_rate/100);
    $amount_repay = $principal_amount + $interest;
    $monthly_repay = $amount_repay / ($year * 12);
    $monthly_repay = number_format($monthly_repay, 2, '.', ',');
    return [$amount_repay, $monthly_repay, $interest];
}

// Function to set session response
function response($message, $success, $icon, $title)
{
    $_SESSION['response'] = compact('message', 'success', 'icon', 'title');
}
