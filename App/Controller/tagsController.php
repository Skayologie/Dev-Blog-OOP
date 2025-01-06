<?php
namespace App\Controller;
require __DIR__."/../../vendor/autoload.php";

use App\Modules\CRUD;
use App\Config\Database;
class tagsController
{
    public static function GetTags(){
        $conn = Database::getConnection();
        $results = CRUD::Get($conn , "tags",0,0);
        return $results;
    }
    public static function AddTag($values){
        $conn = Database::getConnection();
        CRUD::Add($conn,"tags",["name"],$values);
        header("Location:./Tags.php");
    }
    public static function DeleteTag($id){
        $conn = Database::getConnection();
        CRUD::Delete($id,"tags","id");
        header("Location:./Tags.php");
    }
}

