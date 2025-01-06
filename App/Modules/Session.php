<?php
namespace App\Modules;

class Session{
    public static function sessionCheck($sessionName,$direction){
        if (!isset($_SESSION[$sessionName])) {
            header("Location:".$direction);
        }
    }
    public static function checkSessionRole($Role,$directionfalse){
        if ($_SESSION["UserRole"] != $Role) {
            header("Location:".$directionfalse);
        }
    }
}
