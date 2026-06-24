<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/response.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        try {
            if(isset($_GET['user_id'])) {
                if(!is_numeric($_GET['user_id'])) {
                    Response::error("Bad Request: User ID must be a numeric value", 400);
                    exit();
                }
                // Get single user
                $stmt = $db->prepare("SELECT * FROM users WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $_GET['user_id']);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if($user) {
                    Response::success("User found", $user);
                } else {
                    Response::error("User not found", 404);
                }

            }

            elseif (isset($_GET['email'])) {
                $stmt = $db->prepare("SELECT user_id, nama, email, no_hp, created_at FROM users WHERE email = :email");
                $stmt->bindParam(':email', $_GET['email']);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if($user) {
                    Response::success("User found by email", $user);
                } else {
                    Response::error("User not found", 404);
                }
            } 

            elseif (isset($_GET['start_date']) && isset($_GET['end_date'])) {
                $stmt = $db->prepare("
                    SELECT user_id, nama, email, no_hp, created_at 
                    FROM users 
                    WHERE DATE(created_at) BETWEEN :start_date AND :end_date
                    ORDER BY created_at DESC
                ");
                $stmt->bindParam(':start_date', $_GET['start_date']);
                $stmt->bindParam(':end_date', $_GET['end_date']);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                Response::success("Users registered in date range", $users);
            }

            else {
                // Get all users
                $stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                Response::success("Users retrieved", $users);
            }
        } catch (PDOException $e) {
            Response::error("Internal Server Error: " . $e->getMessage(), 500);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->nama) && !empty($data->email) && (!empty($data->password_hash) || !empty($data->password))) {
            if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
                Response::error("Bad Request: Invalid email format", 400);
                exit();
            }

            try {
                $role = !empty($data->role) ? $data->role : 'user';
                $password_hash = !empty($data->password_hash) ? $data->password_hash : password_hash($data->password, PASSWORD_BCRYPT);
                
                $query = "INSERT INTO users (nama, password_hash, email, no_hp, role, created_at) 
                          VALUES (:nama, :password_hash, :email, :no_hp, :role, NOW())";

                $stmt = $db->prepare($query);

                $stmt->bindParam(':nama', $data->nama);
                $stmt->bindParam(':password_hash', $password_hash);
                $stmt->bindParam(':email', $data->email);
                $stmt->bindParam(':no_hp', $data->no_hp);
                $stmt->bindParam(':role', $role);

                if($stmt->execute()) {
                    Response::success("User created successfully", ["user_id" => $db->lastInsertId()]);
                } else {
                    Response::error("Failed to create user", 500);
                }
            } catch(PDOException $e) {
                if($e->getCode() == 23000) {
                    Response::error("Conflict: Email already registered", 409);
                } else {
                    Response::error("Internal Server Error: " . $e->getMessage(), 500);
                }
            }
        } else {
            Response::error("Bad Request: Incomplete data", 400);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Response::error("Bad Request: Invalid JSON body format", 400);
            exit();
        }
        
        if(!empty($data->user_id)) {
            try{
                $role = !empty($data->role) ? $data->role : 'user';
                $query = "UPDATE users SET 
                          nama = :nama, 
                          email = :email, 
                          no_hp = :no_hp,
                          role = :role
                          WHERE user_id = :user_id";

                $stmt = $db->prepare($query);

                $stmt->bindParam(':user_id', $data->user_id);
                $stmt->bindParam(':nama', $data->nama);
                $stmt->bindParam(':email', $data->email);
                $stmt->bindParam(':no_hp', $data->no_hp);
                $stmt->bindParam(':role', $role);

                if($stmt->execute()) {
                    Response::success("User updated successfully", ["status" => "Updated"]);
                } else {
                    Response::error("Failed to update user", 500);
                }
            } catch(PDOException $e) {
                Response::error("Internal Server Error: " . $e->getMessage(), 500);
            }
        } else {
            Response::error("User ID required", 400);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->user_id)) {
            try{
                $query = "DELETE FROM users WHERE user_id = :user_id";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $data->user_id);

                if($stmt->execute()) {
                    Response::success("User deleted successfully", ["status" => "Deleted"]);
                } else {
                    Response::error("Failed to delete user", 500);
                }
            } catch(PDOException $e) {
                Response::error("Internal Server Error: " . $e->getMessage(), 500);
            }
        } else {
            Response::error("User ID required", 400);
        }
        break;

    default:
        Response::error("Method not allowed", 405);
        break;
}
?>