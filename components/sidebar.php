<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button 
                role="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#sidebar"
                class="navbar-toggler">
                <!-- <i class="fas fa-bars"></i> -->
                <span class="navbar-toggler-icon"></span>
            </button>
            <a href="#" class="brand">
                <img src="../images/cldd_logo.png" class="img" />
                <span>CLDD Loan Management System</span>
            </a>
            <button 
                role="button"
                data-bs-target="#main_nav"
                data-bs-toggle="collapse"
                aria-expanded="false"
                class="navbar-toggler">
                <!-- <i class="fas fa-bars"></i> -->
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main_nav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="../" id="logout" class="nav-link">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="offcanvas offcanvas-start p-2 sidebar-nav bg-dark" tabindex="-1" id="sidebar" >
            <div class="oncanvas-header">
                <button 
                    class="btn-close text-reset float-end"
                    data-bs-dismiss="offcanvas">
                </button>
                <div class="clearfix"></div>
                <h5 class="offcanvas-title mt-4 text-center text-white">
                    user66343
                </h5>
            </div>
            <div class="oncanvas-body mt-4 p-2">
                <h5 class="nav-header text-center">Dashboard</h5>
                <ul class="navbar-nav w-100 d-inline-block mt-4" id="sidenav" data-active="<%=active%>">
                    <li class="nav-item">
                        <a href="/cldd/loan/" id="profile" class="nav-link">
                            <i class="fas fa-user me-3"></i>
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/cldd/loan/index.php?type=users" id="sell" class="nav-link">
                            <i class="fas fa-cart-plus me-3"></i>
                            Users
                        </a>
                    </li>
                </ul>
                <h5 class="nav-header text-center mt-4">My Account</h5>
                <ul class="navbar-nav w-100 d-inline-block mt-4" id="sidenav" data-active="<%=active%>">
                    <li class="nav-item">
                        <a href="/cldd/loan/" id="profile" class="nav-link">
                            <i class="fas fa-user me-3"></i>
                            Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/cldd/loan/index.php?type=users" id="sell" class="nav-link">
                            <i class="fas fa-cart-plus me-3"></i>
                            Change Password
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>