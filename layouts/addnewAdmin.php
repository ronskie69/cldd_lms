<?php 
    
    include('./api/functions.php');
    require_once('./api/accounts.php');

    $accounts = new AccountsDB();
    $result = array();

    if(isset($_POST) && isset($_POST['createUser']))
    {
        $username = trimLow($_POST['username']);
        $password = trimLow($_POST['password']);
        $nickname = trimCaps($_POST['nickname']);

        $result = $accounts->registerUser($username, $password, $nickname);
    }

?>
<?php if(!(empty($result)) && $result['status'] == "success"){ ?>

<div class="alert alert-success" role="alert">
    <img src="./images/check.png" class="success_image"/>
    <h5>Success!</h5>
    <p><?php echo $result['message']; ?></p>
    <div class="d-inline-block">
        <a href="./index.php?page=addnewAdmin" class="btn btn-sm btn-success">Add New Admin</a>
        <a href="./index.php?page=signin" class="btn btn-sm btn-primary">Continue Login</a>
    </div>
</div>
<?php } else { ?>
    <form action="./index.php?page=addnewAdmin" class="form" method="POST" id="newAdmin_form">
    <h5>CLDD Loan Management System</h5>
    <img src="./images/cldd_logo.png" alt="CLDD Banner" class="cldd_logo img-responsive">
    <h5 class="mt-2">Add New Admin</h5>

    <label for="username" class="mt-2">Enter username</label>
    <div class="input-group">
        <input type="text" class="form-control" autofocus required placeholder="Enter username here..." id="username" name="username">
        <span class="input-group-text" id="basic-addon1">
            <i class="fa-duotone fa-user"></i>
        </span>
    </div>

    <label for="password" class="mt-2">Enter nickname / alias</label>
    <div class="input-group">
        <input type="text" class="form-control" required placeholder="Enter nickname or alias here..." id="nickname" name="nickname">
        <span class="input-group-text" id="basic-addon1">
            <i class="fa-duotone fa-lock-keyhole"></i>
        </span>               
    </div>

    <label for="password" class="mt-2">Enter password</label>
    <div class="input-group">
        <input type="password" class="form-control" required placeholder="Enter password here..." minlength ="7" id="password" name="password">
        <span class="input-group-text" id="basic-addon1">
            <i class="fa-duotone fa-lock-keyhole"></i>
        </span>               
    </div>

    <label for="password" class="mt-2">Retype password</label>
    <div class="input-group">
        <input type="password" class="form-control" required placeholder="Retype password here..." id="password2" name="password2">
        <span class="input-group-text" id="basic-addon1">
            <i class="fa-duotone fa-lock"></i>
        </span>               
    </div>

        <!-- ERROR -->
        <?php if(!(empty($result)) && $result['status'] == "error") { ?>
        <div class="alert alert-danger mt-2 text-center alert-dismissible fade show mb-0" data-bs-dismiss="alert" role="alert">
            <small><?php echo $result['message']; ?></small>
    </div>
    <?php }?>

    <input type="submit" class="btn btn-outline-success mt-3 w-100" name="createUser" value="Create Admin">
    <div class="actions">
        <a href="./index.php?page=signin">Forgot password</a>
        <span>|</span>
        <a href="./index.php?page=signin">Continue Login</a>
    </div>
</form>

<?php } ?>
