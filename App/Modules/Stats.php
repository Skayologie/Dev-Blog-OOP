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
}
$TotalUsers = Stats::Total("users");
$TotalArticles = Stats::Total("articles");