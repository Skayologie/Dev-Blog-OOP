<?php
namespace App\Modules;
use App\config\Database;
use PDO;
require realpath(__DIR__ . "/../../vendor/autoload.php");

class User{
    protected $id ;
    protected $name ;
    protected $email ;
    protected $password ;
    protected $role ;
    protected $status ;

    public static function login($data) {
        $conn = Database::getConnection();
        if (!isset($data['email']) || !isset($data['password'])) {
            throw new \Exception("Email and password are required.");
        }
        $email = $data['email'];
        $password = $data['password'];

        $sql = "SELECT * FROM users WHERE email = :email AND password_hash = :password";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

        if ($result) {
            if ($stmt->rowCount() > 0) {
                $_SESSION["Logged"] = "Logged";
                $_SESSION["UserID"] = $result["id"];
                $_SESSION["UserName"] = $result["username"];
                $_SESSION["UserRole"] = $result["role"];
                return true;
            }else{
                return false;
            }
        } else {
            return "Invalid email or password.";
        }
        
    }
    
    public function register($name , $email,$password,$role="Normal_user"){
        
    }
    public function updateProfile($id){
        
    }
    public static function logout(){
        session_destroy();
    }

}
