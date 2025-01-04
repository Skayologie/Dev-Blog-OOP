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

    public static function Get($conn,$table,$optionArchive,$optionDelete){
        $sql = "SELECT * FROM $table WHERE isArchived = $optionArchive AND isDeleted = $optionDelete";
        $stmt = $conn->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll();
        } else {
            return false;
        }

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
}


