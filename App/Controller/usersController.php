<?php
namespace App\Controller;
require __DIR__."/../../vendor/autoload.php";

use App\Modules\CRUD;
use App\Config\Database;
use App\Modules\User;

class usersController
{
    public static function GetUsers(){
        $conn = Database::getConnection();
        $results = CRUD::Get($conn , "users",0,0);
        return $results;
    }
    public static function GetArchivedUsers(){
        $conn = Database::getConnection();
        $results = CRUD::Get($conn , "users",1,0);
        return $results;
    }
    public static function addUser($username,$email,$pass,$bio,$pic){
        $conn = Database::getConnection();
        $resultAdd = CRUD::Add($conn,"users",["username","email","password_hash","bio","profile_picture_url"],
                                          [$username,$email,$pass,$bio,$pic]);
        if ($resultAdd) {
            return true;
        }
    }
    public static function updateUser($id,$email,$bio,$pic){
        $conn = Database::getConnection();
        if (User::validate_email($email) && User::validate_email($email)) {
            $data = [
                "email"=>$email,
                "bio"=>$bio,
                "profile_picture_url"=>$pic,
            ];
            $resultAdd = CRUD::Edit($id,"users",$data);
            if ($resultAdd) {
                $_SESSION["ProfilePic"] = $pic;
                return true;
            }
        }
        else{
            $_SESSION["result"] = [
                "message"=>"Update Failed , Check Your Inputs !",
                "color"=>"danger"
            ];
            return $_SESSION["result"];
        }
    }
}


