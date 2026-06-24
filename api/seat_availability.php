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
        // 1. Kondisi jika mencari satu kursi spesifik (bawaan asli)
        if (isset($_GET['showtime_id']) && isset($_GET['seat_id'])) {
            $stmt = $db->prepare("
                SELECT sa.*, s.baris, s.nomor_kursi, st.nama_studio,
                       m.judul as movie_title, sh.Jam, sh.Tanggal
                FROM seat_availability sa
                LEFT JOIN seats s ON sa.seat_id = s.seat_id
                LEFT JOIN studios st ON sa.studio_id = st.studio_id
                LEFT JOIN showtimes sh ON sa.showtime_id = sh.showtime_id
                LEFT JOIN movies m ON sh.movie_id = m.movie_id
                WHERE sa.showtime_id = :showtime_id AND sa.seat_id = :seat_id
            ");
            $stmt->bindParam(':showtime_id', $_GET['showtime_id']);
            $stmt->bindParam(':seat_id', $_GET['seat_id']);
            $stmt->execute();
            $availability = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($availability) {
                Response::success("Seat availability found", $availability);
            } else {
                Response::error("Seat availability not found", 404);
            }
        } 
        
        // 2. Kondisi jika frontend meminta denah satu studio berdasarkan showtime_id
        elseif (isset($_GET['showtime_id'])) {
            $stmt = $db->prepare("
                SELECT sa.*, s.baris, s.nomor_kursi, st.nama_studio,
                       m.judul as movie_title, sh.jam, sh.tanggal, sh.harga_tiket
                FROM seat_availability sa
                LEFT JOIN seats s ON sa.seat_id = s.seat_id
                LEFT JOIN showtimes sh ON sa.showtime_id = sh.showtime_id
                LEFT JOIN studios st ON sh.studio_id = st.studio_id
                LEFT JOIN movies m ON sh.movie_id = m.movie_id
                WHERE sa.showtime_id = :showtime_id
                ORDER BY s.baris ASC, s.nomor_kursi ASC
            ");
            $stmt->bindParam(':showtime_id', $_GET['showtime_id']);
            $stmt->execute();
            $availability = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            Response::success("Seat availability retrieved", $availability);
        }
        
        // 3. Kondisi default jika tidak ada parameter (bawaan asli)
        else {
            $stmt = $db->prepare("
                SELECT sa.*, s.baris, s.nomor_kursi
                FROM seat_availability sa
                LEFT JOIN seats s ON sa.seat_id = s.seat_id
                LEFT JOIN showtimes sh ON sa.showtime_id = sh.showtime_id
                ORDER BY sh.tanggal, sh.jam, s.baris, s.nomor_kursi
            ");
            $stmt->execute();
            $availability = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Response::success("All seat availability retrieved", $availability);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->showtime_id) && !empty($data->seat_id) && !empty($data->status_kursi)) {
            $query = "UPDATE seat_availability SET 
                      status_kursi = :status_kursi,
                      updated_at = NOW()
                      WHERE showtime_id = :showtime_id AND seat_id = :seat_id";
            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':showtime_id', $data->showtime_id);
            $stmt->bindParam(':seat_id', $data->seat_id);
            $stmt->bindParam(':status_kursi', $data->status_kursi);
            
            if($stmt->execute()) {
                Response::success("Seat availability updated successfully");
            } else {
                Response::error("Failed to update seat availability", 500);
            }
        } else {
            Response::error("Incomplete data", 400);
        }
        break;

    default:
        Response::error("Method not allowed", 405);
        break;
}
?>