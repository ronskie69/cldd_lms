<?php

include('../api/functions.php');
require_once('../api/clients.php');

$clients = new DBClients();
$result = array();

if(isset($_POST['submit']) && isset($_POST['submit']) == "Add Client")
{
    $client_user_id = randomUSERID(10, 1000);

    if(isNotValidFile($_FILES['image']['name'])){
        $result = array(
            "status" => 'error',
            "message" => 'File is not an image.',
            "data" => array()
        );
        return;
    }

    $upload = imageUploader($_FILES['image']['name'], $_FILES['image']['tmp_name'], $client_user_id);

    if($upload['isUploaded'] == true)
    {
        $client_data = array(
            "client_user_id" => $client_user_id,
            "fname" => trimCaps(strtolower($_POST['fname'])),
            "lname" => trimCaps(strtolower($_POST['lname'])),
            "dob" => $_POST['dob'],
            "address" => $_POST['address'],
            "account_type" => "active",
            "contact_no" => $_POST['contact_no'],
            "gender" => $_POST['gender'],
            "profile_pic" => $upload['result'],
        );
        $result = $clients->insertNewClient($client_data);
    }
    else
    {
        unlink('../images/uploads/'.$upload['owner_id']."-".$upload['result']);
        $result = array(
            "status" => 'error',
            "message" => 'Failed to upload image.',
            "data" => array()
        );
    }
    unset($_POST);
    // print_r($client_data);
}

if(isset($_POST['save']))
{

    if(isNotValidFile($_FILES['new_profile']['name'])){
        $result = array(
            "status" => 'error',
            "message" => 'File is not an image.',
            "data" => array()
        );
        return;
    }

    $upload = imageUploader($_FILES['new_profile']['name'], $_FILES['new_profile']['tmp_name'], $_POST['client_user_id']);

    $client_data = array(
        "client_user_id" => $_POST['client_user_id'],
        "fname" => trimCaps(strtolower($_POST['fname'])),
        "lname" => trimCaps(strtolower($_POST['lname'])),
        "dob" => $_POST['dob'],
        "address" => $_POST['address'],
        "account_type" => $_POST['account_type'],
        "contact_no" => $_POST['contact_no'],
        "new_profile" => $upload['result'] 
    );
    $result = $clients->replaceClientInfo($client_data);
    unset($_POST);
}

if(isset($_POST['confirm-delete']))
{
    // echo $_POST['client_uid'];
    $result = $clients->deleteClient($_POST['client_uid']);
    unset($_POST);
}

?>

<div class="container">

    <!-- ALERT RESULT -->
    <?php if(!empty($result) && $result['status'] === "error") { ?>
    <div class="alert alert-danger alert-dismissible" role="alert" data-bs-dismiss="alert">
        <div class="d-flex justify-content-between">
            <h6><?php echo $result['message'] ?></h6>
            <a href="/cldd/loan/index.php?page=active"><i class="fa-solid fa-times" data-bs-dismiss="alert"></i></a>
        </div>
    </div>
   <?php } ?>
   <?php if(!empty($result) && $result['status'] === "success") { ?>
    <div class="alert alert-success alert-dismissible" role="alert" data-bs-dismiss="alert">
        <div class="d-flex justify-content-between">
            <h6><?php echo $result['message'] ?></h6>
            <a href="/cldd/loan/index.php?page=active"><i class="fa-solid fa-times" data-bs-dismiss="alert"></i></a>
        </div>
    </div>
   <?php } ?>

   <!-- MODAL FOR EXCEL FILE IMPORT -->
   <?php include('./components/modal_excel.php') ?>


   <!-- ALERT FOR DELETE -->
   <?php if(isset($_GET['delete'])) { ?>
    <div class="alert alert-warning" role="alert">
        <h5>Are you sure to delete <?php echo formatName($_GET['fname'], $_GET['lname']); ?> forever?</h5>
        <p>There is no going back.</p>

        <form class="form" method="POST" action="/cldd/loan/index.php?page=active">
            <input type="hidden" name ="client_uid" value ="<?php echo $_GET['delete'];?>">
            <div class="d-flex justify-content-between">
                <a href="/cldd/loan/index.php?page=active" type="button" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-times me-2"></i>
                    Cancel
                </a>
                <button name="confirm-delete" type="submit" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                    <i class="fa-solid fa-exclamation-triangle me-2"></i>
                    Continue deletion
                </button>
            </div>
        </form>
    </div>
   <?php } ?>


    <?php include_once('./components/newclient_modal.php') ?>
    <h4 class="text-center"><strong>Active Accounts</strong></h4>
    <hr/>
    <?php if(!(isset($_GET['edit']))) { ?>
    <div class="row">
        <div class="col-12">
            <a href="" class="btn btn-sm btn-dark mb-4" data-bs-toggle="modal" data-bs-target="#modal_addnew">
                <i class="fa-solid fa-add"></i>
                New Client
            </a>
            <input type="file" name="excel_file" id="excel_file" onchange="fileSelected(this)" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="d-none">
            <button type="button" name="import" class="btn btn-sm btn-dark mb-4" data-bs-toggle="modal" data-bs-target="#modal_excel" id="button_excel_import">
                <i class="fa-solid fa-question-circle me-1"></i>
                Import Excel
            </button>
            <button type="button" name="excelf" class="btn btn-sm btn-dark mb-4" onclick="openAttachment()" id="button_excel">
                <i class="fa-solid fa-file me-1"></i>
                Import Excel File
            </button>
        </div>
    </div>
    <?php } ?>
    <div class="row">
        <?php

        $classname;
        if(isset($_GET['edit'])) 
        {
            $client_data = $clients->getClient($_GET['edit']);
            $classname = isset($_GET['edit']) ? "col-lg-8 col-md-8 col-sm-12 mb-4" : "col-lg-12 col-md-8 col-sm-12 mb-4";

        ?>
        <div class="col-lg-4 col-md-4 col-sm-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <strong>
                        <i class="fa-solid fa-pen me-1 text-success"></i>
                        Edit data of <?php echo formatName($client_data['fname'], $client_data['lname']);?>
                    </strong>
                </div>
                <div class="card-body p-2">
                    <form method="POST" action="/cldd/loan/index.php?page=active" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" id="client_user_id" name="client_user_id" required value="<?php echo $client_data['client_user_id'] ?>">    
                        <div class="mb-3">
                            <label for="fname" class="col-form-label">First Name:</label>
                            <input type="text" value="<?php echo $client_data['fname'] ?>" class="form-control" id="fname" name="fname" required placeholder="Enter first name here...">
                        </div>
                        <div class="mb-3">
                            <label for="lname" class="col-form-label">Last Name:</label>
                            <input type="text" value="<?php echo $client_data['lname'] ?>" class="form-control" id="lname" name="lname" required placeholder="Enter last name here...">
                        </div>
                        <div class="mb-3">
                            <label for="dob" class="col-form-label">Date of Birth:</label>
                            <input type="date" value="<?php echo $client_data['dob'] ?>" class="form-control" id="dob" name="dob" required>
                        </div>
                        <div class="mb-3">
                            <label for="dob" class="col-form-label">Contact Number:</label>
                            <input type="text" value="<?php echo $client_data['contact_no'] ?>" placeholder="Enter contact number here..." required class="form-control" id="contact_no" name="contact_no">
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
                            <option value="Barangay 1" <?php echo $client_data['address'] === 'Barangay 1' ? 'selected' : '' ?>>Barangay 1</option>
                                <option value="Barangay 2" <?php echo $client_data['address'] === 'Barangay 2' ? 'selected' : '' ?>>Barangay 2</option>
                                <option value="Barangay 3" <?php echo $client_data['address'] === 'Barangay 3' ? 'selected' : '' ?>>Barangay 3</option>
                                <option value="Barangay 4" <?php echo $client_data['address'] === 'Barangay 4' ? 'selected' : '' ?>>Barangay 4</option>
                                <option value="Barangay 5" <?php echo $client_data['address'] === 'Barangay 5' ? 'selected' : '' ?>>Barangay 5</option>
                                <option value="Barangay 6" <?php echo $client_data['address'] === 'Barangay 6' ? 'selected' : '' ?>>Barangay 6</option>
                                <option value="Barangay 7" <?php echo $client_data['address'] === 'Barangay 7' ? 'selected' : '' ?>>Barangay 7</option>
                                <option value="Bagong Kalsada" <?php echo $client_data['address'] === 'Bagong Kalsada' ? 'selected' : '' ?>>Bagong Kalsada</option>
                                <option value="Bañadero" <?php echo $client_data['address'] === 'Bañadero' ? 'selected' : '' ?>>Bañadero</option>
                                <option value="Banlic" <?php echo $client_data['address'] === 'Banlic' ? 'selected' : '' ?>>Banlic</option>
                                <option value="Barandal" <?php echo $client_data['address'] === 'Barandal' ? 'selected' : '' ?>>Barandal</option>
                                <option value="Batino" <?php echo $client_data['address'] === 'Batino' ? 'selected' : '' ?>>Batino</option>
                                <option value="Bubuyan" <?php echo $client_data['address'] === 'Bubuyan' ? 'selected' : '' ?>>Bubuyan</option>
                                <option value="Bucal" <?php echo $client_data['address'] === 'Bucal' ? 'selected' : '' ?>>Bucal</option>
                                <option value="Bunggo" <?php echo $client_data['address'] === 'Bunggo' ? 'selected' : '' ?>>Bunggo</option>
                                <option value="Burol" <?php echo $client_data['address'] === 'Burol' ? 'selected' : '' ?>>Burol</option>
                                <option value="Camaligan" <?php echo $client_data['address'] === 'Camaligan' ? 'selected' : '' ?>>Camaligan</option>
                                <option value="Canlubang" <?php echo $client_data['address'] === 'Canlubang' ? 'selected' : '' ?>>Canlubang</option>
                                <option value="Halang" <?php echo $client_data['address'] === 'Halang' ? 'selected' : '' ?>>Halang</option>
                                <option value="Hornalan" <?php echo $client_data['address'] === 'Hornalan' ? 'selected' : '' ?>>Hornalan</option>
                                <option value="Kay-Anlog" <?php echo $client_data['address'] === 'Kay-Anlog' ? 'selected' : '' ?>>Kay-Anlog</option>
                                <option value="Laguerta" <?php echo $client_data['address'] === 'Laguerta' ? 'selected' : '' ?>>Laguerta</option>
                                <option value="La Mesa" <?php echo $client_data['address'] === 'La Mesa' ? 'selected' : '' ?>>La Mesa</option>
                                <option value="Lawa" <?php echo $client_data['address'] === 'Lawa' ? 'selected' : '' ?>>Lawa</option>
                                <option value="Lecheria" <?php echo $client_data['address'] === 'Lecheria' ? 'selected' : '' ?>>Lecheria</option>
                                <option value="Lingga" <?php echo $client_data['address'] === 'Lingga' ? 'selected' : '' ?>>Lingga</option>
                                <option value="Looc" <?php echo $client_data['address'] === 'Looc' ? 'selected' : '' ?>>Looc</option>
                                <option value="Mabato" <?php echo $client_data['address'] === 'Mabato' ? 'selected' : '' ?>>Mabato</option>
                                <option value="Majada-Labas" <?php echo $client_data['address'] === 'Majada-Labas' ? 'selected' : '' ?>>Majada-Labas</option>
                                <option value="Makiling" <?php echo $client_data['address'] === 'Makiling' ? 'selected' : '' ?>>Makiling</option>
                                <option value="Mapagong" <?php echo $client_data['address'] === 'Mapagong' ? 'selected' : '' ?>>Mapagong</option>
                                <option value="Masili" <?php echo $client_data['address'] === 'Masili' ? 'selected' : '' ?>>Masili</option>
                                <option value="Maunong" <?php echo $client_data['address'] === 'Maunong' ? 'selected' : '' ?>>Maunong</option>
                                <option value="Mayapa" <?php echo $client_data['address'] === 'Mayapa' ? 'selected' : '' ?>>Mayapa</option>
                                <option value="Milagrosa" <?php echo $client_data['address'] === 'Barangay' ? 'selected' : '' ?>>Milagrosa</option>
                                <option value="Paciano Rizal" <?php echo $client_data['address'] === 'Paciano Rizal' ? 'selected' : '' ?>>Paciano Rizal</option>
                                <option value="Parian" <?php echo $client_data['address'] === 'Parian' ? 'selected' : '' ?>>Parian</option>
                                <option value="Prinza" <?php echo $client_data['address'] === 'Prinza' ? 'selected' : '' ?>>Prinza</option>
                                <option value="Puting Lupa" <?php echo $client_data['address'] === 'Puting Lupa' ? 'selected' : '' ?>>Puting Lupa</option>
                                <option value="Real" <?php echo $client_data['address'] === 'Real' ? 'selected' : '' ?>>Real</option>
                                <option value="Saimsim" <?php echo $client_data['address'] === 'Saimsim' ? 'selected' : '' ?>>Saimsim</option>
                                <option value="Sampiruhan" <?php echo $client_data['address'] === 'Sampiruhan' ? 'selected' : '' ?>>Sampiruhan</option>
                                <option value="San Cristobal" <?php echo $client_data['address'] === 'San Cristobal' ? 'selected' : '' ?>>San Cristobal</option>
                                <option value="San Jose" <?php echo $client_data['address'] === 'San Jose' ? 'selected' : '' ?>>San Jose</option>
                                <option value="San Juan" <?php echo $client_data['address'] === 'San Juan' ? 'selected' : '' ?>>San Juan</option>
                                <option value="Sucol" <?php echo $client_data['address'] === 'Sucol' ? 'selected' : '' ?>>Sucol</option>
                                <option value="Turbina" <?php echo $client_data['address'] === 'Turbina' ? 'selected' : '' ?>>Turbina</option>
                                <option value="Ulango" <?php echo $client_data['address'] === 'Ulango' ? 'selected' : '' ?>>Ulango</option>
                                <option value="Uwisan" <?php echo $client_data['address'] === 'Uwisan' ? 'selected' : '' ?>>Uwisan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fname" class="col-form-label">Client's Profile Image</label>
                            <input type="file" accept="image/*" class="form-control" id="new_profile" name="new_profile">
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="/cldd/loan/index.php?page=active" class="btn btn-sm btn-danger">
                                <i class="fa-solid fa-times me-1"></i>
                                Cancel
                            </a>
                        <button type="submit" name="save" class="btn btn-sm btn-success">
                                <i class="fa-solid fa-check me-1"></i>
                                Save Changes
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="<?php echo $classname;?>">
            <div class="card">
                <div class="card-header">
                    <strong>
                        <i class="fa-solid fa-table me-1 text-success"></i>
                        Active Clients Table
                    </strong>
                </div>
                <div class="card-body card-clients">
                    <table class="table shadow-sm" id="users-table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Date of Birth</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Comaker(s)</th>
                                <th>
                                    Options
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $type = isset($_GET['page']) ? $_GET['page'] : "";

                            $data = $clients->getClientInfos($type);

                            if(!empty($data)) {

                            foreach($data as $datax) : ?>
                                <tr>
                                    <td> <?php echo $datax['client_user_id']; ?> </td>
                                    <td> <?php echo $datax['fname']; ?> </td>
                                    <td> <?php echo $datax['lname']; ?> </td>
                                    <td> <?php echo $datax['dob']; ?> </td>
                                    <td> <?php echo $datax['address']; ?> </td>
                                    <td> <?php echo ucwords($datax['account_type']); ?> </td>
                                    <td> <?php echo strlen($datax['comakers']) > 4 ? ucwords($datax['comakers']).", etc." : $datax['comakers']; ?></td>
                                    <td>
                                        <a title ="View Client" href="/cldd/loan/index.php?page=view_client&client_uid=<?php echo $datax['client_user_id'] ?>" class="btn btn-secondary btn-sm">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a title ="Edit" href="/cldd/loan/index.php?page=active&edit=<?php echo $datax['client_user_id'] ?>" class="btn btn-primary btn-sm">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a title ="Delete" href="/cldd/loan/index.php?page=active&delete=<?php echo $datax['client_user_id'] ?>&fname=<?php echo $datax['fname'] ?>&lname=<?php echo $datax['lname'] ?>" class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
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
    </div>
</div>
<script type="text/javascript">

    function openAttachment(){
        document.getElementById('excel_file').click()
    }

    function fileSelected(input){
        document.getElementById('button_excel').value=input.files[0].name
        
        let formData = new FormData();
        formData.append("file", input.files[0])
        $.ajax({
            method: 'POST',
            url: '../ajax/uploadExcel.php',
            contentType: false,
            dataType: 'json',
            processData: false,
            data: formData,
            success: (data) => console.log(data),
            error: (err) => console.log(err)
        });
    }

    $('#users-table').DataTable();

    $('#button_excel').hide();

    $('#button_excel_import').click(function(){
        $(this).hide();
        $('#button_excel').show();
    })
</script>