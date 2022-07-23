<?php 

    include('./api/functions.php');
    require_once('./api/accounts.php');

    $account = new AccountsDB();
    $result = array();

    if(isset($_POST['submit']) && isset($_POST['submit']) == "Login")
    {
        $result = $account->loginUser(trimLow($_POST['username']), trimLow($_POST['password']));

        if(!(empty($result)) && $result['status'] == "success")
        {
            $_SESSION['session_user'] = $result['data']['username'];
            $_SESSION['session_nickname'] = $result['data']['nickname'];
            $_SESSION['session_user_id'] = $result['data']['user_id'];

            //print_r($_SESSION);

            header("location: /cldd/loan/");
        }
    }

?>

<form action="./index.php" class="form" method="POST" id="signin_form">
    <h5>CLDD Loan Management System</h5>
    <img src="./images/cldd_logo.png" alt="CLDD Banner" class="cldd_logo img-responsive">
    <h5 class="mt-2">Login</h5>
    <label for="username" class="mt-2">Enter username</label>
    <div class="input-group">
        <input type="text" class="form-control" autofocus required placeholder="Enter username here..." name="username" id="username">
        <span class="input-group-text" id="basic-addon1">
            <i class="fa-solid fa-user"></i>
        </span>
    </div>
    <label for="password" class="mt-2">Enter password</label>
    <div class="input-group">
        <input type="password" class="form-control" required placeholder="Enter password here..." name="password" id="password">
        <span class="input-group-text" id="basic-addon1">
            <i class="fa-solid fa-lock-keyhole"></i>
        </span>               
        </div>

        <!-- ERROR -->
        <?php if(!(empty($result))) { ?>
        <div class="alert alert-danger mt-2 text-center alert-dismissible fade show mb-0" data-bs-dismiss="alert" role="alert">
            <small><?php echo $result['message']; ?></small>
    </div>
    <?php }?>

    <input type="submit" class="btn btn-outline-success mt-3 w-100" name="submit" value="Login">
    <div class="actions">
        <a href="./">Forgot password</a>
        <span>|</span>
        <a href="./index.php?page=addnewAdmin">Add New Admin</a>
    </div>
</form>
