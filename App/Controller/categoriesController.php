<?php

namespace App\Controller;

use App\Modules\CRUD;
use App\Config\Database;
class categoriesController
{
    public static function GetCategories(){
        $conn = Database::getConnection();
        $results = CRUD::Get($conn , "categories",0,0);
        return $results;
    }
    public static function AddCategorie($values){
        $conn = Database::getConnection();
        CRUD::Add($conn,'categories',['name'],$values);
        header("Location:./Categories.php");
    }
}
