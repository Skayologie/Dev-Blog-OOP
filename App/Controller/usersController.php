<?php
namespace App\Controller;
require __DIR__."/../../vendor/autoload.php";

use App\Modules\CRUD;
use App\Config\Database;
class usersController
{
    public static function GetUsers(){
        $conn = Database::getConnection();
        $results = CRUD::Get($conn , "users");
        return $results;
    }
}


