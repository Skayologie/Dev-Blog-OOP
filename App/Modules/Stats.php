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
}
$TotalUsers = Stats::Total("users");
$TotalArticles = Stats::Total("articles");