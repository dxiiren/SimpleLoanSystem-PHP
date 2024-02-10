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

    $total_payment = $data['total_payment_update'];
    $principal_amount = $data["principal_amount_update"];
    $interest_rate = $data["interest_rate_update"];
    $year = $data["year_update"];

    [$total_amount_repay, $total_monthly_repay, $total_interest_repay] = calcLoan($principal_amount, $interest_rate, $year);

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
