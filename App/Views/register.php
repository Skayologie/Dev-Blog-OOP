<?php

use App\Modules\FileHandler;
use App\Modules\User;
session_start();

require realpath(__DIR__ . "/../../vendor/autoload.php");
if (isset($_SESSION["Logged"])) {
    header("Location:./index.php");
}
if (isset($_POST["submit"]) &&
    isset($_POST["username"]) && !empty($_POST["username"]) &&
    isset($_POST["email"]) && !empty($_POST["email"]) &&
    isset($_POST["password"]) && !empty($_POST["password"]) &&
    isset($_POST["bio"]) && !empty($_POST["bio"])
){
    $profile = $_FILES["profileCover"];
    $name = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $bio = $_POST["bio"];
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
    $path = realpath(__DIR__."/../../public/img/profiles/reference/");
    
    $result = FileHandler::handle_file_upload($profile,$allowed_types,$path ,$_POST["username"]);
    $message = User::register($name,$email,$password,$bio,$result["filename"]);
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

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../public/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <?php if(isset( $message )): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $message ?>
                            </div>
                            <?php endif;?>
                            <form method="POST" class="user"  enctype="multipart/form-data">
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <input value="<?php if(isset($_POST["username"])){echo $_POST["username"];}?>" type="text" name="username" class="form-control form-control-user" 
                                            placeholder="username">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 mb-3 mb-sm-0">
                                        <textarea  name="bio" placeholder="Bio" class="form-control " placeholder="username"><?php if(isset($_POST["bio"])){echo $_POST["bio"];}?></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input value="<?php if(isset($_POST["email"])){echo $_POST["email"];}?>" type="text"  name="email" class="form-control form-control-user" 
                                            placeholder="exemple@gmail.com">
                                    </div>
                                    <div class="col-sm-6 ">
                                        <input type="file" name="profileCover" class="form-control form-control-user"
                                            >
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input value="<?php if(isset($_POST["password"])){echo $_POST["password"];}?>" name="password" type="password" class="form-control form-control-user"
                                             placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input value="<?php if(isset($_POST["password"])){echo $_POST["password"];}?>" type="password" class="form-control form-control-user"
                                             placeholder="Repeat Password">
                                    </div>
                                </div>
                                <button name="submit" type="submit" href="login.html" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                                <hr>
                                <!-- <a href="index.html" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                    <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                                </a> -->
                            </form>
                            <div class="text-center">
                                <a class="small" href="forgot-password.php">Forgot Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
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

</body>

</html>