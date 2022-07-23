<?php 
session_start();

require_once('../api/accounts.php'); 

$accounts = new AccountsDB();


$data = $accounts->getAccountData($_SESSION['session_user_id']);

$profile_pic = empty($data['profile_pic']) ? "../images/lebron.jpg" : "../images/uploads/".$data['user_id']."-".$data['profile_pic'];


if(!isset($_SESSION) && !isset($_SESSION['session_user'])){
    header("location: /cldd/");
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>CLDD LMS</title> 
    <link rel ="shortcut icon" href="../images/favicon.ico">
    <!-- STYLES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../libraries/bootstrap5/css/bootstrap.min.css">
    <link rel="stylesheet" href="../libraries/fonts/Poppins-Regular.ttf">
    <link rel="stylesheet" href="../libraries/fa/css/all.min.css">
    <link rel="stylesheet" href="../libraries/datatable/datatable.css">
    <link rel="stylesheet" href="../libraries/owl/dist/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="../libraries/owl/dist/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="../libraries/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="../css/dashboard-style.css">
    <link rel="stylesheet" href="../css/dashstyle.css">
    <!-- SCRIPTS -->
    <script src="../js/jquery.min.js"></script>
    <script src="../libraries/jquery-ui/jquery-ui.min.js"></script>
    <script src="../libraries/bootstrap5/js/bootstrap.bundle.min.js"></script>
    <script src="../libraries/datatable/datatables.js"></script>
    <script src="../js/swal.js"></script>
    <script src="../libraries/chart/dist/chart.min.js"></script>
    <script src="../libraries/owl/dist/owl.carousel.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="header-menu">
                <div class="title">
                    <img src="../images/cldd_logo.png" alt="" class="title-img">
                    COOP<span>ERATIVES</span>
                </div>
                <div class="sidebar-btn">
                    <i class="fas fa-bars"></i>
                </div>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo $profile_pic;?>" alt="" style="height: 30px; width: 30px; border: solid 2px #FFA209; border-radius: 50%;">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item dropdown bg-white" href="./pages/logout.php">
                                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </div>    
        </div>
        <div class="sidebar">
            <div class="sidebar-menu">
                <center class="profile">
                    <img src="<?php echo $profile_pic;?>" alt="">
                    <p><?php echo $data['nickname']?></p>
                </center>
                <li class="item" title="Dashboard">
                    <a href="/cldd/loan/" class="menu-btn <?php echo $_GET['page'] === "home" || !isset($_GET['page'])?"active-menu":"" ?>">
                        <i class="fa-solid fa-chart-line"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="item" title="Client Chart">
                    <a href="/cldd/loan/index.php?page=client" class="menu-btn <?php echo $_GET['page'] === "client" ?"active-menu":"" ?>">
                        <i class="fa-solid fa-bar-chart"></i><span>Payment Chart</span>
                    </a>
                </li>

                <li class="item" title="Events">
                    <a href="/cldd/loan/index.php?page=coop_events" class="menu-btn <?php echo $_GET['page'] === "coop_events" ?"active-menu":"" ?>">
                        <i class="fa-solid fa-calendar"></i>
                        <span>Events</span>
                        <?php 
                        require_once('../api/accounts.php');
                        $account = new AccountsDB();
                        if($account->getCurrentEventsCount() > 0){ ?>
                            <div class="badge bg-primary"><?php echo $account->getCurrentEventsCount(); ?></div>
                        <?php } ?>
                    </a>
                </li>
                <li class="item" id="profile">
                    <a href="#profile" class="menu-btn">
                        <i class="fa-solid fa-list"></i><span>List of Applicant</span>
                    </a>
                    <div class="sub-menu">
                        <a title="Active Accounts" href="/cldd/loan/index.php?page=active"><i  class="fa-solid fa-folder-open"></i><span>Active Accounts</span></a>
                        <a title="Closed Accounts" href="/cldd/loan/index.php?page=closed"><i class="fa-solid fa-folder-closed"></i><span>Closed Accounts</span></a>
                    </div>
                </li>
                    
                <li class="item" title="Payments">
                    <a href="/cldd/loan/index.php?page=payments" class="menu-btn <?php echo $_GET['page'] === "payments" ?"active-menu":"" ?>">
                        <i class="fa-solid fa-peso-sign"></i><span>Payments</span>
                    </a>
                </li>

                <li class="item" title="Payments">
                    <a href="/cldd/loan/index.php?page=co-makers" class="menu-btn <?php echo $_GET['page'] === "payments" ?"active-menu":"" ?>">
                        <i class="fa-solid fa-people-group"></i><span>Co-Makers</span>
                    </a>
                </li>

                <hr class="bg-secondary"/>
                <li class="item" title="My Profile">
                    <a href="/cldd/loan/index.php?page=profile" class="menu-btn <?php echo $_GET['page'] === "profile" ?"active-menu":"" ?>">
                        <i class="fa-solid fa-user"></i><span>My Profile</span>
                    </a>
                </li>
                    
                <li class="item" title="Change Password">
                    <a href="/cldd/loan/index.php?page=change-password" class="menu-btn <?php echo $_GET['page'] === "change-password" ?"active-menu":"" ?>">
                        <i class="fa-solid fa-cog"></i><span>Change Password</span>
                    </a>
                </li>
            </div>
        </div>
        <div class="main-container">
            <?php
                $type = isset($_GET['page']) ? $_GET['page'] : 'home';
                require_once('./pages/'.$type.'.php');
            ?>
        </div>
    </div>
    </body>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".sidebar-btn").click(function(){
                $(".wrapper").toggleClass("collapser");
                if($(".wrapper").hasClass('collapser')){
                    localStorage.setItem("sidebar", "collapsed")
                } else {
                    localStorage.setItem("sidebar", "")
                }
                sidebar()
            });
            function sidebar(){
                let collapsed = localStorage.getItem("sidebar");
                if(collapsed === "collapsed") {
                    $(".wrapper").addClass("collapser");
                }else {
                    $(".wrapper").removeClass("collapser");
                }
            }
            sidebar()
        });
    </script>
</html>