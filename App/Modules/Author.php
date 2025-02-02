<?php
namespace App\Modules;


use App\Config\Database;

require realpath(__DIR__ . "/../../vendor/autoload.php");

class Author extends User{
    public function Manage_Users(){
        
    }
    public function Manage_Articles(){
        
    }
    public function Manage_Categories(){
        
    }
    public function Manage_Tags(){
        
    }
    public static function totalArticles($id){
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) AS Total FROM articles WHERE author_id = $id";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC)[0]["Total"];
        } else {
            echo false;
        }
    }
    public static function totalViews($id){
        $conn = Database::getConnection();
        $sql = "SELECT SUM(views) AS totalViews FROM articles WHERE author_id = $id";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC)[0]["totalViews"];
        } else {
            echo false;
        }
    }
    public static function topArticles($id){
        $conn = Database::getConnection();
        $sql = "select * ,articles.id AS ArticleID  from articles JOIN users ON users.id = articles.author_id WHERE articles.author_id = $id AND status = 'published' AND articles.isArchived = 0  ORDER BY views DESC LIMIT 3";
        $result = $conn->query($sql);
        $resultQuery = $result->fetchAll(\PDO::FETCH_ASSOC);
        return ($resultQuery);
    }
    public static function pendingArticles($id){
        $conn = Database::getConnection();
        $sql = "select * from articles JOIN users ON users.id = articles.author_id WHERE articles.author_id = $id AND status = 'pending' AND articles.isArchived = 0  ORDER BY created_at DESC";
        $result = $conn->query($sql);
        $resultQuery = $result->fetchAll(\PDO::FETCH_ASSOC);
        return ($resultQuery);
    }



    public static function archive($id,$table,$col){
        $conn = Database::getConnection();
        $sql = "UPDATE $table SET isArchived = 1 WHERE $col = $id";

        if ($conn->exec($sql)){
            $sql = "UPDATE $table SET status = 'archived' WHERE $col = $id";
            if ($conn->exec($sql)){
                return true;
            }
        }else{
            return false;
        }
    }
    public static function restore($id,$table,$col){
        $conn = Database::getConnection();
        $sql = "UPDATE $table SET isArchived = 0  WHERE $col = $id";
        if ($conn->exec($sql)){
            $sql = "UPDATE $table SET status = 'published' WHERE $col = $id";
            if ($conn->exec($sql)){
                return true;
            }
        }else{
            return false;
        }
    }

    public static function GetOwnArticles($id,$isArchived){
        $conn = Database::getConnection();
        $sql = "SELECT * ,
        articles.id AS ArticleId, 
        articles.title AS title, 
        users.username AS author_name, 
        categories.name AS category_name, 
        GROUP_CONCAT(tags.name) AS tags, 
        articles.views, 
        articles.created_at
        FROM articles
        LEFT JOIN 
            users ON articles.author_id = users.id
        LEFT JOIN
            categories ON articles.category_id = categories.id
        LEFT JOIN
            article_tags ON articles.id = article_tags.article_id
        LEFT JOIN
            tags ON article_tags.tag_id = tags.id
        WHERE articles.isArchived = $isArchived AND articles.author_id = $id 
        GROUP BY articles.id, articles.title, users.username, categories.name, articles.views, articles.created_at";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }

    public static function GetOwnArticleStatus($id , $status){
        $conn = Database::getConnection();
        $sql = "SELECT * ,
        articles.id AS ArticleId, 
        articles.title AS title, 
        users.username AS author_name, 
        categories.name AS category_name, 
        GROUP_CONCAT(tags.name) AS tags, 
        articles.views, 
        articles.created_at
        FROM articles
        LEFT JOIN 
            users ON articles.author_id = users.id
        LEFT JOIN
            categories ON articles.category_id = categories.id
        LEFT JOIN
            article_tags ON articles.id = article_tags.article_id
        LEFT JOIN
            tags ON article_tags.tag_id = tags.id
        WHERE articles.status = '$status' AND articles.isArchived = 0 AND articles.author_id = $id
        GROUP BY articles.id, articles.title, users.username, categories.name, articles.views, articles.created_at";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }

    
    
}