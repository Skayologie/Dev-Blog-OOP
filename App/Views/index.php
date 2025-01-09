<?php
session_start();
require __DIR__."/../../vendor/autoload.php";

use App\Modules\Author;
use App\Modules\Stats;
use App\Modules\Session;
$UserID = $_SESSION["UserID"];
// Session::checkSessionRole([""=>"author",""=>"admin"],"./User/index.php");
// Session::sessionCheck("Logged","./login.php");
// if ($_SESSION["UserRole"] != "author" || $_SESSION["UserRole"] != "admin" ) {
//     var_dump($_SESSION["UserRole"]);
//     // header("Location:./User/index.php");
// }else{
//     if($_SESSION["UserRole"] == "author"){
//         header("Location:./index.php");
//     }else if($_SESSION["UserRole"] == "admin"){
//         header("Location:./index.php");
//     }
// }

switch ($_SESSION["UserRole"]) {
    case 'author':
        break;
    case 'admin':
        break;
    default:
        header("Location:./User/index.php");
        break;
}

$TotalUsers = Stats::Total("users");
$TotalArticles = Stats::Total("articles");

$TotalAuthorArticles = Author::totalArticles($UserID);
$TotalAuthorViews = Author::totalViews($UserID);
$my_top_articles = Author::topArticles($UserID);
$my_pending_articles = Author::pendingArticles($UserID);

$TotalTags = Stats::Total("tags");
$TotalCategories = Stats::Total("categories");
$top_users = Stats::get_top_users();


$category_stats = Stats::get_category_stats();
$top_articles = Stats::get_top_articles();


// Prepare data for the chart
$categories = $category_stats;
$categoriesNames = [];
$counts = [];
// Define colors for the chart
$colors = [
    'rgb(78, 115, 223)',    // primary
    'rgb(28, 200, 138)',    // success
    'rgb(54, 185, 204)',    // info
    'rgb(246, 194, 62)',    // warning
    'rgb(231, 74, 59)',     // danger
    'rgb(133, 135, 150)',   // secondary
    'rgb(90, 92, 105)',     // dark
    'rgb(244, 246, 249)'    // light
];
foreach ($categories as $stat) {
    $categoriesNames[] = $stat['CatName'];
    $counts[] = $stat['article_count'];
}


?>


<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dev Blog</title>

    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../public/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Dev Blog <?php echo $_SESSION["UserRole"];?></div>
            </a>
            
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                   aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Management</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <?php if($_SESSION["UserRole"] == "admin"): ?>
                        
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Manage Users</h6>
                            <a class="collapse-item" href="./Admin/TableUsers.php">Users</a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manage Articles</h6>
                        <?php if($_SESSION["UserRole"] == "admin"): ?>
                            <a class="collapse-item" href="./Admin/Articles.php">Articles</a>
                        <?php endif; ?>
                        <?php if($_SESSION["UserRole"] == "author"): ?>
                            <a class="collapse-item" href="./Author/CreateArticle.php">Add Articles</a>
                            <a class="collapse-item" href="./Author/myArticles.php">Show My Articles</a>
                        <?php endif; ?>
                    </div>
                        <?php if($_SESSION["UserRole"] == "admin"): ?>
                            <div class="bg-white py-2 collapse-inner rounded">
                                <h6 class="collapse-header">Manage Tags</h6>
                                <a class="collapse-item" href="./Admin/Tags.php">Tags</a>
                            </div>
                            <div class="bg-white py-2 collapse-inner rounded">
                                <h6 class="collapse-header">Manage Categories</h6>
                                <a class="collapse-item" href="../Pages/buttons.html">Categories</a>
                            </div>
                        <?php endif; ?>
                    
                </div>
            </li>



            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg"
                                            alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg"
                                            alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?= $_SESSION["UserName"]; ?>
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="../../public/img/profiles/reference<?= $_SESSION["ProfilePic"]; ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="User/EditProfile.php?id=<?= $_SESSION["UserID"]; ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>
                    <?php if(isset($_SESSION["Banned"]) && $_SESSION["Banned"] ): ?>
                        <div class="alert alert-danger" role="alert">
                            Your closed ! Contact support @skay_37
                        </div>
                    <?php endif; ?>
                    <!-- Cards Stats For The Admin -->
                    <?php if(isset($_SESSION["UserRole"]) && $_SESSION["UserRole"] == "admin" ): ?>
                        <div class="row">

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Total Users</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $TotalUsers ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Total Articles</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$TotalArticles?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Tags
                                                </div>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?=$TotalTags ?></div>
                                                    </div>
                                                    <!-- <div class="col">
                                                        <div class="progress progress-sm mr-2">
                                                            <div class="progress-bar bg-info" role="progressbar"
                                                                style="width: 90%" aria-valuenow="50" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Total Categoreis</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$TotalCategories?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                     <!-- Cards Stats For The author -->
                     <?php if(isset($_SESSION["UserRole"]) && $_SESSION["UserRole"] == "author" && $_SESSION["Banned"]): ?>
                        <div class="row">

                            <!-- Earnings (Monthly) Card Example -->
                            <!-- <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Total Users</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $TotalUsers ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    My Articles</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $TotalAuthorArticles ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Views
                                                </div>
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-auto">
                                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $TotalAuthorViews ?></div>
                                                    </div>
                                                    <!-- <div class="col">
                                                        <div class="progress progress-sm mr-2">
                                                            <div class="progress-bar bg-info" role="progressbar"
                                                                style="width: 90%" aria-valuenow="50" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Total Categoreis</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$TotalCategories?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Content Row -->

                    <div class="row">
                        <?php if(isset($_SESSION["UserRole"]) && $_SESSION["UserRole"] == "admin"): ?>
                            <!-- Top Users -->
                            <div class="col-xl-8 col-lg-7">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">Top Authors</h6>
                                        <div class="dropdown no-arrow">
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                                aria-labelledby="dropdownMenuLink">
                                                <div class="dropdown-header">Dropdown Header:</div>
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="card-body">
                                            <?php foreach($top_users as $index => $user): ?>
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="mr-3">
                                                        <div class="icon-circle bg-primary text-white">
                                                            <?php if($user['profile_picture_url']): ?>
                                                                <img src="<?= htmlspecialchars($user['profile_picture_url']) ?>" 
                                                                    class="rounded-circle" 
                                                                    style="width: 40px; height: 40px; object-fit: cover;"
                                                                    alt="<?= htmlspecialchars($user['username']) ?>">
                                                            <?php else: ?> 
                                                                <i class="fas fa-user"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="small text-gray-500">Author #<?= $index + 1 ?></div>
                                                        <div class="font-weight-bold"><?= htmlspecialchars($user['username']) ?></div>
                                                        <div class="text-gray-800">
                                                            <?= $user['article_count']?> articles
                                                            <span class="mx-1">•</span>
                                                            <?= number_format((int)$user['viewsAll']) ?> total views
                                                        </div>
                                                    </div>
                                                    <div class="ml-2">
                                                        <a href="../entities/users/user-profile.php?id=<?= $user['id'] ?>"
                                                        class="btn btn-primary btn-sm">
                                                            View Profile
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php if($index < count($top_users) - 1): ?>
                                                    <hr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($_SESSION["UserRole"]) && $_SESSION["UserRole"] == "test"): ?>
                            <!-- Top Users -->
                            <div class="col-xl-8 col-lg-7">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">Top Authors</h6>
                                        <div class="dropdown no-arrow">
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                                aria-labelledby="dropdownMenuLink">
                                                <div class="dropdown-header">Dropdown Header:</div>
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="card-body">
                                            <?php foreach($top_users as $index => $user): ?>
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="mr-3">
                                                        <div class="icon-circle bg-primary text-white">
                                                            <?php if($user['profile_picture_url']): ?>
                                                                <img src="<?= htmlspecialchars($user['profile_picture_url']) ?>" 
                                                                    class="rounded-circle" 
                                                                    style="width: 40px; height: 40px; object-fit: cover;"
                                                                    alt="<?= htmlspecialchars($user['username']) ?>">
                                                            <?php else: ?> 
                                                                <i class="fas fa-user"></i>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="small text-gray-500">Author #<?= $index + 1 ?></div>
                                                        <div class="font-weight-bold"><?= htmlspecialchars($user['username']) ?></div>
                                                        <div class="text-gray-800">
                                                            <?= $user['article_count']?> articles
                                                            <span class="mx-1">•</span>
                                                            <?= number_format((int)$user['viewsAll']) ?> total views
                                                        </div>
                                                    </div>
                                                    <div class="ml-2">
                                                        <a href="../entities/users/user-profile.php?id=<?= $user['id'] ?>"
                                                        class="btn btn-primary btn-sm">
                                                            View Profile
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php if($index < count($top_users) - 1): ?>
                                                    <hr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        
                        <!-- Pie Chart -->
                        <?php if(isset($_SESSION["UserRole"]) && $_SESSION["UserRole"] == "admin"):?>
                            <div class="col-xl-4 col-lg-5">
                                <div class="card shadow mb-4">
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">Categorie Use</h6>
                                        <div class="dropdown no-arrow">
                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                                aria-labelledby="dropdownMenuLink">
                                                <div class="dropdown-header">Dropdown Header:</div>
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-pie pt-4 pb-2">
                                            <canvas id="myPieChart"></canvas>
                                        </div>
                                        <div class="mt-4 text-center small">
                                            <?php foreach ($categories as $index => $stat): ?>
                                                <span class="mr-2">
                                                    <i class="fas fa-circle" style="color: <?= $colors[$index % count($colors)] ?>"></i>
                                                    <?= htmlspecialchars($stat['CatName']) ?>
                                                    (<?= $stat['article_count'] ?>)
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>





                      <!-- Top Articles Author -->
                    <?php if(isset($_SESSION["UserRole"]) && $_SESSION["UserRole"] == "author"):  ?>
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">My Top Articles</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="card-body">
                                    <?php foreach($my_top_articles as $index => $article): ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-success text-white">
                                                    <i class="fas fa-newspaper"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="small text-gray-500">
                                                    Published <?= date('M d, Y', strtotime($article['created_at'])) ?>
                                                    by <?= htmlspecialchars($article['username']) ?>
                                                </div>
                                                <div class="font-weight-bold"><?= htmlspecialchars($article['title']) ?></div>
                                                <div class="text-gray-800">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    <?= number_format($article['views']) ?> views
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <a href="./Author/EditArticle.php?id=<?= $article['ArticleID'] ?>"><button type="button" class="btn btn-info"><i class="fa-solid fa-pen-to-square"></i></button></a>
                                            </div>
                                        </div>
                                        <?php if($index < count($top_articles) - 1): ?>
                                            <hr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if(count($my_top_articles) === 0): ?>
                                        You don't have any top article !
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>
                      <!-- Top Articles admin -->
                    <?php if(isset($_SESSION["UserRole"]) && $_SESSION["UserRole"] == "admin"):  ?>
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">My Top Articles</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="card-body">
                                    <?php foreach($top_articles as $index => $article): ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-success text-white">
                                                    <i class="fas fa-newspaper"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="small text-gray-500">
                                                    Published <?= date('M d, Y', strtotime($article['created_at'])) ?>
                                                    by <?= htmlspecialchars($article['username']) ?>
                                                </div>
                                                <div class="font-weight-bold"><?= htmlspecialchars($article['title']) ?></div>
                                                <div class="text-gray-800">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    <?= number_format($article['views']) ?> views
                                                </div>
                                            </div>
                                            <div class="ml-2">
                                                <a href="./entities/articles/view-article.php?id=<?= $article['id'] ?>"
                                                class="btn btn-success btn-sm">
                                                    Read Article
                                                </a>
                                            </div>
                                        </div>
                                        <?php if($index < count($top_articles) - 1): ?>
                                            <hr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>

                      <!-- Pending Articles Author -->
                      <?php if(isset($_SESSION["UserRole"]) && $_SESSION["UserRole"] == "author"):  ?>
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Pending Articles</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Dropdown Header:</div>
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="card-body">
                                    <?php foreach($my_pending_articles as $index => $article): ?>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-warning text-white">
                                                    <i class="fas fa-newspaper"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="small text-gray-500">
                                                    Published <?= date('M d, Y', strtotime($article['created_at'])) ?>
                                                    by <?= htmlspecialchars($article['username']) ?>
                                                </div>
                                                <div class="font-weight-bold"><?= htmlspecialchars($article['title']) ?></div>
                                                
                                            </div>
                                            <div class="ml-2">
                                                <div
                                                class="btn btn-warning btn-sm">
                                                    Pending
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($index < count($top_articles) - 1): ?>
                                            <hr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if(count($my_pending_articles) === 0): ?>
                                        No pending articles found !
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif;?>


                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="./logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../public/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
<script >
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        // Pie Chart Example
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($categoriesNames) ?>,
            datasets: [{
            data: <?= json_encode($counts) ?>,
            backgroundColor: <?= json_encode(array_slice($colors, 0, count($categories))) ?>,
            hoverBackgroundColor: <?= json_encode(array_slice($colors, 0, count($categories))) ?>,
            hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
            },
            legend: {
            display: false
            },
            cutoutPercentage: 80,
        },
        });

</script>
<script src="https://kit.fontawesome.com/285f192ded.js" crossorigin="anonymous"></script>

</body>

</html>