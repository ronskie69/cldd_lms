<?php 

require_once('../api/clients.php'); 
include_once('../api/functions.php');

$client = new DBClients();
$comaker_data = array();


// LOAD CLIENT DATA
$data = $client->getClient($_GET['client_uid']);

$profile = !empty($data['profile_pic']) ? "../images/uploads/".$_GET['client_uid']."-".$data['profile_pic'] : "../images/noprofile.png";

$today = date('Y/m/d');
$age = date_diff(date_create($today),date_create($data['dob']));
$my_age = $age->format('%d');


// $arr = explode('-', date('Y-m-d'));
// echo date("M", mktime(0,0,0, $arr[1],1))

$w_comaker = false;


// LOAD COMAKERS
$comakers = $client->getCoMakers($_GET['client_uid']);

if(isset($_POST['addcomaker']))
{
    $comaker_uid = randomUSERID(9999, 100000);

    if(isNotValidFile($_FILES['image']['name'])){
        $result = array(
            "status" => 'error',
            "message" => 'File is not an image.',
            "data" => array()
        );
        return;
    }

    $upload = imageUploader($_FILES['image']['name'], $_FILES['image']['tmp_name'], $comaker_uid);
    print_r($_POST);
    $comaker_data = array(
        "comaker_fname" => ucfirst($_POST['fname']),
        "comaker_lname" => ucfirst($_POST['lname']),
        "comaker_gender" => $_POST['gender'],
        "comaker_dob" => $_POST['dob'],
        "comaker_contact_no" => $_POST['contact_no'],
        "comaker_status" => $_POST['account_type'],
        "comaker_address" => $_POST['address'],
        "comaker_profile_pic" => $upload['result'],
        "client_user_id" => $data['client_user_id'],
        "comaker_uid" => $comaker_uid
    );
    $result = $client->addCoMaker($comaker_data);
}

//delete_comaker'
//comaker_id
if(isset($_POST['confirm_delete']))
{
    $result = $client->deleteCoMaker($_POST['delete_comaker_id']);
}
?>

<div class="home">
    <?php if(!empty($result) && $result['status'] === "error") { ?>
        <div class="alert alert-danger alert-dismissible" role="alert" data-bs-dismiss="alert">
            <div class="d-flex justify-content-between">
                <h6>Failed!</h6>
            </div>
        </div>
   <?php } ?>
   <?php if(!empty($result) && $result['status'] === "success") { ?>
        <div class="alert alert-success alert-dismissible" role="alert" data-bs-dismiss="alert">
            <div class="d-flex justify-content-between">
                <h6><?php echo $result['message'] ?></h6>
            </div>
        </div>
   <?php } ?>
    <?php if(isset($_GET['delete_comaker'])) { ?>
    <div class="alert alert-warning alert-dismissible" role="alert" id="alert-delete">
        <h5>Are you sure to delete <?php echo $_GET['fullname'];?> as a co-maker?</h5>
        <form class="form" method="POST" action="/cldd/loan/index.php?page=view_client&client_uid=<?php echo $_GET['client_uid'];?>">
            <input type="hidden" name ="delete_comaker_id" value ="<?php echo $_GET['delete_comaker'];?>">
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-success btn-sm" data-bs-dismiss="alert">
                    <i class="fa-solid fa-times me-2"></i>
                    Cancel
                </button>
                <button name="confirm_delete" type="submit" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Continue deletion
                </button>
            </div>
        </form>
    </div>
   <?php } ?>
    <div class="container">
        <h6 class="back_to_clients_text">
            <a href="/cldd/loan/index.php?page=active">
                <i class="fa-solid fa-chevron-left me-1"></i>Back
            </a>
        </h6>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="card shadow-sm w-100 bg-white mb-4">
                    <div class="card-header bg-white card-profile">
                        <img src="<?php echo $profile ?>" alt="Profile Pic" class="img-fluid d-block mt-4 me-auto ms-auto">
                        <h5 class="text-center mt-4"><strong><?php echo $data['fname']." ".$data['lname'] ?></strong></h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">Date of Birth: <strong><?php echo $data['dob']?></strong></li>
                            <li class="list-group-item">Gender at Birth: <strong><?php echo ucwords($data['gender'])?></strong></li>
                            <li class="list-group-item">Address in Calamba: <strong><?php echo $data['address']?></strong></li>
                            <li class="list-group-item">Contact Number: <strong><?php echo $data['contact_no']?></strong></li>
                            <li class="list-group-item">Account Status: <strong><?php echo ucwords($data['account_type'])?></strong></li>
                            <li class="list-group-item">
                                <a href="/cldd/loan/index.php?page=client&query=<?php echo $data['fname']." ".$data['lname'];?>">
                                    <i class="fa-solid fa-eye text-warning"></i>
                                    <small>View Payment History</small>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="card bg-white card-comakers mb-4">
                        <?php
                            if(empty($comakers[0]) || !isset($comakers[0])){?>
                            <div class="card-body card-no-comaker">
                                <button data-bs-toggle="modal" data-bs-target="#modal_comaker">
                                    <i class="fa-solid fa-user-plus text-warning"></i>
                                    <h6>Add Co-Maker</h6>
                                </button>
                            </div>
                            <?php } else { ?>
                            <div class="card-header bg-white card-profile">
                                <img src="../images/uploads/<?php echo $comakers[0]['comaker_user_id']."-".$comakers[0]['comaker_profile_pic'] ?>" alt="Profile Pic" class="img-fluid d-block me-auto ms-auto">
                                <h6 class="text-center mt-2"><strong><?php echo $comakers[0]['comaker_fname']." ".$comakers[0]['comaker_lname'] ?></strong></h6>
                                <small><p class="text-muted text-center mb-0">Co-Maker</p></small>
                            </div>
                            <div class="card-body">
                                <small>Date of Birth: <strong><?php echo $comakers[0]['comaker_dob']?></strong></small><br>
                                <small>Sex: <strong><?php echo ucwords($comakers[0]['comaker_gender'])?></strong></small><br>
                                <small>Address in Calamba: <strong><?php echo $comakers[0]['comaker_address']?></strong></small><br>
                                <small>Contact Number: <strong><?php echo $comakers[0]['comaker_contact_no']?></strong></small><br>
                                <small>Account Status: <strong><?php echo ucwords($comakers[0]['comaker_status'])?></strong></small><br>
                            </div>
                            <div class="card-footer">
                                <a href="/cldd/loan/index.php?page=view_client&client_uid=<?php echo $_GET['client_uid']?>&delete_comaker=<?php echo $comakers[0]['comaker_user_id']; ?>&fullname=<?php echo $comakers[0]['comaker_fname']." ".$comakers[0]['comaker_lname']; ?>">
                                    <button type="submit" name="delete_comaker" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash me-1"></i>Remove</button>
                                </a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card bg-white card-comakers mb-4">
                        <?php
                            if(empty($comakers[1]) || !isset($comakers[1])){?>
                            <div class="card-body card-no-comaker">
                                <button data-bs-toggle="modal" data-bs-target="#modal_comaker">
                                    <i class="fa-solid fa-user-plus text-warning"></i>
                                    <h6>Add Co-Maker</h6>
                                </button>
                            </div>
                            <?php } else { ?>
                            <div class="card-header bg-white card-profile">
                                <img src="../images/uploads/<?php echo $comakers[1]['comaker_user_id']."-".$comakers[1]['comaker_profile_pic'] ?>" alt="Profile Pic" class="img-fluid d-block me-auto ms-auto">
                                <h6 class="text-center mt-2"><strong><?php echo $comakers[1]['comaker_fname']." ".$comakers[1]['comaker_lname'] ?></strong></h6>
                                <small><p class="text-muted text-center mb-0">Co-Maker</p></small>
                            </div>
                            <div class="card-body">
                                <small>Date of Birth: <strong><?php echo $comakers[1]['comaker_dob']?></strong></small><br>
                                <small>Sex: <strong><?php echo ucwords($comakers[1]['comaker_gender'])?></strong></small><br>
                                <small>Address in Calamba: <strong><?php echo $comakers[1]['comaker_address']?></strong></small><br>
                                <small>Contact Number: <strong><?php echo $comakers[1]['comaker_contact_no']?></strong></small><br>
                                <small>Account Status: <strong><?php echo ucwords($comakers[1]['comaker_status'])?></strong></small><br>
                            </div>
                            <div class="card-footer">
                                <a href="/cldd/loan/index.php?page=view_client&client_uid=<?php echo $_GET['client_uid']?>&delete_comaker=<?php echo $comakers[1]['comaker_user_id']; ?>&fullname=<?php echo $comakers[1]['comaker_fname']." ".$comakers[1]['comaker_lname']; ?>">
                                    <button type="submit" name="delete_comaker" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash me-1"></i>Remove</button>
                                </a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>