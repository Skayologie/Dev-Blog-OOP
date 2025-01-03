<?php
namespace App\Modules;


use App\Config\Database;

require realpath(__DIR__ . "/../../vendor/autoload.php");

class Admin extends User{
    public function Manage_Users(){
        
    }
    public function Manage_Articles(){
        
    }
    public function Manage_Categories(){
        
    }
    public function Manage_Tags(){
        
    }
    public function View_Statistics(){
        
    }
    public static function archive($id,$table,$col){
        $conn = Database::getConnection();
        $sql = "UPDATE $table SET isArchived = 1 WHERE $col = $id";

        if ($conn->exec($sql)){
            return true;
        }else{
            return false;
        }
    }
    public static function restore($id,$table,$col){
        $conn = Database::getConnection();
        $sql = "UPDATE $table SET isArchived = 0  WHERE $col = $id";
        if ($conn->exec($sql)){
            return true;
        }else{
            return false;
        }
    }


}
