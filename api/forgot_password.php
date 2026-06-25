<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/response.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    Response::error("Method not allowed", 405);
    exit();
}

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (json_last_error() !== JSON_ERROR_NONE) {
    Response::error("Bad Request: Invalid JSON body", 400);
    exit();
}

// =============================================
// STEP 1: Cari user berdasarkan email
// =============================================
if (!empty($data->email) && empty($data->token)) {

    if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
        Response::error("Bad Request: Format email tidak valid", 400);
        exit();
    }

    try {
        $stmt = $db->prepare("SELECT user_id, nama, email FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $data->email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            Response::error("Not Found: Email tidak terdaftar di sistem", 404);
            exit();
        }

        // Generate token reset: 6 digit angka acak
        $token = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan token ke database sementara di tabel reset_tokens
        // Cek apakah tabel sudah ada, jika belum buat otomatis
        $db->exec("CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `token` VARCHAR(10) NOT NULL,
            `expires_at` DATETIME NOT NULL,
            `used` TINYINT(1) DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX(`user_id`),
            INDEX(`token`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

        // Hapus token lama untuk user ini
        $stmtDel = $db->prepare("DELETE FROM password_reset_tokens WHERE user_id = :user_id");
        $stmtDel->bindParam(':user_id', $user['user_id']);
        $stmtDel->execute();

        // Simpan token baru (berlaku 10 menit)
        $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        $stmtIns = $db->prepare("INSERT INTO password_reset_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
        $stmtIns->bindParam(':user_id', $user['user_id']);
        $stmtIns->bindParam(':token', $token);
        $stmtIns->bindParam(':expires_at', $expiresAt);
        $stmtIns->execute();

        // Kembalikan token langsung (simulasi — di produksi ini dikirim via email)
        Response::success("Kode reset berhasil dibuat. Silakan cek kode Anda.", [
            "user_id"   => $user['user_id'],
            "nama"      => $user['nama'],
            "token"     => $token, // Ditampilkan langsung karena tidak ada email server
            "expires_in" => "10 menit"
        ]);

    } catch (PDOException $e) {
        Response::error("Internal Server Error: " . $e->getMessage(), 500);
    }

// =============================================
// STEP 2: Verifikasi token & reset password
// =============================================
} elseif (!empty($data->token) && !empty($data->new_password) && !empty($data->user_id)) {

    if (strlen($data->new_password) < 6) {
        Response::error("Bad Request: Password baru minimal 6 karakter", 400);
        exit();
    }

    try {
        // Validasi token
        $stmt = $db->prepare("
            SELECT id, user_id, expires_at, used
            FROM password_reset_tokens
            WHERE user_id = :user_id AND token = :token
            LIMIT 1
        ");
        $stmt->bindParam(':user_id', $data->user_id);
        $stmt->bindParam(':token', $data->token);
        $stmt->execute();
        $tokenRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tokenRow) {
            Response::error("Unauthorized: Kode reset tidak valid", 401);
            exit();
        }

        if ($tokenRow['used']) {
            Response::error("Gone: Kode reset sudah pernah digunakan", 410);
            exit();
        }

        if (strtotime($tokenRow['expires_at']) < time()) {
            Response::error("Gone: Kode reset sudah kedaluwarsa. Minta kode baru.", 410);
            exit();
        }

        // Update password
        $newHash = password_hash($data->new_password, PASSWORD_BCRYPT);
        $stmtUpd = $db->prepare("UPDATE users SET password_hash = :hash WHERE user_id = :user_id");
        $stmtUpd->bindParam(':hash', $newHash);
        $stmtUpd->bindParam(':user_id', $data->user_id);
        $stmtUpd->execute();

        // Tandai token sebagai sudah dipakai
        $stmtMark = $db->prepare("UPDATE password_reset_tokens SET used = 1 WHERE id = :id");
        $stmtMark->bindParam(':id', $tokenRow['id']);
        $stmtMark->execute();

        Response::success("Password berhasil direset. Silakan login dengan password baru Anda.");

    } catch (PDOException $e) {
        Response::error("Internal Server Error: " . $e->getMessage(), 500);
    }

} else {
    Response::error("Bad Request: Parameter tidak lengkap", 400);
}
?>
