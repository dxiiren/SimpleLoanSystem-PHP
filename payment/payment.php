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

    // unset($_SESSION['loans']);

    //check authentication
    if (isset($_SESSION['auth'])) {
        $user = $_SESSION['auth'];
        $role = $user['role'];
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
                <a href="/loan/loan.php">Loan</a>
            <?php endif; ?>

            <?php if ($role == 'customer') : ?>
                <a class="text-danger" href="/payment/payment.php">PAYMENT</a>
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
                            <div class="table-responsive">
                                <table id="paymentTable" class="table table-sm table-striped mt-3">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Total Payment Done</th>
                                            <th scope="col">Total Payment Remaining</th>
                                            <th scope="col">Monthly Payment</th>
                                            <th scope="col">Principal Amount</th>
                                            <th scope="col">Interest Rate</th>
                                            <th scope="col">Year</th>
                                            <th scope="col">Total Interest</th>
                                            <th scope="col">Total Amount Repay</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_SESSION['loans'])) {

                                            $loans = $_SESSION['loans'];
                                            $loans_found = false;

                                            foreach ($loans as $loan) {

                                                if ($loan['customer'] == $user['username']) {
                                                    $loans_found = true;

                                                    echo "<tr>";
                                                    echo "<td>RM{$loan['total_payment']}</td>";
                                                    echo "<td>RM" . ($loan['principal_amount'] - $loan['total_payment']) . "</td>";
                                                    echo "<td>RM{$loan['total_monthly_repay']}</td>";
                                                    echo "<td>RM{$loan['principal_amount']}</td>";
                                                    echo "<td>{$loan['interest_rate']}%</td>";
                                                    echo "<td>{$loan['year']}</td>";
                                                    echo "<td>RM{$loan['total_interest_repay']}</td>";
                                                    echo "<td>RM{$loan['total_amount_repay']}</td>";
                                                    echo "<td>
                                                            <button class='btn btn-sm btn-info' onclick='editLoan(" . json_encode($loan)  . ")'>Make Payment</button>
                                                        </td>";
                                                    echo "</tr>";
                                                }
                                            }

                                            if (empty($loans) || (!$loans_found && count($loans) != 0)) {
                                                echo "<tr><td colspan='10'>No loans found for this user.</td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='10'>No loans found.</td></tr>";
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


    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Make Payment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="make_payment.php" method="post" id="editLoanForm">
                    <div class="modal-body">
                        <input type="text" hidden class="form-control" id="loan_id" name="loan_id" required>
                        <input type="number" hidden class="form-control" id="total_monthly_repay" name="total_monthly_repay" required>

                        <!-- Total Payment Option -->
                        <div class="form-group">
                            <label for="payment_option">Payment Option</label>
                            <select id="payment_option" class="custom-select">
                                <option selected value="free">Enter Free Number</option>
                                <option value="monthly">Choose Number of Months</option>
                            </select>
                        </div>

                        <!-- Input for Number of Months -->
                        <div class="form-group" id="months_input" style="display:none;">
                            <label for="num_months">Number of Months</label>
                            <input type="number" class="form-control" id="num_months" name="num_months">
                        </div>

                        <!-- Input for Total Amount -->
                        <div class="form-group">
                            <label for="total_payment">Total Payment</label>
                            <input type="number" class="form-control" id="total_payment_update" name="total_payment_update">
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
        $('#paymentTable').DataTable({
            dom: 'Blfrtip',
            buttons: ['excel', 'pdf', 'copy'],
            pageLength: 5,
        });
    });

    //edit
    function editLoan(loan) {
        $('#loan_id').val(loan['loan_id']);
        $('#total_monthly_repay').val(loan['total_monthly_repay']);

        $('#payment_option').val("free");
        $('#payment_option').change();
        $('#editModal').modal('show'); // Show the modal
    }

    // Function to update total payment based on number of months
    function updateTotalPayment() {

        var num_months = parseInt($('#num_months').val());

        if (!isNaN(num_months) && (num_months != 0 || num_months != "")) {
            var total_payment = num_months * $('#total_monthly_repay').val();
            $('#total_payment_update').val(total_payment.toFixed(2));
        }
    }

    // Event listener for payment option select change
    $('#payment_option').change(function() {
        var option = $(this).val();
        $('#total_payment_update, #num_months').val("");

        if (option === 'monthly') {
            $('#total_payment_update').prop('readonly', true);
            $('#months_input').show();
        } else {
            $('#total_payment_update').prop('readonly', false);
            $('#months_input').hide();
        }
    });

    // Event listener for number of months input change
    $('#num_months').change(function() {
        updateTotalPayment(); // Update total payment when number of months changes
    });

    // AJAX request to update user
    $('.btn-edit').click(function(e) {
        updateTotalPayment();
        e.preventDefault();

        var formData = $('#editLoanForm').serialize();

        $.ajax({
            type: 'POST',
            url: 'make_payment.php',
            data: formData,
            success: function(response) {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log error response for debugging
            }
        });
    });
</script>

</html>