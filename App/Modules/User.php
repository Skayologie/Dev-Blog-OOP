<?php
namespace App\Modules;
use App\config\Database;
use App\Controller\usersController;
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

        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        // $stmt->bindParam(':password', $password, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

        if ($result) {
            if ($stmt->rowCount() > 0) {
                if(password_verify($password,$result["password_hash"])){
                    if ($result["isArchived"]) {
                        $_SESSION["Banned"] = true;
                        $_SESSION["Logged"] = "Logged";
                        $_SESSION["UserID"] = $result["id"];
                        $_SESSION["UserName"] = $result["username"];
                        $_SESSION["ProfilePic"] = $result["profile_picture_url"];
                        $_SESSION["UserRole"] = $result["role"];
                        return true;
                    }else{
                        $_SESSION["Banned"] = false;
                        $_SESSION["Logged"] = "Logged";
                        $_SESSION["UserID"] = $result["id"];
                        $_SESSION["UserName"] = $result["username"];
                        $_SESSION["ProfilePic"] = $result["profile_picture_url"];
                        $_SESSION["UserRole"] = $result["role"];
                        return true;
                    }
                }
            }else{
                return false;
            }
        } else {
            return "Invalid email or password.";
        }
        
    }
    
    public static function register($username , $email,$password,$bio,$profile,$role="Normal_user"){
        if( self::validate_email($email) &&
            self::validate_password($password) && 
            self::validate_username($username)){
                
                $res = usersController::addUser($username,$email,$password,$bio,$profile);
                if ($res) {
                    header("Location:./login.php");
                    return "Registered Successfully";
                }else{
                    header("Location:./login.php");
                    return "Registered Failed";
                }
        }else{
            return "Check Your Informations";
        }
    }
    public function updateProfile($id){
        
    }
    public static function logout(){
        session_destroy();
    }
    public static function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    public static function validate_password($password) {
        // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
        return strlen($password) >= 8 &&
               preg_match('/[A-Z]/', $password) &&
               preg_match('/[a-z]/', $password) &&
               preg_match('/[0-9]/', $password);
    }
    public static function validate_username($username) {
        // 3-20 characters, letters, numbers, underscores
        return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
    }
}
