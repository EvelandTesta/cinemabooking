<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/database.php';
include_once '../config/response.php';

date_default_timezone_set('Asia/Jakarta');

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

// Pastikan semua data yang dibutuhkan ada
if (empty($data->showtime_id) || empty($data->ticket_id) || empty($data->seat_id)) {
    Response::error("Data tidak lengkap (showtime_id, ticket_id, seat_id diperlukan)", 400);
    exit;
}

$showtime_id = $data->showtime_id;
$ticket_id = $data->ticket_id;
$seat_id = $data->seat_id;

// 1. Ambil data showtime dari DB dulu
$stmt = $db->prepare("SELECT tanggal, jam FROM showtimes WHERE showtime_id = :id");
$stmt->execute(['id' => $showtime_id]);
$showtime = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$showtime) {
    Response::error("Showtime tidak ditemukan", 404);
    exit;
}

// 2. Setup Waktu
$sekarang = new DateTime();
$waktu_tayang_str = $showtime['tanggal'] . ' ' . $showtime['jam'];
$waktu_tayang = new DateTime($waktu_tayang_str);

// 3. Hitung selisih
$selisih_detik = $waktu_tayang->getTimestamp() - $sekarang->getTimestamp();
$menit_sisa = $selisih_detik / 60;

// 4. Logika Validasi
if ($selisih_detik < 0) {
    Response::error("Refund ditolak: Film sudah dimulai.", 403);
} elseif ($menit_sisa < 15) {
    Response::error("Refund ditolak: Sisa waktu kurang dari 15 menit.", 403);
} else {
    // 5. Eksekusi Refund
    try {
        $db->beginTransaction();

        // Update status tiket jadi 'refunded'
        $stmt = $db->prepare("UPDATE tickets SET status = 'refunded' WHERE ticket_id = :tid");
        $stmt->execute(['tid' => $ticket_id]);

        // Kembalikan kursi ke 'available'
        $stmtSeat = $db->prepare("UPDATE seat_availability SET status_kursi = 'available' 
                          WHERE showtime_id = :sid AND seat_id = :seid");
        $stmtSeat->execute(['sid' => $showtime_id, 'seid' => $seat_id]);

        $db->commit();
        Response::success("Refund berhasil diproses.");
    } catch (Exception $e) {
        $db->rollBack();
        Response::error("Gagal memproses refund: " . $e->getMessage(), 500);
    }
}
?>