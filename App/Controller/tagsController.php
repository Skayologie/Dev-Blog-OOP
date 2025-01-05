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
}

