<?php
namespace App\Modules;

use App\config\Database;

require realpath(__DIR__ . "/../../vendor/autoload.php");

class CRUD
{
    public static function Add($conn,$table, $columns, $values){
        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $sql = "INSERT INTO " . $table . " (" . implode(',', $columns) . ") VALUES (" . $placeholders . ")";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute($values)) {
            echo "Inserted successfully!";
        } else {
            echo "Insertion failed.";
        }
    }
    public static function Get($conn,$table,$option=0){
        $sql = "SELECT * FROM $table WHERE isArchived = $option";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }

    }

}


