<?php
namespace App\Controller;
require __DIR__."/../../vendor/autoload.php";

use App\Modules\CRUD;
use App\Config\Database;
use App\Modules\article;

class tagsController
{
    public static function GetTags(){
        $conn = Database::getConnection();
        $results = CRUD::Get($conn , "tags",0,0);
        return $results;
    }
    public static function getTagsByArticleId($articleID){
        $results = CRUD::GetById("article_tags","article_id",$articleID);
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
    public static function GetTagsRelated($Articleid){
        $tagsUsed = [] ;
        $IDtagsUsed = tagsController::getTagsByArticleId($Articleid);
        foreach ($IDtagsUsed as $value) {
            $tagsUsed[] = CRUD::GetById("tags","id",$value["tag_id"])[0]["name"];
        }
        return $tagsUsed;
    }
}

