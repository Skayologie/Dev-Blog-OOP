<?php

namespace App\Modules;
use App\config\Database;
require_once realpath(__DIR__ . '/../config/Database.php');
require realpath(__DIR__ . "/../../vendor/autoload.php");
class Tag
{
    public $id ;
    public $name ;

    public static function addArticle_Tag($articleID,$tagid){
        $conn = Database::getConnection();
        CRUD::Add($conn,"article_tags",["article_id","tag_id"],[$articleID,$tagid]);
    }
    public static function HardDeleteTag($ArticleId,$tagId){
        $conn = Database::getConnection();
        $sql = "DELETE FROM article_tags WHERE article_id = ? AND tag_id = ?";
        $stmt= $conn->prepare($sql);
        if ($stmt->execute([$ArticleId,$tagId])){
            return true;
        }else{
            return false;
        }
    }
}
