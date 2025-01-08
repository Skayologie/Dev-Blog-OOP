<?php
namespace App\Controller;
require __DIR__."/../../vendor/autoload.php";

use App\Modules\CRUD;
use App\Config\Database;
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
}


