<?php

require_once('../api/clients.php');
include('../api/functions.php');

$client = new DBClients();
$payer_details = array();
$payment_update = array();
$result = array();

if(isset($_POST['submit_payment']))
{
    $client_name = splitString($_POST['client_name']);

    $name_size = sizeof($client_name);
    if($name_size <= 1) {
        $result = array(
            "status" => "error",
            "message" => "Full name is required.",
            "data" => array()
        );
    }
    else
    {
        $fname = $name_size >= 3 ? $client_name[0]." ".$client_name[1] : $client_name[0];
        $lname = $name_size >= 3 ? $client_name[2] : $client_name[1];

        $payment_amount = $_POST['payment_amount'];
        $payment_date = $_POST['payment_date'];
        $mode_of_payment = $_POST['mode_of_payment'];
        $payment_type = $_POST['payment_type'];

        $payer_details = array(
            "fname" => $fname,
            "lname" => $lname,
            "payment_amount" => $payment_amount,
            "payment_date" => $payment_date,
            "payment_type" => $payment_type,
            "mode_of_payment" => $mode_of_payment
        );
        //print_r($payer_details);

        $result = $client->makePayment($payment_type, $payer_details);
    }
}

if(isset($_POST['save_payment']))
{
    $payment_update = array(
        "payment_id" => $_POST['payment_id'],
        "payment_user_id" => $_POST['payment_user_id'],
        "payment_amount" => $_POST['payment_amount'],
        "payment_date" => $_POST['payment_date'],
        "payment_type" => $_POST['payment_type'],
        "mode_of_payment" => $_POST['mode_of_payment']
    );
    $result = $client->updatePaymentDetails($payment_update);
}

if(isset($_POST['confirm_delete_payment'])){
    $result = $client->deletePayment($_POST['client_uid'],$_POST['payment_date']);
}

?>

<div class="container payments">
    <h4 class="text-center"><strong>Payments Section</strong></h4>
    <hr/>
    <div class="row">

    <!-- ALERT FOR DELETE -->
    <?php if(isset($_GET['delete_payment'])) { ?>
        <div class="alert alert-warning" role="alert">
            <h5>Are you sure to delete payment for <?php echo $_GET['payment_date']?> of <?php echo $_GET['client_name']?>?</h5>
            <p>There is no going back.</p>
            <form class="form" method="POST" action="/cldd/loan/index.php?page=payments">
                <input type="hidden" name ="client_uid" value ="<?php echo $_GET['delete_payment'];?>">
                <input type="hidden" name ="payment_date" value ="<?php echo $_GET['payment_date'];?>">
                <input type="hidden" name ="client_name" value ="<?php echo $_GET['client_name'];?>">
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-success btn-sm" data-bs-dismiss="alert">
                        <i class="fa-solid fa-times me-2"></i>
                        Cancel
                    </button>
                    <button name="confirm_delete_payment" type="submit" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        Continue deletion
                    </button>
                </div>
            </form>
        </div>
        <?php } ?>

        <!-- ALERT ERROR -->
        <?php if(!empty($result) && $result['status'] === "error") { ?>
        <div class="alert alert-danger alert-dismissible" role="alert" data-bs-dismiss="alert">
            <div class="d-flex justify-content-between">
                <h5>Failed!</h5>
                <i class="fa-solid fa-times" data-bs-dismiss="alert"></i>
            </div>
            <p><?php echo $result['message']; ?></p>
        </div>
        <!-- ALERT SUCCESS -->
        <?php } ?>
        <?php if(!empty($result) && $result['status'] === "success") { ?>
            <div class="alert alert-success alert-dismissible" role="alert" data-bs-dismiss="alert">
                <div class="d-flex justify-content-between">
                    <h5><?php echo $result['message'] ?></h5>
                    <i class="fa-solid fa-times" data-bs-dismiss="alert"></i>
                </div>    
            </div>
        <?php } ?>
        <div class="col-12">
            <?php include_once('./components/modal_payment.php') ?>
            <div class="row action-payments">
                <div class="col-sm-12 col-lg-8 col-md-8 mb-3">
                    <form class="querier w-100" method="GET" id="querier_client" autocomplete="on">
                        <input type="search" name="search_input_client" id="search_input" class="search_input" placeholder="Search client here..." >
                        <button type="submit" class="querier_btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="col-sm-12 col-lg-4 col-md-4">
                    <button class="btn btn-dark btn-sm p-2" style="font-size: 13px;" data-bs-toggle="modal" data-bs-target="#make_payment">
                        <i class="fa-solid fa-plus me-1"></i>
                        New
                    </button>
                </div>
            </div>
        </div>
        <?php
        if(isset($_GET['edit_payment'])){
            $payment_info = $client->getPaymentInfoByDate($_GET['edit_payment'], $_GET['payment_date']);
            // print_r($payment_info);
            
            ?>
            <div class="col-4 mt-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="text-success"><strong>
                            <i class="fa-solid fa-pen me-2"></i>Edit Payment
                        </strong></h6>
                    </div>
                    <div class="card-body">
                        <form action="/cldd/loan/index.php?page=payments" class="form" id="form_payment" method="POST">
                            <input type="hidden" class="form-control" required placeholder="Enter amount of payment here..." value="<?php echo $payment_info['payment_id'] ?>" name="payment_id" id="payment_id">
                            <input type="hidden" class="form-control" required placeholder="Enter amount of payment here..." value="<?php echo $payment_info['payment_user_id'] ?>" name="payment_user_id" id="payment_user_id">
                            <label for="fname">Amount of Payment:</label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" required placeholder="Enter amount of payment here..." value="<?php echo $payment_info['payment_amount'] ?>" name="payment_amount" id="username">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fa-solid fa-money-bill"></i>
                                </span>
                            </div>
                            <label for="fname">Payment Type:</label>
                            <div class="input-group mb-3">
                                <select name="payment_type" id="payment_type" class="form-control">
                                        <option value="Current" selected>Current</option>
                                        <option value="Due">Due</option>
                                </select>
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fa-solid fa-hands"></i>
                                </span>
                            </div>
                            <label for="fname">Mode of Payment:</label>
                            <div class="input-group mb-3">
                                <select name="mode_of_payment" id="mode_of_payment" class="form-control">
                                        <option value="Bank Transfer" <?php echo $payment_info['mode_of_payment'] === 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                                        <option value="Actual Payment" <?php echo $payment_info['mode_of_payment'] === 'Actual Payment' ? 'selected' : '' ?>>Actual Payment</option>
                                </select>
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fa-solid fa-desktop"></i>
                                </span>
                            </div>
                            <label for="fname">Date of Payment:</label>
                            <div class="input-group mb-3">
                                <input type="date" name="payment_date" value="<?php echo $payment_info['payment_date'] ?>" id="payment_date" class="form-control"/>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a role="button" class="btn btn-sm btn-danger" href="/cldd/loan/index.php?page=payments">
                                    <i class="fa-solid fa-times me-2"></i>
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-sm btn-success" name="save_payment">
                                    <i class="fa-solid fa-check me-2"></i>
                                    Submit Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="<?php echo isset($_GET['edit_payment']) ? "col-8 mt-4" : "col-12 mt-4";?>">
            <div class="card bg-white shadow-sm card-table mb-4">
                <div class="card-header text-center">
                    <strong>
                        <i class="fa-solid fa-money-bill me-1 text-success"></i>
                        Payments Table
                    </strong>
                </div>
                <div class="card-body">
                    <div class="table-holder">
                        <table class="table shadow-sm table-striped table-hover text-center" id="users-table">
                            <thead>
                                <tr>
                                    <th>Client ID</th>
                                    <th>Client Name</th>
                                    <th>Payment Amount</th>
                                    <th>Payment Date</th>
                                    <th>Mode of Payment</th>
                                    <th colspan="2">
                                        Options
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="payment_dable"></tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <strong>Guide:</strong><br/>
                    <small>
                        <strong class="text-success">
                            <i class="fa-solid fa-pen"></i> - Edit payment
                        </strong>
                    </small><br/>
                    <small>
                        <strong class="text-danger">
                            <i class="fa-solid fa-trash"></i> - Delete payment
                        </strong>
                    </small>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#search_input').autocomplete({
        source: '../ajax/suggestions.php'
    })
    $('#client_name').autocomplete({
        source: '../ajax/suggestions.php',
        appendTo: '#form_payment'
    })

    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

    $('#querier_client').submit(function(e){
        e.preventDefault()
        loadPayments($('#search_input').val())
    })

    function loadPayments(client_name = ""){
        $.ajax({
            url: '../ajax/loadPayments.php',
            data: {
                client_lname: client_name
            },
            success:(data) => {
                if(!data){
                    $('#payment_dable').html(
                        `
                        <tr><td colspan="6">No data found for ${client_name}</td></tr>
                        `
                    )
                    return;
                }
                data= JSON.parse(data)
                data.reverse()
                $('#payment_dable').html(
                    data.map(datax => {
                        return `
                    <tr>
                        <td>${datax.client_uid}</td>
                        <td>${datax.client_name}</td>
                        <td>${parseInt(datax.payment_amount).toLocaleString()}</td>
                        <td>${datax.payment_date ? datax.payment_date : ""}</td>
                        <td>${datax.mode_of_payment}</td>
                        <td colspan="2">
                            <a href="/cldd/loan/index.php?page=payments&edit_payment=${datax.client_uid}&payment_date=${datax.payment_date}" class="btn btn-sm text-success">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="/cldd/loan/index.php?page=payments&delete_payment=${datax.client_uid}&client_name=${datax.client_name}&payment_date=${datax.payment_date}" class="btn btn-sm text-danger">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    `
                    })
                )
            },
            error:(err) => console.log(err)
        })
    }

    loadPayments()
</script>
