<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard Page</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">

    <!-- Include SweetAlert CSS and JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <?php
    //stored data in session
    session_start();

    $data = json_encode($_SESSION);
    echo "<script> console.log(" . $data . "); </script>";
    ?>

    <div class="nav">
        <input type="checkbox" id="nav-check">
        <?php

        $validUser = false;

        //if not auth then check the login
        if (isset($_SESSION['auth']) && !empty($_SESSION['auth'])) {
            $user = $_SESSION['auth'];
            $validUser = true;
        } else {

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                //get user & password 
                $username = $_POST["username"];
                $password = $_POST["password"];

                // Filter the users array to find a match
                $user = array_values(array_filter($_SESSION['users'], function ($user) use ($username, $password) {
                    return $user['username'] === $username && $user['password'] === $password;
                }));

                $validUser = empty($user) ? false : true;
                $_SESSION['auth']  = $user = $validUser ? $user[0] : null;
            } else {
                header("Location: index.php");
            }
        }

        if (!$validUser) {
            $_SESSION['response']['message'] = "Wrong username or password !";
            $_SESSION['response']['success'] = false;
            $_SESSION['response']['icon'] = 'error';
            $_SESSION['response']['title'] = 'Error';
            header("Location: login.php");
        }

        echo "<script> console.log(" . json_encode($_SESSION) . "); </script>";

        ?>

        <div class="nav-links">
            <a class="text-danger" href="#">DASHBOARD</a>

            <?php if ($user['role'] == 'admin') : ?>
                <a href="/customer/customer.php">Customer</a>
                <a href="/loan/loan.php">Loan</a>
            <?php endif; ?>

            <?php if ($user['role'] == 'customer') : ?>
                <a href="/payment/payment.php">Payment</a>
            <?php endif; ?>

            <a href="/index.php">Log Out</a>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card welcome-card">
                    <div class="card-body text-center">
                        <!-- Display the welcome message with the username -->
                        <?php
                        echo "<h1 class='card-title'>Welcome, {$user['username']}!</h1>";
                        ?>
                        <p class="card-text">Thank you for logging in.</p>
                        <a href="index.php" class="btn btn-primary">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>