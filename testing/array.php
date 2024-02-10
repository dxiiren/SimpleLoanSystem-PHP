<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Array Page</title>
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
                    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["username"])) {
                        $username = $_GET["username"];
                        echo "$username";
                    } else {
                        // Handle the case where username is not present
                        header("Location: index.php");
                        exit();
                    }
                ?>
            </div>
        </div>

        <div class="nav-links">
            <a href="www.linkedin.com/in/akmal-suhaimi">Linked In</a>
            <a href="https://github.com/dxiiren">Github</a>
            <a href="https://codepen.io/)dxiiren">Codepen</a>
            <a href="array.php" style="color: #ff0000;" >Array</a>
            <a href="javascript:goBack();" >Back</a>
            <a href="new.php?username=<?php echo urlencode($username); ?>">New</a>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card welcome-card">
                    <div class="card-body text-center">
                        <h1 class="mb-4">PHP Array Functions Example</h1>

                        <?php
                        // Example array
                        $originalArray = [3, 1, 4, 1, 5, 9, 2, 6, 5, 3, 5];

                        // Display the original array
                        echo "<h3>Original Array:</h3>";
                        printArray($originalArray);

                        // Perform various array functions
                        $uniqueValues = array_unique($originalArray);
                        $sortedArray = sort($originalArray); // Note: sort() modifies the original array in place

                        // Display the results
                        echo "<h3>Unique Values:</h3>";
                        printArray($uniqueValues);

                        echo "<h3>Sorted Array:</h3>";
                        printArray($originalArray); // Display the modified original array

                        echo "<h3>Array Sum:</h3>";
                        echo "<p>Sum: " . array_sum($originalArray) . "</p>";

                        echo "<h3>Array Average:</h3>";
                        echo "<p>Average: " . array_average($originalArray) . "</p>";

                        echo "<h3>Array Reverse:</h3>";
                        $reversedArray = array_reverse($originalArray);
                        printArray($reversedArray);

                        // Custom function to calculate array average
                        function array_average($array)
                        {
                            return count($array) > 0 ? array_sum($array) / count($array) : 0;
                        }

                        // Custom function to print an array
                        function printArray($array)
                        {
                            echo "<pre>";
                            print_r($array);
                            echo "</pre>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS and Popper.js -->
    <script>
    function goBack() {
        window.location.href = 'new.php';  // Replace 'new.php' with the actual URL of the previous page
    }
</script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>