<?php

require __DIR__."/../../../vendor/autoload.php";
use App\Controller\articleController;
use App\Controller\authorController;
use App\Controller\operationsController;
use App\Controller\usersController;
use App\Modules\Session;
session_start();
$isLogged = isset($_SESSION['Logged']);
if(isset($_SESSION["UserID"])){$userID = $_SESSION["UserID"];};
$resArticles = articleController::GetPublishedArticle();
function nicetime($date)
{
    if(empty($date)) {
        return "No date provided";
    }
    
    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
    
    $now             = time();
    $unix_date       = strtotime($date);
    
       // check validity of date
    if(empty($unix_date)) {    
        return "Bad date";
    }

    // is it future date or past date
    if($now > $unix_date) {    
        $difference     = $now - $unix_date;
        $tense         = "ago";
        
    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }
    
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    
    if($difference != 1) {
        $periods[$j].= "s";
    }
    
    return "$difference $periods[$j] {$tense}";
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

    <title>SB Admin 2 - Tables</title>

    <!-- Custom fonts for this template -->
    <link href="../../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../../../public/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="../../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <form class="form-inline">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                </form>

                <!-- Topbar Search -->
                <div
                    class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <h1 class="">DEV BLOG</h1>
                </div>
                
                <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input  id="searchInput"  type="search" class="form-control bg-light border-0 small" placeholder="Search for..."
                               aria-label="Search" aria-describedby="basic-addon2">
                        
                    </div>
                </form>
                <!-- Topbar Navbar -->
                 
                <ul class="navbar-nav ml-auto">
                    <!-- Nav Item - Alerts -->
                    <?php if(isset($_SESSION["UserRole"]) && $_SESSION["UserRole"] === "admin"): ?>
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="../index.php" id="alertsDropdown">
                                <p>Dashboard Admin</p>
                                <!-- Counter - Alerts -->
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <div class="topbar-divider d-none d-sm-block"></div>
                    <!-- Nav Item - User Information -->
                    <?php if(isset($_SESSION["UserName"])):?>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                        <?= $_SESSION["UserName"]; ?>
                                    </span>
                                    <img class="img-profile rounded-circle"
                                        src="../../../public/img/profiles/reference<?= $_SESSION["ProfilePic"]; ?>">
                                </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="./EditProfile.php?id=<?= $_SESSION["UserID"]; ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>

                            </div>
                        </li>
                    <?php endif;?>
                    <?php if(!isset($_SESSION["UserName"])):?>
                        <li class="nav-item ">
                            <a class="nav-link text-blue-500" href="../login.php" id="userDropdown" role="button" >
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    Login
                                </span>
                            </a>
                        </li>
                    <?php endif;?>
                    
                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <h1 class="h3 mb-2 text-gray-800">Last Articles</h1>

                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    
                    <!-- <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Views</th>
                                    <th>Auteur</th>
                                    <th>Status</th>
                                    <th>Tags</th>
                                    <th>categorie</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Operations</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Views</th>
                                    <th>Auteur</th>
                                    <th>Content</th>
                                    <th>categorie</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th></th>
                                </tr>
                                </tfoot>
                                <tbody>
                                    <?php foreach ($resArticles as $row):?>
                                        <tr>
                                            <td><?= $row['ArticleId']?></td>
                                            <td><?= $row['title']?></td>
                                            <td><?= $row['views']?></td>
                                            <td><?= $row['author_name']?></td>
                                            <td class="d-flex align-items-center justify-content-center">
                                                <p  class="text-white px-2 rounded
                                                <?php
                                                        if ($row['status'] == "published"){echo "bg-success";}
                                                        elseif ($row['status'] == "pending"){echo "bg-warning";}
                                                        else{echo "bg-danger";}?>">
                                                        <?= $row['status'] ?>
                                                </p>
                                            </td>
                                            <td><?= $row['tags']?></td>
                                            <td><?= $row['category_name']?></td>
                                            <td><?= $row['created_at']?></td>
                                            <td><?= $row['updated_at']?></td>

                                            <td class="flex ">
                                                <a href="Articles.php?id=<?= $row['ArticleId']?>&op=edit"><button type="button" class="btn btn-info w-auto"><i class="fa-solid fa-pen-to-square"></i></button></a>
                                                <a href="Articles.php?id=<?= $row['ArticleId']?>&op=archive"><button type="button" class="btn btn-warning w-auto "><i class="fa-solid fa-box-archive"></i></button></a>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                    </div> -->

                    <div id="resultarea" style='display:flex;gap:20px;justify-content:center;flex-wrap:wrap;margin-block:40px;'>

                        <?php foreach ($resArticles as $ArticleRow): ?>
                            <div class="card" style="width: 18rem;">
                                <div style="position :absolute; background-color:white;color:black;padding-inline:15px;padding-block:2px;border-radius:50px;margin:10px;">
                                    <i class="fa-solid fa-eye"></i>
                                    <?=$ArticleRow["views"]?>
                                </div>
                                <div style="width:100%;height:200px;">
                                    <img style="height: 100%; width:100%;object-fit:cover;" class="card-img-top " src="./../../../public/img/covers/reference<?=$ArticleRow["featured_image"]?>" alt="Card image cap">
                                    <!-- <img style="height: 100%; width:100%;object-fit:cover;" class="card-img-top " src="./../../../public/img/covers/reference<?=$ArticleRow["featured_image"]?>" alt="Card image cap"> -->
                                </div>
                                <div class="card-body">
                                    <p class="card-title " style="font-size: 13px;">Posted By <strong><?= $ArticleRow["username"] ?> <?= nicetime( $ArticleRow["created_at"])?></strong> </p>
                                    <strong>
                                        <a href="./view.php?id=<?=$ArticleRow["ArticleId"]?>" >    
                                            <h5 class="card-title"><?= $ArticleRow["title"] ?></h5>
                                        </a>
                                    </strong>    
                                    <p style='text-overflow: ellipsis;overflow: hidden; white-space: nowrap;' class="card-text"><?= $ArticleRow["content"] ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Your Website 2020</span>
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
                <a class="btn btn-primary" href="../logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="../../../vendor/jquery/jquery.min.js"></script>
<script src="../../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="../../../vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="../../../public/js/sb-admin-2.min.js"></script>
<script src="../../../public/js/search.js"></script>

<!-- Page level plugins -->
<script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="../../../public/js/demo/datatables-demo.js"></script>
<script src="https://kit.fontawesome.com/285f192ded.js" crossorigin="anonymous"></script>
</body>

</html>