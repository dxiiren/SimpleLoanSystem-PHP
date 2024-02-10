<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include SweetAlert CSS and JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <?php
                        //stored data in session
                        session_start();

                        //output response
                        if (isset($_SESSION['response'])) {
                            $response = $_SESSION['response'];
                            unset($_SESSION['response']);

                            echo "<script>
                                // Display success SweetAlert
                                Swal.fire({
                                    icon: '{$response['icon']}',
                                    title: '{$response['title']}',
                                    text: '{$response['message']}',
                                });
                            </script>";
                        }

                        echo "<script> console.log(" . json_encode($_SESSION) . "); </script>";

                        ?>
                        <h3 class="card-title text-center">Login</h3>
                        <!-- Form with POST method -->
                        <form action="dashboard.php" method="post">
                            <!-- Username input -->
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="form-group">
                                <label for="username">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
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