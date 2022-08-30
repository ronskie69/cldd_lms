<?php

include('../api/functions.php');
require_once('../api/accounts.php'); 

$accounts = new AccountsDB();
$result = array();

$user_id = $_SESSION['session_user_id'];
$user_nickname = $_SESSION['session_nickname'];

$data = $accounts->getAccountData($user_id);

$profile_pic = empty($data['profile_pic']) ? "../images/lebron.jpg" : "../images/uploads/".$data['user_id']."-".$data['profile_pic'];

if(isset($_POST['update_profile_pic']))
{
    if(isNotValidFile($_FILES['new_profile_pic']['name'])){
        $result = array(
            "status" => 'error',
            "message" => 'File is not an image.',
            "data" => array()
        );
    }
    else
    {
        $upload = imageUploader($_FILES['new_profile_pic']['name'], $_FILES['new_profile_pic']['tmp_name'], $user_id);
        $result = $accounts->updateProfilePic($user_id,$upload['result']);
    }
}

if(isset($_POST['update_nickname']))
{
    $result = $accounts->updateNickname($user_id, $_POST['nickname']);
}

?>

<div class="container">
    <h4 class="text-center"><strong>My Profile</strong></h4>
    <hr>
    <!-- ALERT RESULT -->
    <?php if(!empty($result) && $result['status'] === "error") { ?>
    <div class="alert alert-danger alert-dismissible" role="alert" data-bs-dismiss="alert">
        <div class="d-flex justify-content-between">
            <h5>Failed!</h5>
            <i class="fa-solid fa-times" data-bs-dismiss="alert"></i>
        </div>
        <p><?php echo $result['message']; ?></p>
    </div>
   <?php } ?>
   <?php if(!empty($result) && $result['status'] === "success") { ?>
    <div class="alert alert-success alert-dismissible" role="alert" data-bs-dismiss="alert">
        <div class="d-flex justify-content-between">
            <h5><?php echo $result['message'] ?></h5>
            <i class="fa-solid fa-times" data-bs-dismiss="alert"></i>
        </div>
        <small>View or apply changes by <span id="refresh" class="text-primary">Refreshing</span> your browser.</small>
    </div>
   <?php } ?>
    <div class="row">
        <div class="col-12">
            <div class="card card-profile-admin shadow-sm p-2">
                <div class="card-header">
                    <i class="fa-solid fa-address-card me-2 text-info"></i>
                    Profile Picture
                </div>
                <div class="card-body d-flex justify-content-center align-items-center p-4">
                    <img src="<?php echo $profile_pic; ?>" alt="My Profile Pic" class="profile_image_img img-fluid" name="profile_pic">
                </div>
                <div class="card-footer">
                    <form action="/cldd/loan/index.php?page=profile" enctype="multipart/form-data" method="POST">
                        <input type="file" name="new_profile_pic" id="new_profile_pic" accept="image/*" class="form-control" required>
                        <div class="mt-2 d-flex justify-content-between">
                            <button type="reset" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-times me-1"></i>
                                Cancel
                            </button>
                            <button type="submit" name = "update_profile_pic" class="btn btn-success btn-sm">
                                <i class="fa-solid fa-check me-1"></i>
                                Update Profile Picture
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 mt-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-user text-info me-1"></i>
                    Update Nickname
                </div>
                <div class="card-body">
                    <form action="/cldd/loan/index.php?page=profile" method="POST" id="form2">
                        <h5>Edit Profile</h5>
                        <div class="form-group">
                            <label for="nickname">Nickname</label>
                            <input type="text" name="nickname" id="nickname" value="<?php echo $data['nickname'];?>" placeholder="Enter desired nickname here..." class="form-control" required/>
                        </div>
                        <div class="mt-2 d-flex justify-content-between">
                            <button type="reset" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-times me-1"></i>
                                Cancel
                            </button>
                            <button type="submit" name="update_nickname" class="btn btn-success btn-sm">
                                <i class="fa-solid fa-check me-1"></i>
                                Update Nickname
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#refresh').click(() => {
        window.location.reload()
        $('.modal').toggle('hide');
    });
</script>