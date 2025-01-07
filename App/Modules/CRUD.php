<?php
namespace App\Modules;

use App\config\Database;
use PDO;

require realpath(__DIR__ . "/../../vendor/autoload.php");

class CRUD
{
    public static function Add($conn,$table, $columns, $values){
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $sql = "INSERT INTO " . $table . " (" . implode(',', $columns) . ") VALUES (" . $placeholders . ")";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute($values)) {
            return true;
        } else {
            echo false;
        }
    }

    public static function Get($conn,$table,$optionArchive,$optionDelete){
        $sql = "SELECT * FROM $table WHERE isArchived = $optionArchive AND isDeleted = $optionDelete";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }

    public static function GetArticles($isArchived,$isDeleted){
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
        WHERE articles.isArchived = $isArchived AND articles.isDeleted = $isDeleted
        GROUP BY articles.id, articles.title, users.username, categories.name, articles.views, articles.created_at";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }


    public static function GetArticlesByID($isArchived,$isDeleted,$id){
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
        WHERE articles.isArchived = $isArchived AND articles.isDeleted = $isDeleted AND articles.id = $id
        GROUP BY articles.id, articles.title, users.username, categories.name, articles.views, articles.created_at";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }
    public static function AcceptArticle($id){
        $conn = Database::getConnection();
        $sql = "UPDATE articles SET status = 'published' WHERE id = $id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }
    
    public static function RejectArticle($id){
        $conn = Database::getConnection();
        $sql = "UPDATE articles SET status = 'rejected' WHERE id = $id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute();
    }

    public static function GetById($table,$colID,$id){
        $conn = Database::getConnection();
        $sql = "SELECT * FROM $table WHERE $colID = $id";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }

    }

    public static function Delete($id,$table,$col){
        $conn = Database::getConnection();
        $sql = "UPDATE $table SET isDeleted = 1 WHERE $col = $id";
        if ($conn->exec($sql)){
            return true;
        }else{
            return false;
        }
    }
    public static function HardDelete($id,$table,$col){
        $conn = Database::getConnection();
        $sql = "DELETE FROM $table WHERE $col = ? ";
        $sql = "DELETE FROM $table WHERE $col = ? AND col = ?";
        $stmt= $conn->prepare($sql);
        if ($stmt->execute([$id])){
            return true;
        }else{
            return false;
        }
    }

    public static function Edit($id,$table,$data){
        $conn = Database::getConnection();
        $args = array();
        
        foreach ($data as $key => $value) {
            $args[] = "$key = ?";
        }
        $sql = "UPDATE $table SET " . implode(',', $args) . " WHERE id = $id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    public static function GetLastID($table , $idCol){
        $conn = Database::getConnection();
        $sql = "SELECT $idCol FROM $table ORDER BY $idCol DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $lastID = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $lastID[0]["id"];
    }
}

