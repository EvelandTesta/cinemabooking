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

if (!empty($data->nama) && !empty($data->password)) {
    try {
        // Cari user berdasarkan nama lengkap / username
        $query = "SELECT user_id, nama, password_hash, role FROM users WHERE nama = :nama LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nama', $data->nama);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // UJI PENALARAN AUTENTIKASI SECARA RIGOR
        if ($user) {
            // Cocokkan password mentah dengan string hash satu arah di database
            if (password_verify($data->password, $user['password_hash'])) {
                
                // Jangan pernah melempar password_hash kembali ke client/frontend demi keamanan
                unset($user['password_hash']);
                
                Response::success("Login successful", $user);
            } else {
                Response::error("Unauthorized: Kata sandi yang Anda masukkan salah", 401);
            }
        } else {
            Response::error("Not Found: Nama pengguna tidak ditemukan di sistem", 404);
        }
    } catch (PDOException $e) {
        Response::error("Internal Server Error: " . $e->getMessage(), 500);
    }
} else {
    Response::error("Bad Request: Data tidak lengkap", 400);
}
?>