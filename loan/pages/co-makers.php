<?php

include('../api/functions.php');
require_once('../api/comakers.php');

$comakers = new DBComakers();
$result = array();

?>

<div class="container">
    <?php if(!empty($result) && $result['status'] === "error") { ?>
    <div class="alert alert-danger alert-dismissible" role="alert" data-bs-dismiss="alert">
        <div class="d-flex justify-content-between">
            <h5>Failed!</h5>
            <a href="/cldd/loan/index.php?page=co-makers"><i class="fa-solid fa-times" data-bs-dismiss="alert"></i></a>
        </div>
        <p><?php echo $result['message']; ?></p>
    </div>
   <?php } ?>
   <?php if(!empty($result) && $result['status'] === "success") { ?>
    <div class="alert alert-success alert-dismissible" role="alert" data-bs-dismiss="alert">
        <div class="d-flex justify-content-between">
            <h5><?php echo $result['message'] ?></h5>
            <a href="/cldd/loan/index.php?page=co-makers"><i class="fa-solid fa-times" data-bs-dismiss="alert"></i></a>
        </div>
    </div>
   <?php } ?>
   <?php if(isset($_GET['delete'])) { ?>
    <div class="alert alert-warning alert-dismissible" role="alert" data-bs-dismiss="alert" id="alert-delete">
        <h5>Are you sure to delete <?php echo formatName($_GET['fname'], $_GET['lname']);?>?</h5>
        <a href="#" data-bs-dismiss="modal" data-bs-toggle="#alert-delete" class="btn btn-sm btn-dark me-1"><i class="fa-solid fa-times me-1"></i>Cancel Deletion</a>
        <a href ="/cldd/loan/index.php?applicants-type=active&delete-confirm=<?php echo $_GET['delete'];?>" class="btn btn-sm btn-danger me-1"><i class="fa-solid fa-trash me-1"></i>Yes, Delete</a>
    </div>
   <?php } ?>
    <h4 class="text-center"><strong>List of Co-Makers</strong></h4>
    <hr/>
    <div class="col-12">
            <table class="table shadow-sm" id="users-table">
                <thead>
                    <tr>
                        <th>Co-Maker ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Gender at Birth</th>
                        <th>Date of Birth</th>
                        <th>Address</th>
                        <th>Co-Maker Of</th>
                        <th>Co-Makers</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php

                    $data = $comakers->getAllComakers();

                    if(!empty($data)) {

                    foreach($data as $datax) : ?>
                        <tr>
                            <td> <?php echo $datax['comaker_user_id']; ?> </td>
                            <td> <?php echo $datax['comaker_fname']; ?> </td>
                            <td> <?php echo $datax['comaker_lname']; ?> </td>
                            <td> <?php echo $datax['comaker_gender']; ?> </td>
                            <td> <?php echo $datax['comaker_dob']; ?> </td>
                            <td> <?php echo $datax['comaker_address']; ?> </td>
                            <td> <?php echo $datax['comaker_by']; ?> </td>
                            <td> <?php echo ucwords($datax['comaker_status']); ?> </td>
                            <td> <?php echo ucwords($datax['comaker_status']); ?> </td>
                        </tr>
                    <?php endforeach;
                    } else { ?>
                    <tr>
                        <td>You have no active payers.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#users-table').DataTable();

    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>