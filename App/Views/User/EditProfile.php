<?php
session_start();
use App\Controller\articleController;
use App\Controller\categoriesController;
use App\Controller\operationsController;
use App\Controller\tagsController;
use App\Controller\usersController;
use App\Modules\CRUD;
use App\Modules\FileHandler;
use App\Modules\Session;
require __DIR__."/../../../vendor/autoload.php";
Session::sessionCheck("Logged","../login.php");
// Session::checkSessionRole(["author"],"../index.php");


$resUser = usersController::GetUsers();
$resCategorie = categoriesController::GetCategories();
$resTags = tagsController::GetTags();

if (isset($_GET["id"])){
    $resUserById = CRUD::GetById('users','id',$_GET["id"]);
    $User = $resUserById[0];
}else{
    header("Location:../index.php");
}

if (isset($_POST["submit"]) && isset($_POST["email"]) && !empty($_POST["email"])){
    if (isset($_FILES["coverImage"]) && $_FILES["coverImage"]["size"] != 0 ) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
        $Cover = $_FILES["coverImage"];
        $path = realpath(__DIR__."/../../../public/img/profiles/reference/");
        $result = FileHandler::handle_file_upload($Cover,$allowed_types,$path,$_POST["title"])["filename"];
    }else{
        $result = $_POST["coverfeatimage"];
    }
    $UserID = $_SESSION["UserID"];
    $NewEmail = $_POST["email"];
    $NewBio = $_POST["bio"];
    $resultUpdate = usersController::updateUser($UserID,$NewEmail,$NewBio,$result);
    if ($resultUpdate) {
        header("Location:./EditProfile.php?id=".$UserID);
        exit;
    }
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
    <?php if(isset($_SESSION["UserRole"]) && $_SESSION["UserRole"] != "user"): ?>
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
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="../index.php" >
                    <i class="fa-solid fa-house"></i>
                    <span>Home</span>
                </a>
                <a class="nav-link collapsed" href="../Author/myArticles.php" >
                    <i class="fa-solid fa-newspaper"></i>
                    <span>My Articles</span>
                </a>
                <a class="nav-link collapsed" href="./" >
                    <span>Management</span>
                </a>
            </li>
            



            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->
    <?php endif; ?>

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
                        <a class="nav-link dropdown-toggle" href="./index.php" id="alertsDropdown">
                            <i class="fa-solid fa-house"></i>
                            <!-- Counter - Alerts -->
                        </a>
                        
                    </li>


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
                                <a class="dropdown-item"href="./EditProfile.php?id=<?= $_SESSION["UserID"] ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <!-- <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a> -->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>

                            </div>
                        </li>
                    <?php endif;?>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <h1 class="h3 mb-2 text-gray-800">Manage Profile</h1>
                <!-- <?php if(isset($_SESSION["result"])): ?>
                    <div class=" z-5 alert alert-<?= $_SESSION["result"]["color"] ?> top-0 " role="alert">
                        <?= $_SESSION["result"]["message"] ?>
                    </div>
                <?php endif; ?> -->
                
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 justify-content-between d-flex">
                        <h6 class="m-0 align-content-center font-weight-bold text-primary">
                            Edit Profile
                        </h6>

                    </div>
                    <div class="w-100 flex justify-center">
                    <form method="post" class="w-50 d-flex flex-col"  enctype="multipart/form-data">
                          
                        <div class="mb-4">
                            <div class="h-[200px] w-[200px] mb-3 mt-3 overflow-hidden border rounded-[10px]">
                                <img style="object-fit:cover;width:100%;height:100%;" src="../../../public/img/profiles/reference<?= $User["profile_picture_url"] ?>" alt="">
                            </div>
                            <div class="input-group mb-3 h-[10px]">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Upload Image</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="coverImage" >
                                    <input type="hidden"  name="coverfeatimage"  value="<?=$User["profile_picture_url"]?>" class="custom-file-input">
                                    <label class="custom-file-label" for="inputGroupFile01">Choose Image</label>
                                </div>
                            </div>
                        </div>    
                        <div class="mb-3">
                            <label for="exampleInputEmail1"  class="form-label">Username</label>
                            <input  disabled type="text" value="<?= $User["username"] ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label  class="form-label">Your Role</label>
                            <input disabled  type="text" value="<?= $User["role"] ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1"  class="form-label">Email</label>
                            <input name="email" type="text" value="<?= $User["email"] ?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" aria-describedby="emailHelp"><?= $User["bio"] ?></textarea>
                        </div>
                        
                        <button name="submit" type="submit" class="btn btn-primary">Update</button>
                    </form>
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

<!-- Page level plugins -->
<script src="../../../vendor/datatables/jquery.dataTables.min.js"></script>
<script src="../../../vendor/datatables/dataTables.bootstrap4.min.js"></script>

<!-- Page level custom scripts -->
<script src="../../../public/js/demo/datatables-demo.js"></script>
<script src="https://kit.fontawesome.com/285f192ded.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>