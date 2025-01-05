<?php
namespace App\Modules;
use App\config\Database;

require realpath(__DIR__ . "/../../vendor/autoload.php");

class User{
    protected $id ;
    protected $name ;
    protected $email ;
    protected $password ;
    protected $role ;
    protected $status ;

    public function login(){
        $conn = Database::getConnection();
        $email = $_POST["email"];
        $password = $_POST["password"];
        if (!empty($email) && !empty($password)){
            $sql = "INSERT INTO tags(name) VALUES('Mohamed')";
            if ($conn->exec($sql)){
                echo "done";
            }else{
                echo "Didnt done";
            }
        }else{
            echo "Connected Failed";
        }
    }
    public function register($name , $email,$password,$role="Normal_user"){
        
    }
    public function updateProfile($id){
        
    }

}


