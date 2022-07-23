<?php 

require_once('../api/clients.php'); 
include_once('../api/functions.php');

$client = new DBClients();
$comaker_data = array();


// LOAD CLIENT DATA
$data = $client->getClient($_GET['client_uid']);

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
    // print_r($_POST);
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

?>

<div class="home">
    <div class="container">
        <h6 class="back_to_clients_text">
            <a href="/cldd/loan/index.php?page=active">
                <i class="fa-solid fa-chevron-left me-1"></i>Back
            </a>
        </h6>
        <div class="row">
            <div class="col-6">
                <div class="card shadow-sm w-100 bg-white">
                    <div class="card-header bg-white card-profile">
                        <img src="../images/uploads/<?php echo $_GET['client_uid']."-".$data['profile_pic'] ?>" alt="Profile Pic" class="img-fluid d-block mt-4 me-auto ms-auto">
                        <h5 class="text-center mt-4"><strong><?php echo $data['fname']." ".$data['lname'] ?></strong></h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">Date of Birth: <strong><?php echo $data['dob']?></strong></li>
                            <li class="list-group-item">Age: <strong><?php echo $my_age; ?></strong></li>
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
            <div class="col-6">
                <div class="row">
                    <div class="col-6">
                        <div class="card bg-white card-comakers">
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
                                <small>Age: <strong><?php echo $my_age; ?></strong></small><br>
                                <small>Sex: <strong><?php echo ucwords($comakers[0]['comaker_gender'])?></strong></small><br>
                                <small>Address in Calamba: <strong><?php echo $comakers[0]['comaker_address']?></strong></small><br>
                                <small>Contact Number: <strong><?php echo $comakers[0]['comaker_contact_no']?></strong></small><br>
                                <small>Account Status: <strong><?php echo ucwords($comakers[0]['comaker_status'])?></strong></small><br>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <button class="btn btn-primary btn-sm"><i class="fa-solid fa-pen me-1"></i>Edit</button>
                                <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash me-1"></i>Remove</button>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-white card-comakers">
                            <?php
                            if(!isset($comakers[1]) || !isset($comakers[1])){?>
                            <div class="card-body card-no-comaker">
                                <button data-bs-toggle="modal" data-bs-target="#modal_comaker">
                                    <i class="fa-solid fa-user-plus text-warning"></i>
                                    <h6>Add Co-Maker</h6>
                                </button>
                            </div>
                            <?php } else { ?>
                            <div class="card-header bg-white card-profile">
                                <img src="../images/uploads/<?php echo $_GET['client_uid']."-".$data['profile_pic'] ?>" alt="Profile Pic" class="img-fluid d-block me-auto ms-auto">
                                <h6 class="text-center mt-2"><strong><?php echo $data['fname']." ".$data['lname'] ?></strong></h6>
                                <small><p class="text-muted text-center mb-0">Co-Maker</p></small>
                            </div>
                            <div class="card-body">
                                <small>Date of Birth: <strong><?php echo $data['dob']?></strong></small><br>
                                <small>Age: <strong><?php echo $my_age; ?></strong></small><br>
                                <small>Sex: <strong><?php echo ucwords($data['gender'])?></strong></small><br>
                                <small>Address in Calamba: <strong><?php echo $data['address']?></strong></small><br>
                                <small>Contact Number: <strong><?php echo $data['contact_no']?></strong></small><br>
                                <small>Account Status: <strong><?php echo ucwords($data['account_type'])?></strong></small><br>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <button class="btn btn-primary btn-sm"><i class="fa-solid fa-pen me-1"></i>Edit</button>
                                <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash me-1"></i>Remove</button>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL ADD COMAKER -->
    <div class="modal fade" data-bs-backdrop="static" id="modal_comaker">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="fa-solid fa-user-plus me-2 text-success"></i>
                        Add New Co-Maker for <?php echo $data['fname']." ".$data['lname'] ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" action="/cldd/loan/index.php?page=view_client&client_uid=<?php echo $data['client_user_id'] ?>">
                        <input type="hidden" class="form-control" id="client_user_id" name="client_user_id" required value="<?php echo $data['client_user_id'] ?>">    
                        <div class="mb-3">
                            <label for="fname" class="col-form-label">First Name:</label>
                            <input type="text" class="form-control" id="fname" name="fname" required placeholder="Enter first name here...">
                        </div>
                        <div class="mb-3">
                            <label for="lname" class="col-form-label">Last Name:</label>
                            <input type="text" class="form-control" id="lname" name="lname" required placeholder="Enter last name here...">
                        </div>
                        <div class="mb-3">
                            <label for="dob" class="col-form-label">Gender at Birth:</label>
                            <select name="gender" id="gender" required class="form-control">
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dob" class="col-form-label">Date of Birth:</label>
                            <input type="date" class="form-control" id="dob" name="dob" required>
                        </div>
                        <div class="mb-3">
                            <label for="contact_no" class="col-form-label">Contact Number:</label>
                            <input type="text" placeholder="Enter contact number here..." required class="form-control" id="contact_no" name="contact_no">
                        </div>
                        <div class="mb-3">
                            <label for="account_type" class="col-form-label">Account type:</label>
                            <select class="form-select form-select-lg mb-3" aria-label="address" required name="account_type">
                                <option value="active">Active</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="col-form-label">Address:</label>
                            <select class="form-select form-select-lg mb-3" aria-label="address" required name="address">
                            <option value="Barangay 1">Barangay 1</option>
                                <option value="Barangay 2">Barangay 2</option>
                                <option value="Barangay 3">Barangay 3</option>
                                <option value="Barangay 4">Barangay 4</option>
                                <option value="Barangay 5">Barangay 5</option>
                                <option value="Barangay 6">Barangay 6</option>
                                <option value="Barangay 7">Barangay 7</option>
                                <option value="Bagong Kalsada">Bagong Kalsada</option>
                                <option value="Bañadero">Bañadero</option>
                                <option value="Banlic">Banlic</option>
                                <option value="Barandal">Barandal</option>
                                <option value="Batino">Batino</option>
                                <option value="Bubuyan">Bubuyan</option>
                                <option value="Bucal">Bucal</option>
                                <option value="Bunggo">Bunggo</option>
                                <option value="Burol">Burol</option>
                                <option value="Camaligan">Camaligan</option>
                                <option value="Canlubang">Canlubang</option>
                                <option value="Halang">Halang</option>
                                <option value="Hornalan">Hornalan</option>
                                <option value="Kay-Anlog">Kay-Anlog</option>
                                <option value="Laguerta">Laguerta</option>
                                <option value="La Mesa">>La Mesa</option>
                                <option value="Lawa">Lawa</option>
                                <option value="Lecheria"?>>Lecheria</option>
                                <option value="Lingga">Lingga</option>
                                <option value="Looc">Looc</option>
                                <option value="Mabato">Mabato</option>
                                <option value="Majada-Labas">Majada-Labas</option>
                                <option value="Makiling">Makiling</option>
                                <option value="Mapagong">Mapagong</option>
                                <option value="Masili">Masili</option>
                                <option value="Maunong">>Maunong</option>
                                <option value="Mayapa">Mayapa</option>
                                <option value="Milagrosa">Milagrosa</option>
                                <option value="Paciano Rizal">Paciano Rizal</option>
                                <option value="Parian">Parian</option>
                                <option value="Prinza">Prinza</option>
                                <option value="Puting Lupa">Puting Lupa</option>
                                <option value="Real">Real</option>
                                <option value="Saimsim">>Saimsim</option>
                                <option value="Sampiruhan">Sampiruhan</option>
                                <option value="San Cristobal">San Cristobal</option>
                                <option value="San Jose">San Jose</option>
                                <option value="San Juan">San Juan</option>
                                <option value="Sucol">Sucol</option>
                                <option value="Turbina">>Turbina</option>
                                <option value="Ulango">Ulango</option>
                                <option value="Uwisan">Uwisan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fname" class="col-form-label">Co-Maker's Profile Image:</label>
                            <input type="file" accept="image/*" class="form-control" id="image" name="image" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                                <i class="fa-solid fa-times me-1"></i>
                                Cancel
                            </button>
                            <button type="submit" name="addcomaker" class="btn btn-sm btn-success">
                                <i class="fa-solid fa-plus me-1"></i>
                                Add Co-Maker
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>