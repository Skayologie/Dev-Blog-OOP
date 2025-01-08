<?php
namespace App\Modules;

class Session{
    public static function sessionCheck($sessionName,$direction){
        if (!isset($_SESSION[$sessionName])) {
            header("Location:".$direction);
        }
    }
    public static function checkSessionRole($Role=[],$directionfalse){
        if (is_array($Role) || is_object($Role)) {
            foreach ($Role as $value) {
                if ($_SESSION["UserRole"] != $value) {
                    header("Location:".$directionfalse);
                }
            }
        }
        
    }
}
