<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Welcome Page</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="nav">
        <input type="checkbox" id="nav-check">
        <div class="nav-header">
            <div class="nav-title">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $username = $_POST["username"];
                    echo "$username";
                } elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["username"])) {
                    $username = $_GET["username"];
                    echo "$username";
                } else {
                    header("Location: index.php");
                    exit();
                }
                ?>
            </div>
        </div>

        <div class="nav-links">
            <a href="www.linkedin.com/in/akmal-suhaimi" target="_blank">Linked In</a>
            <a href="https://github.com/dxiiren" target="_blank">Github</a>
            <a href="https://codepen.io/dxiiren" target="_blank">Codepen</a>
            <a href="array.php?username=<?php echo urlencode($username); ?>">Array</a>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card welcome-card">
                    <div class="card-body text-center">
                        <!-- Display the welcome message with the username -->
                        <?php
                        echo "<h1 class='card-title'>Welcome, $username!</h1>";
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