<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Loan Page</title>
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
                <a href="/customer/customer.php">Customer</a>
                <a class="text-danger" href="#">LOAN</a>
            <?php endif; ?>

            <?php if ($role == 'customer') : ?>
                <a href="/payment/payment.php">Payment</a>
            <?php endif; ?>

            <a href="/index.php">Log Out</a>
        </div>

    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">

                        <div class="container mt-5">
                            <h2 class="mb-3">Loans</h2>

                            <div class="mb-3">
                                <?php if (count(array_filter($_SESSION['users'], fn ($user) => $user['role'] !== 'admin')) > 0) : ?>

                                    <div class="d-inline-block">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                                            Add Loan
                                        </button>
                                    </div>

                                    <div class="d-inline-block">
                                        <form action="/misc/random_loan.php" method="post">
                                            <button class="btn btn-info" type="submit">Add Random Loan</button>
                                        </form>
                                    </div>

                                    <div class="d-inline-block">
                                        <form action="/misc/unset_loan.php" method="post">
                                            <button class="btn btn-danger" type="submit">Clear Loans</button>
                                        </form>
                                    </div>

                                <?php endif; ?>
                            </div>

                            <div class="table-responsive">
                                <table id="loanTable" class="table table-sm table-striped mt-3">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Admin</th>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Principal Amount</th>
                                            <th scope="col">Interest Rate</th>
                                            <th scope="col">Year</th>
                                            <th scope="col">Total Interest</th>
                                            <th scope="col">Total Amount Repay</th>
                                            <th scope="col">Monthly Payment</th>
                                            <th scope="col">Total Payment</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_SESSION['loans'])) {

                                            $loans = $_SESSION['loans'];

                                            if (empty($loans)) {
                                                echo "<tr><td colspan='10'>No loans Found.</td></tr>";
                                            }

                                            foreach ($loans as $loan) {

                                                echo "<tr>";
                                                echo "<td>{$loan['admin']}</td>";
                                                echo "<td>{$loan['customer']}</td>";
                                                echo "<td>RM{$loan['principal_amount']}</td>";
                                                echo "<td>{$loan['interest_rate']}%</td>";
                                                echo "<td>{$loan['year']}</td>";
                                                echo "<td>RM{$loan['total_interest_repay']}</td>";
                                                echo "<td>RM{$loan['total_amount_repay']}</td>";
                                                echo "<td>RM{$loan['total_monthly_repay']}</td>";
                                                echo "<td>RM{$loan['total_payment']}</td>";
                                                echo "<td>
                                                            <button class='btn btn-sm btn-info' onclick='editLoan(" . json_encode($loan)  . ")'>Edit</button>
                                                            <button class='btn btn-sm btn-danger' onclick='deleteLoan(" . json_encode($loan) . ")'>Delete</button>
                                                        </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='10'>No loans Found.</td></tr>";
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
                    <h5 class="modal-title" id="addModalLabel">Add Loan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="add_loan.php" method="post" id="addLoanForm">
                    <div class="modal-body">

                        <!-- Customer Name input -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Customer</span>
                                </div>
                                <select class="form-control" id="customer_name_add" name="customer_name_add" required>
                                    <?php foreach ($_SESSION['users'] as $user) : ?>
                                        <?php if ($user['role'] === 'customer') : ?>
                                            <option value="<?php echo $user['username']; ?>"><?php echo $user['username']; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Principal Amount input -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Principal Amount</span>
                                </div>
                                <input type="text" class="form-control" id="principal_amount_add" name="principal_amount_add" required>
                            </div>
                        </div>

                        <!-- Interest Rate input -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Interest Rate</span>
                                </div>
                                <input type="number" class="form-control" id="interest_rate_add" name="interest_rate_add" required>
                            </div>
                        </div>

                        <!-- Year input -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Year of loan</span>
                                </div>
                                <input type="number" class="form-control" id="year_add" name="year_add" required>
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
                    <h5 class="modal-title" id="editModalLabel">Update Loan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="edit_loan.php" method="post" id="editLoanForm">
                    <div class="modal-body">

                        <input type="text" hideen class="form-control" id="loan_id" name="loan_id" required>

                        <!-- Principal Amount input -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Principal Amount</span>
                                </div>
                                <input type="text" class="form-control" id="principal_amount_update" name="principal_amount_update" required>
                            </div>
                        </div>

                        <!-- Interest Rate input -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Interest Rate</span>
                                </div>
                                <input type="number" class="form-control" id="interest_rate_update" name="interest_rate_update" required>
                            </div>
                        </div>

                        <!-- Year input -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Year of loan</span>
                                </div>
                                <input type="number" class="form-control" id="year_update" name="year_update" required>
                            </div>
                        </div>

                        <!-- Amount Paid input -->
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Total Payment</span>
                                </div>
                                <input type="number" class="form-control" id="total_payment_update" name="total_payment_update" required>
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
        $('#loanTable').DataTable({
            dom: 'Blfrtip',
            buttons: ['excel', 'pdf', 'copy'],
            pageLength: 5,
        });
    });


    //add
    $('.btn-add').click(function(e) {
        e.preventDefault();

        var formData = $('#addLoanForm').serialize();

        $.ajax({
            type: 'POST',
            url: 'add_loan.php',
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
    function editLoan(loan) {

        $('#loan_id').val(loan['loan_id']);
        $('#principal_amount_update').val(loan['principal_amount']);
        $('#interest_rate_update').val(loan['interest_rate']);
        $('#year_update').val(loan['year']);
        $('#total_payment_update').val(loan['total_payment']);

        $('#editModal').modal('show'); // Show the modal
    }

    // AJAX request to update user
    $('.btn-edit').click(function(e) {
        e.preventDefault();

        var formData = $('#editLoanForm').serialize();

        $.ajax({
            type: 'POST',
            url: 'edit_loan.php',
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
    function deleteLoan(loan) {
        if (confirm("Are you sure you want to delete this loan?")) {
            $.ajax({
                url: 'delete_loan.php',
                type: 'POST',
                data: {
                    loan_id: loan['loan_id'],
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