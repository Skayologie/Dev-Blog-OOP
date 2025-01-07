<?php
namespace App\Modules;

use App\config\Database;
use PDO;

require realpath(__DIR__ . "/../../vendor/autoload.php");

class article
{
    public static function GetArticleStatus($status){
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
        WHERE articles.status = '$status' AND articles.isArchived = 0
        GROUP BY articles.id, articles.title, users.username, categories.name, articles.views, articles.created_at";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }
    }
    public static function incViews($idArticle){
        $conn = Database::getConnection();
        $query = "UPDATE articles 
                  SET views = CASE WHEN views IS NOT NULL THEN views + 1 ELSE 1 END 
                  WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $idArticle, PDO::PARAM_STR);
        $stmt->execute();
    }
}

