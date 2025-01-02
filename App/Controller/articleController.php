<?php
namespace App\Controller;
require realpath(__DIR__."/../../vendor/autoload.php");

use App\Config\Database;
use App\Modules\CRUD;
class articleController{
    public static function AddArticle(){
        $db = Database::getConnection();
        CRUD::Add(
            $db,'articles',
            ["title","content"],
            ["jawaddd"]
        );
    }

    public static function GetArticles()
    {
        $db = Database::getConnection();
        $Results = CRUD::Get($db,'articles');
        return $Results;
    }
}




