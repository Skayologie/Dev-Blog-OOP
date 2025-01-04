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
}
