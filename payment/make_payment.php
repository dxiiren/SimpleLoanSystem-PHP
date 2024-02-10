<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loan_id = $_POST["loan_id"];

    $loan = array_filter($_SESSION['loans'], function ($loan) use ($loan_id) {
        return $loan['loan_id'] === $loan_id;
    });

    if (!empty($loan)) {

        $key = key($loan);
        $loan = $_SESSION['loans'][$key];

        //update loan
        $data = recalculateLoan($_POST,$loan);
        $_SESSION['loans'][$key] = $data;

        $message = "Loan for customer {$loan['customer']} updated successfully.";
        response($message, true, 'success', 'Success');
    } else {
        $message = "Loan not found.";
        response($message, false, 'error', 'Error');
    }
} else {
    header("Location: index.php");
}

function recalculateLoan($data, $loan)
{
    //get all data;
    $loan_id = $loan['loan_id'];
    $admin = $loan['admin'];
    $customer = $loan['customer'];
    $principal_amount = $loan["principal_amount"];
    $interest_rate = $loan["interest_rate"];
    $year = $loan["year"];
    $total_amount_repay = $loan["total_amount_repay"];
    $total_monthly_repay = $loan["total_monthly_repay"];
    $total_interest_repay = $loan["total_interest_repay"];

    //just update this one
    $total_payment = $data['total_payment_update'] + $loan['total_payment'];

    return compact(
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
}

// Function to set session response
function response($message, $success, $icon, $title)
{
    $_SESSION['response'] = compact('message', 'success', 'icon', 'title');
}
