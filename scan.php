<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// 1. WAJIB: Include file konfigurasi agar $db terdefinisi
include_once '../config/database.php';
include_once '../config/response.php';

$database = new Database();
$db = $database->getConnection(); // Sekarang $db sudah ada isinya!

// 2. Ambil data dari input JSON
$data = json_decode(file_get_contents("php://input"));

if (empty($data->kode_tiket)) {
    echo "Error: Kode tiket tidak dikirim!";
    exit;
}

// 3. Logika Scan
try {
    $stmt = $db->prepare("SELECT status FROM tickets WHERE kode_tiket = :kode");
    $stmt->execute(['kode' => $data->kode_tiket]);
    $tiket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tiket) {
        echo "GAGAL: Tiket tidak ditemukan!";
    } elseif ($tiket['status'] === 'refunded') {
        echo "GAGAL: Tiket ini sudah di-refund (Invalid)!";
    } elseif ($tiket['status'] === 'used') {
        echo "GAGAL: Tiket sudah pernah dipakai masuk!";
    } else {
        // Update status ke 'used'
        $update = $db->prepare("UPDATE tickets SET status = 'used' WHERE kode_tiket = :kode");
        $update->execute(['kode' => $data->kode_tiket]);
        echo "BERHASIL: Tiket valid, silakan masuk!";
    }
} catch (PDOException $e) {
    echo "Error Database: " . $e->getMessage();
}
?>