<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::error("Method not allowed", 405);
    exit();
}

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->nama) && !empty($data->email) && !empty($data->no_hp) && !empty($data->password)) {
    
    if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        Response::error("Bad Request: Format email tidak valid", 400);
        exit();
    }

    try {
        // Cek secara rigor apakah email atau no hp sudah terdaftar sebelumnya (Mitigasi Ganda)
        $checkQuery = "SELECT COUNT(*) FROM users WHERE email = :email OR no_hp = :no_hp";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(':email', $data->email);
        $checkStmt->bindParam(':no_hp', $data->no_hp);
        $checkStmt->execute();
        
        if ($checkStmt->fetchColumn() > 0) {
            Response::error("Conflict: Email atau Nomor HP sudah terdaftar", 409);
            exit();
        }

        // AMANKAN KREDENSIAL: Lakukan hashing satu arah di sisi server
        $hashedPassword = password_hash($data->password, PASSWORD_BCRYPT);

        // Role selalu 'user' saat registrasi mandiri — admin hanya bisa diset lewat panel admin
        $defaultRole = 'user';

        $query = "INSERT INTO users (nama, password_hash, email, no_hp, role, created_at) 
                  VALUES (:nama, :password_hash, :email, :no_hp, :role, NOW())";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':nama', $data->nama);
        $stmt->bindParam(':password_hash', $hashedPassword);
        $stmt->bindParam(':email', $data->email);
        $stmt->bindParam(':no_hp', $data->no_hp);
        $stmt->bindParam(':role', $defaultRole);

        if ($stmt->execute()) {
            Response::success("User registered successfully", ["user_id" => $db->lastInsertId()]);
        } else {
            Response::error("Failed to register user", 500);
        }
    } catch (PDOException $e) {
        Response::error("Internal Server Error: " . $e->getMessage(), 500);
    }
} else {
    Response::error("Bad Request: Data tidak lengkap", 400);
}
?>