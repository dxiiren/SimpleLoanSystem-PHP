<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Users Page</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/styles.css">

    <!-- Include SweetAlert CSS and JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- datatable  -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/buttons/1.3.1/css/buttons.bootstrap4.min.css" />
</head>

<body>
    <?php
    //stored data in session
    session_start();

    //check authentication
    if (isset($_SESSION['auth'])) {

        $role = $_SESSION['auth']['role'];
    } else {
        header('Location: login.php');
    }

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

    //display anything stored inside session
    echo "<script> console.log(" . json_encode($_SESSION) . "); </script>";

    ?>

    <div class="nav">
        <div class="nav-links">
            <a href="/dashboard.php">Dashboard</a>

            <?php if ($role == 'admin') : ?>
                <a class="text-danger" href="/customer/customer.php">CUSTOMER</a>
                <a href="/loan/loan.php">Loan</a>
            <?php endif; ?>

            <?php if ($role == 'customer') : ?>
                <a href="/payment/payment.php">Payment</a>
            <?php endif; ?>

            <a href="/index.php">Log Out</a>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card">
                    <div class="card-body text-center">

                        <div class="container mt-5">
                            <h2 class="mb-3">Customers</h2>

                            <div class="mb-3">
                                <div class="d-inline-block">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                                        Add Customer
                                    </button>
                                </div>

                                <div class="d-inline-block">
                                    <form action="/misc/random_user.php" method="post">
                                        <button class="btn btn-info" type="submit">Add Random Customer</button>
                                    </form>
                                </div>

                                <?php if (count(array_filter($_SESSION['users'], fn ($user) => $user['role'] !== 'admin')) > 0) : ?>

                                    <div class="d-inline-block">
                                        <form action="/misc/unset_user.php" method="post">
                                            <button class="btn btn-danger" type="submit">Clear Customer</button>
                                        </form>
                                    </div>

                                <?php endif; ?>
                            </div>

                            <div class="table-responsive">
                                <table id="userTable" class="table table-sm table-striped mt-3">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Username</th>
                                            <th scope="col">Password</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_SESSION['users'])) {

                                            $users = $_SESSION['users'];
                                            $customer_found = false;

                                            foreach ($users as $user) {

                                                if ($user['role'] == 'customer') {
                                                    $customer_found = true;

                                                    echo "<tr>";
                                                    echo "<td>{$user['username']}</td>";
                                                    echo "<td>{$user['password']}</td>";
                                                    echo "<td>
                                                            <button class='btn btn-sm btn-info' onclick='editUser(" . json_encode($user)  . ")'>Edit</button>
                                                            <button class='btn btn-sm btn-danger' onclick='deleteUser(" . json_encode($user) . ")'>Delete</button>
                                                        </td>";
                                                    echo "</tr>";
                                                }
                                            }

                                            if (empty($users) || (!$customer_found && count($users) != 0)) {
                                                echo "<tr><td colspan='3'>No Users Found.</td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='3'>No Users Found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="add_customer.php" method="post" id="addCustomerForm">
                    <div class="modal-body">
                        <!-- Username input -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Username</span>
                                </div>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                        </div>

                        <!-- Password input -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Password</span>
                                </div>
                                <input type="text" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-add">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="edit_customer.php" method="post" id="editCustomerForm">
                    <div class="modal-body">

                        <input type="text" hidden class="form-control" id="editUser" name="editUser" required>

                        <!-- Username input -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Username</span>
                                </div>
                                <input type="text" class="form-control" id="editUsername" name="username" required>
                            </div>
                        </div>

                        <!-- Password input -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Password</span>
                                </div>
                                <input type="text" class="form-control" id="editPassword" name="password" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-edit">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- datatable  -->
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.3.1/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script type="text/javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>

<script>
    //datatable
    $(document).ready(function() {
        $('#userTable').DataTable({
            dom: 'Blfrtip',
            buttons: ['excel', 'pdf', 'copy'],
            pageLength: 5,
        });
    });
    //add
    $('.btn-add').click(function(e) {
        e.preventDefault();

        var formData = $('#addCustomerForm').serialize();

        $.ajax({
            type: 'POST',
            url: 'add_customer.php',
            data: formData,
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log error response for debugging
            }
        });
    });

    //edit
    function editUser(user) {
        $('#editUser').val(JSON.stringify(user));
        $('#editUsername').val(user['username']); // Fill in the username input
        $('#editPassword').val(user['password']); // Fill in the password input
        $('#editModal').modal('show'); // Show the modal
    }

    // AJAX request to update user
    $('.btn-edit').click(function(e) {
        e.preventDefault();

        var formData = $('#editCustomerForm').serialize();

        $.ajax({
            type: 'POST',
            url: 'edit_customer.php',
            data: formData,
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log error response for debugging
            }
        });
    });


    //delete
    function deleteUser(user) {
        if (confirm("Are you sure you want to delete this user?")) {
            $.ajax({
                url: 'delete_customer.php',
                type: 'POST',
                data: {
                    username: user['username'],
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    }
</script>

</html>