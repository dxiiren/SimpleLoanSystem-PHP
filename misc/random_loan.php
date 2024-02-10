<?php
session_start();

// Function to calculate loan details
function calcLoan($principal_amount, $interest_rate, $year)
{
    $interest = $principal_amount * $year * ($interest_rate / 100);
    $amount_repay = $principal_amount + $interest;
    $monthly_repay = $amount_repay / ($year * 12);
    $monthly_repay = number_format($monthly_repay, 2, '.', ',');
    return [$amount_repay, $monthly_repay, $interest];
}

// Check if the user is authenticated as admin
if ($_SESSION['auth']['role'] == 'admin') {
    // Generate random loans for each user
    foreach ($_SESSION['users'] as $user) {
        // Generate random loan details

        if($user['role']=="admin"){
            continue;
        }
        
        $loan_id = uniqid();
        $admin = $_SESSION['auth']['username'];
        $customer = $user['username']; // Use the username of the current user as the customer name
        $principal_amount = rand(1000, 5000); // Random principal amount between 1000 and 5000
        $interest_rate = rand(5, 15); // Random interest rate between 5 and 15 percent
        $year = rand(1, 5); // Random loan term between 1 and 5 years
        $total_payment = 0;

        // Calculate loan details
        [$total_amount_repay, $total_monthly_repay, $total_interest_repay] = calcLoan($principal_amount, $interest_rate, $year);

        // Store loan details in an array
        $loan = compact(
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

        // Add the loan to the list of loans in the session
        if (isset($_SESSION['loans'])) {
            $_SESSION['loans'][] = $loan;
        } else {
            $_SESSION['loans'] = [$loan];
        }
    }
}

// Redirect back to the page where the loan generation was initiated
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
