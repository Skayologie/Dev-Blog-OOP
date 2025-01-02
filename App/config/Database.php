<?php
namespace App\Config;
require __DIR__."/../../vendor/autoload.php";
use PDO;

class Database{
    public static function getConnection()
    {
        $DB_SERVER="localhost";
        $DB_USERNAME="root";
        $DB_PASSWORD="";
        $DB_NAME="devblog_db";
        return new PDO('mysql:host='.$DB_SERVER.';dbname='.$DB_NAME,$DB_USERNAME , $DB_PASSWORD);
    }
}
