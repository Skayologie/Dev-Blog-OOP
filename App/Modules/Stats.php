<?php
namespace App\Modules;

require realpath(__DIR__ . "/../../vendor/autoload.php");

use App\config\Database;
use PDO;

class Stats{
    public static function Total($table){
        $conn = Database::getConnection();
        $sql = "SELECT COUNT(*) AS Total FROM $table";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["Total"];
        } else {
            echo false;
        }
    }
    public static function get_category_stats(){
        $conn = Database::getConnection();
        $sql = "SELECT 
        categories.id , 
        categories.name AS CatName, 
        COUNT(articles.id) AS article_count 
        FROM articles 
        JOIN categories ON categories.id = articles.category_id 
        GROUP BY categories.id";
        $result = $conn->query($sql);
        $resultQuery = $result->fetchAll(PDO::FETCH_ASSOC);
        return ($resultQuery);
    }
    public static function get_top_users(){
        $conn = Database::getConnection();
        $sql = "SELECT * ,
        users.id,profile_picture_url, username, 
        COUNT(articles.id) AS article_count, 
        SUM(articles.views) AS viewsAll 
        FROM articles 
        JOIN users ON users.id = articles.author_id 
        WHERE users.isArchived = 0 AND users.isDeleted = 0
        GROUP BY users.id 
        ORDER BY viewsAll DESC 
        LIMIT 3";
        $result = $conn->query($sql);
        $resultQuery = $result->fetchAll(PDO::FETCH_ASSOC);
        return ($resultQuery);
    }
    public static function get_top_articles(){
        $conn = Database::getConnection();
        $sql = "select * from articles JOIN users ON users.id = articles.author_id ORDER BY views DESC LIMIT 3";
        $result = $conn->query($sql);
        $resultQuery = $result->fetchAll(PDO::FETCH_ASSOC);
        return ($resultQuery);
    }
}
$TotalUsers = Stats::Total("users");
$TotalArticles = Stats::Total("articles");