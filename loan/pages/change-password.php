<?php

include('../api/functions.php');
require_once('../api/clients.php');

$users = new AccountsDB();
$result = array();

if(isset($_POST['submit-password']))
{
    $user_id = $_POST['user_id'];
    $new_password = $_POST['alt_password'];
    $result = $users->updatePassword($user_id, $new_password);
}

?>
<div class="container">
    <h4 class="text-center"><strong>Change Password</strong></h4>
    <hr/>

    <!-- ALERT RESULT -->
    <?php if(!empty($result) && $result['status'] === "error") { ?>
    <div class="alert alert-danger alert-dismissible" role="alert" data-bs-dismiss="alert">
        <div class="d-flex justify-content-between">
            <h5>Failed!</h5>
            <a href="/cldd/loan/index.php?page=change-password"><i class="fa-solid fa-times" data-bs-dismiss="alert"></i></a>
        </div>
        <p><?php echo $result['message']; ?></p>
    </div>
   <?php } ?>

   <?php if(!empty($result) && $result['status'] === "success") { ?>
    <div class="alert alert-success alert-dismissible" role="alert" data-bs-dismiss="alert">
        <div class="d-flex justify-content-between">
            <h5><?php echo $result['message'] ?></h5>
            <a href="/cldd/loan/index.php?page=change-password"><i class="fa-solid fa-times" data-bs-dismiss="alert"></i></a>
        </div>
    </div>
   <?php } ?>

    <div class="">
        <div class="card form-holder p-4">
            <form action="/cldd/loan/index.php?page=change-password" class="form" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['session_user_id'] ?>" class="form control">
                <input type="hidden" name="alt_password" value="" id="alt_password" class="form control">
                <div class="container p-4">
                    <div class="d-flex">
                        <div class="form-item-header w-100">
                            <h6 class="text-center">Password Verification</h6>
                            <div class="progress-holder progress1">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-animated" role="progressbar" id="bar1"></div>
                                </div>
                                <span class="progress-title">1</span>
                            </div>
                        </div>
                        <div class="form-item-header w-100">
                            <h6 class="text-center">Create New Password</h6>
                            <div class="progress-holder progress2">
                                <div class="progress">
                                    <div class="progress-bar" style="width: 0%"role="progressbar" id="bar2"></div>
                                </div>
                                <span class="progress-title">2</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="form-item w-100" id="content1">
                            <div class="form-item-content mb-3">
                                <label for="old_password">Old Password</label>
                                <div class="querier no-bxs form-group">
                                    <input type="password" data-user-id="<?php echo $_SESSION['session_user_id']; ?>"placeholder="Enter old password here..." name="old_password" id="old_password" class="form-control">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <div class="verifier mt-2"></div>
                            </div>
                            <button type="button" id="next" class="d-none btn btn-sm btn-primary float-end">
                                Next
                                <i class="fa-solid fa-chevron-right ms-2"></i>
                            </button>
                        </div>
                        <div class="form-item collapse w-100" id="content2">
                            <small class="mb-2">
                                <strong>
                                    <p class="text-danger">
                                        Note:
                                        Once verified, you cannot change the fields again. To reset, please cancel.
                                    </p>
                                </strong>
                            </small>
                            <div class="form-item-content mb-3">
                                <label for="old_password">Create New Password</label>
                                <div class="querier no-bxs form-group">
                                    <input type="password" placeholder="Enter new password here..." name="new-password" id="new_password" class="form-control">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                            </div>
                            <div class="form-item-content mb-3">
                                <label for="new_password2">Confirm Password</label>
                                <div class="querier no-bxs form-group">
                                    <input type="password" placeholder="Retype your password..." name="confirm-password" id="new_password2" class="form-control">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                            </div>
                            <div class="verifier2 mt-2 mb-2"></div>
                            <div class="d-flex justify-content-between">
                                <a href="/cldd/loan/index.php?page=change-password" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-times me-1"></i>
                                    Cancel
                                </a>
                                <button type="submit" id="submit-password" name="submit-password" class="btn btn-sm btn-primary d-none">
                                    Next
                                    <i class="fa-solid fa-chevron-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">


    $('#new_password2').focusout(function() {

        $('.verifier2').html(
            `<div class="spinner-border spinner-border-sm text-warning" role="status"></div>
            <small class="ms-1">Verifying password...</small>`
        );

        if($(this).val() === $('#new_password').val()){

            setTimeout(() => {

                $('#new_password').attr('disabled', true);
                $(this).attr('disabled', true);
                $('#alt_password').val($(this).val());

                $('.verifier2').html(
                    `<i class="fa-solid fa-check-circle text-success me-1"></i>
                    <small>Password is verified!</small>`
                );

                $('#next').toggleClass('d-none')

                $('.progress2 .progress-bar').animate({
                    width: '100%',
                    backgroundColor: '#FFA209'
                }, 50);

                $('.progress2 .progress-title').addClass('finished');
                $('#submit-password').removeClass('d-none')
            }, 1200)

        } else {

            setTimeout(() => {

                $('#submit-password').addClass('d-none')

                $('.progress2 .progress-bar').animate({
                    width: '0%',
                    backgroundColor: '#FFA209'
                }, 50);
                $('.progress2 .progress-title').removeClass('finished');

                $('.verifier2').html(
                    `<i class="fa-solid fa-times-circle text-danger me-1"></i>
                    <small class="ms-1">Old password doesn't match!</small>`);
            }, 1200)
        }
    })

    $('#old_password').focusout(function(e){

        $(this).attr('disabled', true)

        $('.verifier').html(
            `<div class="spinner-border spinner-border-sm text-warning" role="status"></div>
            <small class="ms-1">Verifying password...</small>`
        );
        //AJAX
        $.ajax({
            type: 'POST',
            url: '../ajax/checkPassword.php',
            data: {
                session_user_id: $(this).attr('data-user-id'),
                old_password: $(this).val()
            },
            success: (data) => {
                if(data === "success") {
                    
                    setTimeout(() => {
                        $('.verifier').html(
                            `<i class="fa-solid fa-check-circle text-success me-1"></i>
                            <small>Password is verified!</small>`
                        );
                        $('#next').toggleClass('d-none')
                        $('.progress1 .progress-bar').animate({
                            width: '100%',
                            backgroundColor: '#FFA209'
                        }, 50);
                        $('.progress1 .progress-title').addClass('finished');
                    }, 1200)
                } else {
                    setTimeout(() => {
                        $(this).removeAttr('disabled')
                        $('.verifier').html(
                            `<i class="fa-solid fa-times-circle text-danger me-1"></i>
                            <small class="ms-1">Old password doesn't match!</small>`);
                    }, 1200)
                }
            },
            error: (err) => {
                setTimeout(() => {

                    $(this).removeAttr('disabled')

                    $('.verifier').html(
                        `<i class="fa-solid fa-times-circle text-danger me-1"></i>
                        <small class="ms-1">Failed to verify. Please contact admin.</small>`);
                }, 1200)
            }

        })

    })

    $('#next').click(() => {
        $('#content2').removeClass('collapse');
        $('#content1').addClass('collapse');
    })
</script>